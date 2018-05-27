<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Store\StoreRepositoryContract;
use App\Repositories\Admin\StoreVideo\StoreVideoRepositoryContract;
use App\Http\Controllers\Admin\QiniuController;

class StoreVideoController extends Controller {

	private $store;
	private $storevideo;
	
	public function __construct(StoreRepositoryContract $store,
		StoreVideoRepositoryContract $storevideo){
		$this->store = $store;
		$this->storevideo = $storevideo;
	}
	
	public function getList(Request $request,$store_id = ''){
		$res = $this->store->detail($store_id, 2);
		if($res){
			return view('admin.store.videoList')
				->with("store_id",$store_id)
				->with("nav","store")
				->with("subnav","storevideolist");
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request){
		$res = $this->storevideo->query($request->all());
		
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}
	
	public function postSave(Request $request){
		$this->storevideo->save($request->all());
		return response()->json(array("error"=>''));
	}
	
	public function postFileurl(Request $request){
		$input = $request->all();
		
		$input['domain'] = getenv('QINIU_BUCKET_URL');
		$input['bucket'] = getenv('QINIU_TEST_BUCKET');
		$this->storevideo->fileurl($input);
		return response()->json(array("error"=>''));
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$res = $this->storevideo->detail($id);
		if($res){
			$qiniu = new QiniuController();
			$qiniu->delete($res->bucket, $res->fileurl);
		}
		$this->storevideo->delete($id);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'删除失败！']);
		}
	}
	
}
