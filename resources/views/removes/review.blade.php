@include('layouts.app')

<div class="alert alert-danger" role="alert">
  <strong>Indok: </strong>{{$reason}}
</div>

<div class="alert alert-info" role="alert">
  <strong>Fellebbezés: </strong>{{$appeal}}
</div>

@include('objects.'.$obj_name, [$obj_name => $object])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Válasz a fellebbezésre') }}</div>
                <div class="card-body">
                    <form class="d-inline" method="POST" action="{{ route('appeal_review', ['id' => $id]) }}">              
                        @csrf
                         
                        <button type="submit" name="submitbutton" value="accept" class="btn btn-success">{{ __('Elfogad') }}</button>
                        <button type="submit" name="submitbutton" value="reject" class="btn btn-danger">{{ __('Elutasít') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

