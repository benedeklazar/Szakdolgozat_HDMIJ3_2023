@include('layouts.app')

<div class="alert alert-info" role="alert">
  <strong>Már fellebbeztél!</strong> Várd meg, amíg egy admin választ ad a fellebbezésre!
</div>

@include('objects.'.$obj_name, [$obj_name => $object])