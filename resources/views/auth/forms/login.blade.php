<form action="{{ route('login.submit') }}" method="POST">
    @csrf
    @if (request('redirect_to'))
        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
    @endif

    <div class="input-box animation" style="--D:1; --S:22">
        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off">
        <label>Email Address</label>
        <i class='bx bxs-envelope'></i>
    </div>
    @error('email')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div class="input-box animation" style="--D:2; --S:23">
        <input type="password" name="password" id="passwordInput" required>
        <label>Password</label>
        <i class='bx bx-hide' id="togglePassword" style="cursor: pointer; z-index: 10;"></i>
    </div>
    @error('password')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div class="remember-box animation" style="--D:3; --S:24; margin: 20px 0 10px;">
        <label
            style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); cursor: pointer;">
            <input type="checkbox" name="remember" style="width: 15px; height: 15px; accent-color: var(--primary);">
            Ingat Saya
        </label>
    </div>

    <div class="input-box animation" style="--D:3; --S:24; margin-top: 10px;">
        <button class="btn" type="submit">Login</button>
    </div>

    {{-- BAGIAN INI AKAN OTOMATIS BERJARAK 30PX SESUAI CSS --}}
    <div class="regi-link animation" style="--D:4; --S:25">
        <p>Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar Disini</a></p>
    </div>
</form>
