<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:21
 */

namespace App\Repositories\Admin\Recommend;

use DB;
use App\Model\RecommendModel as recommend;

class EloquentRecommendRepository implements RecommendRepositoryContract
{

    function __construct()
    {
    }
    // 分页排序
	public function query($input){

		$start = $input['start']?:0;

		$length = $input['length']?:50;

		$dir = $input['order']['0']['dir'];

		$column = $input['order']['0']['column'];
		
		$table = recommend::orderBy($input['columns'][$column]['data'],$dir);
		
		$count = $table->count();
	
		$list = $table->take($length)->skip($start)->get();			
			
		return ['list'=>$list,'count'=>$count];
    }
	
	public function recommendjson($recommend){
		
		$recommend = recommend::all();
		$recommend = json_encode($recommend);	
		return  $recommend;
		
	}
	// 编辑占位值
	public function detail($id){

    	if($id){
    		$res = recommend::find($id);
    		return $res;
    	}else{
    		return new Page();
    	}
    }
	
    public function delete($id)
    {
    	$res = recommend::find($id)->delete();

    	if($res){
			return true;
    	}else{
    		return false;
    	}
    }
    // 发布
    public function publish($id)
    {
    	$res = recommend::find($id);

    	$data = $res['type']==1?2:1;

    	$result = recommend::where('id',$id)->update(['type'=>$data]);

    	if ($result) {
    		return true;
    	}else{
    		return false;
    	}
    }
    // 添加编辑banner
    public function add($request)
    {
    	$data = [
			'title'=>$request['title'],
	        'contents'=>$request['contents'],
	        'orders'=>$request['orders'],
        ];
        if ($request['image1']) 
        {
        	$file = $request['image1'];
        
	        $allowed_extensions = ["png", "jpg", "gif"];

	        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) 
	        {
	            return ['error' => 'You may only upload png, jpg or gif.'];
	        }

	        $destinationPath = 'uploads/banner/'; //public 文件夹下面建 storage/uploads 文件夹
	        
	        $extension = $file->getClientOriginalExtension();
	        // dd($extension);
	        $fileName = time().rand(100,999).'.'.$extension;
	        
	        $file->move($destinationPath, $fileName);
	        
	        $filePath = asset($destinationPath.$fileName);

	        $data['img']= $filePath;
		}

// 七牛
        // if (isset($file) && $file) {
        //     $ext = $file->getClientOriginalExtension();
        //     $filename = 'banner/'.$this->getTimestamp(12).'.'.$ext;
        //     $this->storeFile( $file, $filename);
        //     $url = "https://apissources.bamasoso.com/".$filename;
        // } else {
        //     $url = '';
        // }
       
        $id = $request['bannerid']?:'';
		if (!$id) {
			$result = recommend::create($data);
		}else{
			$result = recommend::where('id',$id)->update($data);
		}

		return $result;
    }
    
}