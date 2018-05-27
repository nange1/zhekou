<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Menu;

use DB;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class EloquentMenuRepository implements MenuRepositoryContract
{

    function __construct()
    {
    }
    
    public function query($input){
    	$draw = isset($input['draw'])?$input['draw']:'';
		$start = isset($input['start'])?$input['start']:0;
		$length = isset($input['length'])?$input['length']:10;
		
		$display = Auth::guard('admin')->user()->btn;
		
		$table = DB::table('menu');
		
		$count = $table->count();
		
		$table->orderByRaw("concat(path,'-',right(concat('0000',id),4)) asc,orders asc");
		
		$table->select(
			"id",
			"orders",
			"path",
			"name",
			"url",
			"tags",
			"img",
			"disabled",
			DB::raw($display." as display")
		);
		
		$list = $table->skip($start)->take($length)->get();
		return ['list'=>$list,'count'=>$count];
    }
	
	public function all(){
		$res = Menu::whereRaw("disabled = ?",array("0"))
				->orderByRaw("concat(path,'-',right(concat('0000',id),4)) asc,orders asc")
				->get();
		return $res;
	}

    public function create($input){
    	if($input['menuid']){
        	$menu = Menu::find($input['menuid']);
        }else{
        	$menu = new Menu;
        }
        if($input['parents']==0){
        	$path = "0";
			$level = "1";
        }else{
        	$parents = Menu::find($input['parents']);
        	$path = $parents->path."-".str_pad($input['parents'],4,'0',STR_PAD_LEFT);
			$level = intval($parents->level) + 1;
        }
		$menu->parents = $input['parents'];
		$menu->path = $path;
		$menu->level = $level;
		$menu->tags = $input['tags'];
		$menu->img = $input['img'];
		$menu->name = $input['name'];
		$menu->url = $input['url'];
		$menu->admin_id = Auth::guard('admin')->user()->id;
		$menu->orders = $input['orders'];
		$menu->disabled = $input['disabled'];
		$res = $menu->save();
		if($input['orders']==0&&$input['menuid']==""){
			$menu->orders = $menu->id;
			$res = $menu->save();
		}
		
		return $res;
    }
    
    public function detail($id){
    	if($id){
    		return Menu::find($id);
    	}else{
    		return null;
    	}
    }
    
    public function delete($id){
    	$res = Menu::find($id);
    	if($res){
    		return $res->delete();
    	}else{
    		return false;
    	}
    }
    
}