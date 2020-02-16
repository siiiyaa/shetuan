<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MajorStoreRequest extends FormRequest
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
            'department_id' => 'bail|required|exists:departments,id',
            'major_name' => 'bail|required|max:20|unique:majors,major_name'
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => '参数不完整',
            'department_id.exists' => '参数错误',
            'major_name.required' => '专业名称不得为空',
            'major_name.unique' => '专业名称不得为空',
            'major_name.max' => '专业名称不得大于20个字符',
        ];
    }
}
