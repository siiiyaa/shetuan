<?php

namespace App\Http\Requests\Admin;

use App\Rules\EmptyArray;
use Illuminate\Foundation\Http\FormRequest;

class StudentDestroyRequest extends FormRequest
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
            'student_id_arr' => ['bail', 'array', new EmptyArray()]
        ];
    }

    public function messages()
    {
        return [
            'student_id_arr.array' => '参数格式错误'
        ];
    }
}
