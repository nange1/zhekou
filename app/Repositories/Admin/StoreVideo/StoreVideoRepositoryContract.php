<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\StoreVideo;


interface StoreVideoRepositoryContract
{
    public function query($input, $datatype);
	
	public function detail($id);
	
	public function save($input);
	
	public function fileurl($input);
	
    public function delete($id);
}