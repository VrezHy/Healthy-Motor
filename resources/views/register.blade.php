<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <input type="password" name="password" data-required>
                <div class="error-message" data-error="password" style="color: red; font-size: 12px; display: none;"></div>
                <div id="password-strength"></div>
                <ul id="password-checklist" style="font-size: 12px; padding-left: 16px; margin: 4px 0;">
                    <li id="check-length" style="color: #9e0e0e;">✗ Minimal 8 karakter</li>
                    <li id="check-upper" style="color: #9e0e0e;">✗ Mengandung huruf kapital</li>
                    <li id="check-number" style="color: #9e0e0e;">✗ Mengandung angka</li>
                    <li id="check-special" style="color: #9e0e0e;">✗ Mengandung karakter spesial (~`!@#$%^&*-+=|\:;"</>?,.)
                    </li>
                </ul>

                @error('blocked')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <button type="submit">Register</button>

            </form>

            <div class="login-text">
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

        document.getElementById('check-length').style.color = hasLength ? '#045404' : '#9e0e0e';
        document.getElementById('check-upper').style.color = hasUpper ? '#045404' : '#9e0e0e';
        document.getElementById('check-number').style.color = hasNumber ? '#045404' : '#9e0e0e';
        document.getElementById('check-special').style.color = hasSpecial ? '#045404' : '#9e0e0e';

        document.getElementById('check-length').textContent = (hasLength ? '✓' : '✗') + ' Minimal 8 karakter';
        document.getElementById('check-upper').textContent = (hasUpper ? '✓' : '✗') + ' Mengandung huruf kapital';
        document.getElementById('check-number').textContent = (hasNumber ? '✓' : '✗') + ' Mengandung angka';
        document.getElementById('check-special').textContent = (hasSpecial ? '✓' : '✗') +
            ' Mengandung karakter spesial (~`!@#$%^&*-+=|:";</>?,\.)';

            // Hitung strength
            const score = [hasLength, hasUpper, hasNumber, hasSpecial].filter(Boolean).length;

            const strengthEl = document.getElementById('password-strength');
            if (value.length === 0) {
                strengthEl.textContent = '';
            } else if (score <= 1) {
                strengthEl.textContent = 'Kekuatan password: Lemah';
                strengthEl.style.color = '#9e0e0e';
            } else if (score === 2 || score === 3) {
                strengthEl.textContent = 'Kekuatan password: Sedang';
                strengthEl.style.color = '#a97617';
            } else {
                strengthEl.textContent = 'Kekuatan password: Kuat';
                strengthEl.style.color = '#045404';
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
    </script>

</body>

</html>
