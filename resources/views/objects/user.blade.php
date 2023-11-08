<table class="table">
<tbody>
<td rowspan="2" style = "width:100px;border:0">

@if ($user != null)
<img src="/image/{{ ($user->image) == null ? "default.jpg" : $user->image }}" 
width="100px" height="100px" border="1px" class="rounded-circle">
@else
<img src="/image/deleted.jpg" 
width="100px" height="100px" border="1px" class="rounded-circle">
@endif

</td><td style = "border:0;font-size:26px; line-height: 15%; vertical-align:bottom">
@if ($user != null)
    <b>{{$user -> username}}</b>
@else
<b>-</b>
@endif
    </td>

    </tr><tr><td style = "border:0;font-size:15px; line-height: 70%; vertical-align:top">
@if ($user != null)
    {{__($user -> role -> name)}}
    @else
    -
    @endif
    </td>
</tr>
</tbody>
</table>