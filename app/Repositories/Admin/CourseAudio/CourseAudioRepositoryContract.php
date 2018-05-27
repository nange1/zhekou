<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\CourseAudio;


interface CourseAudioRepositoryContract
{
    public function query($input);
	
	public function detail($id);
	
	public function status($id, $type);
	
	public function checktitle($input);
	
	public function save($input);
	
    public function delete($id);
}