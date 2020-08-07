<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class FormCard extends FormRequest
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

    public static function rulesField()
    {
        return [
            'institute'                     => 'required',
            'type'                          => 'required',
            'name'                          => 'required',
            'layout'                        => 'required',
        ];
    }

    public static function attributeField()
    {
        return [
            'institute'                     => __('Institute'),
            'type'                          => __('Type'),
            'name'                          => __('Name'),
            'layout'                        => __('Layout'),
            'front'                         => __('Frame front'),
            'background'                    => __('Frame background'),
            'extra_info'                    => __('Extra information'),

        ];
    }

    public static function questionField()
    {
        return [];
    }



    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return [];
    }
}
