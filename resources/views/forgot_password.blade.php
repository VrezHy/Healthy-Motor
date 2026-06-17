<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">
    <div class="card">

        <div class="title">RESET PASSWORD</div>

        <form action="{{ route('forgot.password.reset') }}" method="POST">
            @csrf

            <label>Username</label>
            <input type="text" name="username" required>

            @error('username')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <label>Kode Pemulihan</label>
            <input type="text" name="recovery_code" required>

            @error('recovery_code')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <label>Password Baru</label>
            <input type="password" name="password" required>

            @error('password')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">
                Reset Password
            </button>
        </form>

        <div class="register-text">
            <a href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>

    </div>
</div>

</body>
</html>
