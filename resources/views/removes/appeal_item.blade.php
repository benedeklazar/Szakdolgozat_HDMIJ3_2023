<?php use Carbon\Carbon; Carbon::setLocale('hu'); $date = 
Carbon::parse($remove -> deletion_time) -> diffForHumans();?>

@include('removes.item.'.$remove -> objecttype -> name)

@if ($remove -> obj != null)
@include('removes.badge.'.$remove -> obj -> status -> name)

@endif

