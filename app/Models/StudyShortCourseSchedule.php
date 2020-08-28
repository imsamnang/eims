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
            'requests'   => 'App\Http\Requests\Form' . $tableUcwords,
            'controller'   => 'App\Http\Controllers\Study\\' . $tableUcwords . 'Controller',
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
        return $key ? @$validate[$key] : $validate;
    }

    public function institute()
    {
        return $this->hasMany(Institute::class, 'id', 'institute_id');
    }

    public function study_generation()
    {
        return $this->hasOne(StudyGeneration::class, 'id', 'study_generation_id');
    }
    public function study_subjects()
    {
        return $this->hasOne(StudySubjects::class, 'id', 'study_subject_id');
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

                $add = StudyShortCourseSchedule::insertGetId([
                    'institute_id'           => request('institute'),
                    'study_generation_id'    => request('study_generation'),
                    'study_subject_id'       => request('study_subject'),
                    'study_session_id'       => request('study_session'),
                    'study_start'            => request('study_start'),
                    'study_end'              => request('study_end'),
                    'province_id'            => request('province'),
                    'district_id'            => request('district'),
                    'commune_id'             => request('commune'),
                    'village_id'             => request('village'),
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
                    'institute_id'           => request('institute'),
                    'study_generation_id'    => request('study_generation'),
                    'study_subject_id'       => request('study_subject'),
                    'study_session_id'       => request('study_session'),
                    'study_start'            => request('study_start'),
                    'study_end'              => request('study_end'),
                    'province_id'            => request('province'),
                    'district_id'            => request('district'),
                    'commune_id'             => request('commune'),
                    'village_id'             => request('village'),
                ]);
                if ($update) {

                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [['id' => $id]],
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   => __('Add Successfully'),
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
