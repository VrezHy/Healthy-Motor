<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Mekanik — Healthy Motor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #2d6a4f;
        }

        .sidebar {
            background: linear-gradient(180deg, #40916c 0%, #2d6a4f 100%);
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
            color: #2d6a4f;
            font-weight: 700;
        }

        .main-content {
            background: #e8f5e9;
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
            border-color: #52b788;
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
            width: 100%;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .badge-admin {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            color: white;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body class="min-h-screen">

    {{-- Top bar --}}
    <div class="w-full h-10" style="background: #1b4332;"></div>

    <div class="flex min-h-screen" style="padding: 20px; gap: 20px;">

        {{-- Sidebar --}}
        <div class="sidebar flex flex-col rounded-2xl" style="width: 220px; min-height: calc(100vh - 80px); padding: 28px 16px; flex-shrink: 0;">
            {{-- Profile --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-white mx-auto mb-3 shadow-md overflow-hidden flex items-center justify-center">
                    <span class="text-2xl font-extrabold" style="color: #2d6a4f;">M</span>
                </div>
                <p class="text-white font-bold text-base leading-tight">Mekanik</p>
                <p class="text-white/60 text-xs mt-1">Dashboard Mekanik</p>
                <a href="{{ route('admin.kerusakan') }}" class="badge-admin">→ Dashboard Admin</a>
            </div>

            {{-- Navigation --}}
            <nav class="flex flex-col gap-1 flex-1">
                <a href="{{ route('mekanik.riwayat') }}"
                    class="nav-item {{ request()->is('mekanik/riwayat*') ? 'active' : '' }}">
                    📋 Log Riwayat Motor
                </a>
            </nav>

            {{-- Logout --}}
            <div class="mt-8 text-center">
                <form action="{{ route('mekanik.riwayat') }}" method="GET">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content flex-1 p-8" style="min-height: calc(100vh - 80px);">
            <h1 class="text-2xl font-extrabold text-gray-800 mb-6">Dashboard Mekanik</h1>

            {{-- Stat Card --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Total Log Motor</p>
                    <p class="text-4xl font-extrabold text-gray-800">{{ $totalMotor ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Status Selesai</p>
                    <p class="text-4xl font-extrabold" style="color: #2d6a4f;">
                        {{ isset($motors) ? $motors->where('status','selesai')->count() : 0 }}
                    </p>
                </div>
                <div class="stat-card">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Status Pending</p>
                    <p class="text-4xl font-extrabold text-yellow-500">
                        {{ isset($motors) ? $motors->where('status','pending')->count() : 0 }}
                    </p>
                </div>
            </div>

            {{-- Page Content --}}
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
