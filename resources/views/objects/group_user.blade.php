<table class="table">

<tbody>
<td rowspan="2" style = "width:100px;border:0">

@if($group_user != null && $group_user -> user != null)
<img src="/image/{{ ($group_user->user->image) == null ? "default.jpg" : $group_user->user->image }}" 
width="100px" height="100px" border="1px" class="rounded-circle">
@else
<img src="/image/deleted.jpg" 
width="100px" height="100px" border="1px" class="rounded-circle">
@endif

</td><td style = "border:0;font-size:26px; line-height: 15%; vertical-align:bottom">
@if($group_user != null && $group_user -> user != null)
    <b>{{$group_user -> user -> username}}</b>
@else
    <b>-</b>
@endif
&nbsp;•&nbsp;
@if($group_user != null && $group_user -> group != null)
{{$group_user -> group -> name}}
@else
-
@endif
    </td>

    </tr><tr><td style = "border:0;font-size:15px; line-height: 70%; vertical-align:top">
  @if ($group_user != null)
    @if (($group_user -> group -> user_id == $group_user -> user_id))
      @include('group_user.role.owner')
    @elseif ($group_user -> status -> name != "active")
      @include('group_user.status.'.$group_user -> status -> name)
    @else
      @include('group_user.role.'.$group_user -> role -> name)
    @endif
  @endif
  
    </td>

</tr>
</tbody>
</table>