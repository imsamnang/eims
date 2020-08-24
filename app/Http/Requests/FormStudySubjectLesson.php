<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudySubjectLesson extends FormRequest
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
        $rules['title']                  = 'required';
        $rules['staff_teach_subject']    = 'required';
        //$rules['source_file']            = 'required';

        return $rules;
    }

    public function attributes()
    {
        $attributes['title']               = __('Title');
        $attributes['staff_teach_subject']      = __('Subjects');
        $attributes['source_file']         = __('File');

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
