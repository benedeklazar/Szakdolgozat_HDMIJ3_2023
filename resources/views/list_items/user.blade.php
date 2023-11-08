<tbody style="height:75px" onclick="window.location='/user/{{$user -> id}}';">
<td rowspan="2" style = "width:50px;border:0">
<img src="/image/{{ ($user->image) == null ? "default.jpg" : $user->image }}" 
width="50px" height="50px" border="1px" class="rounded-circle">
</td><td style = "border:0;font-size:17px; line-height: 15%; vertical-align:bottom">
    <b>{{$user -> username}}</b>
    </td></tr><tr><td style = "border:0;font-size:11px; line-height: 70%; vertical-align:top">
    {{__($user -> role -> name)}}
    </td>
</tbody>