<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <title>Log Riwayat Mekanik</title>
</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile">
            <img src="{{ asset('assets/images/mekanik.jpg') }}" alt="Foto Profil">
            <h3>{{ auth()->user()->name }}</h3>
            <small>{{ auth()->user()->username }}</small>
        </div>

        <div class="menu">
            <a href="{{ route('mekanik.diagnosa') }}">Analisis Diagnosa</a>
            <a href="{{ route('mekanik.riwayat') }}" class="active">Log Riwayat</a>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="title">Dashboard Mekanik</div>

        <div class="riwayat-panel">
            <div class="riwayat-table-title">Tabel Perbaikan Motor</div>

            @if(session('success'))
                <div class="riwayat-alert">{{ session('success') }}</div>
            @endif

            <div class="riwayat-table-wrap">
                <table class="riwayat-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Informasi Kerusakan</th>
                            <th>Status</th>
                            <th>Data Pelanggan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $riwayat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="damage-info">
                                        <strong>{{ $riwayat->nama_kerusakan }}</strong>
                                        <span>{{ $riwayat->persentase }}% cocok, {{ $riwayat->jumlah_gejala_cocok }}/{{ $riwayat->total_gejala }} gejala</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ strtolower($riwayat->status) }}">
                                        {{ $riwayat->status }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn-view" onclick="toggleDetail('detail-{{ $riwayat->id }}')">View</button>
                                </td>
                                <td>
                                    <form action="{{ route('mekanik.riwayat.hapus', $riwayat->id) }}" method="POST" onsubmit="return confirm('Batalkan log riwayat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-cancel-riwayat">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="riwayat-detail" id="detail-{{ $riwayat->id }}">
                                <td colspan="5">
                                    <div class="riwayat-detail-box">
                                        <div>
                                            <strong>Mekanik</strong>
                                            <p>{{ $riwayat->user->name ?? 'Mekanik' }}</p>
                                        </div>
                                        <div>
                                            <strong>Gejala Terpilih</strong>
                                            @forelse($riwayat->gejala_terpilih ?? [] as $gejala)
                                                <p>{{ $gejala['kode_gejala'] ?? '-' }} - {{ $gejala['nama_gejala'] ?? '-' }}</p>
                                            @empty
                                                <p>Tidak ada data gejala.</p>
                                            @endforelse
                                        </div>
                                        <div>
                                            <strong>Solusi</strong>
                                            @forelse($riwayat->solusi ?? [] as $solusi)
                                                <p>{{ $solusi['nama_solusi'] ?? '-' }}{{ !empty($solusi['deskripsi']) ? ' - ' . $solusi['deskripsi'] : '' }}</p>
                                            @empty
                                                <p>Belum ada solusi.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-riwayat">Belum ada log riwayat diagnosa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

    function toggleDetail(id) {
        document.getElementById(id).classList.toggle('active');
    }

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
