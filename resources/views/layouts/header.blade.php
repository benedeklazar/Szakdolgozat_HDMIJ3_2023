
<header class="header">

    <div id="app">
        <nav class="navbar navbar-expand-md  shadow-sm">
            <div class="container">
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <ul class="navbar-nav ms-auto">
                @if($logged->auth('id') !== null)
                <li><a href="/" class="nav-link px-2">Kezdőlap</a></li>
                <li><a href="/group" class="nav-link px-2">Csoportok</a></li>
                <li><a href="/user" class="nav-link px-2">Felhasználók</a></li>
                @endif
                </ul>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Bejelentkezés') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Regisztráció') }}</a>
                                </li>
                            @endif
                        @else
                            <ul class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="/image/{{ $logged->auth('image') == null ? "default.jpg" : $logged->auth('image') }}" alt="mdo" width="32" height="32" class="rounded-circle">
                                    &nbsp;{{ $logged->auth('username') }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    
                                    <li><a class="dropdown-item" href="/user/{{$logged->auth('id')}}">Profilom</a></li>
                                    <li><a class="dropdown-item" href="/user/edit/{{$logged->auth('id')}}">Fiók szerkesztése</a></li>
                                    
                    <li>
                        <a class="dropdown-item" href="#">Téma</a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li><a class="dropdown-item" href="#" onclick="setlight()">
                            <i class="bi bi-sun"></i> Világos</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setdark()">
                            <i class="bi bi-moon"></i> Sötét</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setauto()">
                            <i class="bi bi-circle-half" style="font-size:15px"></i> Eszköz témája</a></li>
                        </ul>
                    </li>
                                      
                                    <hr class="dropdown-divider">
                                    
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Kijelentkezés') }}
                                    </a></li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </ul>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>