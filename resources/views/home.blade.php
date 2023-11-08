@inject('logged', 'App\Http\Controllers\Controller')
@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

<link href="{{ asset('css/list.css') }}" rel="stylesheet">

</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
<div class="card">
                <div class="card-header">{{ __('Kezdőlap') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if($logged->auth('status_id') == 2)                        
                    <label class="text-warning-emphasis"> {{__('A fiókodat törölted!')}}
                    @elseif($logged->auth('status_id') == 3)
                    <label class="text-danger-emphasis"> {{__('A fiókodat letiltották!')}}
                    @else <label class="text-success-emphasis">
                    {{ __('Üdvözöljük ')}} <b>{{$logged->auth('username')}}</b>!
                    @endif
                    </label>
                </div>
            </div>
        </div>
</div>
</div>
</div>
<div class="my-3 p-3 bg-body rounded shadow-sm mx-auto" style="width: 850px;">

<div class="card">
<div class="card-header">{{ __('Eltávolított tartalmaid:') }}</div>
<div class="card-body">
<table class="table element">
@if($removes->count() == 0)
<label class="text-success-emphasis">
{{ __('Minden tartalmad aktív!')}}</label>
@endif
@foreach ($removes as $remove)


<tbody style="height:75px" onclick="window.location='/restore/{{$remove -> id}}';">
<td rowspan="2" style = "width:50px;border:0">
    @include('removes.remove_item')
</td></tbody>

@endforeach
</table>
</div>
</div>
@if($logged->auth('status_id') == 1)
<label>
<a href='/appeals'>Beérkező fellebbezések</a>
</label>
@endif