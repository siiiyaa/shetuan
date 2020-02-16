<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeadImageRequest extends FormRequest
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
            'head_image' => 'bail|required|max:5120|image'
        ];
    }

    public function messages()
    {
        return [
            'head_image.required' => '头像不得为空',
            'head_image.max' => '头像大小不得大于5M',
            'head_image.image' => '头像格式错误,必须为图片'
        ];
    }
}
