<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Store\StoreRepositoryContract;

class StoreController extends Controller {

	private $store;
	
	public function __construct(StoreRepositoryContract $store){
		$this->store = $store;
	}
	
	public function getList(Request $request, $type = ''){
		if(in_array($type, [1,2])){
			$name = $type==1?'音频':'视频';
			$subnav = $type==1?'storeaudiolist':'storevideolist';
			return view('admin.store.storeList')
				->with("type",$type)
				->with("name",$name)
				->with("nav","store")
				->with("subnav",$subnav);
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request, $type = ''){
		$res = $this->store->query($request->all(), $type);
		
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}


	public function postSave(Request $request){
		$this->store->save($request->all());
		return response()->json(array("error"=>''));
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$child = $this->store->child($id);
		if(count($child)>0){
			return response()->json(['error'=>'该分类下还有资源不能删除！']);
		}else{
			$res = $this->store->delete($id);
			if($res){
				return response()->json(['error'=>'']);
			}else{
				return response()->json(['error'=>'删除失败！']);
			}
		}
		
	}
	
}
