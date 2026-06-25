<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">
    <div class="card">

        <div class="title">RESET PASSWORD</div>

        <form action="{{ route('forgot.password.reset') }}" method="POST">
            @csrf

            <label>Username</label>
            <input type="text" name="username" required>

            @error('username')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <label>Kode Pemulihan</label>
            <input type="text" name="recovery_code" required>

            @error('recovery_code')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <label>Password Baru</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="passwordInput" required>
                <span class="toggle-password" id="togglePassword">
                    <!-- Eye icon (Open) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <!-- Eye icon (Closed) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-closed" viewBox="0 0 24 24" style="display: none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </span>
            </div>

            @error('password')
                <small style="color:red;">{{ $message }}</small>
            @enderror

            <!-- Password Strength Bar -->
            <div class="strength-wrapper">
                <div class="strength-bar-container">
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                </div>
                <div id="password-strength-label"></div>
            </div>

            <!-- Password Checklist Grid -->
            <ul id="password-checklist" class="checklist-grid">
                <li id="check-length" class="checklist-item neutral">
                    <span class="checklist-icon">○</span>
                    <span class="checklist-text">Minimal 8 karakter</span>
                </li>
                <li id="check-upper" class="checklist-item neutral">
                    <span class="checklist-icon">○</span>
                    <span class="checklist-text">Huruf kapital (A-Z)</span>
                </li>
                <li id="check-number" class="checklist-item neutral">
                    <span class="checklist-icon">○</span>
                    <span class="checklist-text">Mengandung angka (0-9)</span>
                </li>
                <li id="check-special" class="checklist-item neutral">
                    <span class="checklist-icon">○</span>
                    <span class="checklist-text">Karakter spesial (~`!@#$%^&*-+=|\:;"?,.)</span>
                </li>
            </ul>

            <label>Konfirmasi Password Baru</label>
            <div class="password-wrapper">
                <input type="password" name="password_confirmation" id="passwordConfirmInput" required>
                <span class="toggle-password" id="togglePasswordConfirm">
                    <!-- Eye icon (Open) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <!-- Eye icon (Closed) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-closed" viewBox="0 0 24 24" style="display: none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </span>
            </div>

            <button type="submit">
                Reset Password
            </button>
        </form>

        <div class="register-text">
            <a href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>

    </div>
</div>

    <script>
        function checkPasswordStrength(value) {
            const hasLength = value.length >= 8;
            const hasUpper = /[A-Z]/.test(value);
            const hasNumber = /[0-9]/.test(value);
            const hasSpecial = /[~`!@#$%^&*-+=|\:;"</>?,.]/.test(value);

            const isEmpty = value.length === 0;

            function updateChecklistItem(id, isValid) {
                const el = document.getElementById(id);
                if (!el) return;
                
                el.className = 'checklist-item';
                const iconEl = el.querySelector('.checklist-icon');
                
                if (isEmpty) {
                    el.classList.add('neutral');
                    if (iconEl) iconEl.textContent = '○';
                } else if (isValid) {
                    el.classList.add('valid');
                    if (iconEl) iconEl.textContent = '✓';
                } else {
                    el.classList.add('invalid');
                    if (iconEl) iconEl.textContent = '✗';
                }
            }

            updateChecklistItem('check-length', hasLength);
            updateChecklistItem('check-upper', hasUpper);
            updateChecklistItem('check-number', hasNumber);
            updateChecklistItem('check-special', hasSpecial);

            const score = [hasLength, hasUpper, hasNumber, hasSpecial].filter(Boolean).length;
            const segments = document.querySelectorAll('.strength-segment');
            const labelEl = document.getElementById('password-strength-label');

            segments.forEach(seg => {
                seg.style.backgroundColor = '#e2e8f0';
            });

            if (isEmpty) {
                if (labelEl) labelEl.textContent = '';
            } else {
                let color = '';
                let label = '';

                switch (score) {
                    case 1:
                        color = '#ef4444';
                        label = 'Kekuatan password: Lemah';
                        break;
                    case 2:
                        color = '#f97316';
                        label = 'Kekuatan password: Cukup';
                        break;
                    case 3:
                        color = '#84cc16';
                        label = 'Kekuatan password: Kuat';
                        break;
                    case 4:
                        color = '#22c55e';
                        label = 'Kekuatan password: Sangat kuat';
                        break;
                    default:
                        color = '#ef4444';
                        label = 'Kekuatan password: Lemah';
                        break;
                }

                if (labelEl) {
                    labelEl.textContent = label;
                    labelEl.style.color = color;
                }

                const fillCount = score === 0 ? 1 : score;
                for (let i = 0; i < fillCount; i++) {
                    if (segments[i]) {
                        segments[i].style.backgroundColor = color;
                    }
                }
            }
        }

        const passwordInput = document.getElementById('passwordInput');
        if (passwordInput) {
            passwordInput.addEventListener('input', () => {
                checkPasswordStrength(passwordInput.value);
            });
        }

        function setupTogglePassword(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            if (toggle && input) {
                toggle.addEventListener('click', () => {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    const eyeOpen = toggle.querySelector('.eye-open');
                    const eyeClosed = toggle.querySelector('.eye-closed');
                    if (eyeOpen && eyeClosed) {
                        if (type === 'password') {
                            eyeOpen.style.display = 'block';
                            eyeClosed.style.display = 'none';
                        } else {
                            eyeOpen.style.display = 'none';
                            eyeClosed.style.display = 'block';
                        }
                    }
                });
            }
        }

        setupTogglePassword('togglePassword', 'passwordInput');
        setupTogglePassword('togglePasswordConfirm', 'passwordConfirmInput');
    </script>
</body>
</html>
