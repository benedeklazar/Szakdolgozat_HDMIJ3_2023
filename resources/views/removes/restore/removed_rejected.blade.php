@include('layouts.app')

<div class="alert alert-danger" role="alert">
  <strong>A fellebbezésed el lett utasítva!</strong>
</div>

@include('objects.'.$obj_name, [$obj_name => $object])