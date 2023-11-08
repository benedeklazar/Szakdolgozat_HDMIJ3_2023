@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

@include('objects.group_user', ['group_user' => $group_user])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Módosítás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('group_user.update', ['id' => $group_user -> id])}}">
                        @csrf
                    

            <div class="row mb-3">
                <label for="role" class="col-md-4 col-form-label text-md-end">Szerepkör</label>
                <div class="col-md-6">
                    <select name="role_id" class="form-select" value="{{ $role_id }}">
                        @foreach ($roles as $role)
                            @if ($isAdmin || $isOwner 
                            || $logged_role -> priority > $role -> priority)
                            <option value="{{ $role->id }}" <?php if ($role_id == $role->id) {    echo ' selected="selected"';} ?>>{{ __($role->name) }}</option>
                            @endif
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

