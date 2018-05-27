<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CourseRequest extends Request
{

    public function rules(){
        return [
        	'title' => 'required',
            'contents' => 'required',
            'price' => 'required|numeric',
            'inall' => 'required|numeric',
            'fake' => 'numeric',
            'orders' => 'numeric'
		];
    }
	
	public function messages(){
		return [
			'title.required' => '请输入课程标题！',
			'contents.required'  => '请输入课程介绍！',
			'price.required'  => '请输入课程价格！',
			'inall.required'  => '请输入课程总集数！',
			'price.numeric'  => '课程价格必须是数字！',
			'inall.numeric'  => '课程总集数必须是数字！',
			'fake.numeric'  => '虚拟播放量必须是数字！',
			'orders.numeric'  => '课程排序必须是数字！'
		];
	}
}
