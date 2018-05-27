<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Setting\SettingRepositoryContract;

class SettingController extends Controller {

	private $setting;

	public function __construct(SettingRepositoryContract $setting){
		$this->setting = $setting;
	}

	public function getDetail(Request $request){
		$data = $this->setting->detail();
		return view('admin.setting.settingDetail')
				->with("data",$data)
				->with("nav","setting")
				->with("subnav","settingdetail");
	}
	
	public function postDetail(Request $request){
		$data = $this->setting->save($request->all());
		return response()->json(array("error"=>""));
	}

}
