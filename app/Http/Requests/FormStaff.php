<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStaff extends FormRequest
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
            'institute'            => 'required',
            'status'               => 'required',
            'designation'          => 'required',
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

            'pob_province_fk'         => 'required',
            'pob_district_fk'         => 'required',
            'pob_commune_fk'          => 'required',
            'pob_village_fk'          => 'required',
            //'permanent_address'       => 'required',

            'curr_province_fk'     => 'required',
            'curr_district_fk'     => 'required',
            'curr_commune_fk'      => 'required',
            'curr_village_fk'      => 'required',
            //'temporaray_address'   => 'required',

            'father_fullname'      => 'required|only_string',
            'father_occupation'    => 'required',
            //'father_phone'         => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            // 'father_email'         => 'required|email',
            // 'father_extra_info'    => 'required',

            'mother_fullname'      => 'required|only_string',
            'mother_occupation'    => 'required',
            //'mother_phone'         => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            // 'mother_email'         => 'required|email',
            // 'mother_extra_info'    => 'required',

            'guardian'             => 'required',
            '__guardian'          => json_encode([
                'other'             => [
                    'guardian_fullname'    => 'required|only_string',
                    'guardian_occupation'  => 'required',
                    //'guardian_phone'       => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
                    // 'guardian_email'       => 'required|email',
                    // 'guardian_extra_info'  => 'required',
                ]
            ]),

            'phone'                => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            'email'                   => 'required|email',
            // 'password'                => 'required|min:6',
            // 'staff_extra_info'   => 'required',
            //  'photo'                => 'required|image|mimes:jpeg,jpg,bmp,png|max:1024',
        ];
    }

    public static function attributeField()
    {
        return [

            'institute'            => __('Institute'),
            'status'               => __('Status'),
            'designation'          => __('Designation'),
            'institute_extra_info' => __('Extra information'),

            'first_name_km'        => __('First name Khmer'),
            'last_name_km'         => __('Last name Khmer'),
            'first_name_en'        => __('first name Latin'),
            'last_name_en'         => __('last name Latin'),

            'nationality'          => __('Nationality'),
            'mother_tong'          => __('Mother tong'),
            'national_id'          => __('National Id'),

            'gender'               => __('Gender'),
            'date_of_birth'        => __('Date of birth'),
            'marital'              => __('Marital'),

            'pob_province_fk'      => __('Province'),
            'pob_district_fk'      => __('District'),
            'pob_commune_fk'       => __('Commune'),
            'pob_village_fk'       => __('Village'),
            'permanent_address'    => __('Permanent address'),

            'curr_province_fk'     => __('Province'),
            'curr_district_fk'     => __('District'),
            'curr_commune_fk'      => __('Commune'),
            'curr_village_fk'      => __('Village'),
            'temporaray_address'   => __('Temporaray address'),

            'father_fullname'      => __('Father fullname'),
            'father_occupation'    => __('Occupation'),
            'father_phone'         => __('Father phone'),
            'father_email'         => __('Father email'),
            'father_extra_info'    => __('Extra info'),

            'mother_fullname'      => __('Mother fullname'),
            'mother_occupation'    => __('Occupation'),
            'mother_phone'         => __('Mother phone'),
            'mother_email'         => __('Mother email'),
            'mother_extra_info'    => __('Extra info'),

            'guardian'             => __('Guardian'),

            'guardian_fullname'    => __('Guardian fullname'),
            'guardian_occupation'  => __('Occupation'),
            'guardian_phone'       => __('Guardian phone'),
            'guardian_email'       => __('Guardian email'),
            'guardian_extra_info'  => __('Extra info'),
            'phone'                => __('Phone'),
            'email'                => __('Email'),
            'password'             => __('Password'),
            'staff_extra_info'    => __('Extra info'),
            'photo'                => __('Photo'),
        ];
    }

    public static function questionField()
    {
        return [];
    }




    // validation.php // view/lang/en/validation.php
    public static function customMessages()
    {
        return [
            'first_name_km'                                       => [
                'only_khmer_character'                            => __('first_name_km.required_only_khmer_character'),
            ],
            'last_name_km'                                        => [
                'only_khmer_character'                            => __('first_name_km.required_only_khmer_character'),
            ],
        ];
    }
}
