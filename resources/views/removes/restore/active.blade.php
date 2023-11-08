@include('layouts.app')

<div class="alert alert-success" role="alert">
  Tartalmad sikeresen helyre lett állítva!<br>
  <strong>Indok: </strong>{{$reason}}
</div>

@include('objects.'.$obj_name, [$obj_name => $object])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Infó') }}</div>
                <div class="card-body">
                    <form class="d-inline" method="POST" action="{{ route('active_restore', ['id' => $id]) }}">              
                        @csrf
                            <label>A tartalmad jelenleg aktív!</label>
                         <br><br>
                        <button type="submit" class="btn btn-primary">{{ __('Értem') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

