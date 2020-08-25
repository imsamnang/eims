<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudentsStudyShortCourse extends Model
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
                $sid = '';
                foreach (request('students') as $stu_sh_c_request_id) {

                    if (!StudentsStudyShortCourse::existsToTable($stu_sh_c_request_id, request('study_short_course_session'))) {

                        $values = [
                            'stu_sh_c_request_id'  => $stu_sh_c_request_id,
                            'stu_sh_c_session_id'  => request('study_short_course_session'),
                        ];
                        $add = StudentsStudyShortCourse::insertGetId($values);
                        if ($add) {
                            $sid  .= $add . ',';
                        }
                    }
                }
                if ($sid) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => StudentsStudyShortCourse::getData($sid)['data'],
                        'message'   => __('Add Successfully'),

                    );
                } else {
                    $response       = array(
                        'success'   => false,
                        'errors'    => [],
                        'message'   => __('Add Unsuccessful') . PHP_EOL . __('Already exists'),
                    );
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
                $exists =  StudentsStudyShortCourse::existsToTable(request('students')[0], request('study_short_course_session'));

                if ($exists) {
                    $response       = array(
                        'success'   => false,
                        'errors'    => [],
                        'message'   =>  __('Update Unsuccessful') . PHP_EOL . __('Already exists'),
                    );
                }
                if (!$exists) {
                    $update = StudentsStudyShortCourse::where('id', $id)->update([
                        'stu_sh_c_request_id'  =>    request('students')[0],
                        'stu_sh_c_session_id'  => request('study_short_course_session'),
                    ]);
                    if ($update) {
                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => QuizStudents::getData($id),
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

    public static function updateImageToTable($id, $photo)
    {
        $response = array(
            'success'   => false,
            'message'   => __('Update Failed'),
        );
        if ($photo) {
            try {
                $update =  StudentsStudyShortCourse::where('id', $id)->update([
                    'photo'    => $photo,
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }

        return $response;
    }

    public static function existsToTable($stu_sh_c_request_id, $stu_sh_c_session_id)
    {
        $student_request = StudentsShortCourseRequest::where('id', $stu_sh_c_request_id)->get()->first();
        return StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
            ->where('student_id', $student_request->student_id)
            ->where('stu_sh_c_session_id', $stu_sh_c_session_id)
            ->groupBy('student_id')
            ->first();
    }

    public static function getStudy($student_id)
    {
        $get =  StudentsStudyShortCourse::join((new StudentsShortCourseRequest())->getTable(), (new StudentsShortCourseRequest())->getTable() . '.id', (new StudentsStudyShortCourse())->getTable() . '.stu_sh_c_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsShortCourseRequest())->getTable() . '.student_id')
            ->where((new StudentsShortCourseRequest())->getTable() . '.student_id', $student_id)
            ->groupBy('stu_sh_c_session_id')
            ->get()->toArray();

        $stu_sh_c_session_id = [];
        if ($get) {
            foreach ($get as $key => $row) {
                $stu_sh_c_session_id[] = $row['stu_sh_c_session_id'];
            }
            return StudyShortCourseSession::getData($stu_sh_c_session_id);
        } else {
            return StudyShortCourseSession::getData('null');
        }
    }

    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (StudentsStudyShortCourse::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = StudentsStudyShortCourse::whereIn('id', $id)->delete();
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
