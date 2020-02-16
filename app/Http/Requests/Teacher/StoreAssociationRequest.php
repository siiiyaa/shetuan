<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class StoreAssociationRequest extends FormRequest
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
    public function rules(\Illuminate\Http\Request $request)
    {

        $rule = [
            'ass_name' => 'bail|required|unique:associations,ass_name|max:20',
            'corporate_slogan' => 'required|max:100',
            'introduce' => 'bail|required|max:300',
            'learning_objectives' => 'required|max:300',
            'department_id' => 'required|exists:departments,id',
            'english_name' => 'required|max:30',
         ];

        if($request->get('token_data')->token_rule == 'admin'){
            $rule['teacher_id'] = 'bail|required|exists:teachers,id';
        }

        if($request->hasFile('images')){
            $rule['images'] = 'bail|image|max:5120';
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
            'teacher_id.required' => '参数不完整',
            'corporate_slogan.required' => '参数不完整',
            'department_id.required' => '参数不完整',
            'english_name.required' => '英语名称不得为空',
            'corporate_slogan.max' => '社团口号不得超过100个字符',
            'english_name.max' => '英语名称不得大于30个字符',
            'department_id.exists' => '参数错误',
            'teacher_id.exists' => '参数错误',
        ];
    }
}
