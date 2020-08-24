<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class FormClassRoutine extends FormRequest
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
                'class'                  => 'required',
                'subject'                => 'required',
                'day'=> 'required',
                'start_time_hour'        => 'required',
                'start_time_minutes'     => 'required',
                'start_time_meridiem'    => 'required',
                'end_time_hour'          => 'required',
                'end_time_minutes'       => 'required',
                'end_time_meridiem'      => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'class'                  => __('Class'),
            'subject'                => __('subjects'),
            'day'=> __('Day'),
            'start_time_hour'        => __('Start time hour'),
            'start_time_minutes'     => __('start time minutes'),
            'start_time_meridiem'    => __('start time meridiem'),
            'end_time_hour'          => __('end time hour'),
            'end_time_minutes'       => __('end time minutes'),
            'end_time_meridiem'      => __('end time meridiem'),


        ];
    }

    public function questions(){
        return [];
    }



    public function messages()
    {
        return [];

    }
}
