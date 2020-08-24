<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizzesStudentAnswers extends FormRequest
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
            'quiz_student'         => 'required',
            'quiz_question'        => 'required',
            'answer'.$flag               => 'required',
        ];

        return $rules;
    }

    public function attributes($flag = '[]')
    {
        $attributes = [
            'answer'.$flag            => __('Answer'),
        ];

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
