<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Log Riwayat Mekanik</title>
</head>

<body>

<div class="header">
    <div style="display: flex; align-items: center; gap: 12px;">
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <span class="material-icons">menu</span>
        </button>
        <span>DM5S</span>
    </div>
</div>

<div class="container">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <span class="material-icons">chevron_left</span>
            </button>
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
                <a href="{{ route('mekanik.dashboard') }}">Mekanik</a> / <span>Log Riwayat</span>
            </nav>

            <div class="riwayat-panel" id="riwayatPanel">
                <div class="riwayat-table-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Tabel Perbaikan Motor</span>
                    <a href="{{ route('mekanik.riwayat.sampah') }}" class="btn-trash-bin">
                        <span class="material-icons">delete</span>
                        <span>Tempat Sampah</span>
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
                    <form action="{{ route('mekanik.riwayat') }}" method="GET" class="search-form">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan, nomor polisi, kerusakan, atau status..." class="search-input">
                        <button type="submit" class="btn-search">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('mekanik.riwayat') }}" class="btn-clear-search">Batal</a>
                        @endif
                    </form>
                </div>

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
                                <td>{{ ($riwayats->currentPage() - 1) * $riwayats->perPage() + $loop->iteration }}</td>
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
                                    <div class="custom-dropdown status-dropdown">
                                        <div class="dropdown-toggle status-{{ strtolower($riwayat->status) }}" onclick="toggleDropdown(this)">
                                            <span>{{ $riwayat->status }}</span>
                                        </div>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-item {{ $riwayat->status === 'Draft' ? 'active' : '' }}" onclick="selectStatus(this, 'Draft')">Draft</div>
                                            <div class="dropdown-item {{ $riwayat->status === 'Aktif' ? 'active' : '' }}" onclick="selectStatus(this, 'Aktif')">Aktif</div>
                                            <div class="dropdown-item {{ $riwayat->status === 'Done' ? 'active' : '' }}" onclick="selectStatus(this, 'Done')">Done</div>
                                        </div>
                                        <form action="{{ route('mekanik.riwayat.status', $riwayat->id) }}" method="POST" class="status-form-hidden">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $riwayat->status }}">
                                        </form>
                                    </div>
                                </td>
                                <td>
                                     <div style="font-weight: 600; color: var(--text-primary);">{{ $riwayat->nama_pelanggan ?? '-' }}</div>
                                     <div style="font-size: 12px; color: var(--text-muted);">{{ $riwayat->nomor_polisi ?? '-' }}</div>
                                 </td>
                                 <td>
                                     <div class="riwayat-actions-wrap" style="display: flex; gap: 8px; align-items: center;">
                                         <!-- VIEW BUTTON -->
                                         <button type="button" class="btn-view-riwayat" data-id="{{ $riwayat->id }}"
                                             data-nama="{{ $riwayat->nama_pelanggan }}"
                                             data-alamat="{{ $riwayat->alamat_pelanggan }}"
                                             data-polisi="{{ $riwayat->nomor_polisi }}"
                                             data-telepon="{{ $riwayat->nomor_telepon }}"
                                             onclick="openPelangganForm(this, 'view')"
                                             aria-label="Lihat Data Pelanggan">
                                             <span class="material-icons">visibility</span>
                                         </button>

                                         <!-- EDIT BUTTON -->
                                         <button type="button" class="btn-edit-riwayat" data-id="{{ $riwayat->id }}"
                                             data-nama="{{ $riwayat->nama_pelanggan }}"
                                             data-alamat="{{ $riwayat->alamat_pelanggan }}"
                                             data-polisi="{{ $riwayat->nomor_polisi }}"
                                             data-telepon="{{ $riwayat->nomor_telepon }}"
                                             onclick="openPelangganForm(this, 'edit')"
                                             aria-label="Edit Data Pelanggan">
                                             <span class="material-icons">edit</span>
                                         </button>

                                         <form action="{{ route('mekanik.riwayat.hapus', $riwayat->id) }}"
                                             method="POST" class="cancel-riwayat-form" style="margin: 0;">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn-cancel-riwayat" aria-label="Hapus Riwayat">
                                                <span class="material-icons">delete</span>
                                            </button>
                                         </form>
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

                <!-- PAGINATION -->
                {{ $riwayats->links('partials.pagination') }}
            </div>

            <div class="pelanggan-panel" id="pelangganPanel">
                <form class="pelanggan-form" id="pelangganForm" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="button" class="pelanggan-back" onclick="closePelangganForm()"
                        aria-label="Kembali ke tabel">
                        &#8592;
                    </button>

                    <h2 id="pelangganTitle">Data Pelanggan</h2>

                    @if($errors->any() && session('pelanggan_form_id'))
                        <div class="alert alert-danger" style="color: var(--danger); margin-bottom: 15px; font-size: 13px; font-weight: 600;">
                            <ul style="list-style-type: none; padding-left: 0;">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="namaPelanggan">Nama</label>
                        <input type="text" id="namaPelanggan" name="nama_pelanggan" required>
                    </div>

                    <div class="form-group">
                        <label for="alamatPelanggan">Alamat</label>
                        <input type="text" id="alamatPelanggan" name="alamat_pelanggan" required>
                    </div>

                    <div class="form-group">
                        <label for="nomorPolisi">Nomor Polisi</label>
                        <input type="text" id="nomorPolisi" name="nomor_polisi" required>
                    </div>

                    <div class="form-group">
                        <label for="nomorTelepon">Nomor Telepon</label>
                        <input type="tel" id="nomorTelepon" name="nomor_telepon" required>
                    </div>

                    <div class="pelanggan-actions">
                        <button type="button" class="pelanggan-btn cancel"
                            onclick="closePelangganForm()">Batal</button>
                        <button type="submit" class="pelanggan-btn save" id="btnSimpanPelanggan">Simpan</button>
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
        // SIDEBAR COLLAPSE LOGIC
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            if (sidebarToggle) {
                sidebarToggle.querySelector('.material-icons').textContent = 'chevron_right';
            }
        }
        
        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            
            const icon = sidebarToggle.querySelector('.material-icons');
            if (icon) {
                icon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
            }
        });

        // MOBILE DRAWER MENU LOGIC
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (mobileMenuBtn && sidebarOverlay && sidebar) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show-mobile');
                sidebarOverlay.classList.toggle('active');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show-mobile');
                sidebarOverlay.classList.remove('active');
            });
        }

        let pendingLogoutForm = null;
        let pendingCancelRiwayatForm = null;
        const logoutModal = document.getElementById('logoutModal');
        const cancelRiwayatModal = document.getElementById('cancelRiwayatModal');
        const riwayatPanel = document.getElementById('riwayatPanel');
        const pelangganPanel = document.getElementById('pelangganPanel');
        const kerusakanModal = document.getElementById('kerusakanModal');
        const detailKerusakan = document.getElementById('detailKerusakan');
        const detailGejala = document.getElementById('detailGejala');
        const detailSolusi = document.getElementById('detailSolusi');

          function openPelangganForm(button, mode = 'view') {
              const id = button.dataset.id;
              const form = document.getElementById('pelangganForm');
              form.action = `/mekanik/riwayat/${id}/pelanggan`;

              const inputs = [
                  document.getElementById('namaPelanggan'),
                  document.getElementById('alamatPelanggan'),
                  document.getElementById('nomorPolisi'),
                  document.getElementById('nomorTelepon')
              ];

              inputs[0].value = button.dataset.nama || '';
              inputs[1].value = button.dataset.alamat || '';
              inputs[2].value = button.dataset.polisi || '';
              inputs[3].value = button.dataset.telepon || '';

              const title = document.getElementById('pelangganTitle');
              const btnSimpan = document.getElementById('btnSimpanPelanggan');

              if (mode === 'edit') {
                  title.textContent = 'Edit Data Pelanggan';
                  inputs.forEach(input => input.removeAttribute('readonly'));
                  if (btnSimpan) btnSimpan.style.display = 'inline-flex';
              } else {
                  title.textContent = 'Detail Data Pelanggan';
                  inputs.forEach(input => input.setAttribute('readonly', true));
                  if (btnSimpan) btnSimpan.style.display = 'none';
              }

              riwayatPanel.classList.add('is-hidden');
              pelangganPanel.classList.add('active');
          }

        function closePelangganForm() {
            pelangganPanel.classList.remove('active');
            riwayatPanel.classList.remove('is-hidden');
        }

        document.getElementById('pelangganForm')?.addEventListener('submit', (event) => {
            const nopolInput = document.getElementById('nomorPolisi');
            const val = nopolInput.value.trim();
            const plateRegex = /^[A-Za-z]{1,2}[\s-]?\d{1,4}[\s-]?[A-Za-z]{1,3}$/;

            if (!val) {
                event.preventDefault();
                alert('Nomor polisi tidak boleh kosong.');
                return;
            }

            if (!plateRegex.test(val)) {
                event.preventDefault();
                alert('Format nomor polisi tidak valid. Contoh: AB 1234 CD atau B 1234 ABC.');
                return;
            }
        });



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

        // CUSTOM DROPDOWN SCRIPTS
        function toggleDropdown(toggle) {
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                if (dropdown !== toggle.parentElement) {
                    dropdown.classList.remove('open');
                }
            });
            toggle.parentElement.classList.toggle('open');
        }

        function selectStatus(item, value) {
            const dropdown = item.closest('.custom-dropdown');
            const form = dropdown.querySelector('.status-form-hidden');
            form.querySelector('input[name="status"]').value = value;
            form.submit();
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });

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
