<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Recommend;


interface RecommendRepositoryContract
{
    public function publish($input);
	
    public function query($input);
	
	public function recommendjson($recommend);

	public function detail($id);

    public function delete($id);

    public function add($input);
    

	// public function info($input);
	
	// public function save($input);

	// public function adminsave($input);
	
}