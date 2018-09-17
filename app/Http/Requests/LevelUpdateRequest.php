<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'=>'required|regex:/^[a-z0-9]{6,8}$/',
            //密码
            'pass'=>'required|regex:/^[0-9]{6}$/',
            //邮箱
            'email'=>'required|email',
            //手机
            'phone'=>'required|regex:/^1[0-9]{10}$/'


        ];
    }
    public function messages(){
        return [
            'name.required'=>'用户名不能为空',
            'name.regex'=>'用户名必须为6-8的数字字母',
            'name.unique'=>'用户名已经存在',
            'pass.required'=>'密码不能为空',
            'pass.regex'=>'密码必须为6位的数字',
            'phone.required'=>'手机不能为空',
            'phone.regex'=>'手机格式不正确',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱已经被注册'
        ];
    }
}
