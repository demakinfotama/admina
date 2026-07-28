<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Admina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            text-align: center;
            color: #fff;
            max-width: 480px;
            width: 100%;
        }
        .hero-card h1 { font-size: 2.8rem; font-weight: 700; letter-spacing: -1px; }
        .hero-card p  { color: rgba(255,255,255,0.65); font-size: 1.05rem; }
        .btn-login {
            background: linear-gradient(90deg, #e94560, #0f3460);
            border: none;
            padding: .65rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: .5px;
        }
        .btn-login:hover { opacity: .85; }
        .badge-php {
            background: rgba(255,255,255,0.1);
            color: #a8dadc;
            border-radius: 50px;
            padding: .25rem .85rem;
            font-size: .78rem;
            letter-spacing: .5px;
        }
    </style>
</head>
<body>
    <div class="hero-card">
        <div class="mb-3">
            <span class="badge-php">⚙️ PHP Native MVC</span>
        </div>
        <h1>👋 Hello, World!</h1>
        <p class="mt-3 mb-4">
            Selamat datang di <strong>Admina</strong> — panel admin ringan berbasis PHP native.
            Tidak ada framework, tidak ada bloat.
        </p>
        <a href="/login" class="btn btn-login text-white">
            🔐 Masuk ke Dashboard
        </a>
        <hr style="border-color:rgba(255,255,255,0.1);margin:2rem 0">
        <small style="color:rgba(255,255,255,0.35)">
            PHP <?= PHP_VERSION ?> &nbsp;·&nbsp; <?= date('Y') ?>
        </small>
    </div>
</body>
</html>
