<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'stu_number' => 'required',
            'password' => 'bail|required|min:6|max:20',
        ];
    }

    public function messages()
    {
        return [
            'stu_number.required' => '学号不得为空',
            'password.required' => '密码不得为空',
            'password.min' => '密码至少为6位',
        ];
    }
}
