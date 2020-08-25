<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyCourseSchedule extends Model
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

    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/add/'),
            ),
        );
        $data = array();


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

        $get = StudyCourseSchedule::orderBy('id', $orderBy);

        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if (request('ref') == StudyCourseSession::path('url')) {
                $get = $get->whereNotIn('id', StudyCourseSession::select('study_course_schedule_id')->get());
            }
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
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

                $data[$key]                   = array(
                    'id'  => $row['id'],
                    'name' => null,
                    'image' => null,
                    'institute'   => Institute::getData($row['institute_id'])['data'][0],
                    'study_program'   => StudyPrograms::getData($row['study_program_id'])['data'][0],
                    'study_course' => StudyCourse::getData($row['study_course_id'])['data'][0],
                    'study_generation'   => StudyGeneration::getData($row['study_generation_id'])['data'][0],
                    'study_academic_year'   => StudyAcademicYears::getData($row['study_academic_year_id'])['data'][0],
                    'study_semester'   => StudySemesters::getData($row['study_semester_id'])['data'][0],

                    'action'                   => [
                        'edit'   => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/edit/' . $row['id']), //?id
                        'delete' => url(Users::role() . '/study/' . StudyCourseSchedule::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $data[$key]['name']  = $data[$key]['study_course']['name'] . ' - (' . $data[$key]['study_generation']['name'] . ', ' . $data[$key]['study_academic_year']['name'] . ', ' . $data[$key]['study_semester']['name'] . ') ' . $data[$key]['study_program']['name'];

                if (!request('instituteId')) {
                    $data[$key]['name'] = $data[$key]['institute']['short_name'] . ' - ' . $data[$key]['name'];
                }

                $data[$key]['image']  = $data[$key]['study_course']['image'];

                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['name'],
                    'image'  => $data[$key]['image'],
                    'action' => $data[$key]['action'],

                );
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
                $exists = StudyCourseSchedule::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {
                    $add = StudyCourseSchedule::insertGetId([
                        'institute_id'        => request('institute'),
                        'study_program_id'      => request('study_program'),
                        'study_course_id'        => request('study_course'),
                        'study_generation_id'    => request('study_generation'),
                        'study_academic_year_id' => request('study_academic_year'),
                        'study_semester_id'      => request('study_semester')
                    ]);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => StudyCourseSchedule::getData($add)['data'],
                            'type'      => 'add',
                            'message'   => array(
                                'title' => __('Success'),
                                'text'  => __('Add Successfully'),
                                'button'   => array(
                                    'confirm' => __('Ok'),
                                    'cancel'  => __('Cancel'),
                                ),
                            ),
                        );
                    }
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
                $update = StudyCourseSchedule::where('id', $id)->update([
                    'institute_id'        => request('institute'),
                    'study_program_id'      => request('study_program'),
                    'study_course_id'        => request('study_course'),
                    'study_generation_id'    => request('study_generation'),
                    'study_academic_year_id' => request('study_academic_year'),
                    'study_semester_id'      => request('study_semester')
                ]);
                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => StudyCourseSchedule::getData($id),
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

    public static function existsToTable()
    {
        return StudyCourseSchedule::where('institute_id', request('institute'))
            ->where('study_course_id', request('study_course'))
            ->where('study_generation_id', request('study_generation'))
            ->where('study_academic_year_id', request('study_academic_year'))
            ->where('study_semester_id', request('study_semester'))
            ->first();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudyCourseSchedule::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudyCourseSchedule::whereIn('id', $id)->delete();
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
