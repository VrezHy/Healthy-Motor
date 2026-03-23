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
                <input type="text" name="name" required>

                <label>Username</label>
                <input type="text" name="username" required>



                <label>Password</label>
                <input type="password" name="password" required>
                <div id="password-strength"></div>
                <ul id="password-checklist" style="font-size: 12px; padding-left: 16px; margin: 4px 0;">
                    <li id="check-length" style="color: red;">✗ Minimal 8 karakter</li>
                    <li id="check-upper" style="color: red;">✗ Mengandung huruf kapital</li>
                    <li id="check-number" style="color: red;">✗ Mengandung angka</li>
                    <li id="check-special" style="color: red;">✗ Mengandung karakter spesial (~`!@#$%^&*-+=|\:;"</>?,.)
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
                    format: 'Username harus memiliki .role'
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
            if (fieldName === 'username') {
                const formatValid = /^[a-zA-Z0-9]+\.(admin|mekanik)$/.test(value);
                if (!formatValid) {
                    showError(fieldName, rule.messages.format);
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

        // Update checklist
        document.getElementById('check-length').style.color = hasLength ? 'green' : 'red';
        document.getElementById('check-upper').style.color = hasUpper ? 'green' : 'red';
        document.getElementById('check-number').style.color = hasNumber ? 'green' : 'red';
        document.getElementById('check-special').style.color = hasSpecial ? 'green' : 'red';

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
                strengthEl.style.color = 'red';
            } else if (score === 2 || score === 3) {
                strengthEl.textContent = 'Kekuatan password: Sedang';
                strengthEl.style.color = 'orange';
            } else {
                strengthEl.textContent = 'Kekuatan password: Kuat';
                strengthEl.style.color = 'green';
            }
        }

        // Pasang event listener ke semua input
        ['name', 'username', 'password'].forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);

            input.addEventListener('input', () => {
                validate(fieldName, input.value);
                if (fieldName === 'password') checkPasswordStrength(input.value);
            });
            input.addEventListener('blur', () => validate(fieldName, input.value));
        });

        // Cegah submit kalau masih ada error
        document.querySelector('form').addEventListener('submit', (e) => {
            let valid = true;

            ['name', 'username', 'password'].forEach(fieldName => {
                const input = document.querySelector(`input[name="${fieldName}"]`);
                const result = validate(fieldName, input.value);
                console.log(fieldName, ':', input.value, '→ valid:', result);
                if (!result) valid = false;
            });

            console.log('overall valid:', valid);

            if (!valid) {
                console.log('Form Tidak Submit');
                e.preventDefault();
            } else {
                console.log('Form Submit');
            }
        });
    </script>



</body>

</html>
