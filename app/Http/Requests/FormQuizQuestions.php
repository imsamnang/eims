<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormQuizQuestions extends FormRequest
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
        $rules['quiz']   = 'required';
        $rules['quiz_type']   = 'required';
        $rules['quiz_answer_type']   = 'required';
        $rules['question']   = 'required';
        $rules['score']   = 'required';
        return $rules;
    }

    public function attributes()
    {
        $attributes['quiz']    = __('Quiz group');
        $attributes['quiz_type']    = __('Quiz type');
        $attributes['quiz_answer_type']    = __('Quiz answer type');
        $attributes['question']    = __('Quiz question');
        $attributes['score']    = __('Score');

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
