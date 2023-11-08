<?php use Carbon\Carbon; Carbon::setLocale('hu'); 

if ($post != null){
$date = Carbon::parse($post -> created_at) -> diffForHumans();
$scheduled = Carbon::now() -> diffInSeconds($post -> created_at, false) > 0;
}
else {$date = null; $scheduled = false;}
?>

<center>

<div class="card element" style="width:400px;padding: 10px 15px;margin-bottom: 15px;">
<table class="table" style="margin-bottom: -15px;"
onclick="window.location='/post/{{$post -> id}}';">
<thead>

<tr colspan="2">
<td style = "font-size:17px;width:50px;border:0;background: none">
    @if($post != null && $post -> user != null)
    <img src="/image/{{ ($post -> user -> image) == null ? "default.jpg" : $post -> user ->image }}" 
    width="50px" height="50px" border="1px" class="rounded-circle">
    @else
    <img src="/image/deleted.jpg" 
    width="50px" height="50px" border="1px" class="rounded-circle">
    @endif
&nbsp;
    @if($post != null && $post -> user != null)
    <b>{{$post -> user -> username}}</b>
@else
    <b>-</b>
@endif
&nbsp; •&nbsp;
@if ($scheduled)
<label class="text-info-emphasis">
@endif
{{$date}}</label>
&nbsp;

@if ($post -> visibility -> name != "public")
<span class="badge bg-secondary bg-opacity-10 text-secondary-emphasis" style="font-size:10px">
{{__($post -> visibility -> name)}}</span>
@endif

@if ($post -> status -> name != "active")
<span class="badge bg-danger bg-opacity-10 text-danger-emphasis" style="font-size:10px">
{{__($post -> status -> name)}}</span>
@endif

</td>
</tr>

</thead>
<tbody>
<tr>
<td style="border:0;position: relative; bottom: 15px;background: none">

@if($post != null)
{{$post -> text}}<br>
@else
-
@endif

@if($post != null && ($post->image) != null)
<img src="/image/{{$post->image}}" 
width="350px" height="250px" border="1px">
@endif
</td>
</tr>

</tbody>
</table>
</div>

</center>
