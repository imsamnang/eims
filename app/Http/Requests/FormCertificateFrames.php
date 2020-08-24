<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class FormCertificateFrames extends FormRequest
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
            'institute' => 'required',
            'type'      => 'required',
            'name'      => 'required',
            'layout'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'institute' => __('Institute'),
            'type'      => __('Type'),
            'name'      => __('Name'),
            'layout'    => __('Layout'),
            'foreground'=> __('Frame foreground'),
            'background'=> __('Frame Background'),

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
