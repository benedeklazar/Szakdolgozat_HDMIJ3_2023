@include('layouts.app')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Létrehozás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('group.create')}}">
                        @csrf


                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Csoportnév') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    

            <div class="row mb-3">
                <label for="new_member_status_id" class="col-md-4 col-form-label text-md-end">Jelentkezés után</label>
                <div class="col-md-6">
                    <select name="new_member_status_id" class="form-select" value="">
                        
                            <option value="" >Nem lehet csatlakozni</option>
                            <option value="1" >Automatikus csatlakozás</option>
                            <option value="4" >Jóváhagyás szükséges</option>
                    </select>
                </div>
            </div>

                  <div class="row mb-3">
                <label for="visibility_id" class="col-md-4 col-form-label text-md-end">Láthatóság</label>
                <div class="col-md-6">
                    <select name="visibility_id" class="form-select" value="">
                        
                            <option value="1" >Nyilvános</option>
                            <option value="2" >Nem listázott</option>
                            <option value="3" >Privát</option>
                    </select>
                </div>
            </div>      

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Létrehozás') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

