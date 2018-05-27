<?php namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Store extends Model {
	
    public $timestamps = true;
    
    protected $table = 'store';
	
	
	public function detail () {
        return $this->hasMany('App\Models\StoreDetail','store_id','id');
    }
}
