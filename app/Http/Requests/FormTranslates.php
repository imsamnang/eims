<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormTranslates extends FormRequest
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
        $rules =  [
            'phrase'  => 'required',
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

        $attributes =  [
            'phrase'  => __('Phrase'),
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
