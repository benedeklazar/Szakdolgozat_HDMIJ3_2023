<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remove extends Model
{
    use HasFactory;

    protected $guarded = [];  
    public $timestamps = false;

    public function objecttype()
    {
        return $this->belongsTo('App\Models\Objecttype', 'object_type');
    }

    public function appeal_stat()
    {
        return $this->belongsTo('App\Models\Appeal_status', 'appeal_status');
    }

    public function obj()
    {
        $id = $this -> object_type;
        $obj_name = Objecttype::where('id', $id) -> first() -> name;
        return $this -> $obj_name();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'object_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'object_id');
    }

    public function group_user()
    {
        return $this->belongsTo('App\Models\Group_user', 'object_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'object_id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'object_id');
    }
}
