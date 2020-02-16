<?php

namespace App\Http\Requests\Teacher;

use App\Rules\EmptyArray;
use Illuminate\Foundation\Http\FormRequest;

class AddTopicRequest extends FormRequest
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
            'top_name' => 'required',
            'options' => ['bail', new EmptyArray, 'required','array'],
            'correct' => ['bail', new EmptyArray, 'required','array'],
            'top_score' => 'bail|required|numeric',
            'course_id' => 'required',
            'chapter_id' => 'required',
            'association_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'top_name.required' => '题干不得为空',
            'options.required' => '至少要一个选项',
            'options.array' => '选项传参错误',
            'correct.required' => '至少要一个正确答案',
            'correct.array' => '正确答案传参错误',
            'top_score.required' => '分值不得为空',
            'top_score.numeric' => '分值必须为数组',
            'course_id.required' => '所属课程必选',
            'chapter_id.required' => '所属章节必选',
            'association_id.required' => '参数不完整'
        ];
    }
}
