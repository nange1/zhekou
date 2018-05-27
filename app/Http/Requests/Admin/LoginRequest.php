<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class LoginRequest extends Request
{

    public function rules(){
        return [
            'user' => 'required',
            'pwd' => 'required'
		];
    }
	
	public function messages(){
		return [
			'user.required' => '请输入您的帐号！',
			'pwd.required'  => '请输入您的密码！',
		];
	}
}
