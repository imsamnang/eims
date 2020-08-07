<?php

namespace App\Http\Requests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class FormFeatureSlider extends FormRequest
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
        if (Auth::user()->role_id == 1) {
            $rules["institute"]   = "required";
        }
        $rules["title"]           = "required";
        //$rules["image"]         = "required";
        //$rules["description"]   = "required";
        return $rules;
    }

    public static function attributeField()
    {
        $attributes["institute"]   = __('Institute');
        $attributes["title"]       = __('Title');
        $attributes["image"]       = __('Image');
        $attributes["description"] = __('Description');
        return $attributes;
    }

    public static function questionField(){
        return [];
    }



    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return [];
    }
}
