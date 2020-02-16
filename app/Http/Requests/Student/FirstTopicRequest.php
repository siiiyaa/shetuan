<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class FirstTopicRequest extends FormRequest
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
            'assessment_id' => 'bail|required|exists:assessments,id'
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => '参数不完整',
            'assessment_id.exists' => '参数错误'
        ];
    }
}
