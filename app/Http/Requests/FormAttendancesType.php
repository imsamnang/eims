<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormAttendancesType extends FormRequest
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
        $rules['name']   = 'required';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }
        $rules['credit_absent']   = 'required';
        //$rules['description']   = 'required';
        //$rules['image']         = 'required';
        return $rules;
    }

    public static function attributeField()
    {
        $attributes['name']    = __('Attendance type');
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
        $attributes['credit_absent']          = __('Credit absent');
        $attributes['description']            = __('Description');
        $attributes['image']                  = __('Image');
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
