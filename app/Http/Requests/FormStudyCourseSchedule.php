<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourseSchedule extends FormRequest
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
            'institute'            => 'required',
            'study_program'        => 'required',
            'study_course'         => 'required',
            'study_generation'     => 'required',
            'study_academic_year'  => 'required',
            'study_semester'       => 'required',


        ];
    }

    public static function attributeField()
    {
        return [
            'institute'         => __('Institute'),
            'study_program'         => __('Study program'),
            'study_course'         => __('Study course'),
            'study_generation'     => __('Study generation'),
            'study_academic_year'        => __('Study academic year'),
            'study_semester'       => __('Study semester'),
        ];
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
