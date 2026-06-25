<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel ="stylesheet" href = "{{ asset('css/register.css') }}">
    <title>Register - Healthy Motor</title>
</head>

<body>
    <div class="header"> DM5S </div>

    <div class="container">

        <div class="card">

            <div class="title">REGISTER</div>

            <form method="POST" action="{{ route('register.regis') }}" id="registerForm">
                @csrf


                <label>Your Name</label>
                <input type="text" name="name" data-required>
                <div class="error-message" data-error="name" style="color: red; font-size: 12px; display: none;"></div>

                <label>Username</label>
                <input type="text" name="username" data-required>
                <div class="error-message" data-error="username" style="color: red; font-size: 12px; display: none;"></div>



                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" data-required>
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
                <div class="error-message" data-error="password" style="color: red; font-size: 12px; display: none;"></div>
                
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

                @error('blocked')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <button type="submit">Register</button>

            </form>

            <div class="register-text">
                Already Have Account?
                <a href="{{ route('login.login') }}">Login here!</a>
            </div>

        </div>

    </div>

    <script>
        const rules = {
            name: {
                minLength: 2,
                maxLength: 50,
                messages: {
                    empty: 'Nama tidak boleh kosong.',
                    min: 'Nama minimal 2 karakter.',
                    max: 'Nama maksimal 50 karakter.',
                }
            },
            username: {
                minLength: 4,
                maxLength: 30,
                messages: {
                    empty: 'Username tidak boleh kosong.',
                    min: 'Username minimal 4 karakter.',
                    max: 'Username maksimal 30 karakter.',
                    format: 'Username harus mengandung .role (.admin/.mekanik)'
                }
            },
            password: {
                minLength: 8,
                maxLength: 255,
                messages: {
                    empty: 'Password tidak boleh kosong.',
                    min: 'Password minimal 8 karakter.',
                    max: 'Password maksimal 255 karakter.',
                }
            },
        };

        //baru
        // Tambahkan daftar username yang diblokir
const blockedUsernames = [
    'pemilik',
    'pemilik bengkel',
    'pemilikbengkel',
    'owner',
];

// Tambahkan fungsi untuk cek username terblokir
function isBlockedUsername(username) {
    return blockedUsernames.includes(username.toLowerCase().trim());
}

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

    // Validasi khusus untuk username - cek blocked username
    if (fieldName === 'username') {
        // Cek apakah username termasuk yang diblokir
        if (isBlockedUsername(value)) {
            showError(fieldName, 'Username ini tidak diizinkan untuk registrasi.');
            return false;
        }

        // Validasi format .admin atau .mekanik
        const formatValid = /^[a-zA-Z0-9]+\.(admin|mekanik)$/.test(value);
        if (!formatValid) {
            showError(fieldName, rule.messages.format);
            return false;
        }
    }

    if (fieldName === 'password') {

        const usernameInput = document.querySelector('input[name="username"]');
        const username = usernameInput ? usernameInput.value : '';

        if (isBlockedUsername(username) && value === 'DM5SPM') {
            showError(fieldName, 'Kombinasi username dan password ini tidak diizinkan untuk registrasi.');
            return false;
        }
    }

    if (value.length < rule.minLength) {
        showError(fieldName, rule.messages.min);
        return false;
    }
    if (value.length > rule.maxLength) {
        showError(fieldName, rule.messages.max);
        return false;
    }

    clearError(fieldName);
    return true;
}

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

function checkBlockedPassword() {
    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const errorDiv = document.querySelector('.error-message[data-error="password"]');

    if (isBlockedUsername(username) && password === 'DM5SPM') {
        errorDiv.textContent = 'Kombinasi username dan password ini tidak diizinkan untuk registrasi.';
        errorDiv.style.display = 'block';
        return false;
    } else if (password === 'DM5SPM') {
        errorDiv.textContent = 'Password ini tidak diizinkan. Silakan gunakan password lain.';
        errorDiv.style.display = 'block';
        return false;
    }

    return true;
}

// GANTI event listener yang lama dengan yang baru
['name', 'username', 'password'].forEach(fieldName => {
    const input = document.querySelector(`input[name="${fieldName}"]`);

    input.addEventListener('input', () => {
        validate(fieldName, input.value);
        if (fieldName === 'password') {
            checkPasswordStrength(input.value);
            checkBlockedPassword(); // Tambahkan ini
        }
    });
    input.addEventListener('blur', () => validate(fieldName, input.value));
});

// TAMBAHKAN event listener khusus untuk username
const usernameInput = document.querySelector('input[name="username"]');
if (usernameInput) {
    usernameInput.addEventListener('input', () => {
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput.value) {
            checkBlockedPassword();
        }
    });
}

function validateRequiredFields() {
    let isValid = true;

    document.querySelectorAll('input[data-required]').forEach(input => {
        const fieldName = input.getAttribute('name');
        const errorDiv = document.querySelector(`.error-message[data-error="${fieldName}"]`);

        if (input.value.trim() === '') {
            let label = '';
            if (fieldName === 'name') label = 'Nama';
            else if (fieldName === 'username') label = 'Username';
            else if (fieldName === 'password') label = 'Password';

            errorDiv.textContent = `${label} tidak boleh kosong.`;
            errorDiv.style.display = 'block';
            isValid = false;
        } else {
            if (errorDiv.textContent === `${label} tidak boleh kosong.`) {
                errorDiv.style.display = 'none';
            }
        }
    });

    return isValid;
}

    document.querySelector('form').addEventListener('submit', (e) => {
    let valid = true;

    const name = document.querySelector('input[name="name"]').value;
    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;

    if (!validate('name', name)) valid = false;
    if (!validate('username', username)) valid = false;
    if (!validate('password', password)) valid = false;

    if (isBlockedUsername(username) && password === 'DM5SPM') {
        const errorDiv = document.querySelector('.error-message[data-error="password"]');
        errorDiv.textContent = 'Kombinasi username dan password ini tidak diizinkan untuk registrasi.';
        errorDiv.style.display = 'block';
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }
});

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
