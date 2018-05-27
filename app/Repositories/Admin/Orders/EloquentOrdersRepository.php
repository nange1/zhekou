<?php 

	/**
	*date:2018/1/22
	*update:2018/1/26
	*/
namespace App\Repositories\Admin\Orders;
use App\Models\Order;
use Carbon\Carbon;
use DB;
class EloquentOrdersRepository implements  OrdersRepositoryContract
{
		
	public function __construct()
	{		
	}

	// 分页排序
	public function query($input,$type=""){
		// 分页
		$draw = isset($input['draw'])?$input['draw']:'';

		$start = isset($input['start'])?$input['start']:0;

		$length = isset($input['length'])?$input['length']:10;

		$stime = strtotime($input['start_time']);

		$etime = strtotime($input['end_time']);

		$table = Order::whereBetween('created_at',[$input['start_time'],$input['end_time']]);
		// 排序 
		if (isset($input['order'])) {

			$dir = $input['order']['0']['dir'];
				
			$column = $input['order']['0']['column'];
			
			$table = Order::orderBy($input['columns'][$column]['data'],$dir);
		}
		// 搜索
		$where['status'] = $input['status'];

		$where['order_id'] = $input['order_id'];
		
		$where = array_filter($where);
		// dd($where);
		if ($where)
		{
			$table = $table->where($where);
		}

		// if ($input['course_title']) {
		// 	$table = $table->where('course_title','like','%'.$input['course_title'].'%');
		// }
		if ($input['user_title']) {
			$id = DB::table('user')->where('nickname','like','%'.$input['user_title'].'%')->pluck('id');
			// dd($id);
			$table = $table->whereIn('id',$id);
			dd($table);
		}
// dd($where);
		$count = $table->count();
		
		if ($type=="all") {
			$list = $table->get();
		}else{
			$list = $table->take($length)->skip($start)->get();			
		}

		$user = DB::table('user')->select('id','nickname')->get();
		foreach ($user as $key => $value) {
			$array[$value->id]=$value->nickname;
		}
		foreach ($list as $k => $v) {
			$v['user_id'] = $array[$v['user_id']];
		}
// dd($list);	
		return ['list'=>$list,'count'=>$count,'array'=>$array];
    }
// 订单详情（join.user.agent）
    /**
    *用户编号 代理人编号转换名称
    */
    public function find($id)
    {
    	$data = Order::find($id);
    	return $data;
    }

}
?>