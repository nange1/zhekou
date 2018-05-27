<?php 

/**
*date:2018/1/22
*/
	namespace App\Repositories\Admin\Orders;

	interface OrdersRepositoryContract
	{ 
		public function query($request);
		public function find($id);
	}
?>