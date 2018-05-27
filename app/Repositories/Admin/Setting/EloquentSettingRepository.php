<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Setting;

use DB;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class EloquentSettingRepository implements SettingRepositoryContract
{

    function __construct()
    {
    }
	
	public function detail(){
		$res = Setting::orderBy('id', 'asc')->get();
		return $res;
	}
	
	public function save($input){
		foreach($input['data'] as $k=>$v){
			Setting::where('keys', $k)->update(['values'=>$v]);
		}
	}
	
}