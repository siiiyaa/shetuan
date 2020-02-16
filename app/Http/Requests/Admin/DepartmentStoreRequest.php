<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
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
            'department_name' => 'bail|required|max:20|unique:departments,department_name',
            'type' => 'bail|required|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'department_name.required' => '系/组织名称不得为空',
            'department_name.max' => '系/组织名称不得大于20个字符',
            'type.required' => '参数错误',
            'type.in' => '参数错误',
            'department_name.unique' => '此系/组织已存在'
        ];
    }
}
