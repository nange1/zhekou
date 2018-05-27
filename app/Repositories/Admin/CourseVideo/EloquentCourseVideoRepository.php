<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\CourseVideo;

use DB;
use App\Models\Course;
use App\Models\CourseDetail;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class EloquentCourseVideoRepository implements CourseVideoRepositoryContract
{

    function __construct()
    {
    }
    
	public function query($input){
		$start = $input['start']?:0;
		$length = $input['length']?:50;
		
		$display = Auth::guard('admin')->user()->btn;
		
		$table = CourseDetail::where('course_id', $input['course_id']);
		
		$count = $table->count();
		
		$orderby = $this->listorder($input['order']);
		
		if($orderby){
			$table->orderByRaw($orderby);
		}
		
		$table->select(
			"id",
			"hash_id",
			"course_id",
			"title",
			"listen",
			"store_id",
			"store_detail_id",
			"num",
			"fake",
			"orders",
			"status",
			"created_at",
			DB::raw($display." as display")
		);
		
		$list = $table->skip($start)->take($length)->get();
		foreach($list as &$v){
			$v->detailtitle = $v->storedetail?$v->storedetail->title:'';
			$v->fileurl = $v->storedetail?$v->storedetail->domain.$v->storedetail->fileurl:'';
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
		        	$str = "listen ".$dir;
		        	break;
				case 3:
		        	$str = "num ".$dir;
		        	break;
				case 4:
		        	$str = "status ".$dir;
		        	break;
				case 5:
		        	$str = "orders ".$dir.",created_at ".$dir;
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

	public function status($id, $type){
		$res = CourseDetail::find($id);
		$res->status = $type=="on"?1:0;
		$res->save();
		return true;
	}
	
	public function checktitle($input){
		$id = $input['id']?:0;
		$res = CourseDetail::whereRaw('id <> ? and title = ?', [$id, $input['title']])->first();
		return $res;
	}

	
	public function save($input){
		$id = $input["id"];
		$course_id = $input["course_id"];
		$title = $input["title"];
		$description = $input["description"];
		$store_id = $input["store_id"];
		$store_detail_id = $input["store_detail_id"];
		$listen = $input["listen"];
		$contents = $input["contents"];
		$fake = $input["fake"]?:0;
		$orders = $input["orders"]?:0;
		$picurl = $input["picurl"];
		
		if($id){
			$course = CourseDetail::find($id);
			$course->course_id = $course_id;
			$course->title = $title;
			$course->description = $description;
			$course->store_id = $store_id;
			$course->store_detail_id = $store_detail_id;
			$course->listen = $listen;
			$course->contents = $contents;
			$course->fake = $fake;
			$course->orders = $orders;
			if($picurl){
				$course->picurl = $picurl;
			}
			$course->save();
		}else{
			$course = new CourseDetail();
			$course->course_id = $course_id;
			$course->title = $title;
			$course->description = $description;
			$course->store_id = $store_id;
			$course->store_detail_id = $store_detail_id;
			$course->listen = $listen;
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
			$course->hash_id = 'cv'.$hash_id;
			$course->save();
		}
	}
	
	
	public function detail($id){
		$storedetail = CourseDetail::where('id', $id)->first();
		return $storedetail;
	}

    public function delete($id){
    	$storedetail = CourseDetail::find($id);
    	return $storedetail->delete();
    }
    
}