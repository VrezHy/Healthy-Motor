<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Healthy Motor</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>

    <div class="header">
        DM5S
    </div>

    <div class="container">

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
                <input type="password" name="password" required>
                @error('password')
                    <small style="color: red;">{{ $message }}</small>
                @enderror

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
    </script>

</body>

</html>
