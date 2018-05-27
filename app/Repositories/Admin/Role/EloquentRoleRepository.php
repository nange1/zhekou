<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Role;

use DB;
use App\Models\Admin;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EloquentRoleRepository implements RoleRepositoryContract
{

    function __construct()
    {
    }
    
	public function query($input){
		$start = $input['start']?:0;
		$length = $input['length']?:50;
		
		$display = Auth::guard('admin')->user()->btn;
		// dd($display);
		$table = DB::table('admin');
		
		$count = $table->count();
		
		$orderby = $this->listorder($input['order']);
		
		if($orderby){
			$table->orderByRaw($orderby);
		}
		
		$table->select(
			"id",
			"realname",
			"username",
			"level",
			"btn",
			"status",
			"created_at",
			DB::raw($display." as display")
		);
		
		$list = $table->skip($start)->take($length)->get();
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
		        	$str = "realname ".$dir;
		        	break;
		        case 2:
		        	$str = "username ".$dir;
		        	break;
		        case 3:
		        	$str = "level ".$dir;
		        	break;
				case 4:
		        	$str = "btn ".$dir;
		        	break;
				case 5:
		        	$str = "status ".$dir;
		        	break;
				case 6:
		        	$str = "created_at ".$dir;
		        	break;
		        default:
		        	$str = '';
		    }
		}
		return $str;
	}

    public function pwdedit($input){
    	$id = Auth::guard('admin')->user()->id;
    	$admin = Admin::find($id);
		$admin->password = Hash::make($input['newpwd1']);
		return $admin->save();
    }
	
	public function info($input){
		$role = array();
		$id = $input["id"];
		$admin = Admin::find($id);
		if($admin){
			$role = unserialize($admin->role);
		}
		return $this->rolejson($role);
	}
	
	public function save($input){
		$id = $input["id"];
		$role = $input["role"];
		$admin = Admin::find($id);
		if(is_array($role)){
			sort($role);
			$admin->role = serialize($role);
		}
		$admin->save();
	}
	
	public function rolejson($role){
		$menu = Menu::whereRaw("parents = ? and disabled = ?",array("0","0"))
					->orderByRaw("orders asc")->get();
		$parents = Menu::whereRaw("parents = ? and disabled = ?",array("0","0"))->pluck("id")->toArray();	
		$submenu = Menu::whereIn('parents',$parents)
					->whereRaw("disabled = ?",array("0"))
					->orderByRaw("orders asc")->get();
		$result = "[";
		$role = is_array($role)?array_flip($role):array();
		foreach($menu as $m){
			$sub = "";
			foreach($submenu as $s){
				if($s->parents==$m->id){
					$sub .= "{'id':'".$s->id."','text':'".$s->name."','state':{'opened':true";
					if(isset($role[$s->id])){
						$sub .= ",'selected':true";
					}
					$sub .= "}},";
				}
			}
			$result .= "{'id':'".$m->id."','text':'".$m->name."','state':{'opened':true";
			if(isset($role[$m->id])&&$sub==""){
				$result .= ",'selected':true";
			}
			$result .= "},'children':[";
			
			if($sub!=""){
				$result .= substr($sub, 0, -1);
			}
			$result .= "]},";
		}
		
		$result = substr($result, 0, -1)."]";
		return $result;
	}

	public function detail($id){
    	if($id){
    		return Admin::find($id);
    	}else{
    		return new Page();
    	}
    }
	
	public function adminsave($input){
        if($input['adminid']){
        	$admin = Admin::find($input['adminid']);
        }else{
        	$admin = new Admin;
        }
		$admin->username = $input['username'];
		$admin->realname = $input['realname'];
		if(!$input['adminid']){
			$admin->password = Hash::make($input['password']);
		}
		$admin->level = $input['level'];
		$admin->btn = $input['btn'];
		$admin->status = $input['status'];
		$admin->save();
		return true;
	}
	
    public function delete($id){
    	$res = Admin::find($id);
    	if($res){
    		$res->delete();
			return true;
    	}else{
    		return false;
    	}
    }
    
}