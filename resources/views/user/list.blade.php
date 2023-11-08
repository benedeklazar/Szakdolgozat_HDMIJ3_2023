@include('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link href="{{ asset('css/list.css') }}" rel="stylesheet">
</head>
<body>

<table class="table element">

@foreach ($users as $user)

  @include('list_items.user', ['user' => $user])

@endforeach
</table>

