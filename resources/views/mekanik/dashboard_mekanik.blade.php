<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
     <link rel ="stylesheet" href = "{{ asset('css/dashboard_mekanik.css') }}">
    <title>Dashboard Mekanik</title>

</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile">
                <img src="{{asset('assets/images/mekanik.jpg')}}" alt="Foto Profil">
                <h3>{{auth()->user()->name}}</h3>
                <small>{{auth()->user()->username}}</small>
            </div>

        <div class="menu">
            <a href="#">Analisis Diagnosa</a>
            <a href="#">Log Riwayat</a>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="title">Dashboard Mekanik</div>

        <div class="center-box">
            <button class="btn-diagnosa">
                MULAI<br>DIAGNOSA
            </button>
        </div>

    </div>

</div>

</body>
</html>
