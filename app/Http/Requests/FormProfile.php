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

    public function rules()
    {
        $rules['name']       = 'required';
        $rules['email']      = 'required';
        $rules['phone']      = 'required';
        $rules['address']    = 'required';
        $rules['location']   = 'required';
        return $rules;
    }

    public function attributes()
    {
        $attributes['name']        = __('Name');
        $attributes['email']       = __('Email');
        $attributes['phone']       = __('Phone');
        $attributes['address']     = __('Address');
        $attributes['location']    = __('Location');
        $attributes['profile']     = __('Profile');
        return $attributes;
    }

    public function questions()
    {
        return [];
    }



    public function messages()
    {
        return [];
    }
}
