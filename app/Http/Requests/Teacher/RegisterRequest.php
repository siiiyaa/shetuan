<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'teacher_name' => 'required',
            'password' => 'bail|required|min:6|max:20|confirmed',
            'email' => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'teacher_name.required' => '老师姓名不得为空',
            'password.required' => '密码不得为空',
            'password.min' => '密码不得少于6位',
            'password.max' => '密码不得大于20位',
            'password.confirmed' => '两次密码不一致',
            'email.email' => '邮箱格式不正确',
            'email.required' => '邮箱不得为空'
        ];
    }
}
