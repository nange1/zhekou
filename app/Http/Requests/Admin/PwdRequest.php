<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PwdRequest extends Request
{

    public function rules(){
        return [
            'newpwd1' => 'required|same:newpwd2|min:6',
            'newpwd2' => 'required'
		];
    }
	
	public function messages(){
		return [
			'newpwd1.required' => '请输入新密码！',
			'newpwd1.same'  => '新密码和确认密码不一致！',
			'newpwd1.min' => '新密码长度至少6位！',
			'newpwd2.required'  => '请输入确认密码！'
		];
	}
}
