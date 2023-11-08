@inject('logged', 'App\Http\Controllers\Controller')
<?php use App\Models\Group_user; use App\Http\Controllers\PostController;
$group_user = Group_user::where('group_id', $group -> id)->
where('user_id', $logged->auth('id'))->first();?>

<b><a href="/group/left/{{$group_user->id}}">
<button class="btn btn-danger">Kilépés</button>
</a></b>

<?php
$PC = new PostController;
$posts = $PC -> data($group -> id);
?>

<br><br>

@include('post.list', ['posts' => $posts, 'group' => $group])

