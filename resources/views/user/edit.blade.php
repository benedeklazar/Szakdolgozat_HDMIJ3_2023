@include('layouts.app')

@include('objects.user', ['user' => $user])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Módosítás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('user.update', ['id' => $user -> id])}}">
                        @csrf


                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Felhasználónév') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $username }}">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    <div class="row mb-3">
                        <label for="image" class="col-md-4 col-form-label text-md-end">Profilkép</label> 
                    <div class="col-md-6">        
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" class="form-control" placeholder="image">
                        @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        </div>
                    </div>
@if($isAdmin)
            <div class="row mb-3">
                <label for="role" class="col-md-4 col-form-label text-md-end">Szerepkör</label>
                <div class="col-md-6">
                    <select name="role_id" class="form-select" value="{{ $role_id }}">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" <?php if ($role_id == $role->id) {    echo ' selected="selected"';} ?>>{{ __($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
@endif
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Jelszó') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Jelszó újra') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
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

