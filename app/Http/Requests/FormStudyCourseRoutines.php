<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourseRoutines extends FormRequest
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
            'study_course_session'       => 'required',
            'start_time'.$flag           => 'required',
            'end_time'.$flag             => 'required',
            'days'.$flag                  => 'required',
            'teachers'.$flag              => 'required',
            'study_subjects'.$flag        => 'required',
            'study_class'.$flag          => 'required',

        ];
    }

    public function attributes($flag = '[]')
    {
        return [
            'study_course_session'       => __('Study course session'),
            'start_time'.$flag           => __('Start time'),
            'end_time'.$flag             => __('End time'),
            'days'.$flag                  => __('Day'),
            'teachers'.$flag              => __('Teacher'),
            'study_subjects'.$flag        => __('Study subjects'),
            'study_class'.$flag          => __('Study class'),
        ];
    }

    public function questions()
    {
        return array();
    }



    public function messages()
    {
        return array();
    }
}
