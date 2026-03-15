<!DOCTYPE html>
<html lang="in">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href = "{{asset('css/home.css')}}">
    <title>Healthy Motor</title>
</head>

<body>

    <div class="header">
        DM5S
    </div>

    <div class="container">
        <div class="logo">
            <img src="{{ asset('assets/images/mesin.png') }}" alt="mesin">
        </div>

        <div class="title">
            Healthy Motor
        </div>

        <div class="button-group">
            <a href="/login">
                <button class="btn">Login</button>
            </a>

            <a href="/register">
                <button class="btn">Register</button>
            </a>
        </div>
    </div>

</body>
</html>
