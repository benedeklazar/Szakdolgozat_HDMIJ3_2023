<?php use App\Models\Group_user; use App\Models\User;?>

@inject('logged', 'App\Http\Controllers\Controller')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href="{{ asset('css/list.css') }}" rel="stylesheet">
</head>
<body>

</div>

<center>

<div class="card element" style="width:400px;padding: 10px 15px;margin-bottom: 15px;">
<table class="table" style="margin-bottom: -15px;" onclick="window.location='/comment/create/{{$post -> id}}';">
<thead>

<?php $logged_user = User::where('id', $logged->auth('id')) -> first(); ?>

<tr colspan="2">
<td style = "font-size:17px;width:50px;border:0;background: none">
    <img src="/image/{{ ($logged_user->image) == null ? "default.jpg" : $logged_user->image }}" 
    width="50px" height="50px" border="1px" class="rounded-circle">
&nbsp;
    <b>{{$logged_user -> username}}</b>
&nbsp; •&nbsp;
Most
</td></tr></thead><tbody><tr>
<td style="border:0;position: relative; bottom: 15px;background: none">

Új komment létrehozása

</td></tr></tbody></table></div>
</center>


@foreach ($comments as $comment)

  @include('list_items.comment', ['comment' => $comment])

@endforeach



