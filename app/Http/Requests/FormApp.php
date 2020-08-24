<?php

namespace App\Http\Requests;



use App\Rules\KhmerCharacter;
use Illuminate\Foundation\Http\FormRequest;

class FormApp extends FormRequest
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
        $rules = [
            'name'                        => 'required',
            'phone'                       => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            'email'                       => 'required|email|min:10|max:50',
            'address'                     => 'required',
            'website'                     => 'required',
            //'logo'                      => 'required',
            //'favicon'                   => 'required',
        ];
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }
        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'name'                        => __('App Name'),
            'phone'                       => __('Phone'),
            'email'                       => __('Email'),
            'address'                     => __('Address'),
            'website'                     => __('Website'),
            'logo'                        => __('Logo'),
            'favicon'                     => __('Favicon'),
        ];

        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
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
