<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin</title>
    {{-- Tailwind CDN - ganti dengan Vite/mix jika sudah setup --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .header {
            color: white;
            font-size: 24px;
            font-weight: 800;
            font-style: italic;
            padding-left: 36px;
            padding-top: 14px;
        }

        body {
            background: #D4D7EA;
        }

        .sidebar {
            background: linear-gradient(180deg, #5a63a8 0%, #4a5298 100%);
        }

        .nav-item {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
            display: block;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }

        .nav-item.active {
            background: white;
            color: #4a5298;
            font-weight: 700;
        }

        .main-content {
            background: #e8e9f3;
            border-radius: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border: 2px solid transparent;
            transition: border-color 0.2s, transform 0.2s;
        }

        .stat-card:hover {
            border-color: #7c84d0;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .logout-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
        }

        .logout-modal.active {
            display: flex;
        }

        .logout-modal-box {
            width: 90%;
            max-width: 380px;
            background: white;
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .logout-modal-box h2 {
            margin: 0 0 24px;
            color: #1f2937;
            font-size: 20px;
            font-weight: 800;
        }

        .logout-modal-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .modal-btn {
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
        }

        .modal-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .modal-btn.cancel {
            background: #e5e7eb;
            color: #374151;
        }

        .modal-btn.confirm {
            background: #4a5298;
            color: white;
        }
    </style>

    @stack('styles')
</head>

<body class="h-screen flex flex-col overflow-hidden">

    {{-- Top bar --}}
    <div class="w-full flex-shrink-0" style="background: #4a5298; height: 65px;">
        <div class="header">
            DM5S
        </div>
    </div>

    <div class="flex flex-1 min-h-0" style="padding: 20px; gap: 20px;">

        {{-- Sidebar --}}
        <div class="sidebar flex flex-col rounded-2xl h-full overflow-y-auto" style="width: 220px; min-height: calc(100vh - 80px); padding: 28px 16px; flex-shrink: 0;">
            {{-- Profile --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-white mx-auto mb-3 shadow-md overflow-hidden flex items-center justify-center">
                    @auth
                    <span class="text-2xl font-extrabold text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endauth
                </div>
                <p class="text-white font-bold text-base leading-tight">
                    @auth {{ auth()->user()->name }} @else Nama Akun @endauth
                </p>
                <p class="text-white/60 text-xs mt-1">
                    @auth {{ auth()->user()->email }} @else Nama Username @endauth
                </p>
            </div>

            {{-- Navigation --}}
            <nav class="flex flex-col gap-1 flex-1">
                <a href="{{ route('admin.motor') }}"
                    class="nav-item {{ request()->routeIs('admin.motor*') ? 'active' : '' }}">
                    Data Motor
                </a>
                <a href="{{ route('admin.kerusakan') }}"
                    class="nav-item {{ request()->is('admin/kerusakan*') ? 'active' : '' }}">
                    Data Kerusakan
                </a>
                <a href="{{ route('admin.gejala') }}"
                    class="nav-item {{ request()->is('admin/gejala*') ? 'active' : '' }}">
                    Data Gejala
                </a>
                <a href="{{ route('admin.solusi') }}"
                    class="nav-item {{ request()->is('admin/solusi*') ? 'active' : '' }}">
                    Data Solusi
                </a>
            </nav>

            {{-- Logout --}}
            <div class="mt-8 text-center">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
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
                const cancelLogout = document.getElementById('cancelLogout');
                const confirmLogout = document.getElementById('confirmLogout');

                document.querySelectorAll('.logout-form').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();
                        pendingLogoutForm = form;
                        logoutModal.classList.add('active');
                    });
                });

                cancelLogout.addEventListener('click', () => {
                    pendingLogoutForm = null;
                    logoutModal.classList.remove('active');
                });

                confirmLogout.addEventListener('click', () => {
                    if (pendingLogoutForm) {
                        pendingLogoutForm.submit();
                    }
                });

                logoutModal.addEventListener('click', (event) => {
                    if (event.target === logoutModal) {
                        pendingLogoutForm = null;
                        logoutModal.classList.remove('active');
                    }
                });
            </script>
        </div>

        {{-- Main Content --}}
        <div class="main-content flex-1 p-8 h-full flex flex-col overflow-hidden" style="min-height: calc(100vh - 80px);">
            <h1 class="text-2xl font-extrabold text-gray-800 mb-6">Dashboard Admin</h1>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 flex-shrink-0">
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Motor</p>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $totalMotor ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Kerusakan</p>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $totalKerusakan ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Gejala</p>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $totalGejala ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Data Solusi</p>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $totalSolusi ?? 0 }}</p>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="flex-1 min-h-0 flex flex-col">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')

    <!-- CDN SweetAlert2 (Masukkan di bagian <head> atau sebelum tag </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script untuk mendeteksi session flash dari middleware -->
    @if(session('access_blocked'))
    <script>
        Swal.fire({
            icon: 'error', // Ini akan memunculkan tanda silang (X) merah animasi
            title: 'Akses Ditolak',
            text: "{{ session('access_blocked') }}",
            confirmButtonColor: '#d33', // Tombol konfirmasi warna merah
            confirmButtonText: 'Tutup'
        });
    </script>
    @endif
</body>

</html>