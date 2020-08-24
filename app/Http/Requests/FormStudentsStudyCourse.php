<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsStudyCourse extends FormRequest
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
        $rules = [
            'study_course_session' => 'required',
            'students'.$flag       => 'required',
        ];

        return  $rules;
    }

    public function attributes($flag = '[]')
    {
        return [
            'study_course_session' => __('Study course session'),
            'students'.$flag      => __('Students'),
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
