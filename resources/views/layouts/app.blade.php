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

        body {
            background: #6b73b3;
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
    </style>
</head>

<body class="min-h-screen">

    {{-- Top bar --}}
    <div class="w-full h-10" style="background: #4a5298;"></div>

    <div class="flex min-h-screen" style="padding: 20px; gap: 20px;">

        {{-- Sidebar --}}
        <div class="sidebar flex flex-col rounded-2xl" style="width: 220px; min-height: calc(100vh - 80px); padding: 28px 16px; flex-shrink: 0;">
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
                <a href="#"
                    class="nav-item {{ request()->routeIs('admin.motor.*') ? 'active' : '' }}">
                    Data Motor
                </a>
                <a href="#"
                    class="nav-item {{ request()->routeIs('admin.gejala.*') ? 'active' : '' }}">
                    Data Gejala
                </a>
                <a href="{{ route('admin.kerusakan') }}"
                    class="nav-item {{ request()->routeIs('admin.kerusakan.*') ? 'active' : '' }}">
                    Data Kerusakan
                </a>
                <a href="#"
                    class="nav-item {{ request()->routeIs('admin.solusi.*') ? 'active' : '' }}">
                    Data Solusi
                </a>
            </nav>

            {{-- Logout --}}
            <div class="mt-8 text-center">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content flex-1 p-8" style="min-height: calc(100vh - 80px);">
            <h1 class="text-2xl font-extrabold text-gray-800 mb-6">Dashboard Admin</h1>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>