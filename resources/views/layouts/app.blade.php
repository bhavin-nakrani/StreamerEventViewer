<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StreamerEventViewer') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'StreamerEventViewer') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            {{--@if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif--}}
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stream') }}">Favorite Channel</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <section class="mt-3">
                <div class="container clearfix">
                    @if (session('flash_success'))
                        <div class="style-msg successmsg">
                            <div class="sb-msg"><i class="icon-thumbs-up"></i><strong>Well done!</strong> {{ session('flash_success') }}</div>
                        </div>
                    @elseif (session('flash_error'))
                        <div class="style-msg errormsg">
                            <div class="sb-msg"><i class="icon-remove"></i><strong>Oh snap!</strong> {{ session('flash_error') }}</div>
                        </div>
                    @elseif (session('flash_info'))
                        <div class="style-msg infomsg">
                            <div class="sb-msg"><i class="icon-info-sign"></i><strong>Heads up!</strong> {{ session('flash_info') }}</div>
                        </div>
                    @elseif (session('flash_alert'))
                        <div class="style-msg alertmsg">
                            <div class="sb-msg"><i class="icon-warning-sign"></i><strong>Warning!</strong> {{ session('flash_alert') }}</div>
                        </div>
                    @elseif (session('flash_note'))
                        <div class="style-msg style-msg-light">
                            <div class="sb-msg"><i class="icon-question-sign"></i>{{ session('flash_note') }}</div>
                        </div>
                    @endif
                </div>
            </section>

            @yield('content')
        </main>
    </div>
    @yield('js')
</body>
</html>
