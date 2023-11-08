<?php use App\Models\Group;?>
@include('layouts.app')

<div class="alert alert-danger" role="alert">
  Ki lettél tiltva a csoportból. <br><strong>Indok: </strong>{{$reason}}
</div>

@include('objects.group_user', ['group_user' => $object])

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
                                name="appeal">Nem szegtem meg a csoport szabályzatát...</textarea>                 
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

