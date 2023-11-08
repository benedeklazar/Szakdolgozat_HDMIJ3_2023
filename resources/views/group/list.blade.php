<?php use App\Models\Group_user;?>
@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href="{{ asset('css/list.css') }}" rel="stylesheet">
</head>
<body>

<div style="margin-bottom:20px;margin-left:55px">
<b><a href="/group/create">
<button class="btn btn-success">Csoport létrehozása</button></a></b>
</div>

<table class="table element">

@foreach ($groups as $group)

@include('list_items.group', ['group' => $group])

@endforeach
</table>

