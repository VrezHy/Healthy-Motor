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

        <div class="sidebar">
            <div class="profile">
                <img src="{{asset('assets/images/admin.png')}}" alt="Foto Profil">
                <h3>{{ auth()->check() ? auth()->user()->name : 'Admin' }}</h3>
                <small>{{ auth()->check() ? auth()->user()->username : 'admin_dmss' }}</small>
            </div>

            <div class="menu">
                <a href="#">Data Motor</a>
                <a href="#">Data Gejala</a>
                <a href="#">Data Penyakit</a>
                <a href="#">Data Solusi</a>

                <form method="POST" action="{{ route('logout') }}" style="display: inline-block; width: 100%;">
                    @csrf
                    <button type="submit" class="logout" style="background: transparent; border: none; cursor: pointer; text-align: left; width: 100%; font-family: inherit; font-size: inherit; color: inherit; padding: 0;">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content">

            <div class="title">Dashboard Admin</div>

            <div class="cards">
                <div class="card">
                    <h4>Data Motor</h4>
                    <h2>{{ isset($dataMotor) ? $dataMotor->count() : 0 }}</h2>
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

            <div class="table-box">
                <h3>Tabel Data Motor</h3>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Data Motor</th>
                            <th>Status / Kendala</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($dataMotor) && $dataMotor->count() > 0)
                            @foreach($dataMotor as $index => $motor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                
                                <td>{{ $motor->nama_pemilik }} ({{ $motor->plat_nomor }})</td>
                                <td>{{ $motor->kendala }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px;">
                                    Belum ada log riwayat motor di database.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</body>

</html>