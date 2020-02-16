<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeadImgRequest extends FormRequest
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
            'field' => 'bail|required|max:5120|image'
        ];
    }

    public function messages()
    {
        return [
            'field.required' => '头像不得为空',
            'field.max' => '头像大小不得大于5M',
            'field.image' => '头像格式错误,必须为图片'
        ];
    }
}
