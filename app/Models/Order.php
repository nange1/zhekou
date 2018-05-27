<?php namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	
    public $timestamps = true;
    
    protected $table = 'orders';
}