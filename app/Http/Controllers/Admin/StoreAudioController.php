<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Store\StoreRepositoryContract;
use App\Repositories\Admin\StoreAudio\StoreAudioRepositoryContract;
use App\Http\Controllers\Admin\QiniuController;

class StoreAudioController extends Controller {

	private $store;
	private $storeaudio;
	
	public function __construct(StoreRepositoryContract $store,
		StoreAudioRepositoryContract $storeaudio){
		$this->store = $store;
		$this->storeaudio = $storeaudio;
	}
	
	public function getList(Request $request,$store_id = ''){
		$res = $this->store->detail($store_id, 1);
		if($res){
			return view('admin.store.audioList')
				->with("store_id",$store_id)
				->with("nav","store")
				->with("subnav","storeaudiolist");
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request){
		$res = $this->storeaudio->query($request->all());
		
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}
	
	public function postSave(Request $request){
		$this->storeaudio->save($request->all());
		return response()->json(array("error"=>''));
	}
	
	public function postFileurl(Request $request){
		$input = $request->all();
		
		$input['domain'] = getenv('QINIU_BUCKET_URL');
		$input['bucket'] = getenv('QINIU_TEST_BUCKET');
		$this->storeaudio->fileurl($input);
		return response()->json(array("error"=>''));
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$res = $this->storeaudio->detail($id);
		if($res){
			$qiniu = new QiniuController();
			$qiniu->delete($res->bucket, $res->fileurl);
		}
		$this->storeaudio->delete($id);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'删除失败！']);
		}
	}
	
}
