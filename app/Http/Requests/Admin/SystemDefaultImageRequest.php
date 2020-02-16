<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SystemDefaultImageRequest extends FormRequest
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
            'default_images' => 'bail|required|max:5120|image'
        ];
    }

    public function messages()
    {
        return [
            'default_images.required' => '头像不得为空',
            'default_images.max' => '头像不得大于5M',
            'default_images.image' => '头像必须为图片'
        ];
    }
}
