<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Repositories\Admin\Course\CourseRepositoryContract;
use App\Http\Controllers\Admin\QiniuController;
use App\Http\Requests\Admin\CourseRequest;

class CourseController extends Controller {

	private $course;
	
	public function __construct(CourseRepositoryContract $course){
		$this->course = $course;
	}
	
	public function getList(Request $request, $type = ''){
		if(in_array($type, [1,2])){
			$name = $type==1?'音频':'视频';
			$subnav = $type==1?'courseaudiolist':'coursevideolist';
			return view('admin.course.courseList')
				->with("type",$type)
				->with("name",$name)
				->with("nav","course")
				->with("subnav",$subnav);
		}else{
			abort(404);
		}
	}
	
	public function postList(Request $request, $type = ''){
		$res = $this->course->query($request->all(), $type);
		
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
		$this->course->status($id, $type);
		return response()->json(array("error"=>""));
	}
	
	public function getAudioDetail(Request $request, $id = ''){
		$data = $this->course->detail($id);
		$cate = $this->course->cate();
		return view('admin.course.courseDetail')
				->with("data",$data)
				->with("cate",$cate)
				->with("id",$id)
				->with("type",1)
				->with("name","音频")
				->with("nav","course")
				->with("subnav","courseaudiolist");
	}
	
	public function getVideoDetail(Request $request, $id = ''){
		$data = $this->course->detail($id);
		$cate = $this->course->cate();
		return view('admin.course.courseDetail')
				->with("data",$data)
				->with("cate",$cate)
				->with("id",$id)
				->with("type",2)
				->with("name","视频")
				->with("nav","course")
				->with("subnav","coursevideolist");
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
	
	public function postDetail(CourseRequest $request){
		$check = $this->course->checktitle($request->all());
		if($check){
			$type = $request->input('type');
			if($type==1){
				return redirect()->route('AdminCourseGetAudioDetail',['id'=>$request->input('id')])
					->withErrors('课程标题已经存在！')
	                ->withInput();
			}elseif($type==2){
				return redirect()->route('AdminCourseGetVideoDetail',['id'=>$request->input('id')])
					->withErrors('课程标题已经存在！')
	                ->withInput();
			}
		}
        $res = $this->course->save($request->all());
		
		return redirect()->route('AdminCourseGetList',['type'=>$request->input('type')]);
	}
	
	
	public function postDelete(Request $request){
		$id = $request->input('id');
		$child = $this->course->child($id);
		if(count($child)>0){
			return response()->json(['error'=>'该分类下还有选集不能删除！']);
		}else{
			$res = $this->course->delete($id);
			if($res){
				return response()->json(['error'=>'']);
			}else{
				return response()->json(['error'=>'删除失败！']);
			}
		}
	}
	
}
