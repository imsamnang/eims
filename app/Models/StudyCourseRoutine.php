<?php

namespace App\Models;

use Carbon\Carbon;
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
    public function study_course_schedule()
    {
        return $this->belongsToMany(StudyCourseSchedule::class, StudyCourseSession::class, 'id', 'study_course_schedule_id', 'study_course_session_id');
    }

    public function study_course_session()
    {
        return $this->belongsTo(StudyCourseSession::class, 'study_course_session_id');
    }
    /**
     * @param array $routines
     */
    public static function getGroupTimes($routines)
    {
        $data = [];
        foreach ($routines as $row) {
            $data[$row->start_time . '-' . $row->end_time][0] = $row->start_time . ' ⚊ ' . $row->end_time;
            $data[$row->start_time . '-' . $row->end_time][$row->day_id] = [
                'teacher'   => Staff::where('id', $row->teacher_id)->get()->map(function ($row) {
                    $row->name = $row->{'first_name_' . app()->getLocale()} . ' ' . $row->{'last_name_' . app()->getLocale()};
                    $row->photo = ImageHelper::site(Staff::path('image'), $row->photo);
                    return $row;
                })->first(),
                'study_subjects' => StudySubjects::where('id',  $row->study_subject_id)->pluck(app()->getLocale())->first(),
                'study_class' => StudyClass::where('id',  $row->study_class_id)->pluck(app()->getLocale())->first(),
            ];
        }
        return array_values($data);
    }
    public static function getGroupTimesEdit($routines)
    {

        $data = [];
        foreach ($routines as $key => $row) {
            $data[$row->start_time . '-' . $row->end_time][0] = $row->start_time . '-' . $row->end_time;
            $data[$row->start_time . '-' . $row->end_time][$row->day_id] = [
                'day'   => $row->day_id,
                'teacher'   => $row->teacher_id,
                'study_subjects' => $row->study_subject_id,
                'study_class' => $row->study_class_id,
            ];
        }
        return array_values($data);
    }

    public static function addToTable()
    {
        $response = array();
        $validate = self::validate(null, '.*');
        $rules = $validate['rules'];
        $rules['study_course_session'] = 'required|unique:' . self::path('table') . ',study_course_session_id';
        $validator = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                $values = array();
                foreach (request('days') as $k => $days) {

                    foreach ($days as $key => $value) {
                        $teacher = request('teachers')[$k][$key];
                        $study_subject = request('study_subjects')[$k][$key];
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
                $teachers =  array_column($values, 'teacher_id');
                $subjects =  array_column($values, 'study_subject_id');
                $study_class =  array_column($values, 'study_class_id');
                $teachersAllIsNull = empty(array_filter($teachers, function ($a) {
                    return $a !== null;
                }));
                $subjectsAllIsNull = empty(array_filter($subjects, function ($a) {
                    return $a !== null;
                }));
                $studyClassAllIsNull = empty(array_filter($study_class, function ($a) {
                    return $a !== null;
                }));


                if ($teachersAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Teacher on cell.'),
                    ];
                }
                if ($subjectsAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Subjects on cell.'),
                    ];
                }
                if ($studyClassAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Study class on cell.'),
                    ];
                }

                $add = self::insert($values);
                if ($add) {
                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], request('study_course_session'))[0]])->render(),
                        'message'   => __('Add Successfully'),
                    );
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return $response;
    }
    public static function updateToTable($study_course_session_id)
    {

        $response = array();
        $validate = self::validate(null,'.*');
        $rules  = $validate['rules'];
        $rules['study_course_session'] = 'required|unique:' . self::path('table') . ',study_course_session_id,' . $study_course_session_id.',study_course_session_id';
        $validator          = Validator::make(request()->all(), $rules, $validate['messages'], $validate['attributes']);
        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $table = self::where('study_course_session_id', $study_course_session_id);
                $old = $table->first();
                $table->delete();
                $values = array();
                foreach (request('days') as $k => $days) {
                    foreach ($days as $key => $value) {
                        $teacher = request('teachers')[$k][$key];
                        $study_subject = request('study_subjects')[$k][$key];
                        $study_class = request('study_class')[$k][$key];

                        $values[] = array(
                            'study_course_session_id' => $study_course_session_id,
                            'day_id'                   => $value,
                            'start_time'               => request('start_time')[$k],
                            'end_time'                 => request('end_time')[$k],
                            'teacher_id'               => is_numeric($teacher) ? $teacher : null,
                            'study_subject_id'         => is_numeric($study_subject) ? $study_subject : null,
                            'study_class_id'           => is_numeric($study_class) ? $study_class : null,
                            'created_at'               => $old->created_at,
                            'updated_at'               => Carbon::now(),
                        );
                    }
                }

                $teachers =  array_column($values, 'teacher_id');
                $subjects =  array_column($values, 'study_subject_id');
                $study_class =  array_column($values, 'study_class_id');
                $teachersAllIsNull = empty(array_filter($teachers, function ($a) {
                    return $a !== null;
                }));
                $subjectsAllIsNull = empty(array_filter($subjects, function ($a) {
                    return $a !== null;
                }));
                $studyClassAllIsNull = empty(array_filter($study_class, function ($a) {
                    return $a !== null;
                }));


                if ($teachersAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Teacher on cell.'),
                    ];
                }
                if ($subjectsAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Subjects on cell.'),
                    ];
                }
                if ($studyClassAllIsNull) {
                    return [
                        'success'   => false,
                        'type'      => 'add',
                        'message'   => __('Insert Study class on cell.'),
                    ];
                }


                $add = self::insert($values);
                if ($add) {
                    $class  = self::path('controller');
                    $controller = new $class;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [['id' => $study_course_session_id]],
                        'html'      => view(self::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $study_course_session_id)[0]])->render(),
                        'message'   =>  __('Update Successfully'),
                    );
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return $response;
    }

    public static function deleteFromTable($study_course_session_id)
    {
        if ($study_course_session_id) {
            $study_course_session_id  = explode(',', $study_course_session_id);
            if (self::whereIn('study_course_session_id', $study_course_session_id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('study_course_session_id', $study_course_session_id)->delete();
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
