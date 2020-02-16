<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class AddCourseRequest extends FormRequest
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
            'cou_name' => 'required|unique:courses,cou_name'
        ];
    }

    public function messages()
    {
        return [
            'cou_name.required' => '课程名称不得为空',
            'cou_name.unique' => '此课程已存在',
        ];
    }
}
