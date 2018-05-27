<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\StoreVideo;

use DB;
use App\Models\Store;
use App\Models\StoreDetail;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class EloquentStoreVideoRepository implements StoreVideoRepositoryContract
{

    function __construct()
    {
    }
    
	public function query($input, $datatype = ''){
		$start = isset($input['start'])?$input['start']:0;
		$length = isset($input['length'])?$input['length']:50;
		
		$display = Auth::guard('admin')->user()->btn;
		
		$table = StoreDetail::where('store_id', $input['store_id']);
		
		$count = $table->count();
		
		if(isset($input['order'])){
			$orderby = $this->listorder($input['order']);
			$table->orderByRaw($orderby);
		}else{
			$table->orderByRaw('orders asc,created_at asc');
		}
		
		$table->select(
			"id",
			"hash_id",
			"store_id",
			"title",
			"filename",
			"fileurl",
			"domain",
			"orders",
			"created_at",
			DB::raw($display." as display")
		);
		
		if($datatype=='all'){
			$list = $table->get();
		}else{
			$list = $table->skip($start)->take($length)->get();
		}
		
		return ['list'=>$list,'count'=>$count];
    }
	
	private function listorder($order){
		$column = $order['0']['column'];//那一列排序，从0开始
		$dir = $order['0']['dir'];//ase desc 升序或者降序
		$str = "";
		if(isset($column)){
    		$i = intval($column);
		    switch($i){
		    	case 0:
		        	$str = "id ".$dir;
		        	break;
				case 1:
		        	$str = "title ".$dir;
		        	break;
				case 2:
		        	$str = "fileurl ".$dir;
		        	break;
				case 3:
		        	$str = "orders ".$dir;
		        	break;
		        case 4:
		        	$str = "created_at ".$dir;
		        	break;
		        default:
		        	$str = '';
		    }
		}
		return $str;
	}

	
	public function save($input){
		$id = $input["id"];
		$title = $input["title"];
		$orders = $input["orders"];
		$store_id = $input["store_id"];
		
		if($id){
			$storedetail = StoreDetail::find($id);
			$storedetail->store_id = $store_id;
			$storedetail->title = $title;
			$storedetail->orders = $orders;
			$storedetail->filename = "";
			$storedetail->fileurl = "";
			$storedetail->save();
		}else{
			$storedetail = new StoreDetail();
			$storedetail->store_id = $store_id;
			$storedetail->title = $title;
			$storedetail->orders = $orders;
			$storedetail->filename = "";
			$storedetail->fileurl = "";
			$storedetail->save();
			$hash_id = Hashids::encode($storedetail->id);
			$storedetail->hash_id = 'sd'.$hash_id;
			$storedetail->save();
		}
	}
	
	public function fileurl($input){
		$hash_id = $input["hash_id"];
		$filename = $input["filename"];
		$fileurl = $input["fileurl"];
		$domain = $input['domain'];
		$bucket = $input['bucket'];
		$storedetail = StoreDetail::where('hash_id', $hash_id)->first();
		$storedetail->filename = $filename;
		$storedetail->fileurl = $fileurl;
		$storedetail->bucket = $bucket;
		$storedetail->domain = $domain;
		$storedetail->save();
	}
	
	public function detail($id){
		$storedetail = StoreDetail::where('id', $id)->first();
		return $storedetail;
	}

    public function delete($id){
    	$storedetail = StoreDetail::find($id);
    	return $storedetail->delete();
    }
    
}