<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 14:20
 */

namespace App\Repositories\Admin\Menu;


interface MenuRepositoryContract
{
	//查询菜单
    public function query($input);
	//所有菜单
    public function all();
    //创建菜单
    public function create($input);
    //菜单详情
    public function detail($id);
    //删除菜单
    public function delete($id);
}