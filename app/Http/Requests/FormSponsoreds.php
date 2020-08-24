<?php

namespace App\Http\Requests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class FormSponsoreds extends FormRequest
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
        $rules["name"]          = "required";
        $rules["link"]          = "required";
        // $rules["image"]         = "required";
        //$rules["description"]   = "required";
        return $rules;
    }

    public function attributes()
    {

        $attributes["name"]       = __('Name');
        $attributes["link"]        = __('Link');
        $attributes["image"]       = __('Image');
        $attributes["description"] = __('Description');
        return $attributes;
    }

    public function questions(){
        return [];
    }



    public function messages()
    {
        return [];
    }
}
