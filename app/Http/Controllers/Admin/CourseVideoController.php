<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Course\CourseRepositoryContract;
use App\Repositories\Admin\CourseVideo\CourseVideoRepositoryContract;
use App\Repositories\Admin\Store\StoreRepositoryContract;
use App\Repositories\Admin\StoreVideo\StoreVideoRepositoryContract;
use App\Http\Controllers\Admin\QiniuController;
use App\Http\Requests\Admin\CourseVideoRequest;

class CourseVideoController extends Controller {

	private $course;
	private $coursevideo;
	private $store;
	private $storevideo;
	
	public function __construct(CourseRepositoryContract $course,
		CourseVideoRepositoryContract $coursevideo,
		StoreRepositoryContract $store,
		StoreVideoRepositoryContract $storevideo){
		$this->course = $course;
		$this->coursevideo = $coursevideo;
		$this->store = $store;
		$this->storevideo = $storevideo;
	}
	
	public function getList(Request $request,$course_id = ''){
		$res = $this->course->detail($course_id, 2);
		if($res){
			return view('admin.course.videoList')
				->with("course_id",$course_id)
				->with("nav","course")
				->with("subnav","coursevideolist");
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request){
		$res = $this->coursevideo->query($request->all());
		
		return response()->json(array(
		    "draw" => intval($request->input('draw')),
		    "recordsTotal" => intval($res['count']),
		    "recordsFiltered" => intval($res['count']),
		    "data" => $res['list']
		));
	}
	
	public function postStatus(Request $request){
		$id = $request->input("id");
		$type = $request->input("type")?:"on";
		$this->coursevideo->status($id, $type);
		return response()->json(array("error"=>""));
	}
	
	public function getDetail(Request $request,$course_id, $id = ''){
		$data = $this->coursevideo->detail($id);
		$store = $this->store->query([], 2, 'all');
		if($data){
			$store_id = $data->store_id;
		}else{
			$store_id = $store['list']?$store['list'][0]->id:0;
		}
		$storevideo = $this->storevideo->query(['store_id'=>$store_id], 'all');
		return view('admin.course.videoDetail')
				->with("data",$data)
				->with("store",$store['list'])
				->with("storevideo",$storevideo['list'])
				->with("id",$id)
				->with("course_id",$course_id)
				->with("nav","course")
				->with("subnav","coursevideolist");
	}
	
	public function postDetail(CourseVideoRequest $request){
		$check = $this->coursevideo->checktitle($request->all());
		if($check){
			return redirect()->route('AdminCourseVideoGetDetail',['course_id'=>$request->input('course_id'),'id'=>$request->input('id')])
					->withErrors('课程标题已经存在！')
	                ->withInput();
		}
        $res = $this->coursevideo->save($request->all());
		
		return redirect()->route('AdminCourseVideoGetList',['course_id'=>$request->input('course_id')]);
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$res = $this->coursevideo->delete($id);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'删除失败！']);
		}
	}
	
	public function postStoreDetail(Request $request){
		$store_id = $request->input('store_id');
		$storevideo = $this->storevideo->query(['store_id'=>$store_id], 'all');
		return response()->json($storevideo['list']);
	}
	
	public function postUploadpic(Request $request){
		if ($request->hasFile('imagefile')){
			
			$file = $request->file('imagefile');
			
			$name = $file->getClientOriginalName();
	        $size = $file->getSize();
	        $ext = $file->getClientOriginalExtension();
	        $arr = getimagesize($file);
	        $path = $file->getRealPath();
			
			$key = 'coverimg/'.md5(date('His').$name).'.'.$ext;
			
			$bucket = getenv('QINIU_TEST_BUCKET');
			$domain = getenv('QINIU_BUCKET_URL');
			
			$qiniu = new QiniuController();
			$res = $qiniu->putfile($key, $path);
			
			return response()->json([
					'url'=>$domain.$res[0]['key'],
					'size'=>$size,
					'width'=>$arr[0],
					'height'=>$arr[1]
				]);
		}
	}
}
