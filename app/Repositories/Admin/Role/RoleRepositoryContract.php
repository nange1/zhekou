<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Role;


interface RoleRepositoryContract
{
    public function pwdedit($input);
	
    public function query($input);
	
	public function rolejson($role);
	
	public function info($input);
	
	public function save($input);
	
	public function detail($id);
	
	public function adminsave($input);

    public function delete($id);
	
}