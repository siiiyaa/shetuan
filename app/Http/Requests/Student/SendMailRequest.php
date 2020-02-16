<?php

namespace App\Http\Requests\Student;

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
            'stu_number' => 'bail|required|exists:students,stu_number|size:9'
        ];
    }

    public function messages()
    {
        return [
            'stu_number.required' => '学号不得为空',
            'stu_number.exists' => '学号不存在，请先注册',
            'stu_number.size' => '学号位数不正确'
        ];
    }
}
