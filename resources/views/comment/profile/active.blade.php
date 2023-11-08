<?php use App\Models\Group_user;?>
@include('layouts.app')

@include('objects.post', ['post' => $comment -> post])

<table class="table">

<tbody>
<td style="border:0">
@include('objects.comment', ['comment' => $comment])
</td>
<td style="border:0">
    @if ($isAdmin || $isLogged)
        @include('comment.dropdown')
    @endif
</td>
</tbody>
</table>
