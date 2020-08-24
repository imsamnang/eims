<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FormSocailsMedias extends FormRequest
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
        $rules = [
            'facebook'      => 'required',
            'linkedin'      => 'required',
            'google-plus'   => 'required',
            'whatsapp'      => 'required',
            'pinterest'     => 'required',
            'twitter'       => 'required',
            'youtube'       => 'required',
            'instagram'     => 'required',
            'skype'         => 'required',
            'wordpress'     => 'required',
            'tripadvisor'   => 'required',
            'rss'           => 'required',
            'like-cambodia' => 'required',
            'github'        => 'required',
        ];
        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'facebook'      =>  __('Facebook'),
            'linkedin'      =>  __('Linkedin'),
            'google-plus'   =>  __('Google plus'),
            'whatsapp'      =>  __('WhatsApp'),
            'pinterest'     =>  __('Pinterest'),
            'twitter'       =>  __('Twitter'),
            'youtube'       =>  __('Youtube'),
            'instagram'     =>  __('Instagram'),
            'skype'         =>  __('Skype'),
            'wordpress'     =>  __('Wordpress'),
            'tripadvisor'   =>  __('Tripadvisor'),
            'rss'           =>  __('Rss'),
            'like-cambodia' =>  __('Like Cambodia'),
            'github'        =>  __('Github'),
        ];

        return $attributes;
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
