<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DM5S - Dashboard Mekanik</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #7B84B8;
            --bg-sidebar: #7B84B8;
            --bg-content: #9099BF;
            --bg-header: #6168A0;
            --btn-active: #4A52A3;
            --btn-hover: rgba(255,255,255,0.15);
            --text-white: #ffffff;
            --text-muted: rgba(255,255,255,0.75);
            --avatar-bg: #C8CDDF;
            --logout-bg: rgba(255,255,255,0.2);
            --logout-border: rgba(255,255,255,0.35);
            --accent-btn: #4A52A3;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: var(--bg-primary);
        }

        /* ── HEADER ── */
        .header {
            background: var(--bg-header);
            height: 52px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(0,0,0,0.12);
        }

        .header-logo {
            font-family: 'Rajdhani', sans-serif;
            font-size: 26px;
            font-weight: 700;
            font-style: italic;
            color: var(--text-white);
            letter-spacing: 1px;
        }

        /* ── BODY LAYOUT ── */
        .body-layout {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 200px;
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 16px 24px;
            border-right: 1px solid rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .avatar-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: var(--avatar-bg);
            margin-bottom: 14px;
            border: 3px solid rgba(255,255,255,0.3);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-wrap svg {
            width: 52px;
            height: 52px;
            opacity: 0.5;
        }

        .user-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-white);
            text-align: center;
            margin-bottom: 2px;
        }

        .user-username {
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 28px;
        }

        .nav-menu {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            width: 100%;
            padding: 9px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
            text-align: left;
            background: none;
            border: none;
            font-family: inherit;
            letter-spacing: 0.01em;
        }

        .nav-item:hover {
            background: var(--btn-hover);
            color: var(--text-white);
        }

        .nav-item.active {
            background: var(--btn-active);
            color: var(--text-white);
            font-weight: 600;
        }

        .sidebar-spacer {
            flex: 1;
        }

        .logout-btn {
            width: 100%;
            padding: 9px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-white);
            cursor: pointer;
            background: var(--logout-bg);
            border: 1px solid var(--logout-border);
            font-family: inherit;
            text-align: center;
            transition: background 0.18s;
            letter-spacing: 0.02em;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.28);
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            flex: 1;
            background: var(--bg-content);
            display: flex;
            flex-direction: column;
            padding: 32px 36px;
            overflow-y: auto;
        }

        .page-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-white);
            margin-bottom: 0;
            letter-spacing: 0.01em;
        }

        .content-center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .diagnosa-btn {
            padding: 14px 48px;
            border-radius: 28px;
            background: var(--accent-btn);
            color: var(--text-white);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.18s, transform 0.12s;
            line-height: 1.4;
            text-align: center;
        }

        .diagnosa-btn:hover {
            background: #3a42a0;
            transform: translateY(-1px);
        }

        .diagnosa-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <span class="header-logo">DM5S</span>
    </header>

    <!-- BODY -->
    <div class="body-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="avatar-wrap">
                <!-- Placeholder avatar icon -->
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="8" r="4" fill="white"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" fill="white"/>
                </svg>
            </div>

            <div class="user-name">Nama Akun</div>
            <div class="user-username">Nama Username</div>

            <nav class="nav-menu">
                <button class="nav-item active" onclick="setActive(this)">Analisis Diagnosa</button>
                <button class="nav-item" onclick="setActive(this)">Log Riwayat</button>
            </nav>

            <div class="sidebar-spacer"></div>

            <button class="logout-btn">Logout</button>
        </aside>

        <!-- MAIN -->
        <main class="main-content">
            <h1 class="page-title">Dashboard Mekanik</h1>

            <div class="content-center">
                <button class="diagnosa-btn">MULAI<br>DIAGNOSA</button>
            </div>
        </main>

    </div>

    <script>
        function setActive(el) {
            document.querySelectorAll('.nav-item').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
        }
    </script>
</body>
</html>