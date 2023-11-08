<?php use App\Models\Group_user;?>
@include('layouts.app')

@include('objects.group', ['group' => $group])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Létrehozás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('group_user.create', ['id' => $group -> id])}}">
                        @csrf

            <div class="row mb-3">
                <label for="user_id" class="col-md-4 col-form-label text-md-end">Felhasználó meghívása</label>
                <div class="col-md-6">
 
                    <select name="user_id" value=""
                    class="form-select form-control @error('user_id') is-invalid @enderror" name="user_id" value="">
                     
                @foreach($users as $user)
            
                      
                     @if (Group_user::where('user_id', $user->id)->where('group_id', $group->id)->first() == null)
                        <option value="{{$user -> id}}">
                        <div>
                    <img src="/image/{{ ($user->image) == null ? "default.jpg" : $user->image }}" 
                    width="25px" height="25px" border="1px" class="rounded-circle">
                    </div>
                             {{$user->username}}
                             
                        </option>
                    @endif
                @endforeach
                    </select>
                    @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Meghívás') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

