@include('layouts.app')

<div class="alert alert-warning" role="alert">
  Tartalmad törölve lett. <br><strong>Indok: </strong>{{$reason}}
</div>

@include('objects.'.$obj_name, [$obj_name => $object])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Visszaállítás') }}</div>
                <div class="card-body">
                    <form class="d-inline" method="POST" action="{{ route('delete_restore', ['id' => $id]) }}">              
                        @csrf

                         <br>
                        <button type="submit" class="btn btn-primary">{{ __('Tartalom visszaállítása') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

