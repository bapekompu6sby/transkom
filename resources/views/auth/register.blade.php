<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRANSKOM - Daftar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --pupr-blue: #002B7F;
            --pupr-yellow: #FFD700;
            --bg-light: #f8f9fa;
            --text-dark: #212529;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background-color: var(--pupr-blue);
            border-color: var(--pupr-blue);
        }

        .btn-primary:hover {
            background-color: #001f5c;
            border-color: #001f5c;
        }

        a {
            color: var(--pupr-blue);
        }

        a:hover {
            color: var(--pupr-yellow);
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4" style="max-width: 420px; width:100%;">
        <div class="text-center mb-3">
            <img src="{{ asset('storage/assets/Logo_PU.jpg') }}" alt="Logo PUPR" width="60">
            <h5 class="mt-2 fw-semibold mb-0">Keluar Masuk Kendaraan</h5>
            <h5 class="mt-1 fw-semibold">Bapekom VI Surabaya</h5>
            <p class="text-muted small mb-0">Buat akun baru</p>
        </div>

        {{-- Error global (kalau ada) --}}
        @if ($errors->any())
            <div class="alert alert-danger small py-2">
                <div class="fw-semibold mb-1">Ada yang perlu dibenerin:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- opsional, kalau kamu butuh register sebagai user --}}
            <input type="hidden" name="login_as" value="user">

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Nama</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Nama lengkap" value="{{ old('name') }}" required autofocus autocomplete="name">

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="username">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Kata Sandi</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••" required autocomplete="new-password">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text small">
                    Minimal 8 karakter (atau sesuai aturan validasi kamu).
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label fw-medium">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="••••••••"
                    required autocomplete="new-password">

                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Daftar
            </button>

            <p class="text-center small mt-3 mb-0">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk</a>
            </p>
        </form>
    </div>
</body>

</html>
