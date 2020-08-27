<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyShortCourseSchedule extends Model
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

    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . StudyShortCourseSchedule::path('url') . '/add/'),
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

        $get = StudyShortCourseSchedule::orderBy('id', $orderBy);

        if ($id) {
            $get = $get->whereIn('id', $id);
        } else {
            if (request('ref') == StudyCourseSession::path('url')) {
                $get = $get->whereNotIn('id', StudyCourseSession::select('stu_sh_c_schedule_id')->get());
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

                $data[$key]  = array(
                    'id'  => $row['id'],
                    'institute'   => Institute::getData($row['institute_id'])['data'][0],
                    'study_generation'   => StudyGeneration::getData($row['study_generation_id'])['data'][0],
                    'study_subject'   => StudySubjects::getData($row['study_subject_id'])['data'][0],
                    'action'        => [
                        'edit' => url(Users::role() . '/study/' . StudyShortCourseSchedule::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/study/' . StudyShortCourseSchedule::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/study/' . StudyShortCourseSchedule::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $data[$key]['name']  = $data[$key]['study_subject']['name'] . ' - (' . $data[$key]['study_generation']['name']  . ') ';

                if (!request('instituteId')) {
                    $data[$key]['name'] = $data[$key]['institute']['short_name'] . ' - ' . $data[$key]['name'];
                }

                $data[$key]['image']  = $data[$key]['study_subject']['image'];

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
                $exists = StudyShortCourseSchedule::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {
                    $add = StudyShortCourseSchedule::insertGetId([
                        'institute_id'        => request('institute'),
                        'study_generation_id'    => request('study_generation'),
                        'study_subject_id'      => request('study_subject')
                    ]);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => StudyShortCourseSchedule::getData($add)['data'],
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
                $update = StudyShortCourseSchedule::where('id', $id)->update([
                    'institute_id'        => request('institute'),
                    'study_generation_id'    => request('study_generation'),
                    'study_subject_id'      => request('study_subject')
                ]);
                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => StudyShortCourseSchedule::getData($id),
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
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function existsToTable()
    {
        return StudyShortCourseSchedule::where('institute_id', request('institute'))
            ->where('study_generation_id', request('study_generation'))
            ->where('study_subject_id', request('study_subject'))
            ->first();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudyShortCourseSchedule::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudyShortCourseSchedule::whereIn('id', $id)->delete();
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
