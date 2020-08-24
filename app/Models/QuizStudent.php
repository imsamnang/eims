<?php

namespace App\Models;

use DomainException;


use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormQuizStudent;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Validator;

class QuizStudent extends Model
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
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
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

    public static function getData($id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/add/'),
            ),
        );


        $orderBy = 'DESC';
        $data = array();
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
        $get = QuizStudent::select((new QuizStudent())->getTable() . '.*')
            ->join((new Quiz())->getTable(), (new Quiz())->getTable() . '.id', (new QuizStudent())->getTable() . '.quiz_id')
            ->join((new Institute())->getTable(), (new Institute())->getTable() . '.id', (new Quiz())->getTable() . '.institute_id')
            ->orderBy((new QuizStudent())->getTable() . '.id', $orderBy);

        if ($id) {
            $get = $get->whereIn((new QuizStudent())->getTable() . '.id', $id);
        } else {
            if (request('instituteId')) {
                $get = $get->where('institute_id', request('instituteId'));
            }
            if (Auth::user()->role_id == 8) {
                $get = $get->where('staff_id', Auth::user()->node_id);
            }
        }



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

                $student_study_course =   StudentsStudyCourse::where('id', $row['student_study_course_id'])->first()->toArray();
                $student_request =   StudentsRequest::where('id', $student_study_course['student_request_id'])->first()->toArray();
                $student    =   Students::where('id', $student_request['student_id'])->first()->toArray();
                $node = [
                    'id'            => $student['id'],
                    'first_name'         => array_key_exists('first_name_' . app()->getLocale(), $student) ? $student['first_name_' . app()->getLocale()] : $student['first_name_en'],
                    'last_name'          => array_key_exists('last_name_' . app()->getLocale(), $student) ? $student['last_name_' . app()->getLocale()] : $student['last_name_en'],
                    'gender'    => $student['gender_id'] ? (Gender::getData($student['gender_id'])['data'][0]) : null,
                    'photo'     => ImageHelper::site(Students::path('image'), $student['photo']),
                    'email' => $student['email'],
                    'phone' => $student['phone'],
                ];
                $quiz_answered = QuizStudentAnswer::getData($row['id']);

                $data[$key]         = array(
                    'id'            => $row['id'],
                    'quiz'         => Quiz::getData($row['quiz_id'])['data'][0],
                    'quiz_answered'  => $quiz_answered['data'],
                    'quiz_answered_marks'  => $quiz_answered['total_marks'],
                    'student'      => [
                        'id'        =>  $student_study_course['id'],
                        'name'      =>  $node['first_name'] . ' ' . $node['last_name'],
                        'photo'     =>  $student_study_course['photo'] ? (ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $student_study_course['photo'])) : $node['photo'],
                        'node'      =>  $node,
                    ],
                    'action'        => [
                        'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['student']['name'],
                    'image'   =>  $data[$key]['student']['photo'],
                    'action' => $data[$key]['action'],

                );
            }
            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages,
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

    public static function getDataTable()
    {
        $model = QuizStudent::select((new QuizStudent())->getTable() . '.*')
            ->join((new Quiz())->getTable(), (new Quiz())->getTable() . '.id', (new QuizStudent())->getTable() . '.quiz_id')
            ->join((new StudentsStudyCourse())->getTable(), (new StudentsStudyCourse())->getTable() . '.id', (new QuizStudent())->getTable() . '.student_study_course_id')
            ->join((new StudentsRequest())->getTable(), (new StudentsRequest())->getTable() . '.id', (new StudentsStudyCourse())->getTable() . '.student_request_id')
            ->join((new Students())->getTable(), (new Students())->getTable() . '.id', (new StudentsRequest())->getTable() . '.student_id');


        return DataTables::eloquent($model)
            ->setTransformer(function ($row) {
                $row = $row->toArray();
                $student_study_course =   StudentsStudyCourse::where('id', $row['student_study_course_id'])->first()->toArray();
                $student_request =   StudentsRequest::where('id', $student_study_course['student_request_id'])->first()->toArray();
                $student    =   Students::where('id', $student_request['student_id'])->first()->toArray();
                $node = [
                    'id'            => $student['id'],
                    'first_name'         => array_key_exists('first_name_' . app()->getLocale(), $student) ? $student['first_name_' . app()->getLocale()] : $student['first_name_en'],
                    'last_name'          => array_key_exists('last_name_' . app()->getLocale(), $student) ? $student['last_name_' . app()->getLocale()] : $student['last_name_en'],
                    'gender'    => $student['gender_id'] ? (Gender::getData($student['gender_id'])['data'][0]) : null,
                    'photo'     => ImageHelper::site(Students::path('image'), $student['photo']),
                ];
                $quiz_answered = QuizStudentAnswer::getData($row['id']);

                return [
                    'id'            => $row['id'],
                    'quiz'         => Quiz::getData($row['quiz_id'])['data'][0],
                    'quiz_answered'  => $quiz_answered['data'],
                    'quiz_answered_marks'  => $quiz_answered['total_marks'],
                    'student'      => [
                        'id'        =>  $student_study_course['id'],
                        'name'      =>  $student['first_name_km'] . ' ' . $student['last_name_km'] . ' - ' . $student['first_name_en'] . ' ' . $student['last_name_en'],
                        'photo'     =>  $student_study_course['photo'] ? (ImageHelper::site(Students::path('image') . '/' . StudentsStudyCourse::path('image'), $student_study_course['photo'])) : $node['photo'],
                        'node'      =>  $node,
                    ],
                    'action'        => [
                        'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizStudent::path('url') . '/delete/' . $row['id']),
                    ]
                ];
            })
            ->filter(function ($query) {
                if (Auth::user()->role_id == 8) {
                    $query = $query->where((new Quiz())->getTable() . '.staff_id', Auth::user()->node_id);
                } elseif (Auth::user()->role_id == 2) {
                    $query =  $query->where((new Quiz())->getTable() . '.institute_id', Auth::user()->institute_id);
                }

                if (request('quizId')) {
                    $query = $query->where((new QuizStudent())->getTable() . '.quiz_id', request('quizId'));
                }

                if (request('search.value')) {
                    foreach (request('columns') as $i => $value) {
                        if ($value['searchable']) {
                            if ($value['data'] == 'student.name') {
                                $query = Students::searchName($query, request('search.value'));
                            }
                        }
                    }
                }

                return $query;
            })
            ->order(function ($query) {
                if (request('order')) {
                    foreach (request('order') as $order) {
                        $col = request('columns')[$order['column']];
                        if ($col['data'] == 'id') {
                            $query->orderBy('id', $order['dir']);
                        }
                    }
                }
            })
            ->toJson();
    }



    public static function addToTable()
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormQuizStudent::rules(), FormQuizStudent::messages(), FormQuizStudent::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $qsid = '';

                if (trim(request('add_by')) == 'scid') {
                    foreach (request('student') as $student_study_course_id) {
                        if (!QuizStudent::existsToTable(request('quiz'), $student_study_course_id)) {

                            $values = [
                                'quiz_id'  => trim(request('quiz')),
                                'student_study_course_id'  => $student_study_course_id,
                            ];
                            $add = QuizStudent::insertGetId($values);
                            if ($add) {
                                $qsid  .= $add . ',';
                            }
                        }
                    }
                } elseif (trim(request('add_by')) == 'cid') {
                    foreach (request('study_course_session') as $study_course_session_id) {
                        $studentCourseSession = StudentsStudyCourse::where('study_course_session_id', $study_course_session_id)->get()->toArray();

                        if ($studentCourseSession) {
                            foreach ($studentCourseSession as $student_study_course) {
                                if (!QuizStudent::existsToTable(request('quiz'), $student_study_course['id'])) {

                                    $values = [
                                        'quiz_id'  => trim(request('quiz')),
                                        'student_study_course_id'  => $student_study_course['id'],
                                    ];
                                    $add = QuizStudent::insertGetId($values);
                                    if ($add) {
                                        $qsid  .= $add . ',';
                                    }
                                }
                            }
                        }
                    }
                }


                if ($qsid) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => QuizStudent::getData($qsid)['data'],
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
        $validator          = Validator::make(request()->all(), FormQuizStudent::rules(), FormQuizStudent::messages(), FormQuizStudent::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {

                $update = QuizStudent::where('id', $id)->update([
                    'quiz_id'        => trim(request('quiz')),
                    'student_study_course_id'     => trim(request('student')),
                ]);
                if ($update) {

                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => QuizStudent::getData($id),
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function existsToTable($quiz_id, $student_study_course_id)
    {
        $response = false;
        if ($quiz_id && $student_study_course_id) {
            $exists =  QuizStudent::where('quiz_id', $quiz_id)->where('student_study_course_id', $student_study_course_id)->first();
            if ($exists) {
                $response = $exists;
            }
        }

        return $response;
    }


    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (QuizStudent::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = QuizStudent::whereIn('id', $id)->delete();
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
