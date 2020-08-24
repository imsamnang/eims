<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizAnswers extends FormRequest
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
        $rules['answer'.$flag]   = 'required';
        return $rules;
    }

    public function attributes($flag = '[]')
    {

        $attributes['answer'.$flag]    = __('Answer');
        $attributes['correct_answer'.$flag]    = __('Correct answer');

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
