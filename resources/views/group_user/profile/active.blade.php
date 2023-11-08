@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

<table class="table">

<tbody>
<td style="border:0">
@include('objects.group_user', ['group_user' => $group_user])
</td>
<td style="border:0">
    @if ($isAdmin || $isLogged
    || $logged->hasRight('remove group_user', $group_user->id)
    || $logged->hasRight('edit group_user', $group_user->id))

        @include('group_user.dropdown')
        
    @endif
</td>
</tbody>
</table>