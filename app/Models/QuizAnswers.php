<?php

namespace App\Models;

use DomainException;
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


    public static function addToTable($quiz_question_id)
    {

        $response           = array();
        $validate = self::validate(null,'.*');
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'](), $validate['attributes']('.*'));

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
                $add = self::insert($values);
                if ($add) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'data'      => [],
                        'message'   => __('Add Successfully'),
                    );
                }
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }

    public static function updateToTable($quiz_question_id)
    {

        $response           = array();
        $validate = self::validate();
        $validator          = Validator::make(request()->all(), $validate['rules']('.*'), $validate['messages'](), $validate['attributes']('.*'));

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
                        $update = self::where('id', $id)->update([
                            'answer'         => trim($answer),
                            'correct_answer' => isset(request('correct_answer')[$key]) ? 1 : 0,
                        ]);
                    } else {
                        $update = self::insertGetId([
                            'quiz_question_id' => $quiz_question_id,
                            'answer'         => trim($answer),
                            'correct_answer' => isset(request('correct_answer')[$key]) ? 1 : 0,
                        ]);
                        $ids[] = $update;
                    };
                }

                self::whereNotIn('id', $ids)->where('quiz_question_id', $quiz_question_id)->delete();

                if ($update) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'update',
                        //'data'      => self::getData($id),
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
            if (self::whereIn('id', $id)->get()->toArray()) {
                if (request()->method() === 'POST') {
                    try {
                        $delete    = self::whereIn('id', $id)->delete();
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
