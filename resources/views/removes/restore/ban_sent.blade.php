<?php use App\Models\Group;?>
@include('layouts.app')

<div class="alert alert-info" role="alert">
  <strong>Már fellebbeztél!</strong> Várd meg, amíg a csoport tulajdonosa választ ad a fellebbezésre!
</div>

@include('objects.group_user', ['group_user' => $object])