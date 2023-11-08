@include('layouts.app')

@include('objects.post', ['post' => $post])

<body onload = "switch_calendar()"></body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Létrehozás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('comment.create', ['id' => $post_id])}}">
                        @csrf

                        <div class="row mb-3">
                            <label for="text" class="col-md-4 col-form-label text-md-end">{{ __('Hozzászólás') }}</label>

                            <div class="col-md-6">
                                <textarea rows="1" id="text" type="text" class="form-control @error('text') is-invalid @enderror" name="text" value="">{{old('text')}}</textarea>

                                @error('text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Küldés') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

