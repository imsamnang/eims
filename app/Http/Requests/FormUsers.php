<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormUsers extends FormRequest
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
            'institute'     => 'required',
            'name'          => 'required',
            'phone'         => 'required',
            'email'         => 'required|email|max:255',
            'password'      => 'required|min:6',
            'address'       => 'required',
            'location'      => 'required',
            'role'          => 'required',
            //'profile'       => 'required',
        ];
    }

    public static function rulesField2()
    {
       return [
           'institute'             => 'required',
            'first_name_km'        => 'required|only_khmer_character|only_string',
            'last_name_km'         => 'required|only_khmer_character|only_string',
            'first_name_en'        => 'required|string',
            'last_name_en'         => 'required|string',
            'nationality'          => 'required',
            'mother_tong'          => 'required',
            // 'national_id'          => 'required',
            'gender'               => 'required',
            'date_of_birth'        => 'required',
            'marital'              => 'required',
            // 'teacher_or_student'   => 'required',
        ];
    }

    public static function attributeField()
    {
        return [
                'institute'    => __('Institute'),
                'name'         => __('User name'),
                'phone'        => __('Phone'),
                'email'        => __('Email'),
                'password'     => __('Password'),
                'address'      => __('Address'),
                'location'     => __('Location'),
                'role'         => __('Role'),
                'profile'      => __('Profile'),

        ];
    }

    public static function questionField()
    {
        return [];
    }
    public static function customMessages()
    {
        return [];
    }
}
