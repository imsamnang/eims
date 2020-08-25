<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class StudyCourse extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
     public static function path($key = null)
    {
        $table = (new self)->getTable();
        $tableUcwords = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));

        $path = [
            'table'  => $table,
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'\Controller',
        ];
        return $key ? @$path[$key] : $path;
    }

     /**
     *  @param string $key
     *  @param string $flag
     *  @return array
     */
    public static function validate($key = null, $flag = '[]')
    {
        $class = self::path('requests');
        $formRequests = new $class;
        $validate =  [
            'rules'       =>  $formRequests->rules($flag),
            'attributes'  =>  $formRequests->attributes($flag),
            'messages'    =>  $formRequests->messages($flag),
            'questions'   =>  $formRequests->questions($flag),
        ];
        return $key? @$validate[$key] : $validate;
    }



    public static function getData($id = null, $edit = null, $paginate = null, $search = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . StudyCourse::path('url') . '/add/'),
            ),
        );
        $data = array();


        $type   = request('typeId');
        $program   = request('programId');
        $orderBy = 'DESC';
        if ($id) {
            $id  =  gettype($id) == 'array' ? $id : explode(',', $id);
            $sorted = array_values($id);
            sort($sorted);
            if ($id === $sorted) {
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
        }
        $get = StudyCourse::orderBy('id', $orderBy);
        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {



            if ($program) {
                $get = $get->where('study_program_id', $program);
            }

            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
        }

        if ($search) {
            $get = $get->where('name', 'LIKE', '%' . $search . '%');
            if (config('app.languages')) {
                foreach (config('app.languages') as $lang) {
                    $get = $get->orWhere($lang['code_name'], 'LIKE', '%' . $search . '%');
                }
            }
        }



        if ($paginate) {
            $get = $get->paginate($paginate)->toArray();
            foreach ($get as $key => $value) {
                if ($key == 'data') {
                } else {
                    $pages[$key] = $value;
                }
            }

            $get = $get['data'];
        } else {
            $get = $get->get()->toArray();
        }


        if ($get) {
            if (strtolower(request()->server('CONTENT_TYPE')) == 'application/json') {
                foreach ($get as $key => $row) {
                    $data[$key] = [
                        'id'  => $row['id'],
                        '_name'                   => $row['en'],
                        'name' => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                        'study_program'           => $row['study_program_id'] == null ? null : StudyPrograms::getData($row['study_program_id'])['data'][0],
                        'study_generation'        => $row['study_generation_id'] == null ? null : StudyGeneration::getData($row['study_generation_id'])['data'][0],
                        'image'         =>  $row['image'] ? (ImageHelper::site(StudyCourse::path('image'), $row['image'])) : ImageHelper::prefix(),
                    ];

                    if (request('ref') != StudentsStudyCourse::path('image') . '-certificate') {
                        if ($data[$key]['study_program']) {
                            $data[$key]['_name'] =  $data[$key]['_name'] . ' - ' . $data[$key]['study_program']['name'];
                        }

                        $data[$key]['name'] = $data[$key]['_name'];
                    }
                }
            } else {

                foreach ($get as $key => $row) {
                    $data[$key]                   = array(
                        'id'  => $row['id'],
                        'name' => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                        '_name'                   => $row['en'],
                        'description'             => $row['description'],
                        'institute'               => $row['institute_id'] == null ? null : Institute::getData($row['institute_id'])['data'][0],
                        'study_faculty'           => $row['study_faculty_id'] == null ? null : StudyFaculty::getData($row['study_faculty_id'])['data'][0],
                        'course_type'             => $row['course_type_id'] == null ? null : CourseTypes::getData($row['course_type_id'])['data'][0],
                        'study_modality'          => $row['study_modality_id'] == null ? null : StudyModality::getData($row['study_modality_id'])['data'][0],
                        'study_program'           => $row['study_program_id'] == null ? null : StudyPrograms::getData($row['study_program_id'])['data'][0],
                        'study_overall_fund'      => $row['study_overall_fund_id'] == null ? null : StudyOverallFund::getData($row['study_overall_fund_id'])['data'][0],
                        'curriculum_author'       => $row['curriculum_author_id'] == null ? null : CurriculumAuthor::getData($row['curriculum_author_id'])['data'][0],
                        'curriculum_endorsement'  => $row['curriculum_endorsement_id'] == null ? null : CurriculumEndorsement::getData($row['curriculum_endorsement_id'])['data'][0],

                        'image'         =>  $row['image'] ? (ImageHelper::site(StudyCourse::path('image'), $row['image'])) : ImageHelper::prefix(),
                        'action'                   => [
                            'edit' => url(Users::role() . '/study/' . StudyCourse::path('url') . '/edit/' . $row['id']), //?id
                            'view' => url(Users::role() . '/study/' . StudyCourse::path('url') . '/view/' . $row['id']), //?id
                            'delete' => url(Users::role() . '/study/' . StudyCourse::path('url') . '/delete/' . $row['id']), //?id
                        ]
                    );

                    if (request('ref') != StudentsStudyCourse::path('image') . '-certificate') {
                        if ($data[$key]['study_program']) {
                            $data[$key]['_name'] =  $data[$key]['_name'] . ' - ' . $data[$key]['study_program']['name'];
                        }
                    }
                    if (request('ref') == Students::path('url') . '-' . StudentsRequest::path('url')) {
                        $data[$key]['name'] = $data[$key]['_name'];
                    }

                    $pages['listData'][] = array(
                        'id'     => $data[$key]['id'],
                        'name'   => $data[$key]['name'],
                        'image'  => $data[$key]['image'],
                        'action' => $data[$key]['action'],

                    );

                    if ($edit) {
                        $data[$key]['name'] =  $row['name'];
                        if (config('app.languages')) {
                            foreach (config('app.languages') as $lang) {
                                $data[$key][$lang['code_name']] = $row[$lang['code_name']];
                            }
                        }
                    }
                }
            }
            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages
            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'pages'     => $pages,
                'message'   => __('No Data'),
            );
        }
        return $response;
    }


    public static function addToTable()
    {

        $response           = array();
        $validate = self::validate();

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id']                 = request('institute');
                $values['name']                         = trim(request('name'));
                $values['study_faculty_id']             = request('study_faculty');
                $values['course_type_id']               = request('course_type');
                $values['study_modality_id']            = request('study_modality');
                $values['study_program_id']             = request('study_program');
                $values['study_overall_fund_id']        = request('study_overall_fund');
                $values['curriculum_author_id']         = request('curriculum_author');
                $values['curriculum_endorsement_id']    = request('curriculum_endorsement');
                $values['description']                  = trim(request('description'));
                $values['image']                        = null;

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $add = StudyCourse::insertGetId($values);
                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StudyCourse::updateImageToTable($add, ImageHelper::uploadImage($image, StudyCourse::path('image')));
                    } else {
                        ImageHelper::uploadImage(false, StudyCourse::path('image'), StudyCourse::path('image'), public_path('/assets/img/icons/image.jpg', null, true));
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => StudyCourse::getData($add)['data'],
                        'message'   => __('Add Successfully'),

                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateToTable($id)
    {

        $response           = array();
        $validate = self::validate();

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $values['institute_id']                 = request('institute');
                $values['name']                         = trim(request('name'));
                $values['study_faculty_id']             = request('study_faculty');
                $values['course_type_id']               = request('course_type');
                $values['study_modality_id']            = request('study_modality');
                $values['study_program_id']             = request('study_program');
                $values['study_overall_fund_id']        = request('study_overall_fund');
                $values['curriculum_author_id']         = request('curriculum_author');
                $values['curriculum_endorsement_id']    = request('curriculum_endorsement');
                $values['description']                  = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                $update = StudyCourse::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StudyCourse::updateImageToTable($id, ImageHelper::uploadImage($image, StudyCourse::path('image')));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => StudyCourse::getData($id),
                        'message'   => array(
                            'title' => __('Success'),
                            'text'  => __('Update Successfully'),
                            'button'      => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),

                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateImageToTable($id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  StudyCourse::where('id', $id)->update([
                    'image'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }

        return $response;
    }


    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudyCourse::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudyCourse::whereIn('id', $id)->delete();
                        if ($delete) {
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        }
                    } catch (\Exception $e) {
                        return $e;
                    }
                }
            } else {
                return [
                    'success'   => false,
                    'message'   =>   __('No Data'),

                ];
            }
        } else {
            return [
                'success'   => false,
                'message'   =>  __('Please select data!'),

            ];
        }
    }
}
