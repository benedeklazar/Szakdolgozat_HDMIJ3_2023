<?php use App\Models\Group_user; ?>
</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">
@if ($remove -> group != null)

<b>{{$remove -> group -> name}}</b>
&nbsp;
{{Group_user::where('group_id', $remove -> group -> id) -> count()}} tag &nbsp;

<i> ({{__($remove -> group -> status -> name)}})</i>

@else
<i>A csoport nem található!</i>
@endif
</td></tr><tr><td style = "border:0;font-size:16px; line-height: 70%; vertical-align:top">
