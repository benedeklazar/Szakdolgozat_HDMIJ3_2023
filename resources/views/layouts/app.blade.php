@include('setup')
<?php use App\Models\Role; use App\Models\Status; use App\Models\User; ?>
@inject('logged', 'App\Http\Controllers\Controller')

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
data-bs-theme="dark">

<head>
<script>
         var theme = localStorage.getItem('theme');

         if (theme == null || theme == undefined) {
            theme = 'dark';
            localStorage.setItem('theme', theme);
         }
         if (theme == 'auto') {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) 
            {theme = 'dark';}else{theme = 'light';}
         }
         document.documentElement.setAttribute('data-bs-theme', theme);              
</script>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropdown.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"
    rel="stylesheet">
</head>

@include('layouts.header')

<body style="background-color:rgba(0,0,0,.05)">      

<script>
    function setdark() {
        document.documentElement.setAttribute('data-bs-theme', 'dark'); 
        localStorage.setItem('theme', "dark");    
    }

    function setlight() {
        document.documentElement.setAttribute('data-bs-theme', 'light');
        localStorage.setItem('theme', "light");     
    }

    function setauto() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
         {document.documentElement.setAttribute('data-bs-theme', 'dark');
    }else{document.documentElement.setAttribute('data-bs-theme', 'light');
    }
    localStorage.setItem('theme', "auto"); 
}
</script>

</body>
<div class="my-3 p-3 bg-body rounded shadow-sm mx-auto" style="width: 1100px;">
