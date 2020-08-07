<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormInstitute extends FormRequest
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
        $rules['name']         = 'required';
        $rules['short_name']   = 'required';
        $rules['website']      = 'required';
        $rules['address']      = 'required';
        //$rules['location']     = 'required';
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

        $attributes['name']          = __('Institute');
        $attributes['short_name']    = __('Short name');
        $attributes['website']       = __('Website');
        $attributes['address']       = __('Address');
        $attributes['location']      = __('Location').'(Goolge Map)';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
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
