<form action="{{ route('register.submit') }}" method="POST">
    @csrf

    <div class="input-box animation" style="--li:18; --S:1">
        <input type="text" name="workshop_name" value="{{ old('workshop_name') }}" required autocomplete="off">
        <label>Nama Bengkel</label>
        <i class='bx bxs-store'></i>
    </div>
    @error('workshop_name')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div class="input-box animation" style="--li:19; --S:2">
        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off">
        <label>Email</label>
        <i class='bx bxs-envelope'></i>
    </div>
    @error('email')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div class="input-box animation" style="--li:20; --S:3">
        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="off">
        <label>No. WhatsApp</label>
        <i class='bx bxs-phone'></i>
    </div>
    @error('phone_number')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div class="input-box animation" style="--li:21; --S:4">
        <input type="text" name="address" value="{{ old('address') }}" required autocomplete="off">
        <label>Alamat Lengkap</label>
        <i class='bx bxs-map'></i>
    </div>
    @error('address')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    <div style="display: flex; gap: 10px;">
        <div class="input-box animation" style="--li:22; --S:5">
            <input type="password" name="password" id="passwordReg" required>
            <label>Password</label>
            <i class='bx bx-hide' id="togglePasswordReg" style="cursor: pointer;"></i>
        </div>
        <div class="input-box animation" style="--li:23; --S:6">
            <input type="password" name="password_confirmation" id="passwordConfirm" required>
            <label>Ulangi</label>
            <i class='bx bx-hide' id="togglePasswordConfirm" style="cursor: pointer;"></i>
        </div>
    </div>
    @error('password')
        <span class="alert-error">{{ $message }}</span>
    @enderror

    {{-- Tombol dengan wrapper biasa, CSS yang atur margin --}}
    <div class="animation" style="--li:24; --S:7;">
        <button class="btn" type="submit">Daftar Sekarang</button>
    </div>

    {{-- Link Login dengan jarak aman --}}
    <div class="regi-link animation" style="--li:25; --S:8;">
        <p>Sudah punya akun? <a href="{{ route('login') }}" class="login-link">Login</a></p>
    </div>
</form>
