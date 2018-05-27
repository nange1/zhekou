<?php

namespace App\Providers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
		$data = [];
		$res = DB::table('setting')->select('keys', 'values')->get();
		foreach($res as $v){
			$data[$v->keys] = $v->values;
		}
		view()->share('setting', $data);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->admin();
		$this->api();
		$this->web();
    }

	public function admin()
  	{
    	$this->app->bind(
	        \App\Repositories\Admin\Home\HomeRepositoryContract::class,
	        \App\Repositories\Admin\Home\EloquentHomeRepository::class
    	);
		
		$this->app->bind(
	        \App\Repositories\Admin\Login\LoginRepositoryContract::class,
	        \App\Repositories\Admin\Login\EloquentLoginRepository::class
    	);

	    $this->app->bind(
	        \App\Repositories\Admin\Menu\MenuRepositoryContract::class,
	        \App\Repositories\Admin\Menu\EloquentMenuRepository::class
	    );

		$this->app->bind(
	        \App\Repositories\Admin\Role\RoleRepositoryContract::class,
	        \App\Repositories\Admin\Role\EloquentRoleRepository::class
	    );

		$this->app->bind(
	        \App\Repositories\Admin\Setting\SettingRepositoryContract::class,
	        \App\Repositories\Admin\Setting\EloquentSettingRepository::class
	    );
// 推荐
	    $this->app->bind(
	        \App\Repositories\Admin\Recommend\RecommendRepositoryContract::class,
	        \App\Repositories\Admin\Recommend\EloquentRecommendRepository::class
	    );
//订单
		$this->app->bind(
	        \App\Repositories\Admin\Orders\OrdersRepositoryContract::class,
	        \App\Repositories\Admin\Orders\EloquentOrdersRepository::class
	    );		
		$this->app->bind(
	        \App\Repositories\Admin\Store\OrdersRepositoryContract::class,
	        \App\Repositories\Admin\Store\EloquentOrdersRepository::class
	    );
		
		$this->app->bind(
	        \App\Repositories\Admin\StoreAudio\StoreAudioRepositoryContract::class,
	        \App\Repositories\Admin\StoreAudio\EloquentStoreAudioRepository::class
	    );
		
		$this->app->bind(
	        \App\Repositories\Admin\StoreVideo\StoreVideoRepositoryContract::class,
	        \App\Repositories\Admin\StoreVideo\EloquentStoreVideoRepository::class
	    );
		
		$this->app->bind(
	        \App\Repositories\Admin\Course\CourseRepositoryContract::class,
	        \App\Repositories\Admin\Course\EloquentCourseRepository::class
	    );
		
		$this->app->bind(
	        \App\Repositories\Admin\CourseAudio\CourseAudioRepositoryContract::class,
	        \App\Repositories\Admin\CourseAudio\EloquentCourseAudioRepository::class
	    );
		
		$this->app->bind(
	        \App\Repositories\Admin\CourseVideo\CourseVideoRepositoryContract::class,
	        \App\Repositories\Admin\CourseVideo\EloquentCourseVideoRepository::class
	    );
	}


	public function api()
  	{
	
	}

	public function web()
    {
		
	}
}
