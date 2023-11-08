<?php use App\Models\Group_user;?>
@include('layouts.app')

<table class="table">

<tbody>
<td style="border:0">
@include('objects.group', ['group' => $group])
</td>
<td style="border:0">
    @if ($isAdmin || $isOwner)
        @include('group.dropdown')
    @endif
</td>
</tbody>
</table>
@include('group.status.'.$status)