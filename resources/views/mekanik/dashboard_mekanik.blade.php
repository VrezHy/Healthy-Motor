<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard Mekanik - DM5S</title>
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
            <a href="{{ route('mekanik.dashboard') }}" class="active">
                <span class="material-icons">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('mekanik.diagnosa') }}">
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
            <a href="{{ route('mekanik.dashboard') }}">Mekanik</a> / <span>Dashboard</span>
        </nav>

        <!-- STATS CARDS -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-header">
                    <span class="stat-title">Total Diagnosa</span>
                    <span class="material-icons text-primary">analytics</span>
                </div>
                <span class="stat-number">{{ $totalLogs }}</span>
            </div>
            
            <div class="stat-card success">
                <div class="stat-header">
                    <span class="stat-title">Selesai (Done)</span>
                    <span class="material-icons text-success">check_circle</span>
                </div>
                <span class="stat-number">{{ $totalDone }}</span>
            </div>

            <div class="stat-card info">
                <div class="stat-header">
                    <span class="stat-title">Sedang Diperbaiki (Aktif)</span>
                    <span class="material-icons text-info">handyman</span>
                </div>
                <span class="stat-number">{{ $totalActive }}</span>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <span class="stat-title">Draft Diagnosa</span>
                    <span class="material-icons text-warning">edit_note</span>
                </div>
                <span class="stat-number">{{ $totalDraft }}</span>
            </div>
        </div>

        <!-- DASHBOARD GRID -->
        <div class="dashboard-grid">
            
            <!-- LEFT COLUMN: QUICK ACTION & CHART -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- QUICK ACTION -->
                <div class="dashboard-card" style="padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                    <h3 style="margin: 0; font-size: 15px;">Aksi Cepat</h3>
                    <a href="{{ route('mekanik.diagnosa') }}" class="btn-diagnosa" style="padding: 8px 14px; font-size: 12px; margin: 0; width: auto; flex-shrink: 0; white-space: nowrap;">
                        <span class="material-icons icon" style="font-size: 15px;">search</span>
                        <span>Mulai Diagnosa Baru</span>
                    </a>
                </div>

                <!-- CHART CARD -->
                <div class="dashboard-card" style="padding: 20px 20px 16px;">
                    <h3 style="font-size: 15px; margin-bottom: 8px;">Statistik Status Diagnosa</h3>
                    <div style="position: relative; margin: 10px auto 5px; height: 140px; width: 100%; max-width: 140px; display: flex; align-items: center; justify-content: center;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <!-- Legend or details -->
                    <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 12px; font-size: 12px; border-top: 1px solid var(--border-color); padding-top: 10px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--success); display: inline-block;"></span>
                                <span style="font-weight: 500; color: var(--text-secondary);">Selesai (Done)</span>
                            </div>
                            <strong style="color: var(--text-primary);">{{ $totalDone }}</strong>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--primary); display: inline-block;"></span>
                                <span style="font-weight: 500; color: var(--text-secondary);">Sedang Diperbaiki (Aktif)</span>
                            </div>
                            <strong style="color: var(--text-primary);">{{ $totalActive }}</strong>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--warning); display: inline-block;"></span>
                                <span style="font-weight: 500; color: var(--text-secondary);">Draft Diagnosa</span>
                            </div>
                            <strong style="color: var(--text-primary);">{{ $totalDraft }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: RECENT ACTIVITIES -->
            <div class="dashboard-card" style="display: flex; flex-direction: column;">
                <div class="recent-header-wrap" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 16px;">
                    <h3 style="margin: 0; font-size: 18px;">Aktivitas Perbaikan Terbaru</h3>
                    <input type="text" id="recentSearchInput" placeholder="Cari aktivitas..." style="padding: 8px 16px; font-size: 13px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); outline: none; transition: var(--transition-fast); width: 180px; background-color: var(--bg-primary);">
                </div>
                <div class="recent-list" style="flex: 1;">
                    @forelse($recentRiwayats as $riwayat)
                        <div class="recent-item" style="cursor: pointer;" 
                             data-riwayat='@json($riwayat)'
                             data-waktu="{{ $riwayat->created_at->format('d M Y, H:i') }}"
                             onclick="openActivityDetail(this)">
                            <div class="recent-info">
                                <span class="recent-title">{{ $riwayat->nama_pelanggan ?? 'Pelanggan Umum' }} ({{ $riwayat->nomor_polisi ?? 'Tanpa Nopol' }})</span>
                                <span class="recent-meta">Kerusakan: <strong>{{ $riwayat->nama_kerusakan }}</strong> • {{ $riwayat->created_at->diffForHumans() }}</span>
                            </div>
                            <div>
                                <span class="status-select status-{{ strtolower($riwayat->status) }}" style="padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block;">
                                    {{ $riwayat->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="result-placeholder" style="min-height: 150px; padding: 20px;">
                            <p>Belum ada aktivitas perbaikan terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

<!-- LOGOUT MODAL -->
<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-box">
        <h2>Yakin Keluar Dari Aplikasi?</h2>
        <div class="logout-modal-actions">
            <button type="button" class="modal-btn cancel" id="cancelLogout">batal</button>
            <button type="button" class="modal-btn confirm" id="confirmLogout">keluar</button>
        </div>
    </div>
</div>

<!-- ACTIVITY DETAIL MODAL -->
<div class="detail-modal" id="activityDetailModal">
    <div class="detail-modal-box">
        <button type="button" class="detail-modal-close" id="closeActivityModal">×</button>
        <h2>Detail Perbaikan</h2>
        
        <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
            <div class="detail-row">
                <span class="detail-label">Nama Pelanggan</span>
                <strong class="detail-value" id="modalNama">-</strong>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nomor Polisi</span>
                <span class="detail-value" id="modalPolisi">-</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Alamat</span>
                <span class="detail-value" id="modalAlamat">-</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nomor Telepon</span>
                <span class="detail-value" id="modalTelepon">-</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Waktu Diagnosa</span>
                <span class="detail-value" id="modalWaktu">-</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Perbaikan</span>
                <span id="modalStatus" style="padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block;">-</span>
            </div>
        </div>

        <div style="margin-top: 24px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">Hasil Analisis</h4>
            <div style="background-color: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong id="modalKerusakan" style="font-size: 15px; color: var(--text-primary);">-</strong>
                    <span id="modalPersentase" style="background-color: var(--primary); color: white; border-radius: 999px; font-size: 12px; font-weight: 700; padding: 4px 10px;">-%</span>
                </div>
                <p id="modalGejalaCocok" style="font-size: 12px; color: var(--text-muted); margin: 0;"></p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Gejala Terpilih</h4>
            <div id="modalGejalaList" style="max-height: 120px; overflow-y: auto; background-color: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 10px 14px; font-size: 13px; display: flex; flex-direction: column; gap: 6px;">
                <!-- Gejala list will be dynamically populated -->
            </div>
        </div>

        <div style="margin-top: 20px; margin-bottom: 10px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Solusi</h4>
            <div id="modalSolusiList" style="max-height: 120px; overflow-y: auto; background-color: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 10px 14px; font-size: 13px; display: flex; flex-direction: column; gap: 6px;">
                <!-- Solusi list will be dynamically populated -->
            </div>
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

    // CHART.JS INITIALIZATION
    const ctx = document.getElementById('statusChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Selesai (Done)', 'Sedang Diperbaiki (Aktif)', 'Draft Diagnosa'],
                datasets: [{
                    data: [{{ $totalDone }}, {{ $totalActive }}, {{ $totalDraft }}],
                    backgroundColor: ['#10b981', '#453bcf', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                },
                cutout: '72%'
            }
        });
    }

    // RECENT ACTIVITY DETAIL MODAL LOGIC
    const activityDetailModal = document.getElementById('activityDetailModal');
    const closeActivityModal = document.getElementById('closeActivityModal');

    function openActivityDetail(element) {
        const riwayat = JSON.parse(element.getAttribute('data-riwayat'));
        const waktuFormatted = element.getAttribute('data-waktu');

        document.getElementById('modalNama').textContent = riwayat.nama_pelanggan || 'Pelanggan Umum';
        document.getElementById('modalPolisi').textContent = riwayat.nomor_polisi || 'Tanpa Nopol';
        document.getElementById('modalAlamat').textContent = riwayat.alamat_pelanggan || '-';
        document.getElementById('modalTelepon').textContent = riwayat.nomor_telepon || '-';
        document.getElementById('modalWaktu').textContent = waktuFormatted;
        
        // Status element style
        const statusEl = document.getElementById('modalStatus');
        statusEl.textContent = riwayat.status;
        statusEl.className = 'status-' + riwayat.status.toLowerCase();
        if (riwayat.status.toLowerCase() === 'draft') {
            statusEl.style.backgroundColor = '#f1f5f9';
            statusEl.style.color = '#475569';
        } else if (riwayat.status.toLowerCase() === 'aktif') {
            statusEl.style.backgroundColor = 'var(--success-bg)';
            statusEl.style.color = 'var(--success-text)';
        } else {
            statusEl.style.backgroundColor = 'var(--info-bg)';
            statusEl.style.color = 'var(--info-text)';
        }

        // Diagnosis summary
        document.getElementById('modalKerusakan').textContent = riwayat.nama_kerusakan;
        document.getElementById('modalPersentase').textContent = riwayat.persentase + '%';
        document.getElementById('modalGejalaCocok').textContent = riwayat.jumlah_gejala_cocok + ' dari ' + riwayat.total_gejala + ' gejala cocok.';

        // Gejala list
        const gejalaListEl = document.getElementById('modalGejalaList');
        gejalaListEl.innerHTML = '';
        if (riwayat.gejala_terpilih && riwayat.gejala_terpilih.length > 0) {
            riwayat.gejala_terpilih.forEach(gejala => {
                const item = document.createElement('div');
                item.style.paddingBottom = '4px';
                item.style.borderBottom = '1px dashed var(--border-color)';
                item.innerHTML = `<span style="font-weight:700; color:var(--primary); margin-right:6px;">${gejala.kode_gejala}</span> ${gejala.nama_gejala}`;
                gejalaListEl.appendChild(item);
            });
        } else {
            gejalaListEl.innerHTML = '<span style="color:var(--text-muted); font-style:italic;">Tidak ada data gejala</span>';
        }

        // Solusi list
        const solusiListEl = document.getElementById('modalSolusiList');
        solusiListEl.innerHTML = '';
        if (riwayat.solusi && riwayat.solusi.length > 0) {
            riwayat.solusi.forEach(solusi => {
                const item = document.createElement('div');
                item.style.paddingBottom = '4px';
                item.style.borderBottom = '1px dashed var(--border-color)';
                item.innerHTML = `<strong>${solusi.nama_solusi}</strong>${solusi.deskripsi ? ' - ' + solusi.deskripsi : ''}`;
                solusiListEl.appendChild(item);
            });
        } else {
            solusiListEl.innerHTML = '<span style="color:var(--text-muted); font-style:italic;">Belum ada solusi</span>';
        }

        activityDetailModal.style.display = 'flex';
    }

    if (closeActivityModal) {
        closeActivityModal.addEventListener('click', () => {
            activityDetailModal.style.display = 'none';
        });
    }

    if (activityDetailModal) {
        activityDetailModal.addEventListener('click', (e) => {
            if (e.target === activityDetailModal) {
                activityDetailModal.style.display = 'none';
            }
        });
    }

    // CLIENT SIDE SEARCH FOR RECENT ACTIVITIES
    const recentSearchInput = document.getElementById('recentSearchInput');
    recentSearchInput?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.recent-list .recent-item');
        
        items.forEach(item => {
            const title = item.querySelector('.recent-title')?.textContent.toLowerCase() || '';
            const meta = item.querySelector('.recent-meta')?.textContent.toLowerCase() || '';
            
            if (title.includes(query) || meta.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
