<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Store;


interface StoreRepositoryContract
{
    public function query($input, $type, $datatype);
	
	public function detail($id, $type);
	
	public function child($store_id);
	
	public function save($input);
	
    public function delete($id);
}