<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>

<body class="d-flex h-100 text-center text-bg-dark">

    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">Cover</h3>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link fw-bold py-1 px-0 active" aria-current="page" href="#">Home</a>
                    <a class="nav-link fw-bold py-1 px-0" href="{{ url('/login') }}">Login</a>
                    <a class="nav-link fw-bold py-1 px-0" href="{{ url('/signup') }}">Sign Up</a>
                </nav>
            </div>
        </header>

        <main class="px-3">
            <h1>Title</h1>
            <p class="lead">Lorem, ipsum dolor sit amet consectetur adipisicing elit. At, natus quae. Nostrum, dolor
                expedita necessitatibus assumenda optio porro similique iure?</p>
            <p class="lead">
                <a href="#" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Learn more</a>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a
                    href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
        </footer>
    </div>



</body>

</html>
