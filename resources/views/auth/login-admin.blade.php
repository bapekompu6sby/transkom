<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRANSKOM</title>
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
            <img src="storage/assets/Logo_PU.jpg" alt="Logo PUPR" width="60">
            <h5 class="mt-2 fw-semibold">Keluar Masuk Kendaraan</h5>
            <h5 class="mt-2 fw-semibold">Bapekom VI Surabaya</h5>
            <p class="text-muted small mb-0">Masuk ke akun Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="login_as" value="admin">

            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required
                    autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Kata Sandi</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label small" for="remember_me">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="small">Lupa sandi?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Masuk</button>
            <p class="text-center small mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </form>
    </div>
</body>

</html>
