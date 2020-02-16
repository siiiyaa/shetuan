<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'department_name' => 'bail|required|max:20|unique:departments,department_name,'.$id,
            'type_value' => 'bail|required|in:1,2',
            'id' => 'required|exists:departments,id'
        ];
    }

    public function messages()
    {
        return [
            'department_name.required' => '系名称不得为空',
            'department_name.max' => '系名称不得大于20个字符',
            'department_name.unique' => '此系已存在',
            'type.required' => '参数不完整',
            'type.in' => '参数错误',

        ];
    }
}
