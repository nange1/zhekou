<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use zgldh\QiniuStorage\QiniuStorage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //获取时间戳
	public function getTimestamp($digits = false) {
		$digits = $digits > 10 ? $digits : 10;
		$digits = $digits - 10;
		if ((!$digits) || ($digits == 10))
		{
			return time();
		}
		else
		{
			return number_format(microtime(true),$digits,'','');
		}
	}

	public function storeFile($file, $filename) {
		$disk = QiniuStorage::disk('qiniu');
		$disk->put( $filename, file_get_contents( $file->getRealPath()));
		$url = explode("?",$disk->imagePreviewUrl($filename,null))[0];
		return $url;
	}
}
