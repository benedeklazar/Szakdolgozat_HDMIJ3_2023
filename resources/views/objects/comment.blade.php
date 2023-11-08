<?php use Carbon\Carbon; Carbon::setLocale('hu'); 
if ($comment != null)
$date = Carbon::parse($comment -> created_at) -> diffForHumans();
else $date = null;
?>

<center>

<div class="card element" style="width:400px;padding: 10px 15px;margin-bottom: 15px;">

<table class="table" style="margin-bottom: -15px;"
onclick="window.location='/comment/{{$comment -> id}}';">
<thead>
  <tr>
    <td rowspan ="2" style = "vertical-align:top;width:50px;border:0;background: none">
    @if($comment != null && $comment -> user != null)
    <img src="/image/{{ ($comment -> user -> image) == null ? "default.jpg" : $comment -> user ->image }}" 
    width="30px" height="30px" border="1px" class="rounded-circle">
    @else
    <img src="/image/deleted.jpg" 
    width="30px" height="30px" border="1px" class="rounded-circle">
    @endif
</td>
    <td style="border:0;font-size:13px;background: none">
    @if($comment != null && $comment -> user != null)
    <b>{{$comment -> user -> username}}</b>
@else
    <b>-</b>
@endif
&nbsp; •&nbsp;
{{$date}}

</td>
  </tr>
  <tr>
    <td style="border:0;font-size:14px;position: relative; bottom: 15px;background: none">
@if($comment != null)
{{$comment -> text}}
@else
-
@endif
<br></td>
  </tr>
</thead>
</table>

</div>

</center>