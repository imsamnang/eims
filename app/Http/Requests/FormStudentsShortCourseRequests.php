<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsShortCourseRequests extends FormRequest
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

    public function rules($flag = '[]')
    {
        return [
            'institute'           => 'required',
            'students'.$flag       => 'required',
            'study_subject'       => 'required',
            'study_session'       => 'required',

        ];
    }

    public function attributes($flag = '[]')
    {
        return [
            'institute'   => __('Institute'),
            'students'.$flag   => __('Students'),
            'study_subject' => __('Study subjects'),
            'study_session' => __('Study session'),

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
