<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Admina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:620px">

    <div class="mb-4">
        <a href="/" class="text-decoration-none text-secondary">&larr; Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📬 <?= htmlspecialchars($title) ?></h5>
        </div>
        <div class="card-body">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/email-demo/send" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-bold">Kepada <span class="text-danger">*</span></label>
                    <input type="email" name="to" class="form-control"
                           placeholder="penerima@email.com" required
                           value="<?= htmlspecialchars($_POST['to'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control"
                           placeholder="Subjek email" required
                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pesan <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="5"
                              placeholder="Isi pesan..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Attachment <span class="text-secondary fw-normal">(opsional)</span></label>
                    <input type="file" name="attachment" class="form-control">
                    <div class="form-text">PDF, Excel, gambar, dll.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    📤 Kirim Email
                </button>
            </form>

        </div>
    </div>

    <div class="mt-4 p-3 bg-white rounded border">
        <h6 class="fw-bold mb-2">💡 Cara pakai <code>Mailer</code> di controller lain:</h6>
        <pre class="mb-0" style="font-size:.82rem;background:#f8f9fa;padding:12px;border-radius:6px;">use App\Core\Mailer;

// Kirim HTML
Mailer::send(
    to:      'tujuan@email.com',
    subject: 'Notifikasi',
    body:    '&lt;h1&gt;Halo!&lt;/h1&gt;&lt;p&gt;Ini email HTML.&lt;/p&gt;',
);

// Kirim ke banyak penerima + attachment
Mailer::send(
    to:          ['a@email.com', 'b@email.com'],
    subject:     'Laporan Bulanan',
    body:        '&lt;p&gt;Terlampir file laporan.&lt;/p&gt;',
    attachments: ['/path/laporan.pdf'],
    cc:          ['cc@email.com'],
);</pre>
    </div>

</div>
</body>
</html>
