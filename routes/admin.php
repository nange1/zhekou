<?php

Route::group(["middleware" => 'auth:admin'], function() {
	Route::get('/admin', 'Admin\HomeController@getIndex')->name('AdminHomeGetIndex');
	Route::get('/admin/login/out', 'Admin\LoginController@getOut')->name('AdminLoginGetOut');
	Route::get('/admin/login/refresh', 'Admin\LoginController@getRefresh')->name('AdminLoginGetRefresh');

	Route::get('/admin/menu/list', 'Admin\MenuController@getList')->name('AdminMenuGetList');
	Route::post('/admin/menu/list', 'Admin\MenuController@postList')->name('AdminMenuPostList');
	Route::post('/admin/menu/delete', 'Admin\MenuController@postDelete')->name('AdminMenuPostDelete');
	Route::get('/admin/menu/detail/{id?}', 'Admin\MenuController@getDetail')->name('AdminMenuGetDetail');
	Route::post('/admin/menu/detail', 'Admin\MenuController@postDetail')->name('AdminMenuPostDetail');

	Route::get('/admin/role/list', 'Admin\RoleController@getList')->name('AdminRoleGetList');
	Route::post('/admin/role/list', 'Admin\RoleController@postList')->name('AdminRolePostList');
	Route::get('/admin/role/detail/{id?}', 'Admin\RoleController@getDetail')->name('AdminRoleGetDetail');
	Route::post('/admin/role/detail', 'Admin\RoleController@postDetail')->name('AdminRolePostDetail');
	Route::get('/admin/role/info', 'Admin\RoleController@getInfo')->name('AdminRoleGetInfo');
	Route::post('/admin/role/save', 'Admin\RoleController@postSave')->name('AdminRolePostSave');
	Route::post('/admin/role/delete', 'Admin\RoleController@postDelete')->name('AdminRolePostDelete');

	Route::get('/admin/role/pwd', 'Admin\RoleController@getPwd')->name('AdminRoleGetPwd');
	Route::post('/admin/role/pwd', 'Admin\RoleController@postPwd')->name('AdminRolePostPwd');

	Route::get('/admin/setting/detail', 'Admin\SettingController@getDetail')->name('AdminSettingGetDetail');
	Route::post('/admin/setting/detail', 'Admin\SettingController@postDetail')->name('AdminSettingPostDetail');
	// 推荐
	Route::get('/admin/recommend/list', 'Admin\RecommendController@getList')->name('AdminRecommendGetList');
	Route::post('/admin/recommend/list', 'Admin\RecommendController@postList')->name('AdminRecommendPostList');
	Route::post('/admin/recommend/add', 'Admin\RecommendController@addbanner')->name('AdminRecommendPostAddbanner');
	Route::get('/admin/recommend/edit', 'Admin\RecommendController@getEdit')->name('AdminRecommendGetEdit');
	Route::post('/admin/recommend/del', 'Admin\RecommendController@postDel')->name('AdminRecommendPostDel');
	Route::post('/admin/recommend/publish', 'Admin\RecommendController@postPublish')->name('AdminRecommendPostPublish');

	// 代理人
	Route::get('/admin/agent/list', 'Admin\AgentController@getList')->name('AdminAgentGetList');
	Route::post('/admin/agent/list', 'Admin\AgentController@postList')->name('AdminAgentPostList');
	// 订单管理
	Route::get('/admin/orders/list', 'Admin\OrdersController@getList')->name('AdminOrdersGetList');
	Route::post('/admin/orders/list', 'Admin\OrdersController@postList')->name('AdminOrdersPostList');
	Route::get('/admin/orders/info', 'Admin\OrdersController@getInfo')->name('AdminOrdersGetInfo');
	Route::get('/admin/orders/excel', 'Admin\OrdersController@getExcel')->name('AdminOrdersGetExcel');

	Route::get('/admin/store/list/{type}', 'Admin\StoreController@getList')->name('AdminStoreGetList');
	Route::post('/admin/store/list/{type}', 'Admin\StoreController@postList')->name('AdminStorePostList');
	Route::post('/admin/store/save', 'Admin\StoreController@postSave')->name('AdminStorePostSave');
	Route::post('/admin/store/delete', 'Admin\StoreController@postDelete')->name('AdminStorePostDelete');
	
	Route::get('/admin/storeaudio/list/{store_id}', 'Admin\StoreAudioController@getList')->name('AdminStoreAudioGetList');
	Route::post('/admin/storeaudio/list', 'Admin\StoreAudioController@postList')->name('AdminStoreAudioPostList');
	Route::post('/admin/storeaudio/save', 'Admin\StoreAudioController@postSave')->name('AdminStoreAudioPostSave');
	Route::post('/admin/storeaudio/fileurl', 'Admin\StoreAudioController@postFileurl')->name('AdminStoreAudioPostFileurl');
	Route::post('/admin/storeaudio/delete', 'Admin\StoreAudioController@postDelete')->name('AdminStoreAudioPostDelete');
	
	Route::get('/admin/storevideo/list/{store_id}', 'Admin\StoreVideoController@getList')->name('AdminStoreVideoGetList');
	Route::post('/admin/storevideo/list', 'Admin\StoreVideoController@postList')->name('AdminStoreVideoPostList');
	Route::post('/admin/storevideo/save', 'Admin\StoreVideoController@postSave')->name('AdminStoreVideoPostSave');
	Route::post('/admin/storevideo/fileurl', 'Admin\StoreVideoController@postFileurl')->name('AdminStoreVideoPostFileurl');
	Route::post('/admin/storevideo/delete', 'Admin\StoreVideoController@postDelete')->name('AdminStoreVideoPostDelete');
	
	Route::get('/admin/course/list/{type}', 'Admin\CourseController@getList')->name('AdminCourseGetList');
	Route::post('/admin/course/list/{type}', 'Admin\CourseController@postList')->name('AdminCoursePostList');
	Route::post('/admin/course/status', 'Admin\CourseController@postStatus')->name('AdminCoursePostStatus');
	Route::get('/admin/course/audiodetail/{id?}', 'Admin\CourseController@getAudioDetail')->name('AdminCourseGetAudioDetail');
	Route::get('/admin/course/videodetail/{id?}', 'Admin\CourseController@getVideoDetail')->name('AdminCourseGetVideoDetail');
	Route::post('/admin/course/uploadpic', 'Admin\CourseController@postUploadpic')->name('AdminCoursePostUploadpic');
	Route::post('/admin/course/detail', 'Admin\CourseController@postDetail')->name('AdminCoursePostDetail');
	Route::post('/admin/course/delete', 'Admin\CourseController@postDelete')->name('AdminCoursePostDelete');
	
	Route::get('/admin/courseaudio/list/{course_id}', 'Admin\CourseAudioController@getList')->name('AdminCourseAudioGetList');
	Route::post('/admin/courseaudio/list', 'Admin\CourseAudioController@postList')->name('AdminCourseAudioPostList');
	Route::post('/admin/courseaudio/status', 'Admin\CourseAudioController@postStatus')->name('AdminCourseAudioPostStatus');
	Route::get('/admin/courseaudio/detail/{course_id}/{id?}', 'Admin\CourseAudioController@getDetail')->name('AdminCourseAudioGetDetail');
	Route::post('/admin/courseaudio/storedetail', 'Admin\CourseAudioController@postStoreDetail')->name('AdminCourseAudioPostStoreDetail');
	Route::post('/admin/courseaudio/uploadpic', 'Admin\CourseAudioController@postUploadpic')->name('AdminCourseAudioPostUploadpic');
	Route::post('/admin/courseaudio/detail', 'Admin\CourseAudioController@postDetail')->name('AdminCourseAudioPostDetail');
	Route::post('/admin/courseaudio/delete', 'Admin\CourseAudioController@postDelete')->name('AdminCourseAudioPostDelete');
	
	Route::get('/admin/coursevideo/list/{course_id}', 'Admin\CourseVideoController@getList')->name('AdminCourseVideoGetList');
	Route::post('/admin/coursevideo/list', 'Admin\CourseVideoController@postList')->name('AdminCourseVideoPostList');
	Route::post('/admin/coursevideo/status', 'Admin\CourseVideoController@postStatus')->name('AdminCourseVideoPostStatus');
	Route::get('/admin/coursevideo/detail/{course_id}/{id?}', 'Admin\CourseVideoController@getDetail')->name('AdminCourseVideoGetDetail');
	Route::post('/admin/coursevideo/storedetail', 'Admin\CourseVideoController@postStoreDetail')->name('AdminCourseVideoPostStoreDetail');
	Route::post('/admin/coursevideo/uploadpic', 'Admin\CourseVideoController@postUploadpic')->name('AdminCourseVideoPostUploadpic');
	Route::post('/admin/coursevideo/detail', 'Admin\CourseVideoController@postDetail')->name('AdminCourseVideoPostDetail');
	Route::post('/admin/coursevideo/delete', 'Admin\CourseVideoController@postDelete')->name('AdminCourseVideoPostDelete');
	
	
	
});


Route::get('/admin/login', 'Admin\LoginController@getIndex')->name('AdminLoginGetIndex');
Route::post('/admin/login', 'Admin\LoginController@postIndex')->name('AdminLoginPostIndex');

Route::get('/qiniu/uptoken', 'Admin\QiniuController@getUptoken')->name('AdminQiniuGetUptoken');

