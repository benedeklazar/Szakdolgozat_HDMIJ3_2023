<?php use App\Models\Group_user;?>

<tbody style="height:75px" onclick="window.location='/group/{{$group -> id}}';">
<td rowspan="2" style = "width:50px;border:0">
</td><td style = "border:0;font-size:16px; line-height: 15%; vertical-align:bottom;">
    <b>{{$group -> name}}</b>
  &nbsp;
    @if ($group -> visibility -> name != "public")
    <span class="badge bg-secondary bg-opacity-10 text-secondary-emphasis" style="font-size:10px">
    {{__($group -> visibility -> name)}}</span>
    @endif

    @if ($group -> status -> name != "active")
    <span class="badge bg-danger bg-opacity-10 text-danger-emphasis" style="font-size:10px">
    {{__($group -> status -> name)}}</span>
    @endif

    </td></tr><tr><td style = "border:0;font-size:13px; line-height: 70%; vertical-align:top">
    {{Group_user::where('group_id', $group -> id) -> count()}} tag &nbsp;
    
    <?php
    $member = Group_user::where('group_id', $group -> id)
    ->where('user_id', $logged->auth('id')) -> first();

    $status = null;
    
    if ($member != null) {
    $status = $member -> status -> name;
    $role = $member -> role -> name;
    }
    ?>

  @if ($group -> user_id == $logged->auth('id'))
      @include('group.badge.owner')
  @elseif ($status != null)
    @if ($status != 'active')
      @include('group.badge.'.$status)
    @else
      @include('group.badge.'.$role)
    @endif
  @endif

    </td>
</tbody>