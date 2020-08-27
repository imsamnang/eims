<?php

namespace App\Models;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyCourseSession extends Model
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
            'controller'   => 'App\Http\Controllers\Study\\'.$tableUcwords.'Controller',
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
    public function study_course_schedule()
    {
        return $this->hasOne(StudyCourseSchedule::class,'id','study_course_schedule_id');
    }

    public function study_course_routine()
    {
       return $this->hasMany(StudyCourseRoutine::class,'study_course_session_id');
    }

    public function study_session()
    {
        return $this->hasOne(StudySession::class,'id','study_session_id');
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
                $exists = self::existsToTable();
                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'data'      => $exists,
                        'type'      => 'add',
                        'message'   => __('Already exists'),
                    );
                } else {
                    $add = self::insertGetId([
                        'study_course_schedule_id'  => request('study_course_schedule'),
                        'study_session_id'      => request('study_session'),
                        'study_start'      => DateHelper::convert(request('study_start')),
                        'study_end'      => DateHelper::convert(request('study_end')),
                    ]);
                    if ($add) {
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
                $update = self::where('id', $id)->update([
                    'study_course_schedule_id'  => request('study_course_schedule'),
                    'study_session_id'      => request('study_session'),
                    'study_start'      => DateHelper::convert(request('study_start')),
                    'study_end'      => DateHelper::convert(request('study_end')),
                ]);
                if ($update) {
                    $class  = self::path('controller');
                        $controller = new $class;
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => [['id' => $id]],
                            'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                            'message'   => __('Update Successfully'),
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
        return self::where('study_course_schedule_id', request('study_course_schedule'))
            ->where('study_session_id', request('study_session'))
            ->where('study_start', DateHelper::convert(request('study_start')))
            ->where('study_end', DateHelper::convert(request('study_end')))
            ->first();
    }
    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (self::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('id', $id)->delete();
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
