<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Admin\LoginRequest;
use App\Repositories\Admin\Login\LoginRepositoryContract;

class LoginController extends Controller {

	private $login;
	
	public function __construct(LoginRepositoryContract $login){
		$this->login = $login;
	}

	//登录页面
	public function getIndex(Request $request){
		$callback = $request->input('callback');
		return view('admin.common.login')
				->with('callback', $callback);
	}
	
	//处理登录请求
	public function postIndex(LoginRequest $request){
		//验证身份
		$callback = $request->input('callback');
		$res = $this->login->check($request->all());
		if($res){
			//菜单保存至session
			$menu = $this->login->menu();
			session(['wechat_menu'=>$menu]);
			if($callback){
				//如果有待跳转地址，则跳转至相应地址
				return redirect($callback);
			}else{
				//如果没有则跳转至默认页
				return redirect()->route('AdminHomeGetIndex');
			}
		}else{
			return redirect()->route('AdminLoginGetIndex')
					->withErrors('帐号或密码错误请重新输入！')
                    ->withInput();
		}
	}
	
	//刷新菜单
	public function getRefresh(Request $request){
		$menu = $this->login->menu();
		session(['wechat_menu'=>$menu]);
		return redirect()->route('AdminHomeGetIndex');
	}
	
	//退出登录
	public function getOut(Request $request){
		Auth::guard('admin')->logout();
		$request->session()->flush();
		return redirect()->route('AdminLoginGetIndex');
	}

}
