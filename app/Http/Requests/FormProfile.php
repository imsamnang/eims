<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormProfile extends FormRequest
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
        $rules['name']       = 'required';
        $rules['email']      = 'required';
        $rules['phone']      = 'required';
        $rules['address']    = 'required';
        $rules['location']   = 'required';
        return $rules;
    }

    public static function attributeField()
    {
        $attributes['name']        = __('Name');
        $attributes['email']       = __('Email');
        $attributes['phone']       = __('Phone');
        $attributes['address']     = __('Address');
        $attributes['location']    = __('Location');
        $attributes['profile']     = __('Profile');
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
