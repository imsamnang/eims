<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyShortCourseSchedules extends FormRequest
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
            'institute'         => 'required',
            'study_generation'  => 'required',
            'study_subject'     => 'required',
            'study_session'     => 'required',
            'study_start'       => 'required',
            'study_end'         => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'institute'         => __('Institute'),
            'study_generation'  => __('Study Generation'),
            'study_subject'     => __('Study subjects'),
            'study_session'     => __('Study session'),
            'study_start'       => __('Study start'),
            'study_end'         => __('Study end'),
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
