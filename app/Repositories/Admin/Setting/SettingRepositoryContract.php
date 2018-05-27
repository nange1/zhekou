<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Setting;


interface SettingRepositoryContract
{
	public function detail();
	
	public function save($input);
}