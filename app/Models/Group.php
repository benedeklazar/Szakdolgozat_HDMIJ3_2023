<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];  

    public $timestamps = false;

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function visibility()
    {
        return $this->belongsTo('App\Models\Visibility', 'visibility_id');
    }

    public function new_member_status()
    {
        return $this->belongsTo('App\Models\Status', 'new_member_status_id');
    }
}
