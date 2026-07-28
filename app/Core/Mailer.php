<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Mailer — wrapper PHPMailer berbasis konfigurasi .env
 *
 * Cara pakai:
 *   // Kirim HTML biasa
 *   Mailer::send(
 *       to: 'penerima@email.com',
 *       subject: 'Halo!',
 *       body: '<h1>Hello World</h1>',
 *   );
 *
 *   // Kirim dengan attachment
 *   Mailer::send(
 *       to: ['a@email.com', 'b@email.com'],
 *       subject: 'Laporan',
 *       body: '<p>Terlampir laporan bulan ini.</p>',
 *       attachments: [
 *           '/path/ke/file.pdf',
 *           '/path/ke/data.xlsx',
 *       ]
 *   );
 */
class Mailer
{
    /**
     * Kirim email HTML, opsional dengan attachment.
     *
     * @param  string|array  $to          Satu alamat atau array alamat
     * @param  string        $subject     Subject email
     * @param  string        $body        Isi HTML email
     * @param  string        $altBody     Versi plain-text (opsional, di-generate otomatis jika kosong)
     * @param  array         $attachments Array path file untuk di-attach
     * @param  array         $cc          Array alamat CC (opsional)
     * @param  array         $bcc         Array alamat BCC (opsional)
     * @param  string|null   $replyTo     Reply-To address (opsional)
     * @throws \RuntimeException
     */
    public static function send(
        string|array $to,
        string $subject,
        string $body,
        string $altBody     = '',
        array  $attachments = [],
        array  $cc          = [],
        array  $bcc         = [],
        ?string $replyTo    = null
    ): void {
        $mail = new PHPMailer(true);

        try {
            // ---- Server SMTP ----
            $mail->isSMTP();
            $mail->Host       = self::env('MAIL_HOST',         'smtp.gmail.com');
            $mail->Port       = (int) self::env('MAIL_PORT',   '587');
            $mail->SMTPAuth   = true;
            $mail->Username   = self::env('MAIL_USERNAME',     '');
            $mail->Password   = self::env('MAIL_PASSWORD',     '');

            $encryption = strtolower(self::env('MAIL_ENCRYPTION', 'tls'));
            $mail->SMTPSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS
                                                       : PHPMailer::ENCRYPTION_STARTTLS;

            // Debug: set ke SMTP::DEBUG_SERVER untuk troubleshoot
            $mail->SMTPDebug = SMTP::DEBUG_OFF;

            // ---- Pengirim ----
            $mail->setFrom(
                self::env('MAIL_FROM_ADDRESS', $mail->Username),
                self::env('MAIL_FROM_NAME',    'Admina')
            );

            // ---- Reply-To ----
            if ($replyTo) {
                $mail->addReplyTo($replyTo);
            }

            // ---- Penerima ----
            foreach ((array) $to as $address) {
                $mail->addAddress(trim($address));
            }
            foreach ($cc as $address) {
                $mail->addCC(trim($address));
            }
            foreach ($bcc as $address) {
                $mail->addBCC(trim($address));
            }

            // ---- Konten ----
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

            // ---- Attachment ----
            foreach ($attachments as $filePath) {
                if (is_file($filePath) && is_readable($filePath)) {
                    $mail->addAttachment($filePath);
                }
            }

            $mail->send();

        } catch (Exception $e) {
            throw new \RuntimeException('Gagal kirim email: ' . $mail->ErrorInfo);
        }
    }

    /**
     * Buat instance PHPMailer langsung jika butuh konfigurasi lanjutan.
     */
    public static function make(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = self::env('MAIL_HOST',         'smtp.gmail.com');
        $mail->Port       = (int) self::env('MAIL_PORT',   '587');
        $mail->SMTPAuth   = true;
        $mail->Username   = self::env('MAIL_USERNAME',     '');
        $mail->Password   = self::env('MAIL_PASSWORD',     '');
        $encryption       = strtolower(self::env('MAIL_ENCRYPTION', 'tls'));
        $mail->SMTPSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS
                                                  : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom(
            self::env('MAIL_FROM_ADDRESS', $mail->Username),
            self::env('MAIL_FROM_NAME',    'Admina')
        );
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        return $mail;
    }

    // ----------------------------------------------------------------

    private static function env(string $key, string $default = ''): string
    {
        $val = $_ENV[$key] ?? getenv($key);
        return ($val !== false && $val !== '') ? (string) $val : $default;
    }
}
