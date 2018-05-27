<?php

namespace App\Repositories\Admin\Login;

use DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class EloquentLoginRepository implements LoginRepositoryContract
{

    function __construct()
    {
    }

    public function check($input){
    	$user = $input['user'];
    	$pwd = $input['pwd'];
    	if (Auth::guard('admin')->attempt(['username' => $user, 'password' => $pwd])) {
    		$admin = Admin::find(Auth::guard('admin')->user()->id);
    		$admin->last_time = date('Y-m-d H:i:s');
			$admin->save();
			return true;
     	}else{
     		return false;
     	}
    }
    
    public function menu(){
		$role = unserialize(Auth::guard('admin')->user()->role);
    	$res = DB::table('menu as a')
    			->leftJoin('menu as b','a.id','=','b.parents')
    			->orderByRaw('a.orders asc,b.orders asc')
    			->select('a.id as aid','a.name as aname','a.url as aurl','a.tags as atags','a.img as aimg',
    					'b.id as bid','b.name as bname','b.url as burl','b.tags as btags','b.img as bimg'
    					)
    			->whereRaw('a.level = ?',['1'])
    			->get();
		$arr = array();
		$role = is_array($role)?array_flip($role):array();
		foreach($res as $k=>$v){
			if(isset($role[$v->aid])){
				$arr[$v->aid]['id'] = $v->aid;
				$arr[$v->aid]['name'] = $v->aname;
				$arr[$v->aid]['url'] = $v->aurl;
				$arr[$v->aid]['tags'] = $v->atags;
				$arr[$v->aid]['img'] = $v->aimg;
			}
			if(isset($role[$v->bid])){
				$arr[$v->aid]['child'][$v->bid]['id'] = $v->bid;
				$arr[$v->aid]['child'][$v->bid]['name'] = $v->bname;
				$arr[$v->aid]['child'][$v->bid]['url'] = $v->burl;
				$arr[$v->aid]['child'][$v->bid]['tags'] = $v->btags;
				$arr[$v->aid]['child'][$v->bid]['img'] = $v->bimg;
			}
		}
		return $arr;
    }
}