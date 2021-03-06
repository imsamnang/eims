<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizStudents extends FormRequest
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

        $rules['quiz']  = 'required';
        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'study_course_session' => __('Study course session'),
            'student'              => __('Student'),
        ];
        $attributes['quiz']    = __('Quiz group');
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
