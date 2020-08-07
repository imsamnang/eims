<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormCommune extends FormRequest
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
        $rules['province']  = 'required';
        $rules['district']  = 'required';
        $rules['name']      = 'required';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }
        //$rules['description']   = 'required';
        //$rules['image']         = 'required';
        return $rules;
    }

    public static function attributeField()
    {

        $attributes['province']  = __('Province');
        $attributes['district']  = __('District');
        $attributes['name']      = __('Commune');
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }

        $attributes['description'] = __('Description');
        $attributes['image']       = __('Image');

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
