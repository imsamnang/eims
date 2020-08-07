<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizStudent extends FormRequest
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

        $rules['quiz']  = 'required';
        return $rules;
    }

    public static function attributeField()
    {
        $attributes = [
            'study_course_session' => __('Study course session'),
            'student'              => __('Student'),
        ];
        $attributes['quiz']    = __('Quiz group');
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
