<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class RecommendModel extends Model
{
    // protected $connection = 'yinian';
    protected  $table= 'recommend';
    public $timestamps=true;
    protected $fillable = ['title', 'contents','type','orders','img'];
}
