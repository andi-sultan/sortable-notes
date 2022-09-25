<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>

<body class="d-flex h-100 text-center text-bg-dark">

    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">{{ config('app.name') }}</h3>
                @if (Auth::check())
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <span class="nav-link fw-bold py-1 px-0">Hi, {{ auth()->user()->name }}!</span>
                        <a class="nav-link fw-bold py-1 px-0" href="{{ url('/notes') }}">Go to your notes</a>
                    </nav>
                @else
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <a class="nav-link fw-bold py-1 px-0" href="{{ url('/login') }}">Login</a>
                    </nav>
                @endif
            </div>
        </header>

        <main class="px-3">
            <h1>{{ config('app.name') }}</h1>
            <p class="lead">Create Notes and sort it</p>
            <p class="lead">
                @if (Auth::check())
                    <a href="{{ url('/notes') }}" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Go to
                        your notes</a>
                @else
                    <a href="{{ url('/login') }}"
                        class="btn btn-lg btn-secondary fw-bold border-white bg-white">Login</a>
                @endif
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <strong>Copyright &copy; 2022 Sortable Notes.</strong> All rights reserved.
            <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a
                    href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
        </footer>
    </div>



</body>

</html>
