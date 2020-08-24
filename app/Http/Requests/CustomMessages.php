<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class messages extends FormRequest
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


    public function messages()
    {
        return [];
        return [
            'accepted'             => ':attribute must be accepted',
            'active_url'           => ':attribute is not a valid URL',
            'after'                => ':attribute must be a date after :date',
            'after_or_equal'       => ':attribute must be a date after or equal to :date',
            'alpha'                => ':attribute may only contain letters',
            'alpha_dash'           => ':attribute may only contain letters, numbers, and dashes',
            'alpha_num'            => ':attribute may only contain letters and numbers',
            'array'                => ':attribute must be an array',
            'before'               => ':attribute must be a date before :date',
            'before_or_equal'      => ':attribute must be a date before or equal to :date',
            'between'              => [
                'numeric' => ':attribute must be between :min and :max',
                'file'    => ':attribute must be between :min and :max kilobytes',
                'string'  => ':attribute must be between :min and :max characters',
                'array'   => ':attribute must have between :min and :max items',
            ],
            'boolean'              => ':attribute field must be true or false',
            'confirmed'            => ':attribute confirmation does not match',
            'date'                 => ':attribute is not a valid date',
            'date_format'          => ':attribute does not match the format :format',
            'different'            => ':attribute and :other must be different',
            'digits'               => ':attribute must be :digits digits',
            'digits_between'       => ':attribute must be between :min and :max digits',
            'dimensions'           => ':attribute has invalid image dimensions',
            'distinct'             => ':attribute field has a duplicate value',
            'email'                => ':attribute must be a valid email address',
            'exists'               => 'The selected :attribute is invalid',
            'file'                 => ':attribute must be a file',
            'filled'               => ':attribute field must have a value',
            'image'                => ':attribute must be an image',
            'in'                   => 'The selected :attribute is invalid',
            'in_array'             => ':attribute field does not exist in :other',
            'integer'              => ':attribute must be an integer',
            'ip'                   => ':attribute must be a valid IP address',
            'ipv4'                 => ':attribute must be a valid IPv4 address',
            'ipv6'                 => ':attribute must be a valid IPv6 address',
            'json'                 => ':attribute must be a valid JSON string',
            'max'                  => [
                'numeric' => ':attribute '.__('max numeric').' :max',
                'file'    => ':attribute '.__('max file').' :max '.__('kilobytes'),
                'string'  => ':attribute '.__('max string').' :max '.__('characters'),
                'array'   => ':attribute '.__('max array').' :max '.__('items'),
            ],
            'mimes'                => ':attribute must be a file of type: :values',
            'mimetypes'            => ':attribute must be a file of type: :values',
            'min'                  => [
                'numeric' => ':attribute '.__('min_numeric').' :min',
                'file'    => ':attribute '.__('min_file').' :min '.__('kilobytes'),
                'string'  => ':attribute '.__('min_string').' :min '.__('characters'),
                'array'   => ':attribute '.__('min_array').' :min '.__('items'),
            ],
            'not_in'               => 'The selected :attribute is invalid',
            'numeric'              => ':attribute '.__('numeric only'),
            'present'              => ':attribute field must be present',
            'regex'                => ':attribute format is invalid',
            'required'             => ':attribute '.__('required'),
            'required_if'          => ':attribute field is required when :other is :value',
            'required_unless'      => ':attribute field is required unless :other is in :values',
            'required_with'        => ':attribute field is required when :values is present',
            'required_with_all'    => ':attribute field is required when :values is present',
            'required_without'     => ':attribute field is required when :values is not present',
            'required_without_all' => ':attribute field is required when none of :values are present',
            'same'                 => ':attribute and :other must match',
            'size'                 => [
                'numeric' => ':attribute must be :size',
                'file'    => ':attribute must be :size kilobytes',
                'string'  => ':attribute must be :size characters',
                'array'   => ':attribute must contain :size items',
            ],
            'string'               => ':attribute '.__('string only'),
            'timezone'             => ':attribute must be a valid zone',
            'unique'               => ':attribute has already been taken',
            'uploaded'             => ':attribute failed to upload',
            'url'                  => ':attribute format is invalid',
        ];
    }
}
