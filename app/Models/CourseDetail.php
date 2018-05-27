<?php namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model {
	
    public $timestamps = true;
    
    protected $table = 'course_detail';
	
	public function store () {
        return $this->hasOne('App\Models\Store','id','store_id');
    }
	
	public function storedetail () {
        return $this->hasOne('App\Models\StoreDetail','id','store_detail_id');
    }
}
