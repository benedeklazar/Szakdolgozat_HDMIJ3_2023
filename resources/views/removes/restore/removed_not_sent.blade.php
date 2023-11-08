@include('layouts.app')

<div class="alert alert-danger" role="alert">
  Tartalmad tiltva lett. <br><strong>Indok: </strong>{{$reason}}
</div>

@include('objects.'.$obj_name, [$obj_name => $object])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Fellebbezés') }}</div>
                <div class="card-body">
                    <form class="d-inline" method="POST" action="{{ route('remove_restore', ['id' => $id]) }}">
                        
                        @csrf
                        <div class="row mb-3">
                            <label for="appeal" class="col-md-4 col-form-label text-md-end">{{ __('Írd le, hogy miért gondolod a tiltást tévesnek') }}</label>

                            <div class="col-md-6">
                                
                                <textarea rows="5" id="appeal" type="text" class="form-control @error('appeal') is-invalid @enderror" 
                                name="appeal">A tartalmam nem sértett semmilyen irányelvet...</textarea>                 
                                @error('appeal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                         <br><br>
                        <button type="submit" class="btn btn-primary">{{ __('Fellebbezés beküldése') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

