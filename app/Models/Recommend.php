<?php namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Recommend extends Model {
	
    public $timestamps = true;
    
    protected $table = 'recommend';
	
	protected $fillable = ['title', 'type', 'img', 'contents', 'orders'];
	
}
