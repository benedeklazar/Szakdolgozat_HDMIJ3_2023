@include('layouts.app')

@include('objects.post', ['post' => $post])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Módosítás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('post.update', ['id' => $post -> id])}}">
                        @csrf


                        <div class="row mb-3">
                            <label for="text" class="col-md-4 col-form-label text-md-end">{{ __('Szöveg') }}</label>

                            <div class="col-md-6">
                                <input id="text" type="text" class="form-control @error('text') is-invalid @enderror" name="text" value="{{ $text }}">

                                @error('text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                  <div class="row mb-3">
                <label for="visibility_id" class="col-md-4 col-form-label text-md-end">Láthatóság</label>
                <div class="col-md-6">
                    <select name="visibility_id" class="form-select" value="{{ $visibility_id }}">
                        @foreach ($visibilities as $visibility)
                            <option value="{{$visibility -> id}}" <?php if ($visibility_id == $visibility -> id) {echo 'selected="selected"';} ?>>{{__($visibility -> name)}}</option>
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

