<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Course;

use DB;
use App\Models\Course;
use App\Models\CourseDetail;
use App\Models\CourseCate;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class EloquentCourseRepository implements CourseRepositoryContract
{

    function __construct()
    {
    }
    
	public function query($input, $type){
		$start = $input['start']?:0;
		$length = $input['length']?:50;
		
		$display = Auth::guard('admin')->user()->btn;
		
		$table = Course::where('type', $type);
		
		$count = $table->count();
		
		$orderby = $this->listorder($input['order']);
		
		if($orderby){
			$table->orderByRaw($orderby);
		}
		
		$table->select(
			"id",
			"title",
			"type",
			"member",
			"cate_id",
			"price",
			"inall",
			"num",
			"fake",
			"status",
			"orders",
			"created_at",
			DB::raw($display." as display")
		);
		
		$list = $table->skip($start)->take($length)->get();
		foreach($list as &$v){
			$v->count = $v->detail()->count();
			$v->catename = $v->cate->catename;
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
		        	$str = "cate_id ".$dir;
		        	break;
				case 3:
		        	$str = "member ".$dir;
		        	break;
				case 4:
		        	$str = "inall ".$dir;
		        	break;
				case 5:
		        	$str = "num ".$dir;
		        	break;
		        case 6:
		        	$str = "status ".$dir;
		        	break;
		        case 7:
		        	$str = "orders ".$dir.",created_at ".$dir;
		        	break;
		        case 8:
		        	$str = "created_at ".$dir;
		        	break;
		        default:
		        	$str = '';
		    }
		}
		return $str;
	}
	
	public function checktitle($input){
		$id = $input['id']?:0;
		$res = Course::whereRaw('id <> ? and title = ?', [$id, $input['title']])->first();
		return $res;
	}

	
	public function save($input){
		$id = $input["id"];
		$title = $input["title"];
		$description = $input["description"];
		$cate_id = $input["cate_id"];
		$member = $input["member"];
		$price = $input["price"]?:0;
		$inall = $input["inall"]?:0;
		$contents = $input["contents"];
		$fake = $input["fake"]?:0;
		$orders = $input["orders"]?:0;
		$picurl = $input["picurl"];
		$type = $input["type"];
		
		if($id){
			$course = Course::find($id);
			$course->type = $type;
			$course->title = $title;
			$course->description = $description;
			$course->cate_id = $cate_id;
			$course->member = $member;
			$course->price = $price;
			$course->inall = $inall;
			$course->contents = $contents;
			$course->fake = $fake;
			$course->orders = $orders;
			if($picurl){
				$course->picurl = $picurl;
			}
			$course->save();
		}else{
			$course = new Course();
			$course->type = $type;
			$course->title = $title;
			$course->description = $description;
			$course->cate_id = $cate_id;
			$course->member = $member;
			$course->price = $price;
			$course->inall = $inall;
			$course->contents = $contents;
			$course->fake = $fake;
			$course->orders = $orders;
			if($picurl){
				$course->picurl = $picurl;
			}
			$course->num = 0;
			$course->status = 1;
			$course->save();
			$hash_id = Hashids::encode($course->id);
			$course->hash_id = 'ce'.$hash_id;
			$course->save();
		}
	}

	public function status($id, $type){
		$res = Course::find($id);
		$res->status = $type=="on"?1:0;
		$res->save();
		return true;
	}
	
	public function detail($id, $type = ''){
		if($type){
			$course = Course::where('id', $id)->where('type', $type)->first();
		}else{
			$course = Course::find($id);
		}
		return $course;
	}
	
	public function child($course_id){
		$store = CourseDetail::where('course_id', $course_id)->get();
		return $store;
	}

    public function delete($id){
    	$course = Course::find($id);
    	return $course->delete();
    }
	
	public function cate(){
		$cate = CourseCate::orderBy('id', 'asc')->get();
		return $cate;
	}
    
}