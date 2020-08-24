<?php

namespace App\Models;

use DomainException;
use App\Http\Requests\FormQuizAnswer;
use App\Http\Requests\FormQuizQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Quiz\QuizQuestionController;

class QuizQuestion extends Model
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



    public static function addToTable()
    {

        $response           = array();
        $validator          = Validator::make(
            request()->all(),
            FormQuizQuestion::rules() + FormQuizAnswer::rules('.*'),
            FormQuizQuestion::messages() + FormQuizAnswer::messages(),
            FormQuizQuestion::attributes() + FormQuizAnswer::attributes('.*')
        );

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $add = QuizQuestion::insertGetId([
                    'quiz_id'        => trim(request('quiz')),
                    'quiz_question_type_id' => trim(request('quiz_type')),
                    'quiz_answer_type_id' => trim(request('quiz_answer_type')),
                    'question'       => trim(request('question')),
                    'score'       => trim(request('score')),
                ]);
                if ($add && QuizAnswer::addToTable($add)['success']) {
                    $controller = new QuizQuestionController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'html'      => view(QuizQuestion::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $add)[0]])->render(),
                        'message'   => __('Add Successfully'),
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
        $newAnswer = [];
        foreach (request('answer') as $key => $value) {
            if ($value) {
                $newAnswer[$key] = $value;
            }
        }
        request()->merge(['answer' => $newAnswer]);
        $response           = array();
        $validator          = Validator::make(
            request()->all(),
            FormQuizQuestion::rules() + FormQuizAnswer::rules('.*'),
            FormQuizQuestion::messages() + FormQuizAnswer::messages(),
            FormQuizQuestion::attributes() + FormQuizAnswer::attributes('.*')
        );

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {

            try {

                $update = QuizQuestion::where('id', $id)->update([
                    'quiz_id'        => trim(request('quiz')),
                    'quiz_question_type_id' => trim(request('quiz_type')),
                    'quiz_answer_type_id' => trim(request('quiz_answer_type')),
                    'question'       => trim(request('question')),
                    'score'       => trim(request('score')),
                ]);

                if ($update && QuizAnswer::updateToTable($id)['success']) {
                    $controller = new QuizQuestionController;
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        'data'      => [
                            [
                                'id' => $id,
                            ]

                        ],
                        'html'      => view(QuizQuestion::path('view') . '.includes.tpl.tr', ['row' => $controller->list([], $id)[0]])->render(),
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
            if (QuizQuestion::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = QuizQuestion::whereIn('id', $id)->delete();
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
