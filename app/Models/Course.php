<?php namespace App\Models;
	
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
	
    public $timestamps = true;
    
    protected $table = 'course';
	
	
	public function detail () {
        return $this->hasMany('App\Models\CourseDetail','course_id','id');
    }
	
	public function cate () {
        return $this->hasOne('App\Models\CourseCate','id','cate_id');
    }
}
