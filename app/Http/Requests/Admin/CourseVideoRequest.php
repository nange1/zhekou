<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CourseVideoRequest extends Request
{

    public function rules(){
        return [
        	'title' => 'required',
            'contents' => 'required',
            'store_id' => 'required',
            'store_detail_id' => 'required',
            'fake' => 'numeric',
            'orders' => 'numeric'
		];
    }
	
	public function messages(){
		return [
			'title.required' => '请输入选集标题！',
			'contents.required'  => '请输入选集介绍！',
			'store_id.required'  => '请输入选集内容！',
			'store_detail_id.required'  => '请输入选集内容！',
			'fake.numeric'  => '虚拟播放量必须是数字！',
			'orders.numeric'  => '选集排序必须是数字！'
		];
	}
}
