<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'bail|required|min:6|max:20',
            'password' => 'bail|required|min:6|max:20|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => '旧密码不得为空',
            'old_password.min' => '旧密码不得少于6位',
            'old_password.max' => '旧密码不得大于20位',
            'password.required' => '新密码不得为空',
            'password.min' => '新密码不得为空',
            'password.max' => '新密码不得为空',
        ];
    }
}
