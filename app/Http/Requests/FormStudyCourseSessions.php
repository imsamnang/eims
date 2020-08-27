<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudyCourseSessions extends FormRequest
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
            'study_course_schedule'      => 'required',
            'study_session'              => 'required',
            'study_start'                => 'required',
            'study_end'                  => 'required'
        ];
    }

    public function attributes()
    {
        return [

            'study_course_schedule'      => __('Study Course Schedule'),
            'study_session'              => __('Study Session'),
            'study_start'                => __('Study start'),
            'study_end'                  => __('Study end'),

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