<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Store;

use DB;
use App\Models\Store;
use App\Models\StoreDetail;
use Illuminate\Support\Facades\Auth;

class EloquentStoreRepository implements StoreRepositoryContract
{

    function __construct()
    {
    }
    
	public function query($input, $type, $datatype = ''){
		$start = isset($input['start'])?$input['start']:0;
		$length = isset($input['length'])?$input['length']:50;
		
		$display = Auth::guard('admin')->user()->btn;
		
		$table = Store::where('type', $type);
		
		$count = $table->count();
		
		if(isset($input['order'])){
			$orderby = $this->listorder($input['order']);
			$table->orderByRaw($orderby);
		}else{
			$table->orderByRaw('created_at desc');
		}
		
		$table->select(
			"id",
			"title",
			"created_at",
			DB::raw($display." as display")
		);
		
		if($datatype=='all'){
			$list = $table->get();
		}else{
			$list = $table->skip($start)->take($length)->get();
		}
		foreach($list as &$v){
			$v->count = $v->detail()->count();
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
		        case 3:
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
		$type = $input["type"];
		
		if($id){
			$store = Store::find($id);
		}else{
			$store = new Store();
		}
		$store->type = $type;
		$store->title = $title;
		$store->save();
	}
	
	public function detail($id, $type){
		$store = Store::where('id', $id)->where('type', $type)->first();
		return $store;
	}
	
	public function child($store_id){
		$store = StoreDetail::where('store_id', $store_id)->get();
		return $store;
	}

    public function delete($id){
    	$store = Store::find($id);
    	return $store->delete();
    }
    
}