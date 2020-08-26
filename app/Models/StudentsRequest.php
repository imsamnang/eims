<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Students\StudentsRequestController;

class StudentsRequest extends Model
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
            'attributes'  =>  $formRequests->attributes($flag),
            'messages'    =>  $formRequests->messages($flag),
            'questions'   =>  $formRequests->questions($flag),
        ];
        return $key? @$validate[$key] : $validate;
    }

    public static function addToTable()
    {
        $response           = array();
        $validate = self::validate('.*');

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {
                $sid      = '';
                $exists   = null;
                foreach (request('student', []) as $key => $id) {
                    $values = [
                        'student_id'     => $id,
                        'institute_id'     => request('institute'),
                        'study_program_id'     => request('study_program'),
                        'study_course_id'     => request('study_course'),
                        'study_generation_id'     => request('study_generation'),
                        'study_academic_year_id'     => request('study_academic_year'),
                        'study_semester_id'     => request('study_semester'),
                        'study_session_id'     => request('study_session'),
                        'description' => request('description'),
                    ];

                    if (StudentsRequest::existsToTable($id)) {
                        $exists   = true;
                    } else {
                        $add = StudentsRequest::insertGetId($values);
                        if ($add) {
                            if (count(request('student')) == 1 && request()->hasFile('photo')) {
                                $image      = request()->file('photo');
                                StudentsRequest::updateImageToTable($add, ImageHelper::uploadImage($image, Students::path('image') . '/' . StudentsRequest::path('image')));
                            }
                            $sid  .= $add . ',';
                        }
                    }
                }

                if ($sid) {
                    $controller = new StudentsRequestController;
                    $html = '';
                    foreach ($controller->list([], $sid) as  $row) {
                        $html .= view(StudentsRequest::path('view') . '.includes.tpl.tr', ['row' => $row])->render();
                    }

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => $html,
                        'message'   =>  __('Add Successfully')
                    );
                } elseif ($exists) {
                    $response       = array(
                        'success'   => false,
                        'type'      => 'add',
                        'data'      => [],
                        'message'   => __('Already exists'),
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
        $validate = self::validate('.*');

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                if (StudentsRequest::where('id', $id)->first()->status == 1) {
                    $response       =  array(
                        'success'   => false,
                        'message'   => __('Can not edit or delete!') . PHP_EOL
                            . __('Approved'),
                    );
                } else {
                    $exists = StudentsRequest::existsToTable($id);
                    if ($exists) {
                        $response       = array(
                            'success'   => false,
                            'type'      => 'update',
                            'data'      => [],
                            'message'   => __('Already exists'),
                        );
                    } else {
                        $update = StudentsRequest::where('id', $id)->update([
                            'institute_id'     => request('institute'),
                            'study_program_id'     => request('study_program'),
                            'study_course_id'     => request('study_course'),
                            'study_generation_id'     => request('study_generation'),
                            'study_academic_year_id'   => request('study_academic_year'),
                            'study_semester_id'     => request('study_semester'),
                            'study_session_id'     => request('study_session'),
                            'description' => request('description'),
                        ]);
                        if ($update) {
                            if (request()->hasFile('photo')) {
                                $image      = request()->file('photo');
                                StudentsRequest::updateImageToTable($id, ImageHelper::uploadImage($image, Students::path('url') . '/' . StudentsRequest::path('image')));
                            }
                            $controller = new StudentsRequestController;
                            $response       = array(
                                'success'   => true,
                                'type'      => 'update',
                                'data'      => [
                                    [
                                        'id' => $id,
                                    ]

                                ],
                                'html'      => view(StudentsRequest::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                                'message'   =>  __('Update Successfully')
                            );
                        }
                    }
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }
    public static function updateStatus($id, $status)
    {
        return StudentsRequest::where('id', $id)->update([
            'status' => $status
        ]);
    }

    public static function updateImageToTable($id, $image)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($image) {
            try {
                $update =  StudentsRequest::where('id', $id)->update([
                    'photo'    => $image,
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

    public static function existsToTable($student_id)
    {
        return StudentsRequest::where('student_id', $student_id)
            ->where('institute_id', request('institute'))
            ->where('study_program_id', request('study_program'))
            ->where('study_course_id', request('study_course'))
            ->where('study_generation_id', request('study_generation'))
            ->where('study_academic_year_id', request('study_academic_year'))
            ->where('study_semester_id', request('study_semester'))
            ->where('study_session_id', request('study_session'))
            ->get()->toArray();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);

            if (StudentsRequest::whereIn('id', $id)->exists()) {

                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudentsRequest::whereIn('id', $id)->where('status', '0')->delete();
                        if ($delete) {
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        } else {

                            if (count($id) == 1) {
                                return [
                                    'success'   => false,
                                    'message'   => __('Can not edit or delete!') . PHP_EOL
                                        . __('Approved'),
                                ];
                            }
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
