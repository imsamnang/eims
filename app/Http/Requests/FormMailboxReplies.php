<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormMailboxReplies extends FormRequest
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
        return [
            'mailbox_id' => 'required',
            'recipient' . $flag => 'required',
            'message'  => 'required',
        ];
    }

    public function attributes($flag = '[]')
    {
        return [
            'mailbox_id'  => __('Mailbox Id'),
            'recipient' . $flag  => __('Recipient'),
            'message'  => __('Message'),
        ];
    }

    public static function questions($flag = '[]')
    {
        return [];
    }

    // validation.php // view/lang/en/validation.php
    public static function messages($flag = '[]')
    {
        return [];
    }
}
