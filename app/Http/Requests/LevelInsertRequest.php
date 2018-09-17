<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelInsertRequest extends FormRequest
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
            //设置用户名 required 数据不能为空 其他规则用|隔开
            'A'=>'required|regex:/^[0-9]$/',
            //B区
            'B'=>'required|regex:/^[0-9]$/',
            //日收入
            'day'=>'required|regex:/^[0-9]$/',
            //等级
            'grade'=>'required|unique:level',

        ];
    }

    public function messages(){
        return [
            'A.required'=>'A区不能为空',
            'A.regex'=>'A区必须数字',
            'B.required'=>'A区不能为空',
            'B.regex'=>'A区必须数字',
            'day.required'=>'日收入不能为空',
            'day.regex'=>'日收入必须数字',
            'grade.required'=>'等级不能为空',
            'grade.unique'=>'等级不能重复',
            
        ];
    }
}
