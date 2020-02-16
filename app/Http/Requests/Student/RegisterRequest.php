<?php

namespace App\Http\Requests\Student;

use App\Rules\Identity;
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
            'stu_number' => 'bail|required|unique:students,stu_number',
            'stu_name' => 'bail|required|max:4',
            'email' => 'bail|required|email',
            'password' => 'bail|required|confirmed|min:6|max:20',
            'sex' => 'required|in:1,2',
            'class_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'stu_number.required' => '学号不得为空',
            'stu_number.unique' => '学号已存在',
            'stu_name.required' => '名称不得为空',
            'stu_name.max' => '名称不得大于4位',
            'email.required' => '邮箱不得为空',
            'email.email' => '邮箱格式错误',
            'password.required' => '密码不得为空',
            'password.confirmed' => '两次密码不一致',
            'password.min' => '密码不得少于6位',
            'password.max' => '密码不得大于20位',
            'sex.required' => '性别不能为空',
            'sex.in' => '参数错误',
            'class_id.required' => '参数错误',
            ];
    }
}
