<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    @stack('styles')
</head>

<body style="font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: var(--bg-primary); height: 100vh; overflow: hidden;">

    <!-- HEADER -->
    <div class="header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span class="material-icons">menu</span>
            </button>
            <span>DM5S</span>
        </div>
    </div>

    <!-- CONTAINER -->
    <div class="container" style="display: flex; max-width: none; width: 100%; height: calc(100vh - 64px); overflow: hidden; margin: 0; padding: 0;">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- SIDEBAR -->
        <div class="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <span class="material-icons">chevron_left</span>
            </button>
            <div class="profile">
                <img src="{{ asset('assets/images/admin.png') }}" alt="Foto Profil">
                <h3>{{ auth()->check() ? auth()->user()->name : 'Admin' }}</h3>
                <small>{{ auth()->check() ? auth()->user()->username : 'admin' }}</small>
            </div>
            
            <div class="menu">
                <a href="{{ route('admin.motor') }}" class="{{ request()->routeIs('admin.motor*') || request()->is('admin/data-motor*') ? 'active' : '' }}">
                    <span class="material-icons">motorcycle</span>
                    <span>Data Motor</span>
                </a>
                <a href="{{ route('admin.kerusakan') }}" class="{{ request()->is('admin/kerusakan*') ? 'active' : '' }}">
                    <span class="material-icons">report_problem</span>
                    <span>Data Kerusakan</span>
                </a>
                <a href="{{ route('admin.gejala') }}" class="{{ request()->is('admin/gejala*') ? 'active' : '' }}">
                    <span class="material-icons">assignment</span>
                    <span>Data Gejala</span>
                </a>
                <a href="{{ route('admin.solusi') }}" class="{{ request()->is('admin/solusi*') ? 'active' : '' }}">
                    <span class="material-icons">build</span>
                    <span>Data Solusi</span>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout">Logout</button>
                </form>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content content-fixed-layout" style="display: flex; flex-direction: column; flex: 1; min-height: 0; height: 100%;">
            <!-- Page Header -->
            <div class="title" style="margin-bottom: 20px; font-size: 24px; font-weight: 800; color: var(--text-primary);">
                Dashboard Admin
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 flex-shrink-0">
                <div class="stat-card" style="border-left: 4px solid var(--primary);">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Motor</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalMotor ?? 0 }}</p>
                </div>
                <div class="stat-card" style="border-left: 4px solid var(--danger);">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Kerusakan</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalKerusakan ?? 0 }}</p>
                </div>
                <div class="stat-card" style="border-left: 4px solid var(--warning);">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Gejala</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalGejala ?? 0 }}</p>
                </div>
                <div class="stat-card" style="border-left: 4px solid var(--info);">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Solusi</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalSolusi ?? 0 }}</p>
                </div>
            </div>

            <!-- Page Content (Scrollable Container) -->
            <div style="flex: 1; min-height: 0; overflow-y: auto; padding-right: 6px;">
                @yield('content')
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

    @stack('scripts')

    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script block for shared triggers -->
    <script>
        // SIDEBAR COLLAPSE LOGIC
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            if (sidebarToggle) {
                sidebarToggle.querySelector('.material-icons').textContent = 'chevron_right';
            }
        }
        
        sidebarToggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('collapsed');
            const isCollapsed = sidebar?.classList.contains('collapsed');
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

        // LOGOUT MODAL LOGIC
        let pendingLogoutForm = null;
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');

        document.querySelectorAll('.logout-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingLogoutForm = form;
                logoutModal.classList.add('active');
            });
        });

        cancelLogout?.addEventListener('click', () => {
            pendingLogoutForm = null;
            logoutModal.classList.remove('active');
        });

        confirmLogout?.addEventListener('click', () => {
            if (pendingLogoutForm) {
                pendingLogoutForm.submit();
            }
        });

        logoutModal?.addEventListener('click', (event) => {
            if (event.target === logoutModal) {
                pendingLogoutForm = null;
                logoutModal.classList.remove('active');
            }
        });
    </script>

    <!-- Script untuk mendeteksi session flash dari middleware -->
    @if(session('access_blocked'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: "{{ session('access_blocked') }}",
            confirmButtonColor: '#d33',
            confirmButtonText: 'Tutup'
        });
    </script>
    @endif
</body>

</html>