<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsRequest extends FormRequest
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
        $rules['institute']           = 'required';
        $rules['study_program']       = 'required';
        $rules['study_course']        = 'required';
        $rules['study_generation']    = 'required';
        $rules['study_academic_year'] = 'required';
        $rules['study_semester']      = 'required';
        $rules['study_session']       = 'required';
        return  $rules;
    }

    public static function attributeField()
    {

        $attributes['institute']          = __('Institute');
        $attributes['study_program']      = __('Study program');
        $attributes['study_course']       = __('Study course');
        $attributes['study_generation']   = __('Study generation');
        $attributes['study_academic_year']= __('Study academic​ year');
        $attributes['study_semester']     = __('Study semester');
        $attributes['study_session']      = __('Study session');
        $attributes['photo']              = __('Photo');

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
