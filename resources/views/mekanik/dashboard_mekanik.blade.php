<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            
            <!-- LEFT COLUMN: QUICK ACTION -->
            <div class="dashboard-card">
                <h3>Aksi Cepat</h3>
                <div class="center-box">
                    <a href="{{ route('mekanik.diagnosa') }}" class="btn-diagnosa">
                        <span class="material-icons icon">search</span>
                        <span>Mulai Diagnosa Baru</span>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: RECENT ACTIVITIES -->
            <div class="dashboard-card">
                <h3>Aktivitas Perbaikan Terbaru</h3>
                <div class="recent-list">
                    @forelse($recentRiwayats as $riwayat)
                        <div class="recent-item">
                            <div class="recent-info">
                                <span class="recent-title">{{ $riwayat->nama_pelanggan ?? 'Pelanggan Umum' }} ({{ $riwayat->nomor_polisi ?? 'Tanpa Nopol' }})</span>
                                <span class="recent-meta">Kerusakan: <strong>{{ $riwayat->nama_kerusakan }}</strong> • {{ $riwayat->created_at->diffForHumans() }}</span>
                            </div>
                            <div>
                                <span class="status-select status-{{ strtolower($riwayat->status) }}" style="padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
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
</script>

</body>
</html>
