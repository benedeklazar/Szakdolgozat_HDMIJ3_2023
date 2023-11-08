<?php use App\Models\Group;?>
@include('layouts.app')

<div class="alert alert-danger" role="alert">
  <strong>A fellebbezésed el lett utasítva!</strong>
</div>

@include('objects.group_user', ['group_user' => $object])