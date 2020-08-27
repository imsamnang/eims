<?php

namespace App\Models;

use DomainException;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudentsShortCourseRequest extends Model
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

    public static function addToTable()
    {
        $response           = array();
        $validate = self::validate(null,'.*');

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
                foreach (request('students', []) as $key => $id) {
                    $values = [
                        'added_by'       => Auth::user()->id,
                        'student_id'     => $id,
                        'institute_id'     => request('institute'),
                        'study_subject_id'     => request('study_subject'),
                        'study_session_id'     => request('study_session'),
                        'description' => request('description'),
                    ];

                    if (StudentsShortCourseRequest::existsToTable($id)) {
                        $exists   = true;
                    } else {
                        $add = StudentsShortCourseRequest::insertGetId($values);
                        if ($add) {
                            if (count(request('students')) == 1 && request()->hasFile('photo')) {
                                $image      = request()->file('photo');
                                StudentsShortCourseRequest::updateImageToTable($add, ImageHelper::uploadImage($image, Students::path('image') . '/' . StudentsShortCourseRequest::path('image')));
                            }
                            $sid  .= $add . ',';
                        }
                    }
                }

                if ($sid) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => StudentsShortCourseRequest::getData($sid)['data'],
                        'message'   => __('Add Successfully'),
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
        $validate = self::validate();

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                if (StudentsShortCourseRequest::where('id', $id)->first()->status == 1) {
                    $response       =  array(
                        'success'   => false,
                        'message'   => __('Can not edit or delete!') . PHP_EOL
                            . __('Approved'),
                    );
                } else {
                    $exists = StudentsShortCourseRequest::existsToTable($id);
                    if ($exists) {
                        $response       = array(
                            'success'   => false,
                            'type'      => 'update',
                            'data'      => [],
                            'message'   => __('Already exists'),
                        );
                    } else {
                        $update = StudentsShortCourseRequest::where('id', $id)->update([
                            'updated_by'       => Auth::user()->id,
                            'institute_id'     => request('institute'),
                            'study_subject_id'     => request('study_subject'),
                            'study_session_id'     => request('study_session'),
                            'description' => request('description'),
                        ]);
                        if ($update) {
                            if (request()->hasFile('photo')) {
                                $image      = request()->file('photo');
                                StudentsShortCourseRequest::updateImageToTable($id, ImageHelper::uploadImage($image, Students::path('url') . '/' . StudentsShortCourseRequest::path('image')));
                            }
                            $response       = array(
                                'success'   => true,
                                'type'      => 'update',
                                'data'      => StudentsShortCourseRequest::getData($id)['data'],
                                'message'   => array(
                                    'title' => __('Success'),
                                    'text'  => __('Update Successfully'),
                                    'button'   => array(
                                        'confirm' => __('Ok'),
                                        'cancel'  => __('Cancel'),
                                    ),
                                ),
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
        return StudentsShortCourseRequest::where('id', $id)->update([
            'updated_by'       => Auth::user()->id,
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
                $update =  StudentsShortCourseRequest::where('id', $id)->update([
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
        return StudentsShortCourseRequest::where('student_id', $student_id)
            ->where('institute_id', request('institute'))
            ->where('study_subject_id', request('study_subject'))
            ->where('study_session_id', request('study_session'))
            ->get()->toArray();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudentsShortCourseRequest::whereIn('id', $id)->get()->toArray()) {

                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudentsShortCourseRequest::whereIn('id', $id)->where('status', '0')->delete();
                        if ($delete) {
                            return [
                                'success'   => true,
                                'message'   => __('Delete Successfully'),
                            ];
                        } else {

                            if (count($id) == 1) {

                                return [
                                    'success'   => false,                                    'message'   => __('Can not edit or delete!') . PHP_EOL
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
