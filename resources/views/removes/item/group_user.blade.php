</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">
@if ($remove -> group_user != null)

    @if ($remove -> group_user -> user != null)
    <i><b>{{$remove -> group_user -> user -> username}}</b></i>
    @else
    <i><b>-</b></i>
    @endif ki lett tiltva a 
    @if ($remove -> group_user -> group != null)
    <i><b>{{$remove -> group_user -> group -> name}}</b></i>
    @else
    <i><b>-</b></i>
    @endif csoportból!

@else
<i>A tag nem található.</i>
@endif
</td></tr><tr><td style = "border:0;font-size:16px; line-height: 70%; vertical-align:top">