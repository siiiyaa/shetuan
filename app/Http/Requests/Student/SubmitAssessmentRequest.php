<?php

namespace App\Http\Requests\Student;

use App\Rules\EmptyArray;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAssessmentRequest extends FormRequest
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
            'assessment_id' => 'required|exists:assessments,id',
            'association_id' => 'required|exists:associations,id',
            'score' => 'bail|required|numeric',
            'topic_id' => 'bail|required|exists:topics,id',
            'answer' => ['bail','required',new EmptyArray(),'array']
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => '参数不完整',
            'assessment_id.exists' => '参数错误',
            'association_id.required' => '参数不完整',
            'association_id.exists' => '参数错误',
            'score.required' => '参数不完整',
            'score.numeric' => '参数不完整',
        ];
    }
}
