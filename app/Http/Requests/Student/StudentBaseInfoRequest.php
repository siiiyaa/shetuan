<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentBaseInfoRequest extends FormRequest
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
            'stu_name' => 'required',
            'sex' => ['required',Rule::in([1,2])]
        ];
    }

    public function messages()
    {
        return [
            'stu_name.required' => '姓名不得为空',
            'sex.required' => '性别不得为空',
            'sex.in' => '性别参数错误'
        ];
    }
}
