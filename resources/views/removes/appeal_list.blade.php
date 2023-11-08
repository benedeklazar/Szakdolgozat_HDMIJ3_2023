@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

<link href="{{ asset('css/list.css') }}" rel="stylesheet">

</head>
<body>

<div class="card">
<div class="card-header">{{ __('Beérkező fellebbezések:') }}</div>
<div class="card-body">
<table class="table element">
@if($appeals->count() == 0)
<label class="text-success-emphasis">
{{ __('Nincs semmi teendő!')}}</label>
@endif
@foreach ($appeals as $remove)


<tbody style="height:75px" onclick="window.location='/appeal/{{$remove -> id}}';">
<td rowspan="2" style = "width:50px;border:0">
    @include('removes.appeal_item')
</td></tbody>

@endforeach

</table>
</div>