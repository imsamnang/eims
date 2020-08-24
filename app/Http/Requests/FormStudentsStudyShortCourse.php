<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudentsStudyShortCourse extends FormRequest
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
            'students' . $flag               => 'required',
            'study_short_course_session'        => 'required',

        ];
    }

    public function attributes($flag  = '[]')
    {
        return [
            'students' . $flag    => __('Students'),
            'study_short_course_session' => __('Short course session'),

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
