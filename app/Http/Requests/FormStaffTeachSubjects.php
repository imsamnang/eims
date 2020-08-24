<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStaffTeachSubjects extends FormRequest
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
        $rules['institute']          = 'required';
        $rules['staff']          = 'required';
        $rules['study_subject']   = 'required';
        $rules['year']   = 'required';
        return $rules;
    }

    public function attributes()
    {
        $attributes['institute']          = __('Institute');
        $attributes['staff']          = __('Staff');
        $attributes['study_subject']   = __('Study subjects');
        $attributes['year']   = __('Years');

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
