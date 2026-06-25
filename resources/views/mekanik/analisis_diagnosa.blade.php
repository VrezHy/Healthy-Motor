<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Diagnosa Mekanik</title>
</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">

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
            <a href="{{ route('mekanik.diagnosa') }}" class="active">
                <span class="material-icons">search</span>
                <span>Analisis Diagnosa</span>
            </a>
            <a href="{{ route('mekanik.riwayat') }}">
                <span class="material-icons">history</span>
                <span>Log Riwayat</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content content-fixed-layout">
        <!-- BREADCRUMB -->
        <nav class="breadcrumb">
            <a href="{{ route('mekanik.dashboard') }}">Mekanik</a> / <span>Analisis Diagnosa</span>
        </nav>

        <!-- DIAGNOSA LAYOUT -->
        <div class="diagnosa-layout">
            <!-- LEFT: Customer Form Card -->
            <div class="diagnosa-left">
                <div class="pelanggan-card">
                    <div class="pelanggan-card-header">
                        <span class="material-icons">person</span>
                        <h3>Data Pelanggan</h3>
                    </div>
                    <div class="pelanggan-card-body">
                        <div class="form-group">
                            <label for="inputNama">Nama Pelanggan</label>
                            <input type="text" id="inputNama" autocomplete="off" placeholder="Masukkan nama pelanggan"
                                value="{{ old('nama_pelanggan', $pelanggan['nama_pelanggan'] ?? '') }}">
                            <span class="error-message" id="errNama">@error('nama_pelanggan'){{ $message }}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="inputPolisi">Nomor Polisi</label>
                            <input type="text" id="inputPolisi" autocomplete="off" placeholder="AB 1234 CD"
                                value="{{ old('nomor_polisi', $pelanggan['nomor_polisi'] ?? '') }}">
                            <span class="error-message" id="errPolisi">@error('nomor_polisi'){{ $message }}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="inputAlamat">Alamat</label>
                            <input type="text" id="inputAlamat" autocomplete="off" placeholder="Masukkan alamat"
                                value="{{ old('alamat_pelanggan', $pelanggan['alamat_pelanggan'] ?? '') }}">
                            <span class="error-message" id="errAlamat">@error('alamat_pelanggan'){{ $message }}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="inputTelepon">Nomor Telepon</label>
                            <input type="tel" id="inputTelepon" autocomplete="off" placeholder="08xxxxxxxxxx"
                                value="{{ old('nomor_telepon', $pelanggan['nomor_telepon'] ?? '') }}">
                            <span class="error-message" id="errTelepon">@error('nomor_telepon'){{ $message }}@enderror</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Wizard & Result -->
            <div class="diagnosa-right">
                <form action="{{ route('mekanik.diagnosa.proses') }}" method="POST" class="wizard-card" id="diagnosaForm">
                    @csrf
                    <!-- Hidden fields untuk data pelanggan agar persist saat proses -->
                    <input type="hidden" name="nama_pelanggan" id="hiddenNama" value="{{ $pelanggan['nama_pelanggan'] ?? '' }}">
                    <input type="hidden" name="alamat_pelanggan" id="hiddenAlamat" value="{{ $pelanggan['alamat_pelanggan'] ?? '' }}">
                    <input type="hidden" name="nomor_polisi" id="hiddenPolisi" value="{{ $pelanggan['nomor_polisi'] ?? '' }}">
                    <input type="hidden" name="nomor_telepon" id="hiddenTelepon" value="{{ $pelanggan['nomor_telepon'] ?? '' }}">

                    <div class="wizard-card-header" style="justify-content: space-between; width: 100%; display: flex; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="material-icons">assignment</span>
                            <h3>Apakah Gejala Ini Terjadi?</h3>
                        </div>
                        <button type="button" id="selectAllBtn" class="select-all-btn" title="Pilih Semua / Batal Pilih" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 4px; color: var(--text-muted); transition: var(--transition-fast);">
                            <span class="material-icons" id="selectAllIcon" style="font-size: 24px;">check_box_outline_blank</span>
                        </button>
                    </div>

                    @if($gejalas->isEmpty())
                        <div class="wizard-empty">
                            <h2>Data gejala belum tersedia</h2>
                            <p>Tambahkan data gejala melalui dashboard admin terlebih dahulu.</p>
                        </div>
                    @else
                        <div class="symptom-list-container">
                            @foreach($gejalas as $gejala)
                                @php
                                    $nilai = isset($jawaban) ? ($jawaban[$gejala->id] ?? null) : null;
                                @endphp
                                <div class="symptom-row">
                                    <div class="symptom-row-left">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span class="question-code" style="margin-bottom: 0;">{{ $gejala->kode_gejala }}</span>
                                        </div>
                                        <span class="symptom-row-text">{{ $gejala->nama_gejala }}</span>
                                    </div>
                                    <div class="symptom-options-group">
                                        <label class="symptom-checkbox-wrapper">
                                            <input type="checkbox" name="jawaban[{{ $gejala->id }}]" value="ya" class="symptom-checkbox" {{ $nilai === 'ya' ? 'checked' : '' }}>
                                            <span class="custom-checkbox"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="wizard-actions">
                            <button type="submit" class="wizard-btn submit" id="submitBtn" style="display: inline-flex; width: 100%; justify-content: center;">Proses Diagnosa</button>
                        </div>
                    @endif
                </form>

                <div class="diagnosa-result">
                    <div class="result-heading">
                        <h2>Hasil Diagnosa</h2>
                        @isset($selectedGejalas)
                            <span>{{ $selectedGejalas->count() }} gejala dipilih</span>
                        @else
                            <span>Menunggu proses</span>
                        @endisset
                    </div>

                    <div class="diagnosa-result-scroll">
                    @if(!isset($hasil))
                        <div class="result-placeholder">
                            <h3>Belum ada hasil diagnosa</h3>
                            <p>Jawab pertanyaan gejala di sebelah kiri, lalu klik tombol Proses.</p>
                        </div>
                    @elseif($selectedGejalas->isEmpty())
                        <p class="result-message">Belum ada gejala yang dijawab "Ya", jadi hasil diagnosa belum bisa ditentukan.</p>
                    @elseif($hasil->isEmpty())
                        <p class="result-message">Gejala yang dipilih belum cocok dengan data kerusakan.</p>
                    @else
                        @foreach($hasil as $item)
                            <div class="result-item">
                                <div class="result-title">
                                    <strong>{{ $item->kerusakan->nama_kerusakan }}</strong>
                                    <span>{{ $item->persentase }}%</span>
                                </div>
                                <div class="result-bar">
                                    <span style="width: {{ $item->persentase }}%"></span>
                                </div>
                                <p>{{ $item->jumlahCocok }} dari {{ $item->totalGejala }} gejala cocok.</p>

                                @if($item->kerusakan->solusies->isNotEmpty())
                                    <div class="solution-box">
                                        <strong>Solusi</strong>
                                        @foreach($item->kerusakan->solusies as $solusi)
                                            <p>{{ $solusi->nama_solusi }}{{ $solusi->deskripsi ? ' - ' . $solusi->deskripsi : '' }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    </div>

                    @if(isset($hasil) && !$hasil->isEmpty())
                    <form id="saveDiagnosaForm" class="save-diagnosa-form">
                        @csrf
                        @foreach($selectedGejalas as $gejala)
                            <input type="hidden" name="gejala_ids[]" value="{{ $gejala->id }}">
                        @endforeach
                        <input type="hidden" name="nama_pelanggan" value="{{ $pelanggan['nama_pelanggan'] ?? '' }}">
                        <input type="hidden" name="alamat_pelanggan" value="{{ $pelanggan['alamat_pelanggan'] ?? '' }}">
                        <input type="hidden" name="nomor_polisi" value="{{ $pelanggan['nomor_polisi'] ?? '' }}">
                        <input type="hidden" name="nomor_telepon" value="{{ $pelanggan['nomor_telepon'] ?? '' }}">
                        <button type="submit" class="btn-save-riwayat" id="btnSimpan">
                            Simpan
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- TOAST NOTIFICATION SIMPAN -->
            <div class="toast-notification" id="toastSimpan" style="display: none;">
                <span class="material-icons toast-icon">check_circle</span>
                <span class="toast-message" id="toastSimpanMessage">Hasil diagnosa berhasil disimpan!</span>
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

    let pendingLogoutForm = null;
    const logoutModal = document.getElementById('logoutModal');

    // SYNC DATA PELANGGAN: visible inputs → hidden fields in wizard form
    const pelangganMap = [
        { input: 'inputNama', hidden: 'hiddenNama' },
        { input: 'inputAlamat', hidden: 'hiddenAlamat' },
        { input: 'inputPolisi', hidden: 'hiddenPolisi' },
        { input: 'inputTelepon', hidden: 'hiddenTelepon' },
    ];

    pelangganMap.forEach(({ input, hidden }) => {
        const inputEl = document.getElementById(input);
        const hiddenEl = document.getElementById(hidden);
        if (inputEl && hiddenEl) {
            inputEl.addEventListener('input', () => {
                hiddenEl.value = inputEl.value;
            });
        }
    });

    // CLIENT SIDE VALIDATION FOR DATA PELANGGAN
    const inputNama = document.getElementById('inputNama');
    const inputAlamat = document.getElementById('inputAlamat');
    const inputPolisi = document.getElementById('inputPolisi');
    const inputTelepon = document.getElementById('inputTelepon');

    const errNama = document.getElementById('errNama');
    const errAlamat = document.getElementById('errAlamat');
    const errPolisi = document.getElementById('errPolisi');
    const errTelepon = document.getElementById('errTelepon');

    function validateNama() {
        const val = inputNama.value.trim();
        if (!val) {
            errNama.textContent = 'Nama pelanggan tidak boleh kosong.';
            return false;
        }
        if (!/^[A-Za-z\s.\']+$/.test(val)) {
            errNama.textContent = 'Nama hanya boleh berisi huruf, spasi, titik, dan apostrof.';
            return false;
        }
        errNama.textContent = '';
        return true;
    }

    function validateAlamat() {
        const val = inputAlamat.value.trim();
        if (!val) {
            errAlamat.textContent = 'Alamat tidak boleh kosong.';
            return false;
        }
        errAlamat.textContent = '';
        return true;
    }

    function validatePolisi() {
        const val = inputPolisi.value.trim();
        if (!val) {
            errPolisi.textContent = 'Nomor polisi tidak boleh kosong.';
            return false;
        }
        const plateRegex = /^[A-Za-z]{1,2}[\s-]?\d{1,4}[\s-]?[A-Za-z]{1,3}$/;
        if (!plateRegex.test(val)) {
            errPolisi.textContent = 'Format nomor polisi tidak valid. Contoh: AB 1234 CD atau B 1234 ABC.';
            return false;
        }
        errPolisi.textContent = '';
        return true;
    }

    function validateTelepon() {
        const val = inputTelepon.value.trim();
        if (!val) {
            errTelepon.textContent = 'Nomor telepon tidak boleh kosong.';
            return false;
        }
        if (!/^[0-9]{10,13}$/.test(val)) {
            errTelepon.textContent = 'Nomor telepon harus berisi 10 sampai 13 angka.';
            return false;
        }
        errTelepon.textContent = '';
        return true;
    }

    inputNama?.addEventListener('input', validateNama);
    inputAlamat?.addEventListener('input', validateAlamat);
    inputPolisi?.addEventListener('input', validatePolisi);
    inputTelepon?.addEventListener('input', validateTelepon);

    function validateCustomerForm() {
        const isNamaValid = validateNama();
        const isAlamatValid = validateAlamat();
        const isPolisiValid = validatePolisi();
        const isTeleponValid = validateTelepon();
        return isNamaValid && isAlamatValid && isPolisiValid && isTeleponValid;
    }

    // SYMPTOMS CHECKBOX AND ROW TOGGLE LOGIC
    const selectAllBtn = document.getElementById('selectAllBtn');
    const selectAllIcon = document.getElementById('selectAllIcon');
    let allSelected = false;

    function updateSelectAllIcon() {
        const checkboxes = Array.from(document.querySelectorAll('.symptom-checkbox'));
        if (!checkboxes.length) return;
        const checkedCount = checkboxes.filter(cb => cb.checked).length;
        
        if (checkedCount === checkboxes.length) {
            allSelected = true;
            if (selectAllIcon) {
                selectAllIcon.textContent = 'check_box';
                selectAllIcon.style.color = 'var(--primary)';
            }
        } else {
            allSelected = false;
            if (selectAllIcon) {
                selectAllIcon.textContent = checkedCount > 0 ? 'indeterminate_check_box' : 'check_box_outline_blank';
                selectAllIcon.style.color = checkedCount > 0 ? 'var(--primary)' : 'var(--text-muted)';
            }
        }
    }

    selectAllBtn?.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.symptom-checkbox');
        // Toggle overall state
        allSelected = !allSelected;
        
        checkboxes.forEach(cb => {
            cb.checked = allSelected;
            const row = cb.closest('.symptom-row');
            if (row) {
                row.classList.toggle('active', allSelected);
            }
        });

        if (selectAllIcon) {
            selectAllIcon.textContent = allSelected ? 'check_box' : 'check_box_outline_blank';
            selectAllIcon.style.color = allSelected ? 'var(--primary)' : 'var(--text-muted)';
        }
    });

    document.querySelectorAll('.symptom-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('.symptom-row');
            if (row) {
                row.classList.toggle('active', this.checked);
            }
            updateSelectAllIcon();
        });

        // Trigger initial state
        if (cb.checked) {
            const row = cb.closest('.symptom-row');
            if (row) row.classList.add('active');
        }
    });

    // Make the entire row clickable to toggle check state
    document.querySelectorAll('.symptom-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.closest('.symptom-checkbox-wrapper')) return;
            const cb = this.querySelector('.symptom-checkbox');
            if (cb) {
                cb.checked = !cb.checked;
                cb.dispatchEvent(new Event('change'));
            }
        });
    });

    // Run initial select all icon check
    updateSelectAllIcon();

    document.getElementById('diagnosaForm')?.addEventListener('submit', (event) => {
        if (!validateCustomerForm()) {
            event.preventDefault();
        }
    });

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

    // AJAX SIMPAN DIAGNOSA + TOAST NOTIFICATION
    const saveForm = document.getElementById('saveDiagnosaForm');
    if (saveForm) {
        saveForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = document.getElementById('btnSimpan');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(saveForm);

                const response = await fetch("{{ route('mekanik.diagnosa.simpan') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    showToastSimpan(data.message || 'Hasil diagnosa berhasil disimpan!');
                    btn.textContent = '✓ Tersimpan';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.textContent = originalText;
                    alert(data.message || 'Gagal menyimpan.');
                }
            } catch (err) {
                btn.disabled = false;
                btn.textContent = originalText;
                alert('Terjadi kesalahan saat menyimpan.');
            }
        });
    }

    function showToastSimpan(message) {
        const toast = document.getElementById('toastSimpan');
        const toastMsg = document.getElementById('toastSimpanMessage');

        toastMsg.textContent = message;
        toast.style.display = 'flex';
        toast.classList.remove('hide');

        // Force reflow for animation restart
        toast.offsetHeight;
        toast.style.animation = 'none';
        toast.offsetHeight;
        toast.style.animation = '';

        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                toast.style.display = 'none';
            }, 400);
        }, 3000);
    }
</script>

</body>
</html>
