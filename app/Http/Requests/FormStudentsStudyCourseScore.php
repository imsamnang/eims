<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsStudyCourseScore extends FormRequest
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

    public static function rulesField($flag = '[]')
    {
        $rules = [
            'student'            => 'required',
            'study_subject' . $flag      => 'required',
            'attendance_score'   => 'required',
            'other_score'        => 'required',

        ];

        return  $rules;
    }

    public static function attributeField($flag = '[]')
    {
        return [
            'student'        => __('Student'),
            'study_subject' . $flag        => __('Study subjects'),
            'attendance_score'     => __('Attendance Score'),
            'other_score'          => __('Other score'),
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
