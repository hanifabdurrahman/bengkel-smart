<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $action == 'register' ? 'Register' : 'Login' }}</title>
    <link rel="icon" href="{{ asset('images/icon url.png') }}" type="image/svg+xml">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}?v={{ time() }}">
</head>

<body>

    <a href="{{ route('home') }}" class="btn-home">
        <i class='bx bx-left-arrow-alt'></i> Kembali
    </a>

    <div class="theme-toggle" id="themeToggle">
        <i class='bx bx-moon'></i>
    </div>

    <div class="container {{ $action === 'register' ? 'active' : '' }}">

        <div class="form-box Login">
            <div class="form-header">
                <h2>Login</h2>
                <p>Masuk untuk mengelola bengkel Anda</p>
            </div>
            @include('auth.forms.login')
        </div>

        <div class="form-box Register">
            <div class="form-header">
                <h2>Register</h2>
                <p>Bergabunglah bersama kami sekarang</p>
            </div>
            @include('auth.forms.register')
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="info-content Register">
                    <h2>Sudah punya akun?</h2>
                    <p>Silakan login untuk kembali mengakses dashboard Anda.</p>
                </div>

                <div class="info-content Login">
                    <h2>Halo, Kawan!</h2>
                    <p>Belum punya akun? Daftarkan bengkel Anda dan nikmati kemudahannya.</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        const container = document.querySelector('.container');
        const registerLink = document.querySelector('.register-link');
        const loginLink = document.querySelector('.login-link');

        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');
        const body = document.body;

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            body.classList.add('light-mode');
            themeIcon.classList.replace('bx-moon', 'bx-sun');
        }
        themeToggle.onclick = () => {
            body.classList.toggle('light-mode');
            if (body.classList.contains('light-mode')) {
                themeIcon.classList.replace('bx-moon', 'bx-sun');
                localStorage.setItem('theme', 'light');
            } else {
                themeIcon.classList.replace('bx-sun', 'bx-moon');
                localStorage.setItem('theme', 'dark');
            }
        }

        if (registerLink) {
            registerLink.onclick = (e) => {
                e.preventDefault();
                container.classList.add('active');
                window.history.pushState({
                    path: 'register'
                }, '', '{{ route('register') }}');
                document.title = 'Register';
            }
        }

        if (loginLink) {
            loginLink.onclick = (e) => {
                e.preventDefault();
                container.classList.remove('active');
                window.history.pushState({
                    path: 'login'
                }, '', '{{ route('login') }}');
                document.title = 'Login';
            }
        }

        function setupPasswordToggle(toggleId, inputId) {
            const toggleIcon = document.getElementById(toggleId);
            const passwordInput = document.getElementById(inputId);
            if (toggleIcon && passwordInput) {
                toggleIcon.onclick = function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('bx-hide');
                    this.classList.toggle('bx-show');
                    this.style.color = type === 'text' ? "var(--primary)" : "";
                };
            }
        }

        setupPasswordToggle('togglePassword', 'passwordInput');
        setupPasswordToggle('togglePasswordReg', 'passwordReg');
        setupPasswordToggle('togglePasswordConfirm', 'passwordConfirm');

        window.onpopstate = (e) => {
            if (window.location.pathname.includes('register')) {
                container.classList.add('active');
                document.title = 'Register';
            } else {
                container.classList.remove('active');
                document.title = 'Login';
            }
        };

        @if ($errors->any())
            @if (old('workshop_name') || $action === 'register')
                container.classList.add('active');
                document.title = 'Register';
            @endif
        @endif
    </script>
</body>

</html>
