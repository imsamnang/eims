<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Quiz\QuizQuestionController;

class QuizQuestions extends Model
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



    public static function addToTable()
    {

        $response           = array();
        $validate_q = self::validate();
        $validate_a = QuizAnswers::validate('.*');
        $validator          = Validator::make(
            request()->all(),
            $validate_q['rules'] + $validate_a['rules'],
            $validate_q['messages'] + $validate_a['messages'],
            $validate_q['attributes'] + $validate_a['attributes']
        );

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $add = QuizQuestions::insertGetId([
                    'quiz_id'        => trim(request('quiz')),
                    'quiz_question_type_id' => trim(request('quiz_type')),
                    'quiz_answer_type_id' => trim(request('quiz_answer_type')),
                    'question'       => trim(request('question')),
                    'score'       => trim(request('score')),
                ]);
                if ($add && QuizAnswers::addToTable($add)['success']) {
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
        $newAnswer = [];
        foreach (request('answer') as $key => $value) {
            if ($value) {
                $newAnswer[$key] = $value;
            }
        }
        request()->merge(['answer' => $newAnswer]);
        $response           = array();
        $validate_q = QuizAnswers::validate();
        $validate_a = QuizAnswers::validate('.*');
        $validator          = Validator::make(
            request()->all(),
            $validate_q['rules'] + $validate_a['rules'],
            $validate_q['messages'] + $validate_a['messages'],
            $validate_q['attributes'] + $validate_a['attributes']
        );

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $update = QuizQuestions::where('id', $id)->update([
                    'quiz_id'        => trim(request('quiz')),
                    'quiz_question_type_id' => trim(request('quiz_type')),
                    'quiz_answer_type_id' => trim(request('quiz_answer_type')),
                    'question'       => trim(request('question')),
                    'score'       => trim(request('score')),
                ]);

                if ($update && QuizAnswers::updateToTable($id)['success']) {
                    $controller = new QuizQuestionController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
                        'html'      => view(QuizQuestions::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
                        'message'   => __('Update Successfully'),
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
            if (QuizQuestions::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = QuizQuestions::whereIn('id', $id)->delete();
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
