<?php use App\Models\Group_user; use App\Models\Remove;?>
@inject('logged', 'App\Http\Controllers\Controller')
<div class="alert alert-danger">
<strong> Ki lettél tiltva ebből a csoportból!</strong>
</div>
<?php
$group_user = Group_user::where('user_id', $logged->auth('id'))
->where('group_id', $group -> id) ->first();
$remove = Remove::where('object_type', 4)->where('object_id', $group_user -> id)->first();
?>

@if ($remove != null)
<center>
<b><a href="/restore/{{$remove->id}}">
<button class="btn btn-primary">Fellebbezés</button>
</a></b>
</center>
@endif