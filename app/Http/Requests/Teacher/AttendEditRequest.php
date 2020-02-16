<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class AttendEditRequest extends FormRequest
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
            'association_id' => 'required|numeric',
            'attendance_id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'association_id.required' => '参数不完整',
            'association_id.numeric' => '参数有误',
            'attendance_id.required' => '参数不完整',
            'attendance_id.numeric' => '参数有误',
        ];
    }
}
