@if ($remove -> user != null)
<img src="/image/{{ ($remove -> user -> image) == null ? "default.jpg" : $remove -> user ->image }}" 
width="50px" height="50px" border="1px" class="rounded-circle">
@endif

</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">

@if ($remove -> user != null)
<b>{{$remove -> user -> username}}</b><i> ({{__($remove -> user -> status -> name)}})</i>

@else
<i>A felhasználó nem található!</i>
@endif

</td></tr><tr><td style = "border:0;font-size:16px; line-height: 70%; vertical-align:top">