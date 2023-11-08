@inject('logged', 'App\Http\Controllers\Controller')
<?php use App\Models\Group_user;
$group_user = Group_user::where('group_id', $group -> id)->
where('user_id', $logged->auth('id'))->first();?>

<div class="alert alert-info">
<strong> Sikeresen elküldted a jelentkezésed!</strong>
<br> Várd meg, amíg egy admin felvesz a csoportba!
</div>
<b><a href="/group/left/{{$group_user->id}}">
<button class="btn btn-danger">Kilépés</button>
</a></b>