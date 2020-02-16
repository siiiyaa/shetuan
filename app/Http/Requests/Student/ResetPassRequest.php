<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassRequest extends FormRequest
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
            'password' => 'bail|required|min:6|max:20|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '密码不得为空',
            'password.min' => '密码不得少于6位',
            'password.max' => '密码不得大于20位',
            'password.confirmed' => '两次密码不一致'

        ];
    }
}
