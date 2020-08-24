<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormPassword extends FormRequest
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
        $rules['old_password']               = 'required|min:6';
        $rules['password']                   = 'required|min:6';
        $rules['password_confirmation']      = 'required|min:6';
        return $rules;
    }

    public function attributes()
    {
        $attributes['old_password']                = __('Old password');
        $attributes['password']                    = __('New password');
        $attributes['password_confirmation']       = __('Password confirm');

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
