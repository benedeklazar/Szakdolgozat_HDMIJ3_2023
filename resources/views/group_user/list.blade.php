<?php use App\Models\Group_user;?>
@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

@include('objects.group', ['group' => $group])

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href="{{ asset('css/list.css') }}" rel="stylesheet">
</head>
<body>

@if ($isOwner || $isAdmin)
<div style="margin-bottom:20px;margin-left:55px">
<b><a href="/member/create/{{$group->id}}">
<button class="btn btn-success">Új tag meghívása</button></a></b>
</div>
@endif

<table class="table element">

@foreach ($members as $member)

@include('list_items.group_user', ['member' => $member])

@endforeach
</table>

