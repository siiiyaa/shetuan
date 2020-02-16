<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class AssociationUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = $request->input('association_id');
        $rule = [
            'ass_name' => 'bail|sometimes|unique:associations,ass_name,'.$id.'|max:20',
            'corporate_slogan' => 'sometimes|max:100',
            'introduce' => 'bail|sometimes|max:300',
            'learning_objectives' => 'sometimes|max:300',
            'department_id' => 'sometimes|exists:departments,id',
            'english_name' => 'sometimes|max:30',
        ];

        if($request->hasFile('images')){
            $rule['images'] = 'bail|sometimes|image|max:5120';
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'images.image' => '头像必须为图片格式',
            'images.max' => '图片大小不得大于5M',
            'ass_name.required' => '社团名称不得为空',
            'ass_name.unique' => '社团已存在',
            'ass_name.max' => '社团名称不得大于20位',
            'introduce.required' => '学习目标不得为空',
            'introduce.max' => '学习目标不能超过300个字符',
            'learning_objectives.max' => '学习目标不能超过300个字符',
            'learning_objectives.required' => '学习目标不得为空',
            'association_id.required' => '参数错误'
        ];
    }
}
