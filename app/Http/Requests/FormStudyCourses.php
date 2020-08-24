<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourses extends FormRequest
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
        $rules['name'] = 'required';
        if (config('app.languages')) {
            foreach (config('app.languages') as $lang) {
                $rules[$lang['code_name']] =  'required';
            }
        }

        $rules['institute']                = 'required';
        //$rules['study_faculty']            = 'required';
        //$rules['course_type']              = 'required';
        //$rules['study_modality']           = 'required';
        $rules['study_program']            = 'required';
        //$rules['study_overall_fund']       = 'required';
        //$rules['curriculum_author']        = 'required';
        //$rules['curriculum_endorsement']   = 'required';
        //$rules['description']            = 'required';
        //$rules['image']                  = 'required';

        return  $rules;
    }

    public function attributes()
    {

            $attributes['name']                     = __('Name');
            if (config('app.languages')) {
                foreach (config('app.languages') as $lang) {
                    $attributes[$lang['code_name']] =  $lang['translate_name'];
                }
            }

            $attributes['institute']                = __('Institute');
            $attributes['study_faculty']            = __('Study faculty');
            $attributes['course_type']              = __('Course type');
            $attributes['study_modality']           = __('Study Modality');
            $attributes['study_program']            = __('Study Program');
            $attributes['study_overall_fund']       = __('Study overall fund');
            $attributes['curriculum_author']        = __('Curriculum Author');
            $attributes['curriculum_endorsement']   = __('Curriculum Endorsement');
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
