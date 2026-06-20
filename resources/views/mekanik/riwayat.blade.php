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


        <div class="content">
            <div class="title">Dashboard Mekanik</div>

            <div class="riwayat-panel" id="riwayatPanel">
                <div class="riwayat-table-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Tabel Perbaikan Motor</span>
                    <a href="{{ route('mekanik.riwayat.sampah') }}" style="background-color: #ff9800; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px; border: none; cursor: pointer;">
                        🗑️ Tempat Sampah
                    </a>
                </div>

                @if (session('success'))
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
                                            ...
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('mekanik.riwayat.status', $riwayat->id) }}"
                                        method="POST" class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status"
                                            class="status-select status-{{ strtolower($riwayat->status) }}"
                                            onchange="this.form.submit()">
                                            <option value="Draft"
                                                {{ $riwayat->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="Aktif"
                                                {{ $riwayat->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Done"
                                                {{ $riwayat->status === 'Done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <button type="button" class="btn-view" data-id="{{ $riwayat->id }}"
                                        data-nama="{{ $riwayat->nama_pelanggan }}"
                                        data-alamat="{{ $riwayat->alamat_pelanggan }}"
                                        data-polisi="{{ $riwayat->nomor_polisi }}"
                                        data-telepon="{{ $riwayat->nomor_telepon }}"
                                        onclick="openPelangganForm(this)">
                                        View
                                    </button>
                                </td>
                                <td>
                                    <form action="{{ route('mekanik.riwayat.hapus', $riwayat->id) }}"
                                        method="POST" class="cancel-riwayat-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-cancel-riwayat">Cancel</button>
                                    </form>
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

            <div class="pelanggan-panel" id="pelangganPanel">
                <form method="POST" class="pelanggan-form" id="pelangganForm">
                    @csrf
                    @method('PUT')

                    <button type="button" class="pelanggan-back" onclick="closePelangganForm()"
                        aria-label="Kembali ke tabel">
                        &#8592;
                    </button>

                    <h2>Data Pelanggan</h2>

                    <div class="form-group">
                        <label for="namaPelanggan">Nama</label>
                        <input type="text" name="nama_pelanggan" id="namaPelanggan" autocomplete="off" required>
                        <span class="error-message" id="errorNama"></span>
                    </div>

                    <div class="form-group">
                        <label for="alamatPelanggan">Alamat</label>
                        <input type="text" name="alamat_pelanggan" id="alamatPelanggan" autocomplete="off" required>
                        <span class="error-message" id="errorAlamat"></span>
                    </div>

                    <div class="form-group">
                        <label for="nomorPolisi">Nomor Polisi</label>
                        <input type="text" name="nomor_polisi" id="nomorPolisi" autocomplete="off" required>
                        <span class="error-message" id="errorPolisi"></span>
                    </div>

                    <div class="form-group">
                        <label for="nomorTelepon">Nomor Telepon</label>
                        <input type="tel" name="nomor_telepon" id="nomorTelepon" autocomplete="off" required>
                        <span class="error-message" id="errorTelepon"></span>
                    </div>

                    <div class="pelanggan-actions">
                        <button type="button" class="pelanggan-btn cancel"
                            onclick="closePelangganForm()">Cancel</button>
                        <button type="submit" class="pelanggan-btn save">Simpan</button>
                    </div>
                </form>
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

    <div class="kerusakan-modal" id="kerusakanModal">
        <div class="kerusakan-modal-box">
            <button type="button" class="kerusakan-modal-close" id="closeKerusakanModal"
                aria-label="Tutup detail">×</button>
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

    <div class="cancel-riwayat-modal" id="cancelRiwayatModal">
        <div class="cancel-riwayat-modal-box">
            <h2>Batalkan Log Riwayat Ini?</h2>
            <div class="cancel-riwayat-actions">
                <button type="button" class="modal-btn cancel" id="batalCancelRiwayat">batal</button>
                <button type="button" class="modal-btn confirm" id="confirmCancelRiwayat">batalkan</button>
            </div>
        </div>
    </div>

    <script>
        let pendingLogoutForm = null;
        let pendingCancelRiwayatForm = null;
        const logoutModal = document.getElementById('logoutModal');
        const cancelRiwayatModal = document.getElementById('cancelRiwayatModal');

        const riwayatPanel = document.getElementById('riwayatPanel');
        const pelangganPanel = document.getElementById('pelangganPanel');
        const pelangganForm = document.getElementById('pelangganForm');
        const nomorTelepon = document.getElementById('nomorTelepon');
        const nomorPolisi = document.getElementById('nomorPolisi');
        const kerusakanModal = document.getElementById('kerusakanModal');
        const detailKerusakan = document.getElementById('detailKerusakan');
        const detailGejala = document.getElementById('detailGejala');
        const detailSolusi = document.getElementById('detailSolusi');

        const namaInput = document.getElementById('namaPelanggan');
        const alamatInput = document.getElementById('alamatPelanggan');
        const polisiInput = document.getElementById('nomorPolisi');
        const telponInput = document.getElementById('nomorTelepon');

        // Fungsi untuk membuat atau mendapatkan span error
        function getErrorSpan(input, id) {
            let errorSpan = document.getElementById(id);
            if (!errorSpan) {
                errorSpan = document.createElement('span');
                errorSpan.className = 'error-message';
                errorSpan.id = id;
                input.parentNode.appendChild(errorSpan);
            }
            return errorSpan;
        }

        // Validasi Nama (hanya huruf, spasi, titik, apostrof)
        function validateNama() {
            const value = namaInput.value.trim();
            const regex = /^[A-Za-z\s.\']+$/;
            const errorSpan = getErrorSpan(namaInput, 'errorNama');

            if (value === '') {
                namaInput.classList.add('error');
                errorSpan.textContent = '❌ Nama tidak boleh kosong';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else if (!regex.test(value)) {
                namaInput.classList.add('error');
                errorSpan.textContent = '❌ Nama hanya boleh berisi huruf, spasi, titik, dan apostrof';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else {
                namaInput.classList.remove('error');
                errorSpan.textContent = '✓ Nama valid';
                errorSpan.style.color = '#ffffff'; // putih
                //errorSpan.style.backgroundColor = '#28a745'; // background hijau
                setTimeout(() => {
                    if (errorSpan.textContent === '✓ Nama valid') {
                        errorSpan.textContent = '';
                        errorSpan.style.backgroundColor = '';
                    }
                }, 1500);
                return true;
            }
        }

        // Validasi Alamat
        function validateAlamat() {
            const value = alamatInput.value.trim();
            const errorSpan = getErrorSpan(alamatInput, 'errorAlamat');

            if (value === '') {
                alamatInput.classList.add('error');
                errorSpan.textContent = '❌ Alamat tidak boleh kosong';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else {
                alamatInput.classList.remove('error');
                errorSpan.textContent = '✓ Alamat valid';
                errorSpan.style.color = '#ffffff'; // putih
                //errorSpan.style.backgroundColor = '#28a745'; // background hijau
                setTimeout(() => {
                    if (errorSpan.textContent === '✓ Alamat valid') {
                        errorSpan.textContent = '';
                        errorSpan.style.backgroundColor = '';
                    }
                }, 1500);
                return true;
            }
        }

        // Validasi Nomor Polisi
        // Validasi Nomor Polisi dengan format: AB-1234-MM atau L-123-MM
        function validatePolisi() {
            let value = polisiInput.value.toUpperCase();
            const errorSpan = getErrorSpan(polisiInput, 'errorPolisi');


            value = value.replace(/[^A-Z0-9 ]/g, '');
            polisiInput.value = value;


            const regex = /^[A-Z]{1,2}\s[0-9]{3,4}\s[A-Z]{2,3}$/;

            if (value.trim() === '') {
                errorSpan.textContent = 'Silahkan isi sesuai dengan format no polisi contoh (AB 1005 NN)';
                errorSpan.style.color = '#dc3545'; // merah
                polisiInput.classList.add('error');
                return false;
            }

            if (regex.test(value)) {
                errorSpan.textContent = '';
                polisiInput.classList.remove('error');
                return true;
            }

            // Selama mengetik dan format belum sesuai
            errorSpan.textContent = 'Silahkan isi sesuai dengan format no polisi contoh (AB 1005 NN)';
            polisiInput.classList.add('error');
            return false;
        }

        // Validasi Nomor Telepon
        function validateTelepon() {
            let value = telponInput.value.replace(/\D/g, '');
            telponInput.value = value;
            const errorSpan = getErrorSpan(telponInput, 'errorTelepon');

            if (value === '') {
                telponInput.classList.add('error');
                errorSpan.textContent = '❌ Nomor telepon tidak boleh kosong';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else if (value.length < 10) {
                telponInput.classList.add('error');
                errorSpan.textContent = '❌ Nomor telepon minimal 10 angka';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else if (value.length > 13) {
                telponInput.classList.add('error');
                errorSpan.textContent = '❌ Nomor telepon maksimal 13 angka';
                errorSpan.style.color = '#dc3545'; // merah
                return false;
            } else {
                telponInput.classList.remove('error');
                errorSpan.textContent = '✓ Nomor telepon valid';
                errorSpan.style.color = '#ffffff'; // putih

                setTimeout(() => {
                    if (errorSpan.textContent === '✓ Nomor telepon valid') {
                        errorSpan.textContent = '';
                        errorSpan.style.backgroundColor = '';
                    }
                }, 1500);
                return true;
            }
        }
        // Event untuk validasi Nama
        if (namaInput) {
            namaInput.addEventListener('input', validateNama);
            namaInput.addEventListener('blur', validateNama);
        }

        // Event untuk validasi Alamat
        if (alamatInput) {
            alamatInput.addEventListener('input', validateAlamat);
            alamatInput.addEventListener('blur', validateAlamat);
        }

        // Event untuk validasi Nomor Polisi (sudah termasuk auto-format)
        if (polisiInput) {
            polisiInput.addEventListener('input', validatePolisi);
            polisiInput.addEventListener('blur', validatePolisi);
        }

        // Event untuk validasi Nomor Telepon
        if (telponInput) {
            telponInput.addEventListener('input', validateTelepon);
            telponInput.addEventListener('blur', validateTelepon);
        }

        // Validasi sebelum submit form
        if (pelangganForm) {
            pelangganForm.addEventListener('submit', function(e) {
                const isNamaValid = validateNama();
                const isAlamatValid = validateAlamat();
                const isPolisiValid = validatePolisi();
                const isTeleponValid = validateTelepon();

                if (!isNamaValid || !isAlamatValid || !isPolisiValid || !isTeleponValid) {
                    e.preventDefault();
                    // Scroll ke field yang pertama kali error
                    if (!isNamaValid) {
                        namaInput.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        namaInput.focus();
                    } else if (!isAlamatValid) {
                        alamatInput.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        alamatInput.focus();
                    } else if (!isPolisiValid) {
                        polisiInput.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        polisiInput.focus();
                    } else if (!isTeleponValid) {
                        telponInput.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        telponInput.focus();
                    }
                }
            });
        }

        function openPelangganForm(button) {
            const id = button.dataset.id;

            pelangganForm.action = `/mekanik/riwayat/${id}/pelanggan`;
            document.getElementById('namaPelanggan').value = button.dataset.nama || '';
            document.getElementById('alamatPelanggan').value = button.dataset.alamat || '';
            document.getElementById('nomorPolisi').value = button.dataset.polisi || '';
            document.getElementById('nomorTelepon').value = button.dataset.telepon || '';

            riwayatPanel.classList.add('is-hidden');
            pelangganPanel.classList.add('active');
        }

        function closePelangganForm() {
            pelangganPanel.classList.remove('active');
            riwayatPanel.classList.remove('is-hidden');
        }



        function renderDetailList(target, items, formatter, emptyText) {
            target.innerHTML = '';

            if (!items.length) {
                const empty = document.createElement('p');
                empty.textContent = emptyText;
                target.appendChild(empty);
                return;
            }

            items.forEach((item) => {
                const row = document.createElement('p');
                row.textContent = formatter(item);
                target.appendChild(row);
            });
        }

        function openKerusakanModal(button) {
            const gejalas = JSON.parse(button.dataset.gejala || '[]');
            const solusies = JSON.parse(button.dataset.solusi || '[]');

            detailKerusakan.textContent = button.dataset.kerusakan || '-';
            renderDetailList(
                detailGejala,
                gejalas,
                (gejala) => `${gejala.kode_gejala || '-'} - ${gejala.nama_gejala || '-'}`,
                'Tidak ada data gejala.'
            );
            renderDetailList(
                detailSolusi,
                solusies,
                (solusi) => `${solusi.nama_solusi || '-'}${solusi.deskripsi ? ' - ' + solusi.deskripsi : ''}`,
                'Belum ada solusi.'
            );

            kerusakanModal.classList.add('active');
        }

        document.getElementById('closeKerusakanModal').addEventListener('click', () => {
            kerusakanModal.classList.remove('active');
        });

        kerusakanModal.addEventListener('click', (event) => {
            if (event.target === kerusakanModal) {
                kerusakanModal.classList.remove('active');
            }
        });

        @if(session('pelanggan_form_id'))
        pelangganForm.action = `/mekanik/riwayat/{{ session('pelanggan_form_id') }}/pelanggan`;
        document.getElementById('namaPelanggan').value = @json(old('nama_pelanggan'));
        document.getElementById('alamatPelanggan').value = @json(old('alamat_pelanggan'));
        document.getElementById('nomorPolisi').value = @json(old('nomor_polisi'));
        document.getElementById('nomorTelepon').value = @json(old('nomor_telepon'));
        riwayatPanel.classList.add('is-hidden');
        pelangganPanel.classList.add('active');
        @endif

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

        document.querySelectorAll('.cancel-riwayat-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingCancelRiwayatForm = form;
                cancelRiwayatModal.classList.add('active');
            });
        });

        document.getElementById('batalCancelRiwayat').addEventListener('click', () => {
            pendingCancelRiwayatForm = null;
            cancelRiwayatModal.classList.remove('active');
        });

        document.getElementById('confirmCancelRiwayat').addEventListener('click', () => {
            if (pendingCancelRiwayatForm) {
                pendingCancelRiwayatForm.submit();
            }
        });
    </script>

</body>

</html>