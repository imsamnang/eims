<?php

namespace App\Models;

use DomainException;


use App\Http\Requests\FormQuizAnswers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class QuizAnswers extends Model
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

    public static function getData($quiz_question_id = null, $edit = null, $paginate = null)
    {
        $pages['form'] = array(
            'action'  => array(
                'add'    => url(Users::role() . '/' . QuizAnswers::path('url') . '/add/'),
            ),
        );



        $data = array();

        $get = QuizAnswers::orderBy('id', 'desc');

        if ($quiz_question_id) {
            $get = $get->where('quiz_question_id', $quiz_question_id);
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
                // if( $row['id'] == 1 && Auth::user()->role_id != 1){
                //     continue;
                // }

                $data[$key]         = array(
                    'id'            => $row['id'],
                    'answer'        => $row['answer'],
                    'correct_answer' => $row['correct_answer'],
                    'action'        => [
                        'edit' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswers::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswers::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . Quiz::path('url') . '/' . QuizAnswers::path('url') . '/delete/' . $row['id']),
                    ]
                );
                $pages['listData'][] = array(
                    'id'     => $data[$key]['id'],
                    'name'   => $data[$key]['answer'],
                    'image'   => null,
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

    public static function addToTable($quiz_question_id)
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormQuizAnswers::rules('.*'), FormQuizAnswers::messages(), FormQuizAnswers::attributes('.*'));

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            $values = [];
            foreach (request('answer') as $key => $answer) {
                $values[] = [
                    'quiz_question_id' => $quiz_question_id,
                    'answer'         => trim($answer),
                    'correct_answer' => isset(request('correct_answer')[$key]) ? 1 : 0,
                ];
            }
            try {
                $add = QuizAnswers::insert($values);
                if ($add) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => [],
                        'message'   => __('Add Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }

    public static function updateToTable($quiz_question_id)
    {

        $response           = array();
        $validator          = Validator::make(request()->all(), FormQuizAnswers::rules('.*'), FormQuizAnswers::messages(), FormQuizAnswers::attributes('.*'));

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {
                $ids = [];
                $update = null;
                foreach (request('answer') as $key => $answer) {
                    preg_match('/^id-/', $key, $match);
                    if ($match) {
                        $id = str_replace('id-', '', $key);
                        $ids[] = $id;
                        $update = QuizAnswers::where('id', $id)->update([
                            'answer'         => trim($answer),
                            'correct_answer' => isset(request('correct_answer')[$key]) ? 1 : 0,
                        ]);
                    } else {
                        $update = QuizAnswers::insertGetId([
                            'quiz_question_id' => $quiz_question_id,
                            'answer'         => trim($answer),
                            'correct_answer' => isset(request('correct_answer')[$key]) ? 1 : 0,
                        ]);
                        $ids[] = $update;
                    };
                }

                QuizAnswers::whereNotIn('id', $ids)->where('quiz_question_id', $quiz_question_id)->delete();

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        //'data'      => QuizAnswers::getData($id),
                        'message'   => __('Update Successfully'),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }


    public static function deleteFromTable($id)
    {
        if ($id) {
            $id  = explode(',', $id);
            if (QuizAnswers::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = QuizAnswers::whereIn('id', $id)->delete();
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
