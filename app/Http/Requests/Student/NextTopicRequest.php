<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class NextTopicRequest extends FormRequest
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
            'topic_id' => 'bail|required|exists:topics,id',
            'answer' => 'bail|required|array',
            'assessment_id' => 'bail|required|exists:assessments,id',
            'score' => 'bail|required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => '参数不完整',
            'topic_id.exists' => '参数错误',
            'answer.required' => '参数不完整',
            'answer.array' => '参数错误',
            'assessment_id.required' => '参数不完整',
            'assessment_id.exists' => '参数错误',
            'score.required' => '参数不完整',
            'score.numeric' => '参数错误',
        ];
    }
}
