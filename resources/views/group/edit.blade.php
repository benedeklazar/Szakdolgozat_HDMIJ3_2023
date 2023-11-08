@include('layouts.app')

@include('objects.group', ['group' => $group])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Módosítás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('group.update', ['id' => $group -> id])}}">
                        @csrf


                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Csoportnév') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $name }}">

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
                    <select name="new_member_status_id" class="form-select" value="{{ $new_member_status_id }}">
                        
                            <option value="" <?php if($new_member_status_id == null) {echo ' selected="selected"';} ?>>Nem lehet csatlakozni</option>
                            <option value="1" <?php if($new_member_status_id == 1) {echo ' selected="selected"';} ?>>Automatikus csatlakozás</option>
                            <option value="4" <?php if($new_member_status_id == 4) {echo ' selected="selected"';} ?>>Jóváhagyás szükséges</option>
                    </select>
                </div>
            </div>

                  <div class="row mb-3">
                <label for="visibility_id" class="col-md-4 col-form-label text-md-end">Láthatóság</label>
                <div class="col-md-6">
                    <select name="visibility_id" class="form-select" value="{{ $visibility_id }}">
                        @foreach ($visibilities as $visibility)
                            <option value="{{$visibility -> id}}" <?php if (old('visibility_id') == $visibility -> id) {    echo ' selected="selected"';} ?>>{{__($visibility -> name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>      

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Módosítás') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

