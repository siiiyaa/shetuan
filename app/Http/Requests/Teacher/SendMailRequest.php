<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
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
            'teacher_name' => 'bail|required|exists:teachers,te_name'
        ];
    }

    public function messages()
    {
        return [
            'teacher_name.required' => '用户名不得为空',
            'teacher_name.exists' => '用户名不存在，请先注册',
        ];
    }
}
