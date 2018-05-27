<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class QiniuController extends Controller {

	
	public function __construct()
	{
	}

	public function getUptoken(Request $request){
		$accessKey = getenv('QINIU_ACCESS_KEY');
		$secretKey = getenv('QINIU_SECRET_KEY');
		$bucket = getenv('QINIU_TEST_BUCKET');
		
		// 构建鉴权对象
		$auth = new Auth($accessKey, $secretKey);
		
		// 生成上传 Token
		$token = $auth->uploadToken($bucket);
		
		return response()->json(['uptoken'=>$token]);
	}
	
	public function putfile($key, $filepath){
		$accessKey = getenv('QINIU_ACCESS_KEY');
		$secretKey = getenv('QINIU_SECRET_KEY');
		$bucket = getenv('QINIU_TEST_BUCKET');
		
		// 构建鉴权对象
		$auth = new Auth($accessKey, $secretKey);
		
		// 生成上传 Token
		$token = $auth->uploadToken($bucket);
		
		$uploadmanager = new UploadManager();
		$err = $uploadmanager->putFile($token, $key, $filepath);
		
		return $err;
	}
	
	public function delete($bucket, $key){
		$accessKey = getenv('QINIU_ACCESS_KEY');
		$secretKey = getenv('QINIU_SECRET_KEY');
		$auth = new Auth($accessKey, $secretKey);
		$bucketManager = new BucketManager($auth);
		$err = $bucketManager->delete($bucket, $key);
		
		return $err;
	}

}
