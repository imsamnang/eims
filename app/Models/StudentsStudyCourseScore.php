<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class StudentsStudyCourseScore extends Model
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

    public static function getData($id = null, $edit = null, $paginate = null, $student_study_course_id = null, $generate = false)
    {


        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . StudentsStudyCourseScore::path('url') . '/add/'),
            ),
        );

        $response = array(
            'success'         => false,
            'data'            => [],
            'study_subject'   => [],
            'pages'           => $pages,
            'gender'          => Students::gender(null),
            'message'         => __('No Data'),
        );

        $data = array();


        $orderBy = 'ASC';
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


        $get = StudentsStudyCourseScore::select((new StudentsStudyCourseScore())->getTable() . '.*', (new Students())->getTable() . '.gender_id')
            ->join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new StudentsStudyCourseScore())->getTable() . '.student_study_course_id')
            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id')
            ->join((new StudyCourseSession())->getTable(), (new StudyCourseSession())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.study_course_session_id')
            ->join((new StudyCourseSchedule())->getTable(), (new StudyCourseSchedule())->getTable() . '.id', (new StudyCourseSession())->getTable() . '.study_course_schedule_id')
            ->join((new Institute())->getTable(), (new Institute())->getTable() . '.id', (new StudyCourseSchedule())->getTable() . '.institute_id')
            ->whereNotIn('study_status_id', [7])
            ->orderBy((new StudentsStudyCourseScore())->getTable() . '.id', $orderBy);

        if ($id) {
            $get = $get->whereIn((new StudentsStudyCourseScore())->getTable() . '.id', $id);
        } else {
            if ($student_study_course_id) {

                $get = $get->where('student_study_course_id', $student_study_course_id);
            }
            if (request('course-sessionId')) {
                $get = $get->where('study_course_session_id', request('course-sessionId'));
            }
            if (request('instituteId')) {
                $get = $get->where((new StudyCourseSchedule())->getTable() . '.institute_id', request('instituteId'));
            }
        }






        $gender  = Students::gender($get);
        $node = StudentsStudyCourse::getData(null, 'count');

        if ($id == null && $student_study_course_id == null && count($get->get()->toArray()) < $node) {
            $get      = false; //
        } else {

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
        }


        $average_all = [];
        $recall = false;
        if ($get) {
            foreach ($get as $row) {
                $key = $row['id'];
                $node = StudentsStudyCourse::getData($row['student_study_course_id'])['data'][0];
                $study_course_session = StudyCourseSession::where('id', request('course-sessionId', $node['study_course_session']['id']))
                    ->first();
                $scores = StudentsScore::getData($row['id'], $study_course_session->id);
                $study_subject = StudyCourseRoutine::getSubject($study_course_session->id);

                if ($generate) {
                    return array(
                        'success'        => true,
                        'data'           => [],
                        'study_subject'  => $study_subject,
                        'gender'         => $gender,
                        'pages'          => $pages
                    );
                }

                $grade = __('Fail');

                $scores = $scores + [
                    'attendance_score' => [
                        'id'    => $row['id'],
                        'study_subject' => [
                            'name'  => __('Attendance score'),
                        ],
                        'score' => $row['attendance_score'],
                        'pass_or_fail' => null,
                        'action'        => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/attendance/edit/' . $row['id']),
                    ],
                    'other_score' => [
                        'id'    => $row['id'],
                        'study_subject' => [
                            'name'  => __('Other score'),
                        ],
                        'score' => $row['other_score'],
                        'pass_or_fail' => null,
                        'action'        => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/other/edit/' . $row['id']),
                    ]
                ];
                $total_marks = StudentsScore::where('student_study_course_score_id', $row['id'])->sum('subject_score') + $row['attendance_score'] + $row['other_score'];


                $average = $total_marks > 0 ? $total_marks / count($study_subject) : 0;
                $study_grade = StudyGrade::getData();

                if ($study_grade['success']) {
                    foreach ($study_grade['data'] as $value) {
                        if ($average  > 0 && $average <= $value['score'])
                            $grade = $value['name'];
                    }
                } else {
                    if ($average >= 0 && $average <= 50)
                        $grade = __('Fail');
                    if ($average > 50 && $average <= 70)
                        $grade = __('C');
                    if ($average > 70 && $average <= 80)
                        $grade = __('B');
                    if ($average > 80 && $average <= 90)
                        $grade = __('A');
                    if ($average > 90)
                        $grade = __('E');
                }

                $data[$key]         = array(
                    'id'           => $row['id'],
                    'name'         => $node['name'],
                    'node'         => $node,
                    'scores'       => $scores,
                    'total_marks'  => $total_marks,
                    'average'      => $average,
                    'grade'        => $grade,
                    'action'       => [
                        'view'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/view/' . $row['id']),
                        'edit'   => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/edit/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Students::path('url') . '/' . StudentsStudyCourse::path('url') . '/' . StudentsStudyCourseScore::path('url') . '/delete/' . $row['id']),
                    ]
                );

                $average_all[$key] = $data[$key]['average'] ? $data[$key]['average'] : 0;


                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['node']['name'],
                    'image'  => $data[$key]['node']['photo'],
                    'action' => $data[$key]['action'],

                );
            }


            // if (Auth::user()->role_id != 6) {
            //     arsort($average_all);
            //     $i = 1;
            //     foreach ($average_all as $key => $value) {
            //         if($value){
            //             $data[$key]['grade'] = $i;
            //             StudentsStudyCourseScore::updateGradeToTable($data[$key]['id'], $i);
            //             $i++;
            //         }else{
            //             $data[$key]['grade'] = null;
            //             StudentsStudyCourseScore::updateGradeToTable($data[$key]['id'], null);
            //         }


            //     }
            // }

            $response       = array(
                'success'        => true,
                'data'           => array_values($data),
                'study_subject'  => $study_subject,
                'gender'         => $gender,
                'pages'          => $pages
            );
        } else {

            if ($id) {
                if (!StudentsStudyCourseScore::existsToTable($id)) {
                    $recall = true;

                    StudentsStudyCourseScore::addToTable($id);
                }
            } else {
                foreach (StudentsStudyCourse::getData(null, 'origin') as $n) {

                    if ($n['study_status_id'] && in_array($n['study_status_id'], [4, 6]) == false) {
                        if (!StudentsStudyCourseScore::existsToTable($n['id'])) {
                            $recall = true;
                            StudentsStudyCourseScore::addToTable($n['id']);
                        }
                    }
                }
            }
        }
        if ($recall) {

            return StudentsStudyCourseScore::getData($id, $edit, $paginate);
        }

        return $response;
    }




    public static function existsToTable($student_study_course_id)
    {
        $response = false;
        if ($student_study_course_id) {
            $exists =  StudentsStudyCourseScore::where('student_study_course_id', $student_study_course_id)->first();
            if ($exists) {
                $response = $exists;
            }
        }

        return $response;
    }

    public static function addToTable($student_study_course_id)
    {

        $response  = array();
        if (StudentsStudyCourseScore::existsToTable($student_study_course_id)) {

            $response       = array(
                'success'   => false,
                'type'      => 'add',
                'message'   => array(
                    'title' => __('Error'),
                    'text'  => __('Exists'),
                    'button'      => array(
                        'confirm' => __('Ok'),
                        'cancel'  => __('Cancel'),
                    ),
                ),

            );
        } else {
            $add = StudentsStudyCourseScore::insertGetId([
                'student_study_course_id'        => $student_study_course_id
            ]);
            if ($add) {
                $response       = array(
                    'success'   => true,
                    'data'      => StudentsStudyCourseScore::getData(null, null, null, $student_study_course_id)['data'],
                    'type'      => 'add',
                    'message'   => array(
                        'title' => __('Success'),
                        'text'  => __('Add Successfully'),
                        'button'      => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),

                );
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

                foreach (request('study_subject') as $key => $value) {
                    StudentsScore::addToTable($id, $key, $value);
                }

                $update = StudentsStudyCourseScore::where('id', $id)->update([
                    'student_study_course_id'  => request('students'),
                    'attendance_score'         => request('attendance_score'),
                    'other_score'              => request('other_score'),
                ]);

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [],
                        'message'   => __('Update Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function updateMarksToTable($id, $type_marks, $score)
    {
        $response = [
            'sucess'    => false,
            'error'     => []
        ];
        if ($id && $type_marks &&  $score) {

            $update =  StudentsStudyCourseScore::where('id', $id)->update([
                $type_marks => $score,
            ]);

            if ($update) {

                $response       = array(
                    'success'   => true,
                    'type'      => 'update',
                    'data'      => StudentsStudyCourseScore::getData($id)['data'],
                    'message'   =>  __('Update Successfully'),
                );
            }
        }

        return $response;
    }

    public static function updateGradeToTable($id, $grade)
    {
        $response = [
            'sucess'    => false,
            'error'     => []
        ];
        if ($id) {
            $update =  StudentsStudyCourseScore::where('id', $id)->update([
                'grade' => $grade,
            ]);
            if ($update) {
                $response       = array(
                    'success'   => true,
                    'type'      => 'update',
                    //'data'      => StudentsStudyCourseScore::getData($id)['data'],
                    'message'   =>  __('Update Successfully'),
                );
            }
        }
        return $response;
    }
}
