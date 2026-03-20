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

            <form method="POST" action="{{ route('register.regis') }}">
                @csrf


                <label>Your Name</label>
                <input type="text" name="name" required>

                <label>Username</label>
                <input type="text" name="username" required>

                @error('blocked')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

                <label>Password</label>
                <input type="password" name="password" required>

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
                noSpace: true,
                messages: {
                    empty: 'Username tidak boleh kosong.',
                    min: 'Username minimal 4 karakter.',
                    max: 'Username maksimal 30 karakter.',
                    format: 'Username harus memiliki .role'
                }
            },
            password: {
                minLength: 6,
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

        // Pasang event listener ke semua input
        ['name', 'username', 'password'].forEach(fieldName => {
            const input = document.querySelector(`input[name="${fieldName}"]`);

            input.addEventListener('input', () => validate(fieldName, input.value));
            input.addEventListener('blur', () => validate(fieldName, input.value));
        });

        // Cegah submit kalau masih ada error
        document.querySelector('form').addEventListener('submit', (e) => {
            let valid = true;

            ['name', 'username', 'password'].forEach(fieldName => {
                const input = document.querySelector(`input[name="${fieldName}"]`);
                if (!validate(fieldName, input.value)) valid = false;
            });

            if (!valid) e.preventDefault();
        });
    </script>

</body>

</html>
