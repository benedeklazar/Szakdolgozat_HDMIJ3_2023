<?php
class Post extends Model
{
	use HasFactory;
	protected $guarded = [];  
	public $timestamps = false;
	
	public function status(){
	return $this
    ->belongsTo('App\Models\Status', 'status_id');
	}
	
	public function group(){
	return $this
    ->belongsTo('App\Models\Group', 'group_id');
	}
	
...
