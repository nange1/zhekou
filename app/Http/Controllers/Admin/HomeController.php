<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\Admin\Home\HomeRepositoryContract;

class HomeController extends Controller {

	private $home;
	
	public function __construct(HomeRepositoryContract $home)
	{
		$this->home = $home;
	}

	public function getIndex(Request $request){
		return view('admin.common.home')
			->with("nav", "home")
			->with("subnav", "");
	}

}
