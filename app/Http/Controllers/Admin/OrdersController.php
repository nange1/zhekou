<?php 

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Orders\OrdersRepositoryContract;
use DB;
use Excel;

	/**
	* 
	*/
	class OrdersController extends Controller
	{
		private $orders;

		public function __construct(OrdersRepositoryContract $orders)
		{
		$this->orders = $orders;	
		}

		public function getList(Request $request)
		{
			return view('admin.order.list')
			->with('nav','orders')
			->with('subnav','pagelist');
		}
		public function postList(Request $request)
		{
			// dd($request->all());
		// dd($request->except('columns','draw','order'));
			$res = $this->orders->query($request->all());
			return response()->json(array(
			    "draw" => intval($request->input('draw')),
			    "recordsTotal" => intval($res['count']),
			    "recordsFiltered" => intval($res['count']),
			    "data" => $res['list']
			));
		}

		public function getInfo(Request $request)
		{
			// dd($request->all());
			$res = $this->orders->find($request->input('id'));
			return $res;
		}

		public function getExcel(Request $request)
		{
		set_time_limit(0);
		$filename = "订单(".date('Y-m-d H:i').")";
		$user = $this->orders->query($request->all(),"all");
		Excel::create($filename, function($excel) use($user) {
            $excel->sheet('Sheetname', function($sheet) use($user) {
                $sheet->loadView('admin.order.orderToExcel',array('user'=>$user));
            });
        })->export('xls');
	}
	}
?>