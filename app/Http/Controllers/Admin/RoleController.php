<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use App\Http\Requests\Admin\PwdRequest;
use App\Repositories\Admin\Role\RoleRepositoryContract;
use App\Repositories\Admin\Login\LoginRepositoryContract;

class RoleController extends Controller {

	private $role;
	private $login;
	
	public function __construct(RoleRepositoryContract $role,LoginRepositoryContract $login){
		$this->role = $role;
		$this->login = $login;
	}
	
	public function getList(Request $request){
		$menu = $this->role->rolejson(array());
		return view('admin.menu.roleList')
				->with("menu",$menu)
				->with("nav","admin")
				->with("subnav","rolelist");
	}
	
	public function postList(Request $request){
		$res = $this->role->query($request->all());
		// dd($res['list']);
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}

	public function getPwd(){
		return view('admin.menu.pwd')
				->with("nav","admin")
				->with("subnav","rolepwd");
	}
	
	public function postPwd(PwdRequest $request){
		$res = $this->role->pwdedit($request->all());
		if($res){
			return redirect()->route('AdminHomeGetIndex');
		}else{
			return redirect()->route('AdminRoleGetPwd')->withErrors('修改密码失败！');
		}
	}

	public function getInfo(Request $request){
		$role = array();
		$menu = $this->role->info($request->all());
		
		return view('admin.menu.roleInfo')
				->with("menu",$menu);
	}
	
	public function postSave(Request $request){
		$this->role->save($request->all());
		if(Auth::guard('admin')->user()->id==$request->input("id")){
			$menu = $this->login->menu();
			session(['wechat_menu'=>$menu]);
		}
		return response()->json(array("error"=>''));
	}
	
	public function getDetail(Request $request,$id = ''){
		$res = $this->role->detail($id);
		return response()->json($res);
	}
	
	public function postDetail(Request $request){
		$res = $this->role->adminsave($request->all());
		return response()->json(array("error"=>""));
	}
	
	public function postDelete(Request $request){
		$adminid = $request->input('adminid');
		$res = $this->role->delete($adminid);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'管理员删除失败！']);
		}
	}
}
