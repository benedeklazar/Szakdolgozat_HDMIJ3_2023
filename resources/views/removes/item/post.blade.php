@if($remove -> post != null && ($remove -> post ->image) != null)
<img src="/image/{{$remove -> post -> image}}" 
width="80px" height="80px" border="1px">
@endif
</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">

@if ($remove -> post != null)
{{$remove -> post -> text}}<i> ({{__($remove -> post -> status -> name)}})</i>

@else
<i>A poszt nem található!</i>
@endif
</td></tr><tr><td style = "border:0;font-size:16px; line-height: 70%; vertical-align:top">