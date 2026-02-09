<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TRANSKOM — Daftar Peminjam</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --pupr-blue: #002B7F;
            --pupr-yellow: #FFD700;
            --ink: #0f172a;
            --muted: #64748b;
            --card: #ffffff;
            --border: rgba(15, 23, 42, .10);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 10% 10%, rgba(0, 43, 127, .12), transparent 60%),
                radial-gradient(900px 500px at 90% 20%, rgba(255, 215, 0, .10), transparent 55%),
                linear-gradient(180deg, #f8fafc, #f1f5f9);
        }

        .auth-wrap {
            width: 100%;
            max-width: 1000px;
        }

        .auth-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: var(--card);
            box-shadow: 0 10px 35px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .auth-left {
            padding: 28px;
            background: linear-gradient(135deg, rgba(0, 43, 127, .95), rgba(0, 43, 127, .75));
            color: #fff;
            position: relative;
        }

        /* highlight putih halus (bukan glow kuning) */
        .auth-left::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .08), transparent 40%);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .brand img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 10px;
            background: #fff;
            padding: 6px;
        }

        .brand .app {
            font-weight: 600;
            font-size: 14px;
            opacity: .92;
            line-height: 1.1;
        }

        .brand .org {
            font-weight: 600;
            font-size: 16px;
            line-height: 1.1;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
            font-size: 12px;
            position: relative;
            z-index: 2;
        }

        .role-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--pupr-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, .18);
        }

        .auth-left h1 {
            margin-top: 18px;
            font-size: 20px;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }

        .auth-left p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, .80);
            font-size: 13px;
            line-height: 1.55;
            position: relative;
            z-index: 2;
        }

        .auth-right {
            padding: 28px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: var(--ink);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid rgba(15, 23, 42, .12);
        }

        .form-control:focus {
            border-color: rgba(0, 43, 127, .45);
            box-shadow: 0 0 0 .25rem rgba(0, 43, 127, .10);
        }

        .btn-primary {
            background: var(--pupr-blue);
            border-color: var(--pupr-blue);
            border-radius: 12px;
            padding: 11px 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #001f5c;
            border-color: #001f5c;
        }

        .subtle-link {
            color: var(--pupr-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }

        .subtle-link:hover {
            color: #001f5c;
            text-decoration: underline;
        }

        .helper {
            color: var(--muted);
            font-size: 12px;
        }

        /* input group button */
        .btn-eye {
            border-radius: 12px;
            border: 1px solid rgba(15, 23, 42, .12);
            background: #fff;
        }

        @media (max-width: 767.98px) {
            .auth-left {
                padding: 22px;
            }

            .auth-right {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="row g-0">
                <!-- LEFT -->
                <div class="col-12 col-md-5">
                    <div class="auth-left h-100">
                        <div class="brand">
                            <img src="{{ asset('storage/assets/Logo_PU.jpg') }}" alt="Logo PUPR">
                            <div>
                                <div class="app">TRANSKOM</div>
                                <div class="org">Bapekom VI Surabaya</div>
                            </div>
                        </div>

                        <div class="role-badge">
                            <span class="role-dot"></span>
                            <span>Daftar sebagai <strong>Peminjam</strong></span>
                        </div>

                        <h1>Buat Akun Baru</h1>
                        <p>Lengkapi data di samping untuk mulai mengajukan peminjaman kendaraan.</p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-12 col-md-7">
                    <div class="auth-right">
                        <div class="mb-3">
                            <div class="fw-semibold" style="font-size:16px;">Daftar</div>
                            <div class="helper">Isi data dengan benar. Password minimal 8 karakter.</div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <div class="fw-semibold mb-1">Ada yang perlu dibenerin:</div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="login_as" value="user">

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                                    value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="nama@email.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kata Sandi</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••" required autocomplete="new-password">
                                    <button class="btn btn-eye" type="button" id="togglePassword"
                                        aria-label="Tampilkan password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="helper mt-1">Minimal 8 karakter (atau sesuai aturan validasi kamu).</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="••••••••" required autocomplete="new-password">
                                    <button class="btn btn-eye" type="button" id="togglePasswordConfirm"
                                        aria-label="Tampilkan konfirmasi password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Daftar</button>

                            <p class="text-center small mt-3 mb-0">
                                Sudah punya akun? <a class="subtle-link" href="{{ route('login') }}">Masuk</a>
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="text-center mt-3 helper">© {{ date('Y') }} TRANSKOM — Bapekom VI Surabaya</div>
    </div>

    <script>
        function bindToggle(btnId, inputId) {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');

            btn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            });
        }

        bindToggle('togglePassword', 'password');
        bindToggle('togglePasswordConfirm', 'password_confirmation');
    </script>
</body>

</html>
