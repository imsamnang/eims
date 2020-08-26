<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use App\Helpers\Encryption;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyCourseRoutine extends Model
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

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'](), $validate['attributes']());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $exists = self::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {

                    $values = array();
                    foreach (request('day') as $k => $day) {
                        foreach ($day as $key => $value) {
                            $teacher = request('teacher')[$k][$key];
                            $study_subject = request('study_subject')[$k][$key];
                            $study_class = request('study_class')[$k][$key];

                            $values[] = array(
                                'study_course_session_id' => request('study_course_session'),
                                'day_id'                   => $value,
                                'start_time'               => request('start_time')[$k],
                                'end_time'                 => request('end_time')[$k],
                                'teacher_id'               => is_numeric($teacher) ? $teacher : null,
                                'study_subject_id'         => is_numeric($study_subject) ? $study_subject : null,
                                'study_class_id'           => is_numeric($study_class) ? $study_class : null,
                            );
                        }
                    }

                    $add = self::insert($values);
                    if ($add) {
                        if (request()->hasFile('image')) {
                            $image    = request()->file('image');
                            $image   = ImageHelper::uploadImage($image, self::path('image'));
                            self::updateImageToTable($add, $image);
                        }
                        $class  = self::path('controller');
                        $controller = new $class;
                        $response       = array(
                            'success'   => true,
                            'type'      => 'add',
                            'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                            'message'   => __('Add Successfully'),
                        );
                    }
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }
    public static function updateToTable()
    {

        $response           = array();
        $validate = self::validate();

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'](), $validate['attributes']());
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $exists = self::existsToTable();

                if ($exists) {
                    self::where('study_course_session_id', $exists->study_course_session_id)->delete();
                    $values = array();
                    foreach (request('day') as $k => $day) {
                        foreach ($day as $key => $value) {
                            $teacher = request('teacher')[$k][$key];
                            $study_subject = request('study_subject')[$k][$key];
                            $study_class = request('study_class')[$k][$key];

                            $values[] = array(
                                'study_course_session_id' => request('study_course_session'),
                                'day_id'                   => $value,
                                'start_time'               => request('start_time')[$k],
                                'end_time'                 => request('end_time')[$k],
                                'teacher_id'               => is_numeric($teacher) ? $teacher : null,
                                'study_subject_id'         => is_numeric($study_subject) ? $study_subject : null,
                                'study_class_id'           => is_numeric($study_class) ? $study_class : null,
                                'created_at'               => $exists->created_at,
                                'updated_at'               => Carbon::now(),
                            );
                        }
                    }


                    $add = self::insert($values);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => [],
                            'type'      => 'update',
                            'message'   =>  __('Update Successfully'),
                        );
                    }
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }
    public static function existsToTable()
    {
        return self::where('study_course_session_id', request('study_course_session'))->first();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {

            $ids  = explode(',', $id);
            $id   = [];
            foreach ($ids as $key => $value) {
                $id[] = Encryption::decode($value)['study_course_session_id'];
            }
            if (self::whereIn('study_course_session_id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('study_course_session_id', $id)->delete();
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
