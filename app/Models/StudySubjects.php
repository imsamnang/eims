<?php

namespace App\Models;

use DomainException;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudySubjects extends Model
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
            'controller'   => 'App\Http\Controllers\\'.$tableUcwords.'Controller',
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
            'attributes'  =>  $formRequests->attributes(),
            'messages'    =>  $formRequests->messages(),
            'questions'   =>  $formRequests->questions(),
        ];
        return $key? @$validate[$key] : $validate;
    }

    protected $fillable = [
        'name', 'image',
    ];


    public static function getData($id = null, $edit = null, $paginate = null, $search = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . StudySubjects::path('url') . '/add/'),
            ),
        );

        $orderBy = 'DESC';
        $data = array();
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
        $get = StudySubjects::orderBy('id', $orderBy);
        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
            if (request('courseTId')) {
                $get = $get->where('course_type_id', request('courseTId'));
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

            foreach ($get as $key => $row) {
                $data[$key]                       = array(
                    'id'   => $row['id'],
                    'name' => $row[app()->getLocale()] ? $row[app()->getLocale()] : $row['name'],
                    'course_type'              => CourseTypes::getData($row['course_type_id'])['data'][0],
                    'full_mark_theory'         => $row['full_mark_theory'],
                    'pass_mark_theory'         => $row['pass_mark_theory'],
                    'full_mark_practical'      => $row['full_mark_practical'],
                    'pass_mark_practical'      => $row['pass_mark_practical'],
                    'credit_hour'              => $row['credit_hour'],
                    'description'              => $row['description'],
                    'file' => $row['file'] ? FileHelper::site(StudySubjects::path('file'), $row['file']) : $row['file'],
                    'image' =>  $row['image'] ? (ImageHelper::site(StudySubjects::path('image'), $row['image'])) : ImageHelper::prefix(),
                    'action'                   => [
                        'edit' => url(Users::role() . '/study/' . StudySubjects::path('url') . '/edit/' . $row['id']), //?id
                        'view' => url(Users::role() . '/study/' . StudySubjects::path('url') . '/view/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/study/' . StudySubjects::path('url') . '/delete/' . $row['id']), //?id
                    ]
                );
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



            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages,

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
                $values['institute_id']      = request('institute');
                $values['course_type_id']      = request('course_type');
                $values['name']                = trim(request('name'));
                $values['full_mark_theory']    =  request('full_mark_theory');
                $values['pass_mark_theory']    =  request('pass_mark_theory');
                $values['full_mark_practical'] =  request('full_mark_practical');
                $values['pass_mark_practical'] =  request('pass_mark_practical');
                $values['credit_hour']         =  request('credit_hour');
                $values['description']         = trim(request('description'));


                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                if (request()->hasFile('file')) {
                    $file      = request()->file('file');
                    $values['file'] = FileHelper::uploadFile($file, StudySubjects::path('file'));
                }
                $add = StudySubjects::insertGetId($values);
                if ($add) {

                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StudySubjects::updateImageToTable($add, ImageHelper::uploadImage($image, StudySubjects::path('image')));
                    } else {
                        ImageHelper::uploadImage(false, StudySubjects::path('image'), null, public_path('/assets/img/icons/image.jpg'));
                    }



                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => StudySubjects::getData($add)['data'],
                        'message'   => __('Add Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
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
                $values['institute_id']      = request('institute');
                $values['course_type_id']      = request('course_type');
                $values['name']                = trim(request('name'));
                $values['full_mark_theory']    =  request('full_mark_theory');
                $values['pass_mark_theory']    =  request('pass_mark_theory');
                $values['full_mark_practical'] =  request('full_mark_practical');
                $values['pass_mark_practical'] =  request('pass_mark_practical');
                $values['credit_hour']         =  request('credit_hour');
                $values['description']         = trim(request('description'));

                if (config('app.languages')) {
                    foreach (config('app.languages') as $lang) {
                        $values[$lang['code_name']] = trim(request($lang['code_name']));
                    }
                }
                if (request()->hasFile('file')) {
                    $file      = request()->file('file');
                    $values['file'] = FileHelper::uploadFile($file, StudySubjects::path('file'));
                }
                $update = StudySubjects::where('id', $id)->update($values);
                if ($update) {
                    if (request()->hasFile('image')) {
                        $image      = request()->file('image');
                        StudySubjects::updateImageToTable($id, ImageHelper::uploadImage($image, StudySubjects::path('image')));
                    }
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
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
                $update =  StudySubjects::where('id', $id)->update([
                    'image'    => $image,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }

        return $response;
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudySubjects::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudySubjects::whereIn('id', $id)->delete();
                        if ($delete) {
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        }
                    } catch (\Throwable $th) {
                        throw $th;
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
