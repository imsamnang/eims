<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyGrade extends FormRequest
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
        $rules['score']   = 'required';
        return $rules;
    }

    public static function attributeField()
    {
        $attributes['name']    = __('Name');
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
        $attributes['score']            = __('score');
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
