<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use App\model\RecommendModel as recommend;
use App\Repositories\Admin\Recommend\RecommendRepositoryContract;
use zgldh\QiniuStorage\QiniuStorage;

class RecommendController extends Controller
{
	private $recommend;
	
	public function __construct(RecommendRepositoryContract $recommend)
	{
		$this->recommend = $recommend;
	}

    public function getList(Request $request)
    {
		return view('admin.recommend.list')
				->with("nav","recommend")
				->with("subnav","recommendlist");
	}
	
	public function postList(Request $request)
	{
		$res = $this->recommend->query($request->all());

		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}

	public function addbanner(Request $request)
	{
		$res = $this->recommend->add($request->all());

		if ($res) {	
			return response()->json(['error'=>""]);	
		}else{
			return response()->json(['error'=>"推荐添加失败"]);
		}
	}

	public function getEdit(Request $request)
	{
		
		$info = $this->recommend->detail($request->input('id'));

		return $info;
	}

	public function postDel(Request $request)
	{
		$resu = $this->recommend->delete($request->input('bannerid2'));

		if ($resu) {	
			return response()->json(['error'=>""]);	
		}else{
			return response()->json(['error'=>"删除失败"]);
		}
	}

	public function postPublish(Request $request)
	{
		$result = $this->recommend->publish($request->input('banner_id'));

		if ($result) {	
			return response()->json(['error'=>""]);	
		}else{
			return response()->json(['error'=>"删除失败"]);
		}
	}
}
