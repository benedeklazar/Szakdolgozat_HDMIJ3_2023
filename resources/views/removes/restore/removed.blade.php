@include('layouts.app')

<div class="alert alert-warning" role="alert">
  <strong>A tartalom már nem elérhető!</strong>
</div>

@include('objects.'.$obj_name, [$obj_name => $object])