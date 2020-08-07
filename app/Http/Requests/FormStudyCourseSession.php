<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourseSession extends FormRequest
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
        return [
            'study_course_schedule'      => 'required',
            'study_session'              => 'required',
            'study_start'                => 'required',
            'study_end'                  => 'required'
        ];
    }

    public static function attributeField()
    {
        return [

            'study_course_schedule'      => __('Study course schedule'),
            'study_session'              => __('Study session'),
            'study_start'                => __('Study start'),
            'study_end'                  => __('Study end'),

        ];
    }

    public static function questionField()
    {
        return array();
    }



    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return array();
    }
}
