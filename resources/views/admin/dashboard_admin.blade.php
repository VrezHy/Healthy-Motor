<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel ="stylesheet" href = "{{ asset('css/dashboard_admin.css') }}">
    <title>Dashboard Admin</title>

</head>
<body>

    <div class="header">
        DMSS
    </div>

    <div class="container">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="profile">
                <img src="{{asset('assets/images/admin.png')}}" alt="Foto Profil">
                <h3>{{auth()->user()->name}}</h3>
                <small>{{auth()->user()->username}}</small>
            </div>

            <div class="menu">
                <a href="#">Data Motor</a>
                <a href="#">Data Gejala</a>
                <a href="#">Data Penyakit</a>
                <a href="#">Data Solusi</a>

                <a href="#" class="logout">Logout</a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="title">Dashboard Admin</div>

            <!-- CARD -->
            <div class="cards">
                <div class="card">
                    <h4>Data Motor</h4>
                    <h2>0</h2>
                </div>

                <div class="card">
                    <h4>Data Kerusakan</h4>
                    <h2>0</h2>
                </div>

                <div class="card">
                    <h4>Data Gejala</h4>
                    <h2>0</h2>
                </div>

                <div class="card">
                    <h4>Data Solusi</h4>
                    <h2>0</h2>
                </div>
            </div>

            <!-- TABEL DATA MOTOR (BELUM ADA ISI) -->
            <div class="table-box">
                <h3>Tabel Data Motor</h3>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data Motor</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Data nanti diisi dari database -->
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</body>

</html>
