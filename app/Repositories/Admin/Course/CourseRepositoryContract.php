<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Course;


interface CourseRepositoryContract
{
    public function query($input, $type);
	
	public function status($id, $type);
	
	public function detail($id);
	
	public function checktitle($input);
	
	public function save($input);
	
	public function child($course_id);
	
    public function delete($id);
	
	public function cate();
}