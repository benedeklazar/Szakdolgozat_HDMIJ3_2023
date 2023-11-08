<?php use App\Models\Group_user;?>
<table class="table">
<tbody>
<td rowspan="2" style = "width:100px;border:0">

</td><td style = "border:0;font-size:26px; line-height: 15%; vertical-align:bottom">
    @if ($group != null)
    <b>{{$group -> name}}</b>
    @else
    <b>-</b>
    @endif

    </td>

    </tr><tr><td style = "border:0;font-size:15px; line-height: 70%; vertical-align:top">
    @if ($group != null)
    <a style="color:inherit;text-decoration:inherit;" href="/group/members/{{$group->id}}">
    {{Group_user::where('group_id', $group -> id) -> count()}} tag
    </a> &nbsp;
    @else
    0 tag &nbsp;
    @endif
    </td>

</tr>
</tbody>
</table>