<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormContact extends FormRequest
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
        $rules['email']  = 'required';
        //$rules['phone']  = 'required';
        $rules['message']  = 'required';
        return $rules;
    }

    public static function attributeField()
    {

        $attributes['name']    = __('Name');
        $attributes['email']   = __('Email');
        $attributes['phone']   = __('Phone');
        $attributes['message']   = __('Message');
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
