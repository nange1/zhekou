<?php

namespace App\Repositories\Admin\Login;


interface LoginRepositoryContract
{
    //登录认证
    public function check($input);
    //获取后台菜单列表
    public function menu();
}