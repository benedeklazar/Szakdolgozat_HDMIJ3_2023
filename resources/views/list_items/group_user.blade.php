<tbody style="height:75px" onclick="window.location='/group/member/{{$member -> id}}';">

<td rowspan="2" style = "width:50px;border:0">

@if($member -> user != null)
<img src="/image/{{ ($member->user->image) == null ? "default.jpg" : $member->user->image }}" 
width="50px" height="50px" border="1px" class="rounded-circle">
@else
<img src="/image/deleted.jpg" 
width="50px" height="50px" border="1px" class="rounded-circle">
@endif

</td><td style = "border:0;font-size:16px; line-height: 15%; vertical-align:bottom;">

@if($member -> user != null)
    <b>{{$member -> user -> username}}</b>
@else
    <b>-</b>
@endif

    </td>

    </tr><tr><td style = "border:0;font-size:13px; line-height: 70%; vertical-align:top">
    @if ($member -> group -> user_id == $member -> user_id)
      @include('group_user.role.owner')
    @elseif ($member -> status -> name != "active")
      @include('group_user.status.'.$member -> status -> name)
    @else
      @include('group_user.role.'.$member -> role -> name)
    @endif
    </td>
    
</tbody>