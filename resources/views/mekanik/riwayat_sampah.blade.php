<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sampah.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Tempat Sampah - Log Riwayat</title>
</head>

<body>

    <div class="header">
        DM5S
    </div>

    <div class="container">

        <div class="sidebar">
            <div class="profile">
                <img src="{{ asset('assets/images/mekanik.jpg') }}" alt="Foto Profil">
                <h3>{{ auth()->user()->name }}</h3>
                <small>{{ auth()->user()->username }}</small>
            </div>

            <div class="menu">
                <a href="{{ route('mekanik.dashboard') }}">
                    <span class="material-icons">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mekanik.diagnosa') }}">
                    <span class="material-icons">search</span>
                    <span>Analisis Diagnosa</span>
                </a>
                <a href="{{ route('mekanik.riwayat') }}" class="active">
                    <span class="material-icons">history</span>
                    <span>Log Riwayat</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout">Logout</button>
                </form>
            </div>
        </div>

        <div class="content content-fixed-layout">
            <!-- BREADCRUMB -->
            <nav class="breadcrumb">
                <a href="{{ route('mekanik.dashboard') }}">Mekanik</a> / <a href="{{ route('mekanik.riwayat') }}">Log Riwayat</a> / <span>Tempat Sampah</span>
            </nav>

            <div class="riwayat-panel" id="riwayatPanel">
                <div class="riwayat-table-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Data Riwayat yang Dihapus</span>
                    <a href="{{ route('mekanik.riwayat') }}" class="btn-back-utama">
                        <span class="material-icons">arrow_back</span>
                        <span>Kembali ke Riwayat Utama</span>
                    </a>
                </div>

                @if (session('success'))
                <div class="toast-notification" id="toastNotification">
                    <span class="material-icons toast-icon">check_circle</span>
                    <span class="toast-message">{{ session('success') }}</span>
                </div>
                @endif

                <!-- SEARCH BAR -->
                <div class="riwayat-search-bar">
                    <form action="{{ route('mekanik.riwayat.sampah') }}" method="GET" class="search-form">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan, nomor polisi, kerusakan, atau status..." class="search-input">
                        <button type="submit" class="btn-search">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('mekanik.riwayat.sampah') }}" class="btn-clear-search">Batal</a>
                        @endif
                    </form>
                </div>

                <div class="riwayat-table-wrap">
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Informasi Kerusakan</th>
                                <th>Status Terakhir</th>
                                <th>Nama Pelanggan</th>
                                <th>Aksi (Restore / Hapus)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatsSampah as $riwayat)
                            <tr>
                                <td>{{ ($riwayatsSampah->currentPage() - 1) * $riwayatsSampah->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="damage-info">
                                        <div>
                                            <strong>{{ $riwayat->nama_kerusakan }}</strong>
                                            <span>{{ $riwayat->persentase }}% cocok,
                                                {{ $riwayat->jumlah_gejala_cocok }}/{{ $riwayat->total_gejala }}
                                                gejala</span>
                                        </div>
                                        <button type="button" class="btn-detail-kerusakan"
                                            data-kerusakan="{{ $riwayat->nama_kerusakan }}"
                                            data-gejala='@json($riwayat->gejala_terpilih ?? [])'
                                            data-solusi='@json($riwayat->solusi ?? [])'
                                            onclick="openKerusakanModal(this)"
                                            aria-label="Lihat detail gejala, penyakit, dan solusi">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-{{ strtolower($riwayat->status) }}" style="padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                                        {{ $riwayat->status }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn-view"
                                        data-nama="{{ $riwayat->nama_pelanggan }}"
                                        data-alamat="{{ $riwayat->alamat_pelanggan }}"
                                        data-polisi="{{ $riwayat->nomor_polisi }}"
                                        data-telepon="{{ $riwayat->nomor_telepon }}"
                                        onclick="openPelangganView(this)">
                                        View
                                    </button>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('mekanik.riwayat.restore', $riwayat->id) }}" method="POST" class="restore-form" style="margin: 0;">
                                            @csrf
                                            <button type="button" class="btn-restore" onclick="bukaModalRestore(this)">Restore</button>
                                        </form>

                                        <form action="{{ route('mekanik.riwayat.permanen', $riwayat->id) }}" method="POST" class="permanen-form" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-permanen" onclick="bukaModalPermanen(this)">Hapus Permanen</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="empty-riwayat" style="text-align: center; padding: 20px;">Tempat sampah kosong. Ruang putih bersih, mata nyaman!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                {{ $riwayatsSampah->links('partials.pagination') }}
            </div>

            <!-- PANEL DETAIL DATA PELANGGAN (Hanya View, Read-Only) -->
            <div class="pelanggan-panel" id="pelangganPanel">
                <form class="pelanggan-form" id="pelangganForm" onsubmit="event.preventDefault();">
                    <button type="button" class="pelanggan-back" onclick="closePelangganForm()"
                        aria-label="Kembali ke tabel">
                        &#8592;
                    </button>

                    <h2>Data Pelanggan (Tempat Sampah)</h2>

                    <div class="form-group">
                        <label for="namaPelanggan">Nama</label>
                        <input type="text" id="namaPelanggan" autocomplete="off" readonly>
                    </div>

                    <div class="form-group">
                        <label for="alamatPelanggan">Alamat</label>
                        <input type="text" id="alamatPelanggan" autocomplete="off" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nomorPolisi">Nomor Polisi</label>
                        <input type="text" id="nomorPolisi" autocomplete="off" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nomorTelepon">Nomor Telepon</label>
                        <input type="tel" id="nomorTelepon" autocomplete="off" readonly>
                    </div>

                    <div class="pelanggan-actions">
                        <button type="button" class="pelanggan-btn cancel"
                            onclick="closePelangganForm()">Kembali</button>
                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="kerusakan-modal" id="kerusakanModal">
        <div class="kerusakan-modal-box">
            <button type="button" class="kerusakan-modal-close" id="closeKerusakanModal" aria-label="Tutup detail">×</button>
            <h2>Detail Analisis</h2>
            <div class="kerusakan-detail-section">
                <strong>Penyakit / Kerusakan</strong>
                <p id="detailKerusakan">-</p>
            </div>
            <div class="kerusakan-detail-section">
                <strong>Gejala</strong>
                <div id="detailGejala"></div>
            </div>
            <div class="kerusakan-detail-section">
                <strong>Solusi</strong>
                <div id="detailSolusi"></div>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="restoreModal">
        <div class="custom-modal-box">
            <h2>Kembalikan Data Riwayat Ini?</h2>
            <div class="custom-modal-actions">
                <button type="button" class="custom-btn" onclick="tutupModalRestore()">Batal</button>
                <button type="button" class="custom-btn" id="confirmRestore" style="background-color: green;color: white;">Ya!</button>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="permanenModal">
        <div class="custom-modal-box">
            <h2>Hapus Permanen Riwayat Ini?</h2>
            <div class="custom-modal-actions">
                <button type="button" class="custom-btn" onclick="tutupModalPermanen()">batal</button>
                <button type="button" class="custom-btn" id="confirmPermanen" style="background-color: #dc2626;color: white;">hapus</button>
            </div>
        </div>
    </div>

    <script>
        // -- Logika Modal Kerusakan --
        function openKerusakanModal(button) {
            const modal = document.getElementById('kerusakanModal');
            document.getElementById('detailKerusakan').textContent = button.getAttribute('data-kerusakan');

            const gejalaContainer = document.getElementById('detailGejala');
            gejalaContainer.innerHTML = '';
            JSON.parse(button.getAttribute('data-gejala')).forEach(g => {
                const p = document.createElement('p');
                p.textContent = `- ${g.kode_gejala} : ${g.nama_gejala}`;
                gejalaContainer.appendChild(p);
            });

            const solusiContainer = document.getElementById('detailSolusi');
            solusiContainer.innerHTML = '';
            JSON.parse(button.getAttribute('data-solusi')).forEach(s => {
                const p = document.createElement('p');
                p.textContent = `- ${s.nama_solusi} : ${s.deskripsi}`;
                solusiContainer.appendChild(p);
            });

            modal.style.display = 'flex';
        }

        document.getElementById('closeKerusakanModal').addEventListener('click', function() {
            document.getElementById('kerusakanModal').style.display = 'none';
        });


        // -- Logika Custom Modal Restore & Permanen --
        let formYangAkanDisubmit = null;

        // Buka Modal Restore
        function bukaModalRestore(button) {
            formYangAkanDisubmit = button.closest('form');
            document.getElementById('restoreModal').style.display = 'flex';
        }

        function tutupModalRestore() {
            document.getElementById('restoreModal').style.display = 'none';
            formYangAkanDisubmit = null;
        }

        document.getElementById('confirmRestore').addEventListener('click', function() {
            if (formYangAkanDisubmit) {
                formYangAkanDisubmit.submit();
            }
        });

        // Buka Modal Hapus Permanen
        function bukaModalPermanen(button) {
            formYangAkanDisubmit = button.closest('form');
            document.getElementById('permanenModal').style.display = 'flex';
        }

        function tutupModalPermanen() {
            document.getElementById('permanenModal').style.display = 'none';
            formYangAkanDisubmit = null;
        }

        document.getElementById('confirmPermanen').addEventListener('click', function() {
            if (formYangAkanDisubmit) {
                formYangAkanDisubmit.submit();
            }
        });

        // -- Logika Pelanggan View (Recycle Bin) --
        const riwayatPanel = document.getElementById('riwayatPanel');
        const pelangganPanel = document.getElementById('pelangganPanel');

        function openPelangganView(button) {
            document.getElementById('namaPelanggan').value = button.getAttribute('data-nama') || '';
            document.getElementById('alamatPelanggan').value = button.getAttribute('data-alamat') || '';
            document.getElementById('nomorPolisi').value = button.getAttribute('data-polisi') || '';
            document.getElementById('nomorTelepon').value = button.getAttribute('data-telepon') || '';

            riwayatPanel.classList.add('is-hidden');
            pelangganPanel.classList.add('active');
        }

        function closePelangganForm() {
            pelangganPanel.classList.remove('active');
            riwayatPanel.classList.remove('is-hidden');
        }

        // TOAST NOTIFICATION AUTO HIDE
        const toast = document.getElementById('toastNotification');
        if (toast) {
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 3000);
        }
    </script>
</body>

</html>