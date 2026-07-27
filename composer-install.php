<?php
/**
 * Admina - Web-Based Composer Installer
 * Jalankan sekali via browser: https://domain/composer-install.php
 * HAPUS FILE INI setelah instalasi berhasil!
 *
 * Keamanan: dilindungi dengan SECRET_TOKEN
 */

// ============================================================
// KONFIGURASI - Ganti token ini sebelum upload!
// ============================================================
define('SECRET_TOKEN', 'GANTI_TOKEN_RAHASIA_INI');
define('BASE_PATH', __DIR__);

// ============================================================
// Keamanan dasar
// ============================================================
if (php_sapi_name() === 'cli') {
    exit('Run via browser only.');
}

ini_set('display_errors', 1);
ini_set('max_execution_time', 300);
set_time_limit(300);

$token   = $_GET['token'] ?? '';
$action  = $_GET['action'] ?? 'check';
$isValid = hash_equals(SECRET_TOKEN, $token);

// Helper: print log baris
function logLine(string $msg, string $type = 'info'): void
{
    $colors = ['info' => '#0d6efd', 'success' => '#198754', 'error' => '#dc3545', 'warn' => '#ffc107'];
    $color  = $colors[$type] ?? '#333';
    echo '<div style="margin:2px 0;font-family:monospace;font-size:13px;color:' . $color . '">';
    echo '[' . date('H:i:s') . '] ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    echo '</div>';
    ob_flush();
    flush();
}

// Helper: jalankan command dengan output
function runCmd(string $cmd): array
{
    $output = [];
    $code   = 0;
    exec($cmd . ' 2>&1', $output, $code);
    return ['output' => implode("\n", $output), 'code' => $code];
}

// Helper: cek apakah exec tersedia
function canExec(): bool
{
    if (!function_exists('exec')) return false;
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
    return !in_array('exec', $disabled);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composer Installer - Admina</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .log-box { background:#1e1e1e; color:#d4d4d4; border-radius:8px; padding:16px; font-family:monospace; font-size:13px; min-height:120px; max-height:500px; overflow-y:auto; }
        .log-box .info    { color: #4fc3f7; }
        .log-box .success { color: #81c784; }
        .log-box .error   { color: #e57373; }
        .log-box .warn    { color: #ffb74d; }
    </style>
</head>
<body>
<div class="container py-4" style="max-width:780px">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">⚙️ Admina — Web Composer Installer</h5>
        </div>
        <div class="card-body">

<?php if (!$isValid): ?>
    <!-- TOKEN FORM -->
    <p class="text-muted">Masukkan <strong>SECRET_TOKEN</strong> yang sudah Anda set di file ini untuk melanjutkan.</p>
    <form method="GET">
        <div class="mb-3">
            <label class="form-label fw-bold">Secret Token</label>
            <input type="text" name="token" class="form-control font-monospace" placeholder="Token rahasia..." required>
        </div>
        <input type="hidden" name="action" value="check">
        <button class="btn btn-primary">Verifikasi Token</button>
    </form>

<?php else: ?>

    <!-- NAVIGASI AKSI -->
    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="?token=<?= urlencode($token) ?>&action=check"   class="btn btn-outline-secondary btn-sm">🔍 Cek Environment</a>
        <a href="?token=<?= urlencode($token) ?>&action=download" class="btn btn-outline-primary btn-sm">⬇️ Download Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=install"  class="btn btn-success btn-sm">🚀 Install Dependencies</a>
        <a href="?token=<?= urlencode($token) ?>&action=delete"   class="btn btn-danger btn-sm">🗑️ Hapus File Ini</a>
    </div>

    <div class="log-box" id="logBox">
<?php

    ob_start();
    ob_implicit_flush(true);
    ob_end_flush();

    // ========================================================
    // ACTION: CHECK
    // ========================================================
    if ($action === 'check') {
        logLine('=== CEK ENVIRONMENT ===');

        // PHP version
        $phpVer = PHP_VERSION;
        $phpOk  = version_compare($phpVer, '8.0', '>=');
        logLine('PHP Version  : ' . $phpVer . ($phpOk ? ' ✓' : ' ✗ (perlu >= 8.0)'), $phpOk ? 'success' : 'error');

        // exec()
        $execOk = canExec();
        logLine('exec()       : ' . ($execOk ? 'tersedia ✓' : 'tidak tersedia ✗'), $execOk ? 'success' : 'error');

        // PHP CLI
        $phpPaths = ['/usr/local/bin/php', '/usr/bin/php', '/opt/cpanel/ea-php82/root/usr/bin/php',
                     '/opt/cpanel/ea-php81/root/usr/bin/php', '/opt/cpanel/ea-php80/root/usr/bin/php'];
        $phpCli = '';
        foreach ($phpPaths as $p) {
            if (file_exists($p)) { $phpCli = $p; break; }
        }
        if (!$phpCli && $execOk) {
            $res = runCmd('which php');
            if ($res['code'] === 0 && trim($res['output'])) $phpCli = trim($res['output']);
        }
        logLine('PHP CLI      : ' . ($phpCli ?: 'tidak ditemukan'), $phpCli ? 'success' : 'warn');

        // allow_url_fopen
        $urlFopen = ini_get('allow_url_fopen');
        logLine('allow_url_fopen: ' . ($urlFopen ? 'On ✓' : 'Off ✗'), $urlFopen ? 'success' : 'error');

        // curl
        $curlOk = function_exists('curl_init');
        logLine('cURL         : ' . ($curlOk ? 'tersedia ✓' : 'tidak tersedia'), $curlOk ? 'success' : 'warn');

        // folder writable
        $folders = [BASE_PATH, BASE_PATH . '/vendor'];
        foreach ($folders as $dir) {
            $exists   = is_dir($dir);
            $writable = $exists ? is_writable($dir) : is_writable(dirname($dir));
            $label    = str_replace(BASE_PATH, '.', $dir);
            logLine('Writable ' . $label . ': ' . ($writable ? 'Ya ✓' : 'Tidak ✗'), $writable ? 'success' : 'error');
        }

        // disk space
        $free = disk_free_space(BASE_PATH);
        $freeM = $free ? round($free / 1024 / 1024) : 0;
        logLine('Disk Free    : ' . $freeM . ' MB', $freeM > 100 ? 'success' : 'warn');

        // composer.json
        $cjExists = file_exists(BASE_PATH . '/composer.json');
        logLine('composer.json: ' . ($cjExists ? 'ada ✓' : 'tidak ada ✗'), $cjExists ? 'success' : 'error');

        // composer.phar
        $pharExists = file_exists(BASE_PATH . '/composer.phar');
        logLine('composer.phar: ' . ($pharExists ? 'sudah ada ✓' : 'belum ada'), $pharExists ? 'success' : 'info');

        // vendor
        $vendorExists = is_dir(BASE_PATH . '/vendor');
        logLine('vendor/      : ' . ($vendorExists ? 'sudah ada ✓' : 'belum ada'), $vendorExists ? 'success' : 'info');

        logLine('--- Selesai. Lanjut ke: ⬇️ Download Composer → 🚀 Install ---');
    }

    // ========================================================
    // ACTION: DOWNLOAD COMPOSER.PHAR
    // ========================================================
    elseif ($action === 'download') {
        logLine('=== DOWNLOAD COMPOSER.PHAR ===');

        $pharPath = BASE_PATH . '/composer.phar';

        if (file_exists($pharPath)) {
            logLine('composer.phar sudah ada, skip download.', 'success');
        } else {
            $composerUrl = 'https://getcomposer.org/composer-stable.phar';
            logLine('Mengunduh dari: ' . $composerUrl);

            $downloaded = false;

            // Coba via cURL
            if (function_exists('curl_init')) {
                logLine('Mencoba download via cURL...');
                $ch = curl_init($composerUrl);
                $fp = fopen($pharPath, 'wb');
                curl_setopt_array($ch, [
                    CURLOPT_FILE           => $fp,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT        => 120,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_USERAGENT      => 'admina-composer-installer/1.0',
                ]);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                fclose($fp);

                if ($result && $httpCode === 200 && file_exists($pharPath) && filesize($pharPath) > 100000) {
                    $downloaded = true;
                    logLine('Download via cURL berhasil ✓', 'success');
                } else {
                    @unlink($pharPath);
                    logLine('cURL gagal (HTTP ' . $httpCode . '), mencoba file_get_contents...', 'warn');
                }
            }

            // Fallback: file_get_contents
            if (!$downloaded && ini_get('allow_url_fopen')) {
                logLine('Mencoba download via file_get_contents...');
                $ctx  = stream_context_create(['http' => ['timeout' => 120, 'user_agent' => 'admina-composer-installer/1.0']]);
                $data = @file_get_contents($composerUrl, false, $ctx);
                if ($data && strlen($data) > 100000) {
                    file_put_contents($pharPath, $data);
                    $downloaded = true;
                    logLine('Download via file_get_contents berhasil ✓', 'success');
                } else {
                    logLine('file_get_contents juga gagal.', 'error');
                }
            }

            if (!$downloaded) {
                logLine('GAGAL download. Upload manual composer.phar ke: ' . BASE_PATH, 'error');
                logLine('Download dari: https://getcomposer.org/composer-stable.phar', 'warn');
            }
        }

        if (file_exists($pharPath)) {
            $size = round(filesize($pharPath) / 1024) . ' KB';
            logLine('composer.phar siap (' . $size . ') ✓', 'success');
            logLine('--- Lanjut ke: 🚀 Install Dependencies ---');
        }
    }

    // ========================================================
    // ACTION: INSTALL
    // ========================================================
    elseif ($action === 'install') {
        logLine('=== INSTALL COMPOSER DEPENDENCIES ===');

        if (!canExec()) {
            logLine('GAGAL: fungsi exec() tidak tersedia di server ini.', 'error');
            logLine('Hubungi hosting provider untuk mengaktifkan exec().', 'warn');
        } else {
            $pharPath = BASE_PATH . '/composer.phar';
            if (!file_exists($pharPath)) {
                logLine('composer.phar tidak ditemukan! Jalankan dulu ⬇️ Download Composer.', 'error');
            } else {
                // Cari PHP CLI
                $phpPaths = ['/usr/local/bin/php', '/usr/bin/php',
                             '/opt/cpanel/ea-php82/root/usr/bin/php',
                             '/opt/cpanel/ea-php81/root/usr/bin/php',
                             '/opt/cpanel/ea-php80/root/usr/bin/php'];
                $phpCli = '';
                foreach ($phpPaths as $p) {
                    if (file_exists($p)) { $phpCli = $p; break; }
                }
                if (!$phpCli) {
                    $res = runCmd('which php');
                    if ($res['code'] === 0) $phpCli = trim($res['output']);
                }

                if (!$phpCli) {
                    logLine('PHP CLI tidak ditemukan! Coba isi path PHP di konfigurasi.', 'error');
                } else {
                    logLine('Menggunakan PHP CLI: ' . $phpCli, 'success');

                    $cmd = sprintf(
                        'cd %s && %s composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1',
                        escapeshellarg(BASE_PATH),
                        escapeshellarg($phpCli)
                    );

                    logLine('Menjalankan: composer install --no-dev --optimize-autoloader');
                    logLine('Harap tunggu, proses ini bisa memakan waktu 1-3 menit...');

                    $result = runCmd($cmd);

                    // Tampilkan output baris per baris
                    foreach (explode("\n", $result['output']) as $line) {
                        $line = trim($line);
                        if (!$line) continue;
                        $type = 'info';
                        if (stripos($line, 'error') !== false || stripos($line, 'fatal') !== false) $type = 'error';
                        elseif (stripos($line, 'warning') !== false) $type = 'warn';
                        elseif (stripos($line, 'install') !== false || stripos($line, 'generat') !== false) $type = 'success';
                        logLine($line, $type);
                    }

                    if ($result['code'] === 0 && is_dir(BASE_PATH . '/vendor')) {
                        logLine('');
                        logLine('✅ INSTALASI BERHASIL! Folder vendor/ sudah terbuat.', 'success');
                        logLine('⚠️  SEGERA HAPUS file ini: ?action=delete&token=...', 'warn');
                    } else {
                        logLine('❌ Instalasi gagal (exit code: ' . $result['code'] . ')', 'error');
                    }
                }
            }
        }
    }

    // ========================================================
    // ACTION: DELETE (self-delete)
    // ========================================================
    elseif ($action === 'delete') {
        logLine('=== HAPUS FILE INSTALLER ===');
        $self = __FILE__;
        if (@unlink($self)) {
            logLine('File composer-install.php berhasil dihapus ✓', 'success');
            logLine('Installer sudah tidak bisa diakses lagi.', 'success');
            echo '</div>';
            echo '<div class="alert alert-success mt-3">✅ File installer sudah dihapus. Aplikasi siap digunakan!</div>';
            echo '</div></div></div></body></html>';
            exit;
        } else {
            logLine('Gagal hapus otomatis. Hapus manual file: composer-install.php', 'error');
        }
    }

?>
    </div><!-- /.log-box -->

    <div class="alert alert-warning mt-3 mb-0 small">
        ⚠️ <strong>Keamanan:</strong> Segera hapus file <code>composer-install.php</code> setelah instalasi berhasil!
        Gunakan tombol <strong>🗑️ Hapus File Ini</strong> di atas.
    </div>

<?php endif; ?>

        </div><!-- /.card-body -->
    </div><!-- /.card -->
</div>
</body>
</html>
