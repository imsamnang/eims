<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class QuizStudentAnswer extends Model
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
    public static function getData($quiz_student_id, $paginate = null)
    {
        $total_marks = 0;
        $response       = array(
            'success'   => false,
            'data'      => [],
            'total_marks' => $total_marks . ' ' . __('score'),
        );
        if ($quiz_student_id) {
            $get = self::where('quiz_student_id', $quiz_student_id);
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

            $data = [];

            if ($get) {
                foreach ($get  as $key => $row) {
                    $data[$key] = [
                        'id'        => $row['id'],
                        'question'  => QuizQuestions::getData($row['quiz_question_id'])['data'][0],
                        'answered'  => $row['answered'],
                        'correct'   => false,
                        'correct_marks' => 0,
                        'score'     => $row['score'],
                    ];

                    if ($data[$key]['question']['quiz_answer_type']['id'] == 1) {
                        $quizAnswer = QuizAnswers::find($data[$key]['answered']);
                        if ($quizAnswer->correct_answer) {
                            $data[$key]['correct'] = true;
                            $data[$key]['correct_marks'] = $data[$key]['question']['score'];
                        };
                    } elseif ($data[$key]['question']['quiz_answer_type']['id'] == 2) {
                        $correct = [];
                        $correct_marks = 0;
                        $quizAnswerCorrect = QuizAnswers::where('quiz_question_id', $data[$key]['question']['id'])->where('correct_answer', 1)->count();
                        foreach (explode(',', $data[$key]['answered']) as $answer) {
                            $quizAnswer = QuizAnswers::find($answer);
                            if ($quizAnswer->correct_answer) {
                                $correct[] = $quizAnswer->correct_answer;
                                $correct_marks += $data[$key]['question']['score'] / $quizAnswerCorrect;
                            }
                        }

                        if ($quizAnswerCorrect == count($correct)) {
                            $data[$key]['correct'] = true;
                            $data[$key]['correct_marks'] = $data[$key]['question']['score'];
                        } else {
                            $data[$key]['correct'] = false;
                            $data[$key]['correct_marks'] = $correct_marks;
                        }
                    } elseif ($data[$key]['question']['quiz_answer_type']['id'] == 3) {
                        if ($data[$key]['question']['answer'][0]['answer'] == $data[$key]['answered']) {
                            $data[$key]['correct'] = true;
                            $data[$key]['correct_marks'] = $data[$key]['question']['score'];
                        }
                    }

                    $total_marks += $data[$key]['correct_marks'];
                }

                $response       = array(
                    'success'   => true,
                    'data'      => $data,
                    'total_marks' => $total_marks . ' ' . __('score'),
                );
            }
        }

        return $response;
    }
    public static function getData1($student_study_course_id)
    {

        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . 'study/' . Quiz::path('url') . '/' . self::path('url') . '/add/'),
            ),
        );

        $a = [];
        $b = [];
        if ($student_study_course_id && gettype($student_study_course_id) == 'string') {
            $student_study_course_id = explode(',', $student_study_course_id);
        }
        $get = QuizStudents::whereIn('student_study_course_id', $student_study_course_id);

        if (request('quizId')) {
            $get = $get->where('quiz_id', request('quizId'));
        }

        $get = $get->get()->toArray();
        if ($get) {
            foreach ($get as $qstu) {
                $countQ = QuizQuestions::where('quiz_id', $qstu['quiz_id'])->count();
                $countA = self::where('quiz_student_id', $qstu['id'])->count();

                if ($countQ != $countA) {
                    $qq = Quiz::getData($qstu['quiz_id'])['data'][0];
                    $a[$qstu['quiz_id']] = [
                        'id'    => $qstu['quiz_id'],
                        'name'  => $qq['name'],
                        'image'  => $qq['image'],
                        'quiz_student'    => $qstu['id'],
                        'children'  => &$b[$qstu['quiz_id']]
                    ];
                    $quizQuestion = QuizQuestions::where('quiz_id', $qstu['quiz_id'])->paginate(5)->toArray();

                    if ($quizQuestion) {
                        foreach ($quizQuestion['data'] as $question) {
                            $answered = self::where('quiz_student_id', $qstu['id'])->where('quiz_question_id', $question['id'])->get()->toArray();
                            $b[$qstu['quiz_id']][] = [
                                'id'    => $question['id'],
                                'quiz_type'    => QuizQuestionTypes::getData($question['quiz_question_type_id'])['data'][0],
                                'quiz_answer_type'    => QuizAnswerTypes::getData($question['quiz_answer_type_id'])['data'][0],
                                'question'    => $question['question'],
                                'answer_limit'  => QuizAnswers::where('quiz_question_id', $question['id'])->where('correct_answer', 1)->count(),
                                'answer'      => QuizAnswers::getData($question['id'])['data'],
                                'answered'    => $answered ? [
                                    'id'          => $answered[0]['id'],
                                    'answered'    => $answered[0]['answered'],
                                    'score'       => $answered[0]['score'],
                                ] : null,
                                'score'    => $question['score'],

                            ];
                        }

                        foreach ($quizQuestion as $key => $value) {
                            if ($key == 'data') {
                            } else {
                                $pages[$key] = $value;
                            }
                        }
                    }
                } else {
                    //Answer All already
                    $response = array(
                        'success'   => false,
                        'data'      => [],
                        'message'   => __('No Data'),
                        'pages'     => $pages
                    );
                }
            }

            if ($a) {
                $response = array(
                    'success'   => true,
                    'data'      => $a,
                    'pages'     => $pages

                );
            }
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'message'   => __('No Data'),
                'pages'     => $pages
            );
        }
        return $response;
    }



    public static function addToTable()
    {

        $response           = array();
        $validate = self::validate('.*');

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            if (self::exists(request('quiz_student'), request('quiz_question'))) {
                return array(
                    'success'   => false,
                    'type'      => 'exists',
                    'message'      => __('Already exists'),
                );
            }
            try {
                $quiz_question = QuizQuestions::find(request('quiz_question'));
                $answer = null;
                if ($quiz_question->quiz_answer_type_id == 1) {
                    $answer = request('answer')[0];
                } elseif ($quiz_question->quiz_answer_type_id == 2) {
                    $answer = request('answer');
                    $answer = implode(',', $answer);
                } elseif ($quiz_question->quiz_answer_type_id == 3) {
                    $answer = request('answer')[0];
                }
                $add = self::insertGetId([
                    'quiz_student_id'   => trim(request('quiz_student')),
                    'quiz_question_id'   => trim(request('quiz_question')),
                    'answered'   => $answer,
                ]);
                if ($add) {

                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => [
                            'id'          => $add,
                            'answered'    => request('answer'),
                        ],
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
        $validate = self::validate('.*');

        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'], $validate['attributes']);

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $quiz_student = self::where('id', $id)->first();
                if (!is_null($quiz_student->score)) {
                    $response       = array(
                        'success'   => false,
                        'type'      => 'update',
                        'data'      => [
                            'id'       => $id,
                            'question'    => QuizQuestions::getData($quiz_student->quiz_question_id),
                        ],
                        'message'   => array(
                            'title' => __('Error'),
                            'text'  => __('Update Unsuccessful'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    );
                } else {

                    $quiz_question = QuizQuestions::find($quiz_student->quiz_question_id);
                    $answer = null;
                    if ($quiz_question->quiz_answer_type_id == 1) {
                        $answer = request('answer')[0];
                    } elseif ($quiz_question->quiz_answer_type_id == 2) {
                        $answer = request('answer');
                        $answer = implode(',', $answer);
                    } elseif ($quiz_question->quiz_answer_type_id == 3) {
                        $answer = request('answer')[0];
                    }

                    $update = self::where('id', $id)->update([
                        'answered'   => $answer,
                    ]);
                    if ($update) {

                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => [
                                'id'          => $id,
                                'answered'    => request('answer'),
                            ],
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
    public static function updateAnswerAgainToTable($id)
    {
        $update = self::where('id', $id)->update([
            'score'     => null
        ]);
        if ($update) {
            $response       = array(
                'success'   => true,
                'type'      => 'update',
                'data'      => [
                    'id'       => $id,
                    'score'       => 0,
                ],
                'message'   => array(
                    'title' => __('Success'),
                    'text'  => __('answer_again.successfully'),
                    'button'   => array(
                        'confirm' => __('Ok'),
                        'cancel'  => __('Cancel'),
                    ),
                ),
            );
        } else {
            $response = response(
                array(
                    'success'   => false,
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('No-ID'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                )
            );
        }
        return $response;
    }
    public static function updateMarksToTable($id)
    {
        if ($id) {
            $response           = array();
        $validate = self::validate();

            $validator          = Validator::make(request()->all(),$validate['rules'],$validate['messages'],$validate['attributes']);

            if ($validator->fails()) {
                $response       = array(
                    'success'   => false,
                    'errors'    => $validator->getMessageBag(),
                );
            } else {
                try {

                    $update = self::where('id', $id)->update([
                        'score'     => trim(request('score'))
                    ]);
                    if ($update) {

                        $response       = array(
                            'success'   => true,
                            'type'      => 'update',
                            'data'      => [
                                'id'       => $id,
                                'score'       => trim(request('score')),
                            ],
                            'message'   =>  __('Update Successfully'),
                        );
                    }
                } catch (DomainException $e) {
                    return $e;
                }
            }
        } else {
            $response = response(
                array(
                    'success'   => false,
                    'message'   => array(
                        'title' => __('Error'),
                        'text'  => __('No Id'),
                        'button'   => array(
                            'confirm' => __('Ok'),
                            'cancel'  => __('Cancel'),
                        ),
                    ),
                )
            );
        }
        return $response;
    }
    public static function exists($quiz_student_id, $quiz_question_id)
    {
        return self::where('quiz_student_id', $quiz_student_id)->where('quiz_question_id', $quiz_question_id)->first();
    }
}
