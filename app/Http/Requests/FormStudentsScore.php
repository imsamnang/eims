<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsScore extends FormRequest
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
            'study_subject'.$flag  => 'required',
        ];

        return  $rules;
    }

    public static function attributeField($flag = '[]')
    {
        return [
            'student'        => __('Student'),
            'study_subject'.$flag        => __('Study Subjects'),
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
