@include('layouts.app')

<body onload = "switch_calendar()"></body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-32">
            <div class="card">

            <div class="card-header">{{ __('Létrehozás') }}</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('post.create', ['id' => $group_id])}}">
                        @csrf

                        <div class="row mb-3">
                            <label for="text" class="col-md-4 col-form-label text-md-end">{{ __('Szöveg') }}</label>

                            <div class="col-md-6">
                                <textarea rows="1" id="text" type="text" class="form-control @error('text') is-invalid @enderror" name="text" value="">{{old('text')}}</textarea>

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
                    <select name="visibility_id" class="form-select" value="{{old('visibility_id')}}">
                        @foreach ($visibilities as $visibility)
                            <option value="{{$visibility -> id}}" <?php if (old('visibility_id') == $visibility -> id) {    echo ' selected="selected"';} ?>>{{__($visibility -> name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

                    <div class="row mb-3">
                        <label for="image" class="col-md-4 col-form-label text-md-end">Kép</label> 
                    <div class="col-md-6">        
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" class="form-control" placeholder="image">
                        @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        </div>
                    </div>
           
<label for="checkbox" class="col-md-4 col-form-label text-md-end">Ütemezés</label>
<input type="checkbox" id="schedule_box" name="schedule_box" onclick="switch_calendar()" 
value="on" {{ old('schedule_box') == 'on' ? 'checked' : '' }}>

<script>
function switch_calendar() {
var checkBox = document.getElementById("schedule_box");
var calendar = document.getElementById("calendar");
var created_at = document.getElementById("created_at");

  if (checkBox.checked == true){
    calendar.style.display = "";
  } else {
    calendar.style.display = "none";
    created_at.value = null;
  }
}

</script>

<div class="row mb-3" id="calendar" style="display:none">
<label for="created_at" class="col-md-4 col-form-label text-md-end">Ütemezés</label>
<div class="col-md-6">
    <input type="date" class="form-control @error('created_at') is-invalid @enderror" id="created_at" name="created_at"
    class="form-control" placeholder="created_at" value="{{old('created_at')}}">
                        @error('created_at')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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

