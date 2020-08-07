<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Translator;
class FormClassRoutine extends FormRequest
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
                'class'                  => 'required',
                'subject'                => 'required',
                'day'                    => 'required',
                'start_time_hour'        => 'required',
                'start_time_minutes'     => 'required',
                'start_time_meridiem'    => 'required',
                'end_time_hour'          => 'required',
                'end_time_minutes'       => 'required',
                'end_time_meridiem'      => 'required',
        ];
    }

    public static function attributeField()
    {
        return [
            'class'                  => __('Class'),
            'subject'                => __('subjects'),
            'day'                    => __('Day'),
            'start_time_hour'        => __('Start time hour'),
            'start_time_minutes'     => __('start time minutes'),
            'start_time_meridiem'    => __('start time meridiem'),
            'end_time_hour'          => __('end time hour'),
            'end_time_minutes'       => __('end time minutes'),
            'end_time_meridiem'      => __('end time meridiem'),


        ];
    }

    public static function questionField(){
        return [];
    }



    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return [];

    }
}
