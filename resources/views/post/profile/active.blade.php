<?php use App\Http\Controllers\CommentController; ?>
@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

<table class="table">

<tbody>
<td style="border:0">
@include('objects.post', ['post' => $post])
</td>
<td style="border:0">
    @if ($isAdmin || $isLogged
    || $logged->hasRight('remove post', $post->id)
    || $logged->hasRight('edit post', $post->id))

        @include('post.dropdown')
        
    @endif
</td>
</tbody>
</table>

<?php
$CC = new CommentController;
$comments = $CC -> data($post -> id);
?>

<br><br>

@include('comment.list', ['comments' => $comments, 'post' => $post])