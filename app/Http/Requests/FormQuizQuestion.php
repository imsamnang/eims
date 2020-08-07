<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizQuestion extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public static function rulesField()
    {
        $rules['quiz']   = 'required';
        $rules['quiz_type']   = 'required';
        $rules['quiz_answer_type']   = 'required';
        $rules['question']   = 'required';
        $rules['score']   = 'required';
        return $rules;
    }

    public static function attributeField()
    {
        $attributes['quiz']    = __('Quiz group');
        $attributes['quiz_type']    = __('Quiz type');
        $attributes['quiz_answer_type']    = __('Quiz answer type');
        $attributes['question']    = __('Quiz question');
        $attributes['score']    = __('Score');

        return $attributes;
    }

    public static function questionField()
    {
        return [];
    }



    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return [];
    }
}
