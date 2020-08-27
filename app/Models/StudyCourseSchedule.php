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
    public function institute()
    {
        return $this->hasOne(Institute::class,  'id', 'institute_id');
    }
    public function study_course_session()
    {
        return $this->hasMany(StudyCourseSession::class);
    }
    public function study_program()
    {
        return $this->hasOne(StudyPrograms::class,'id','study_program_id');
    }
    public function study_course()
    {
        return $this->hasOne(StudyCourse::class,'id','study_course_id');
    }
    public function study_generation()
    {
        return $this->hasOne(StudyGeneration::class,'id','study_generation_id');
    }
    public function study_academic_year()
    {
        return $this->hasOne(StudyAcademicYears::class,'id','study_academic_year_id');
    }
    public function study_semester()
    {
        return $this->hasOne(StudySemesters::class,'id','study_semester_id');
    }

    public function study_course_routine(){
      return  $this->hasManyThrough(
            StudyCourseRoutine::class,
            StudyCourseSession::class,
            'study_course_schedule_id',
            'study_course_session_id',
            'id',
            'id'
        );

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
                $update = StudyCourseSchedule::where('id', $id)->update([
                    'institute_id'        => request('institute'),
                    'study_program_id'      => request('study_program'),
                    'study_course_id'        => request('study_course'),
                    'study_generation_id'    => request('study_generation'),
                    'study_academic_year_id' => request('study_academic_year'),
                    'study_semester_id'      => request('study_semester')
                ]);
                if ($update) {
                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [['id'=>$id]],
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
