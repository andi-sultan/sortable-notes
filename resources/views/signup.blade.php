<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form>
            <h1 class="h3 mb-3 fw-normal">Sign Up</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="name">
                <label for="name">Name</label>
            </div>
            <div class="form-floating">
                <input type="email" class="form-control" id="email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" placeholder="Password">
                <label for="password">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign Up</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
            <p><a href="{{ url('/login') }}">I already have an account</a></p>
        </form>
    </main>
</body>

</html>
