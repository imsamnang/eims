<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyShortCourseSchedule extends FormRequest
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
            'study_generation'     => 'required',
            'study_subject'        => 'required',
        ];
    }

    public static function attributeField()
    {
        return [
            'institute'         => __('Institute'),
            'study_generation'     => __('Study generation'),
            'study_subject'         => __('Study subjects'),
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
