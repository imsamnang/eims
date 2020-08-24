<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyModalities extends FormRequest
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
        $rules['name']   = 'required';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }
        //$rules['description']   = 'required';
        //$rules['image']         = 'required';
        return $rules;
    }

    public function attributes()
    {

        $attributes['name']    = __('Name');
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
        $attributes['description']            = __('Description');
        $attributes['image']                  = __('Image');
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
