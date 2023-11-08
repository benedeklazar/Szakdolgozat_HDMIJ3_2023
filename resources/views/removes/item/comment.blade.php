
</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">

@if ($remove -> comment != null)
{{$remove -> comment -> text}}<i> ({{__($remove -> comment -> status -> name)}})</i>
@else
<i>A komment nem található.</i>
@endif
</td></tr><tr><td style = "border:0;font-size:16px; line-height: 70%; vertical-align:top">