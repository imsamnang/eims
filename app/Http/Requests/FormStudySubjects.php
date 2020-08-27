<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudySubjects extends FormRequest
{
    /**
     * Determine if the user is authorized to make this required.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the required.
     *
     * @return array
     */

    public function rules()
    {
        $rules['name']                     = 'required';
        $rules['course_type']              = 'required';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }
        $rules['full_mark_theory']         = 'required';
        $rules['pass_mark_theory']         = 'required';
        $rules['full_mark_practical']      = 'required';
        $rules['pass_mark_practical']      = 'required';
        $rules['credit_hour']              = 'required';
        //$rules['description']              = 'required';
        //$rules['image']                    = 'required';

        return $rules;
    }

    public function attributes()
    {

        $attributes['name']                     = __('Name');
        $attributes['course_type']              = __('Course Type');
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $attributes[$lang['code_name']] =  $lang['translate_name'];
            }
        }
        $attributes['full_mark_theory']         = __('Full mark theory');
        $attributes['pass_mark_theory']         = __('Pass mark theory');
        $attributes['full_mark_practical']      = __('Full mark practical');
        $attributes['pass_mark_practical']      = __('Pass mark practical');
        $attributes['credit_hour']              = __('Credit hour');
        $attributes['description']              = __('Description');
        $attributes['image']                    = __('Image');

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
