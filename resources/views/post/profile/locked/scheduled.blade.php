@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

<div class="alert alert-info">

<strong>Ez a poszt még nem elérhető!</strong>
<br>Ekkorra ütemezve: {{$post -> created_at}}

</div>