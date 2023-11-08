@include('layouts.app')

<table class="table">

<tbody>
<td style = "border:0">
@include('objects.user', ['user' => $user])
</td>
<td style = "border:0">

    @if ($isAdmin || $isLogged)

        @include('user.dropdown')
        
    @endif

</td>
</tbody>
</table>