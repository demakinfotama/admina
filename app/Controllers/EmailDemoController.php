<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Mailer;

class EmailDemoController extends Controller
{
    /**
     * GET /email-demo — tampilkan form kirim email
     */
    public function index(): void
    {
        $this->view('email_demo.index', [
            'title'   => 'Demo Kirim Email',
            'success' => null,
            'error'   => null,
        ]);
    }

    /**
     * POST /email-demo — proses kirim email
     */
    public function send(): void
    {
        $to      = trim($_POST['to']      ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validasi sederhana
        if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !$subject || !$message) {
            $this->view('email_demo.index', [
                'title' => 'Demo Kirim Email',
                'error' => 'Semua field wajib diisi dan alamat email harus valid.',
                'success' => null,
            ]);
            return;
        }

        // Buat body HTML dari pesan plain-text
        $htmlBody = '
        <!DOCTYPE html>
        <html lang="id">
        <head><meta charset="UTF-8"></head>
        <body style="font-family:Arial,sans-serif;background:#f4f4f4;padding:30px">
            <div style="max-width:580px;margin:0 auto;background:#fff;border-radius:8px;
                        padding:32px;box-shadow:0 2px 8px rgba(0,0,0,.08)">
                <h2 style="color:#0f3460;margin-top:0">📬 Pesan dari Admina</h2>
                <p style="color:#555;line-height:1.7">' . nl2br(htmlspecialchars($message)) . '</p>
                <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
                <small style="color:#aaa">Dikirim via Admina &mdash; ' . date('d M Y H:i') . '</small>
            </div>
        </body></html>';

        // Tangani attachment (opsional)
        $attachments = [];
        if (!empty($_FILES['attachment']['tmp_name'])) {
            $tmpPath  = $_FILES['attachment']['tmp_name'];
            $origName = $_FILES['attachment']['name'];
            $destPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($origName);
            move_uploaded_file($tmpPath, $destPath);
            $attachments[] = $destPath;
        }

        try {
            Mailer::send(
                to:          $to,
                subject:     $subject,
                body:        $htmlBody,
                attachments: $attachments,
            );

            $this->view('email_demo.index', [
                'title'   => 'Demo Kirim Email',
                'success' => "Email berhasil dikirim ke {$to} ✅",
                'error'   => null,
            ]);
        } catch (\RuntimeException $e) {
            $this->view('email_demo.index', [
                'title'   => 'Demo Kirim Email',
                'error'   => $e->getMessage(),
                'success' => null,
            ]);
        }
    }
}
