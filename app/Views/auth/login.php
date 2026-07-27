<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="card shadow" style="width:380px">
        <div class="card-body p-4">
            <h4 class="card-title text-center mb-4">Admina Login</h4>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_GET['error'] === 'empty' ? 'Username dan password wajib diisi.' : 'Username atau password salah.' ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <?= \App\Core\Security::csrfField() ?>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
