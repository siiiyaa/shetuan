<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClassUpdateRequest extends FormRequest
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
            'class_name' => 'bail|required|max:20|unique:class,cls_name,'.$id,
            'major_id' => 'bail|required|exists:majors,id'
        ];
    }

    public function messages()
    {
        return [
            'class_name.required' => '班级名称不得为空',
            'class_name.max' => '班级名称不得大于20个字符',
            'class_name.unique' => '此班级已存在',
            'major_id.required' => '参数不完整',
            'major_id.exists' => '参数错误'
        ];
    }
}
