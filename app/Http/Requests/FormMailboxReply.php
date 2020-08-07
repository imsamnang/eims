<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormMailboxReply extends FormRequest
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

    public static function rulesField($flag = '[]')
    {
        return [
            'mailbox_id' => 'required',
            'recipient' . $flag => 'required',
            'message'  => 'required',
        ];
    }

    public static function attributeField($flag = '[]')
    {
        return [
            'mailbox_id'  => __('Mailbox Id'),
            'recipient' . $flag  => __('Recipient'),
            'message'  => __('Message'),
        ];
    }

    public static function questionField($flag = '[]')
    {
        return [];
    }

    // validation.php // view/lang/en/validation.php
    public static function customMessages($flag = '[]')
    {
        return [];
    }
}
