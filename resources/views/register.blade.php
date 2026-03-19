<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href = "{{ asset('css/register.css') }}">
    <title>Register - Healthy Motor</title>
</head>

<body>
    <div class="header"> DM5S </div>

    <div class="container">

        <div class="card">

            <div class="title">REGISTER</div>

            <form method="POST" action="{{ route('register.regis') }}">
                @csrf


                <label>Your Name</label>
                <input type="text" name="name" required>
                @error('name')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <label>Username</label>
                <input type="text" name="username" required>
                @error('username')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
                @error('blocked')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <label>Password</label>
                <input type="password" name="password" required>
                @error('password')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <button type="submit">Register</button>

            </form>

            <div class="login-text">
                Already Have Account?
                <a href="{{ route('login.login') }}">Login here!</a>
            </div>

        </div>

    </div>

</body>

</html>
