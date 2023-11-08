@inject('logged', 'App\Http\Controllers\Controller')
<?php use App\Models\Group_user;
$group_user = Group_user::where('group_id', $group -> id)->
where('user_id', $logged->auth('id'))->first();?>

<div class="alert alert-primary">
<strong> Meghívást kaptál a csoportba!</strong>
</div>

<center>
<b><a href="/group/accept/{{$group_user->id}}">
<button class="btn btn-success">Elfogadás</button></a></b>
&nbsp;
<b><a href="/group/left/{{$group_user->id}}">
<button class="btn btn-danger">Elutasítás</button>
</a></b>
</center>
