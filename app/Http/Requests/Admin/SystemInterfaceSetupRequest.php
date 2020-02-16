<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SystemInterfaceSetupRequest extends FormRequest
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
            'value' => 'bail|required|in:1,0'
        ];
    }

    public function messages()
    {
        return [
            'value.required' => '参数错误',
            'value.in' => '参数错误',

        ];
    }
}
