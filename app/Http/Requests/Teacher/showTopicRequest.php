<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class showTopicRequest extends FormRequest
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
//            'course_id' => 'required',
//            'chapter_id' => 'required',
            'association_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => '参数不完整',
            'chapter_id.required' => '参数不完整',
            'association_id.required' => '参数不完整',
        ];
    }
}
