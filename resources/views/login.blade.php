<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthy Motor</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>

    <div class="header">
        DM5S
    </div>

    <div class="container">

        @if (session('success'))
        <div id="successPopup"
            style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            z-index: 9999;
            border-left: 5px solid #6f78b3;
        ">

            <div style="font-size: 45px; color: green; margin-bottom: 10px;">✓</div>

            <div style="font-size: 18px; font-weight: bold; margin-bottom: 8px;">
                {{ session('success') }}
            </div>

            @if (session('recovery_code'))
            <div style="margin-top:15px;">
                <strong>Kode Pemulihan:</strong>
                <br>

                <span id="recoveryCode"
                    style="
                        font-size:18px;
                        color:#d35400;
                        font-weight:bold;
                    ">
                    {{ session('recovery_code') }}
                </span>

                <br>

                <small>Simpan kode ini untuk reset password.</small>

                <br><br>

                <div class="display: flex; flex-direction: column; gap: 12px;">
                    <button type="button" onclick="copyRecoveryCode()">
                        Salin Kode
                    </button>

                    <button type="button" onclick="closePopup()" style="margin-top: 12px;">
                        Saya Sudah Menyimpan
                    </button>
                </div>

            </div>
            @endif

        </div>

        @if (session('recovery_code'))
        <script>
            function copyRecoveryCode() {
                const code = document.getElementById('recoveryCode').innerText;

                navigator.clipboard.writeText(code)
                    .then(() => {
                        alert('Kode pemulihan berhasil disalin');
                    });
            }

            function closePopup() {
                document.getElementById('successPopup').style.display = 'none';
            }
        </script>
        @else
        <script>
            setTimeout(() => {
                const popup = document.getElementById('successPopup');

                if (popup) {
                    popup.style.display = 'none';
                }
            }, 4000);
        </script>
        @endif

        @endif

        <div class="card">

            <div class="title">LOGIN</div>

            <form action="{{ route('login.login') }}" method="POST">
                @csrf

                <label>Username</label>
                <input type="text" name="username" required>
                @error('username')
                <small style="color: red;">{{ $message }}</small>
                @enderror

                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" required>
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
                <small style="color: red;">{{ $message }}</small>
                @enderror

                <div class="forgot-password">
                    <a href="{{ route('forgot.password') }}">
                        Lupa Password?
                    </a>
                </div>
                <button type="submit">Login</button>

            </form>

            <div class="register-text">
                Not Registered?
                <a href="{{ route('register.regis') }}">Sign up!</a>
            </div>

        </div>

    </div>

    <script>
        const rules = {
            username: {
                messages: {
                    empty: 'Username tidak boleh kosong.',
                }
            },
            password: {
                messages: {
                    empty: 'Password tidak boleh kosong.',
                }
            },
        };

        function showError(fieldName, message) {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            let errorEl = input.nextElementSibling;

            if (!errorEl || !errorEl.classList.contains('js-error')) {
                errorEl = document.createElement('small');
                errorEl.classList.add('js-error');
                errorEl.style.color = 'red';
                input.insertAdjacentElement('afterend', errorEl);
            }

            errorEl.textContent = message;
        }

        function clearError(fieldName) {
            const input = document.querySelector(`input[name="${fieldName}"]`);
            const errorEl = input.nextElementSibling;

            if (errorEl && errorEl.classList.contains('js-error')) {
                errorEl.textContent = '';
            }
        }

        function validate(fieldName, value) {
            const rule = rules[fieldName];
            if (!rule) return true;

            if (value.trim() === '') {
                showError(fieldName, rule.messages.empty);
                return false;
            }

            clearError(fieldName);
            return true;
        }

        ['username', 'password'].forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);

            input.addEventListener('input', () => validate(fieldName, input.value));
            input.addEventListener('blur', () => validate(fieldName, input.value));
        });

        document.querySelector('form').addEventListener('submit', (e) => {
            let valid = true;

            ['username', 'password'].forEach(fieldName => {
                const input = document.querySelector(`input[name="${fieldName}"]`);
                if (!validate(fieldName, input.value)) valid = false;
            });

            if (!valid) e.preventDefault();
        });

        function copyRecoveryCode() {
            const code = document.getElementById('recoveryCode').innerText;

            navigator.clipboard.writeText(code)
                .then(() => {
                    alert('Kode pemulihan berhasil disalin');
                });
        }

        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
        }

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.querySelector('input[name="password"]');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const eyeOpen = togglePassword.querySelector('.eye-open');
                const eyeClosed = togglePassword.querySelector('.eye-closed');
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
    </script>


</body>

</html>