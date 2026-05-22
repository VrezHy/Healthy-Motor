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
            <a href="{{ route('mekanik.diagnosa') }}" class="active">Analisis Diagnosa</a>
            <a href="{{ route('mekanik.riwayat') }}">Log Riwayat</a>

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
            <a href="{{ route('mekanik.diagnosa') }}" class="btn-diagnosa">
                MULAI<br>DIAGNOSA
            </a>
        </div>

    </div>

</div>

<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-box">
        <h2>Yakin Keluar Dari Aplikasi?</h2>
        <div class="logout-modal-actions">
            <button type="button" class="modal-btn cancel" id="cancelLogout">batal</button>
            <button type="button" class="modal-btn confirm" id="confirmLogout">keluar</button>
        </div>
    </div>
</div>

<script>
    let pendingLogoutForm = null;
    const logoutModal = document.getElementById('logoutModal');

    document.querySelectorAll('.logout-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingLogoutForm = form;
            logoutModal.classList.add('active');
        });
    });

    document.getElementById('cancelLogout').addEventListener('click', () => {
        pendingLogoutForm = null;
        logoutModal.classList.remove('active');
    });

    document.getElementById('confirmLogout').addEventListener('click', () => {
        if (pendingLogoutForm) {
            pendingLogoutForm.submit();
        }
    });
</script>

</body>
</html>
