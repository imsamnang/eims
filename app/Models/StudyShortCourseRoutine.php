<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use App\Helpers\Encryption;
use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudyShortCourseRoutine extends Model
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

    public static function getData($stu_sh_c_session_id = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/study/' . self::path('url') . '/add/'),
            ),
        );
        $data = array();
        $get = self::select((new self())->getTable() . '.*')
            ->join((new StudyShortCourseSession())->getTable(), (new StudyShortCourseSession())->getTable() . '.id', (new self())->getTable() . '.stu_sh_c_session_id')
            ->join((new StudyShortCourseSchedule())->getTable(), (new StudyShortCourseSchedule())->getTable() . '.id', (new StudyShortCourseSession())->getTable() . '.stu_sh_c_schedule_id')
            ->orderBy('stu_sh_c_session_id', 'DESC');

        if ($stu_sh_c_session_id) {
            $stu_sh_c_session_id  =  (gettype($stu_sh_c_session_id) == 'array') ? $stu_sh_c_session_id :  explode(',', $stu_sh_c_session_id);
            $get = $get->whereIn('stu_sh_c_session_id', $stu_sh_c_session_id);
        }

        if (request('instituteId')) {
            $get = $get->where('institute_id', request('instituteId'));
        }

        $get = $get->groupBy('stu_sh_c_session_id');

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
                $routine = self::where('stu_sh_c_session_id', $row['stu_sh_c_session_id'])->get()->toArray();
                $kdata = [];

                foreach ($routine as  $k) {
                    $teacher = Staff::find($k['teacher_id']);
                    if ($teacher) {

                        $first_name = array_key_exists('first_name_' . app()->getLocale(), $teacher->getAttributes()) ? $teacher->{'first_name_' . app()->getLocale()} : $teacher->{'first_name_en'};
                        $last_name  = array_key_exists('last_name_' . app()->getLocale(), $teacher->getAttributes()) ? $teacher->{'last_name_' . app()->getLocale()} : $teacher->{'last_name_en'};
                        $teacher = [
                            'id'    => $teacher->id,
                            'name'  => $first_name . ' ' . $last_name,
                            'email'  => $teacher->email,
                            'phone'  => $teacher->phone,
                            'photo'  => ImageHelper::site(Staff::path('image'), $teacher->photo),
                        ];
                    }
                    $kdata[$k['start_time'] . '-' . $k['end_time']]['times'] = [
                        'start_time' => $k['start_time'],
                        'end_time' => $k['end_time'],
                    ];
                    $kdata[$k['start_time'] . '-' . $k['end_time']]['days'][] = [
                        'day' => Days::getData($k['day_id'])['data'][0],
                        'teacher' => $teacher,
                        'study_class' => StudyClass::getData($k['study_class_id'])['data'][0],
                    ];
                }
                $generateId = Encryption::encode([
                    'stu_sh_c_session_id' => $row['stu_sh_c_session_id'],
                ]);
                $data[$key]    = array(
                    'id'    => $generateId,
                    'study_course_session' => StudyShortCoursesession::getData($row['stu_sh_c_session_id'])['data'][0],
                    'children' => array_values($kdata),
                    'action' => [
                        'edit'    => url(users::role() . '/study/' . self::path('url') . '/edit/' . $generateId),
                        'delete'  => url(users::role() . '/study/' . self::path('url') . '/delete/' . $generateId),
                    ]
                );
                $data[$key]['name'] =  $data[$key]['study_course_session']['name'];
                $data[$key]['image'] =  $data[$key]['study_course_session']['image'];

                $pages['listData'][$key] = array(
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
                            $study_class = request('study_class')[$k][$key];

                            $values[] = array(
                                'stu_sh_c_session_id' => request('study_course_session'),
                                'day_id'                   => $value,
                                'start_time'               => request('start_time')[$k],
                                'end_time'                 => request('end_time')[$k],
                                'teacher_id'               => is_numeric($teacher) ? $teacher : null,
                                'study_class_id'           => is_numeric($study_class) ? $study_class : null,
                            );
                        }
                    }

                    $add = self::insert($values);
                    if ($add) {
                        $response       = array(
                            'success'   => true,
                            'data'      => [],
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
                    self::where('stu_sh_c_session_id', $exists->stu_sh_c_session_id)->delete();
                    $values = array();
                    foreach (request('day') as $k => $day) {
                        foreach ($day as $key => $value) {
                            $teacher = request('teacher')[$k][$key];
                            $study_class = request('study_class')[$k][$key];

                            $values[] = array(
                                'stu_sh_c_session_id' => request('study_course_session'),
                                'day_id'                   => $value,
                                'start_time'               => request('start_time')[$k],
                                'end_time'                 => request('end_time')[$k],
                                'teacher_id'               => is_numeric($teacher) ? $teacher : null,
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
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }
    public static function existsToTable()
    {
        return self::where('stu_sh_c_session_id', request('study_course_session'))->first();
    }

    public static function deleteFromTable($id)
    {
        if ($id) {

            $ids  = explode(',', $id);
            $id   = [];
            foreach ($ids as $key => $value) {
                $id[] = Encryption::decode($value)['stu_sh_c_session_id'];
            }
            if (self::whereIn('stu_sh_c_session_id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('stu_sh_c_session_id', $id)->delete();
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
