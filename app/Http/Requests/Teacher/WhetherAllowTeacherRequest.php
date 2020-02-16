<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class WhetherAllowTeacherRequest extends FormRequest
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
            'association_id' => 'required',
            'teacher_id' => 'required',
            'status' => 'bail|required|in:1,0',
        ];
    }

    public function messages()
    {
        return [
            'association_id.required' => '参数不完整',
            'teacher_id.required' => '参数不完整',
            'status.required' => '参数不完整',
            'status.in' => '参数错误',
        ];
    }


}
