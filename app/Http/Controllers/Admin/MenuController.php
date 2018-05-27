<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\Menu\MenuRepositoryContract;

class MenuController extends Controller {
	
	private $menu;
	
	public function __construct(MenuRepositoryContract $menu){
		$this->menu = $menu;
	}

	public function getList(Request $request){
		$res = $this->menu->all();
		return view('admin.menu.menuList')
				->with("all",$res)
				->with("nav","admin")
				->with("subnav","menulist");
	}
	
	public function postList(Request $request){
		$res = $this->menu->query($request->all());
		
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}

	public function getDetail(Request $request,$id = ''){
		$menu = $this->menu->detail($id);
		return response()->json($menu);
	}
	
	public function postDetail(Request $request){
		$res = $this->menu->create($request->all());
		if($res){
			return response()->json(array('error'=>''));
		}else{
			return response()->json(array('error'=>'菜单提交失败！'));
		}
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$res = $this->menu->delete($id);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'菜单删除失败！']);
		}
	}

	
}
