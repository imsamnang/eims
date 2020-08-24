<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourseSchedules extends FormRequest
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
        return [
            'institute'            => 'required',
            'study_program'        => 'required',
            'study_course'         => 'required',
            'study_generation'     => 'required',
            'study_academic_year'  => 'required',
            'study_semester'       => 'required',


        ];
    }

    public function attributes()
    {
        return [
            'institute'         => __('Institute'),
            'study_program'         => __('Study program'),
            'study_course'         => __('Study course'),
            'study_generation'     => __('Study generation'),
            'study_academic_year'        => __('Study academic year'),
            'study_semester'       => __('Study semester'),
        ];
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
