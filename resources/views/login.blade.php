<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthy Motor</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>

    <div class="header">
        DM5S
    </div>

    <div class="container">

        <div class="card">

            <div class="title">LOGIN</div>

            <form action="{{ route('login.login') }}" method="POST">
                @csrf

                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit">Login</button>

            </form>

            <div class="register-text">
                Not Registered?
                <a href="{{ route('register.regis') }}">Sign up!</a>
            </div>

        </div>

    </div>

</body>

</html>
