<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormStudents extends FormRequest
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

            'institute'            => 'required',
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

            'pob_province'         => 'required',
            'pob_district'         => 'required',
            'pob_commune'          => 'required',
            'pob_village'          => 'required',
            //'permanent_address'       => 'required',

            'curr_province'     => 'required',
            'curr_district'     => 'required',
            'curr_commune'      => 'required',
            'curr_village'      => 'required',
            //'temporaray_address'   => 'required',

            'father_fullname'      => 'required|only_string',
            'father_occupation'    => 'required',
            'father_phone'         => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            // 'father_email'         => 'required|email',
            // 'father_extra_info'    => 'required',

            'mother_fullname'      => 'required|only_string',
            'mother_occupation'    => 'required',
            'mother_phone'         => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            // 'mother_email'         => 'required|email',
            // 'mother_extra_info'    => 'required',

            'guardian'             => 'required',
            '__guardian'          => json_encode([
                'other'             => [
                    'guardian_fullname'    => 'required|only_string',
                    'guardian_occupation'  => 'required',
                    'guardian_phone'       => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
                    // 'guardian_email'       => 'required|email',
                    // 'guardian_extra_info'  => 'required',
                ]
            ]),

            'phone'                   => 'required|regex:/^([0-9\(\)\/\+ \-]*)$/|min:9',
            'email'                   => 'required|email',
            // 'student_extra_info'   => 'required',
            //  'photo'                => 'required|image|mimes:jpeg,jpg,bmp,png|max:1024',
        ];
    }

    public function attributes()
    {
        return [

            'institute'            => __('Institute'),
            'first_name_km'        => __('First name Khmer'),
            'last_name_km'         => __('Last name Khmer'),
            'first_name_en'        => __('First name Latin'),
            'last_name_en'         => __('Last name Latin'),

            'nationality'          => __('Nationality'),
            'mother_tong'          => __('Mother tong'),
            'national_id'          => __('National Id'),

            'gender'               => __('Gender'),
            'date_of_birth'        => __('Date of birth'),
            'marital'              => __('Marital'),

            'pob_province'      => __('Province'),
            'pob_district'      => __('District'),
            'pob_commune'       => __('Commune'),
            'pob_village'       => __('Village'),
            'permanent_address'    => __('Permanent address'),

            'curr_province'     => __('Province'),
            'curr_district'     => __('District'),
            'curr_commune'      => __('Commune'),
            'curr_village'      => __('Village'),
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
            'mother_extra_info'    => __('extra info'),

            'guardian'             => __('Guardian'),

            'guardian_fullname'    => __('Guardian fullname'),
            'guardian_occupation'  => __('Occupation'),
            'guardian_phone'       => __('Guardian phone'),
            'guardian_email'       => __('Guardian email'),
            'guardian_extra_info'  => __('Extra info'),
            'phone'                => __('Phone'),
            'email'                => __('Email'),
            'student_extra_info'   => __('Extra info'),
            'photo'                => __('Photo'),
        ];
    }

    public function questions()
    {
        return [];
    }

    public function messages()
    {
        return [
            'first_name_km'                   => [
                'only_khmer_character'        => __('first_name_km.required_only_khmer_character'),
            ],
            'last_name_km'                    => [
                'only_khmer_character'        => __('first_name_km.required_only_khmer_character'),
            ],
        ];
    }
}
