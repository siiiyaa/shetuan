<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class WhetherToPassRequest extends FormRequest
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
            'pass' => 'required|in:1,-1',
            'student_id' => 'required|numeric',
            'association_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'pass.required' => '参数不完整',
            'pass.in' => '参数有误',
            'student_id.required' => '参数不完整',
            'student_id.numeric' => '参数有误',
            'association_id.required' => '参数不完整',
            'association_id.numeric' => '参数有误',
        ];
    }
}
