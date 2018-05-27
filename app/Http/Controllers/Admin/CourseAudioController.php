<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Course\CourseRepositoryContract;
use App\Repositories\Admin\CourseAudio\CourseAudioRepositoryContract;
use App\Repositories\Admin\Store\StoreRepositoryContract;
use App\Repositories\Admin\StoreAudio\StoreAudioRepositoryContract;
use App\Http\Controllers\Admin\QiniuController;
use App\Http\Requests\Admin\CourseAudioRequest;

class CourseAudioController extends Controller {

	private $course;
	private $courseaudio;
	private $store;
	private $storeaudio;
	
	public function __construct(CourseRepositoryContract $course,
		CourseAudioRepositoryContract $courseaudio,
		StoreRepositoryContract $store,
		StoreAudioRepositoryContract $storeaudio){
		$this->course = $course;
		$this->courseaudio = $courseaudio;
		$this->store = $store;
		$this->storeaudio = $storeaudio;
	}
	
	public function getList(Request $request,$course_id = ''){
		$res = $this->course->detail($course_id, 1);
		if($res){
			return view('admin.course.audioList')
				->with("course_id",$course_id)
				->with("nav","course")
				->with("subnav","courseaudiolist");
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request){
		$res = $this->courseaudio->query($request->all());
		
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
		$this->courseaudio->status($id, $type);
		return response()->json(array("error"=>""));
	}
	
	public function getDetail(Request $request,$course_id, $id = ''){
		$data = $this->courseaudio->detail($id);
		$store = $this->store->query([], 1, 'all');
		if($data){
			$store_id = $data->store_id;
		}else{
			$store_id = $store['list']?$store['list'][0]->id:0;
		}
		$storeaudio = $this->storeaudio->query(['store_id'=>$store_id], 'all');
		return view('admin.course.audioDetail')
				->with("data",$data)
				->with("store",$store['list'])
				->with("storeaudio",$storeaudio['list'])
				->with("id",$id)
				->with("course_id",$course_id)
				->with("nav","course")
				->with("subnav","courseaudiolist");
	}
	
	public function postDetail(CourseAudioRequest $request){
		$check = $this->courseaudio->checktitle($request->all());
		if($check){
			return redirect()->route('AdminCourseAudioGetDetail',['course_id'=>$request->input('course_id'),'id'=>$request->input('id')])
					->withErrors('课程标题已经存在！')
	                ->withInput();
		}
        $res = $this->courseaudio->save($request->all());
		
		return redirect()->route('AdminCourseAudioGetList',['course_id'=>$request->input('course_id')]);
	}
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$res = $this->courseaudio->delete($id);
		if($res){
			return response()->json(['error'=>'']);
		}else{
			return response()->json(['error'=>'删除失败！']);
		}
	}
	
	public function postStoreDetail(Request $request){
		$store_id = $request->input('store_id');
		$storeaudio = $this->storeaudio->query(['store_id'=>$store_id], 'all');
		return response()->json($storeaudio['list']);
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
