<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AttendanceStoreRequest extends FormRequest
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

        $assoc_id = request()->input('association_id');
        if(!$assoc_id){
            throw (new HttpResponseException(response()->json([
                'data' => ['association_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422)));
        }
        return [
            'time' => ['required', Rule::unique('attendances')->where(function ($query){
                $query->where('assoc_id',request()->input('association_id'));
            })],
        ];
    }


    public function messages()
    {
        return [
            'time.required' => '考勤日期不得为空',
            'time.unique' => '此考勤日期已存在',
        ];
    }
}
