<?php
/**
 * Admina - Web-Based Composer Installer
 * Jalankan sekali via browser: https://domain/composer-install.php
 * HAPUS FILE INI setelah instalasi berhasil!
 *
 * Token diambil dari .env: COMPOSER_INSTALL_TOKEN=xxx
 */

define('BASE_PATH', __DIR__);

// ============================================================
// Keamanan dasar
// ============================================================
if (php_sapi_name() === 'cli') {
    exit('Run via browser only.');
}

ini_set('display_errors', 0);
ini_set('max_execution_time', 300);
set_time_limit(300);

// ============================================================
// Baca COMPOSER_INSTALL_TOKEN dari .env (manual parse,
// tidak perlu vendor/autoload.php karena belum tentu ada)
// ============================================================
function readEnvToken(): string
{
    $envFile = BASE_PATH . '/.env';
    if (!file_exists($envFile)) {
        return '';
    }
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_starts_with($line, 'COMPOSER_INSTALL_TOKEN=')) {
            $value = substr($line, strlen('COMPOSER_INSTALL_TOKEN='));
            // Hapus kutip jika ada
            return trim($value, '"\' ');
        }
    }
    return '';
}

$secretToken = readEnvToken();
$token       = $_GET['token'] ?? '';
$action      = $_GET['action'] ?? 'check';

// Jika token di .env kosong, tampilkan pesan khusus
$envMissing = ($secretToken === '');
$isValid    = !$envMissing && hash_equals($secretToken, $token);

// Helper: print log baris
function logLine(string $msg, string $type = 'info'): void
{
    $colors = ['info' => '#4fc3f7', 'success' => '#81c784', 'error' => '#e57373', 'warn' => '#ffb74d'];
    $color  = $colors[$type] ?? '#d4d4d4';
    echo '<div style="margin:2px 0;padding:1px 0;">';
    echo '<span style="color:#888">[' . date('H:i:s') . ']</span> ';
    echo '<span style="color:' . $color . '">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>';
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

// Helper: set COMPOSER_HOME ke folder tmp di dalam project
function getComposerHome(): string
{
    $home = BASE_PATH . '/storage/composer-home';
    if (!is_dir($home)) {
        mkdir($home, 0755, true);
    }
    return $home;
}

// Helper: cari PHP CLI path
function findPhpCli(): string
{
    $phpPaths = [
        '/usr/local/bin/php',
        '/usr/bin/php',
        '/opt/cpanel/ea-php84/root/usr/bin/php',
        '/opt/cpanel/ea-php83/root/usr/bin/php',
        '/opt/cpanel/ea-php82/root/usr/bin/php',
        '/opt/cpanel/ea-php81/root/usr/bin/php',
        '/opt/cpanel/ea-php80/root/usr/bin/php',
    ];
    foreach ($phpPaths as $p) {
        if (file_exists($p)) return $p;
    }
    if (canExec()) {
        $res = runCmd('which php');
        if ($res['code'] === 0 && trim($res['output'])) return trim($res['output']);
    }
    return '';
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
        .log-box {
            background: #1e1e1e;
            color: #d4d4d4;
            border-radius: 8px;
            padding: 16px;
            font-family: monospace;
            font-size: 13px;
            min-height: 140px;
            max-height: 520px;
            overflow-y: auto;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="container py-4" style="max-width:800px">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">⚙️ Admina — Web Composer Installer</h5>
        </div>
        <div class="card-body">

<?php if ($envMissing): ?>
    <!-- .ENV TIDAK ADA / TOKEN BELUM DISET -->
    <div class="alert alert-danger">
        <strong>❌ COMPOSER_INSTALL_TOKEN belum diset!</strong><br>
        Tambahkan baris berikut ke file <code>.env</code> Anda:
        <pre class="mt-2 mb-0 p-2 bg-dark text-light rounded">COMPOSER_INSTALL_TOKEN=isi_token_rahasia_anda</pre>
    </div>

<?php elseif (!$isValid): ?>
    <!-- TOKEN FORM -->
    <p class="text-muted mb-3">
        Masukkan nilai <code>COMPOSER_INSTALL_TOKEN</code> dari file <code>.env</code> Anda.
    </p>
    <form method="GET">
        <div class="mb-3">
            <label class="form-label fw-bold">Installer Token</label>
            <input type="password" name="token" class="form-control font-monospace"
                   placeholder="Nilai COMPOSER_INSTALL_TOKEN dari .env" required autofocus>
        </div>
        <input type="hidden" name="action" value="check">
        <button class="btn btn-primary">🔓 Verifikasi</button>
    </form>

<?php else: ?>

    <!-- NAVIGASI AKSI -->
    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="?token=<?= urlencode($token) ?>&action=check"    class="btn btn-outline-secondary btn-sm">🔍 Cek Environment</a>
        <a href="?token=<?= urlencode($token) ?>&action=download"  class="btn btn-outline-primary btn-sm">⬇️ Download Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=install"   class="btn btn-success btn-sm">🚀 Install Dependencies</a>
        <a href="?token=<?= urlencode($token) ?>&action=cleanup"   class="btn btn-warning btn-sm">🧹 Cleanup Temp</a>
        <a href="?token=<?= urlencode($token) ?>&action=delete"    class="btn btn-danger btn-sm">🗑️ Hapus File Ini</a>
    </div>

    <div class="log-box" id="logBox">
<?php

    // ========================================================
    // ACTION: CHECK
    // ========================================================
    if ($action === 'check') {
        logLine('=== CEK ENVIRONMENT ===');

        // .env & token
        logLine('.env file        : ' . (file_exists(BASE_PATH . '/.env') ? 'ada ✓' : 'tidak ada ✗'),
            file_exists(BASE_PATH . '/.env') ? 'success' : 'error');
        logLine('COMPOSER_INSTALL_TOKEN: terbaca dari .env ✓', 'success');

        $phpVer = PHP_VERSION;
        $phpOk  = version_compare($phpVer, '8.0', '>=');
        logLine('PHP Version      : ' . $phpVer . ($phpOk ? ' ✓' : ' ✗ (perlu >= 8.0)'), $phpOk ? 'success' : 'error');

        $execOk = canExec();
        logLine('exec()           : ' . ($execOk ? 'tersedia ✓' : 'tidak tersedia ✗'), $execOk ? 'success' : 'error');

        $phpCli = findPhpCli();
        logLine('PHP CLI          : ' . ($phpCli ?: 'tidak ditemukan ✗'), $phpCli ? 'success' : 'warn');

        $urlFopen = ini_get('allow_url_fopen');
        logLine('allow_url_fopen  : ' . ($urlFopen ? 'On ✓' : 'Off ✗'), $urlFopen ? 'success' : 'error');

        $curlOk = function_exists('curl_init');
        logLine('cURL             : ' . ($curlOk ? 'tersedia ✓' : 'tidak tersedia'), $curlOk ? 'success' : 'warn');

        $homeEnv = getenv('HOME') ?: '';
        logLine('HOME env         : ' . ($homeEnv ?: '(kosong — akan pakai COMPOSER_HOME)'), $homeEnv ? 'success' : 'warn');
        $composerHome = getComposerHome();
        logLine('COMPOSER_HOME    : ' . $composerHome . ' ✓', 'success');

        $folders = [
            BASE_PATH,
            BASE_PATH . '/vendor',
            BASE_PATH . '/storage',
            BASE_PATH . '/storage/composer-home',
        ];
        foreach ($folders as $dir) {
            $writable = is_dir($dir) ? is_writable($dir) : is_writable(dirname($dir));
            $label    = str_replace(BASE_PATH . '/', '', $dir) ?: '.';
            logLine('Writable ' . $label . '  : ' . ($writable ? 'Ya ✓' : 'Tidak ✗'), $writable ? 'success' : 'error');
        }

        $free  = disk_free_space(BASE_PATH);
        $freeM = $free ? round($free / 1024 / 1024) : 0;
        logLine('Disk Free        : ' . $freeM . ' MB', $freeM > 100 ? 'success' : 'warn');

        logLine('composer.json    : ' . (file_exists(BASE_PATH . '/composer.json') ? 'ada ✓' : 'tidak ada ✗'),
            file_exists(BASE_PATH . '/composer.json') ? 'success' : 'error');
        logLine('composer.phar    : ' . (file_exists(BASE_PATH . '/composer.phar') ? 'sudah ada ✓' : 'belum ada'),
            file_exists(BASE_PATH . '/composer.phar') ? 'success' : 'info');
        logLine('vendor/          : ' . (is_dir(BASE_PATH . '/vendor') ? 'sudah ada ✓' : 'belum ada'),
            is_dir(BASE_PATH . '/vendor') ? 'success' : 'info');

        logLine('--- Selesai. Urutan: ⬇️ Download → 🚀 Install → 🧹 Cleanup → 🗑️ Hapus ---');
    }

    // ========================================================
    // ACTION: DOWNLOAD COMPOSER.PHAR
    // ========================================================
    elseif ($action === 'download') {
        logLine('=== DOWNLOAD COMPOSER.PHAR ===');

        $pharPath = BASE_PATH . '/composer.phar';

        if (file_exists($pharPath) && filesize($pharPath) > 100000) {
            logLine('composer.phar sudah ada (' . round(filesize($pharPath)/1024) . ' KB), skip. ✓', 'success');
        } else {
            if (file_exists($pharPath)) @unlink($pharPath);

            $composerUrl = 'https://getcomposer.org/composer-stable.phar';
            logLine('Mengunduh dari: ' . $composerUrl);
            $downloaded  = false;

            if (function_exists('curl_init')) {
                logLine('Mencoba download via cURL...');
                $ch = curl_init($composerUrl);
                $fp = fopen($pharPath, 'wb');
                curl_setopt_array($ch, [
                    CURLOPT_FILE           => $fp,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT        => 180,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_USERAGENT      => 'admina-composer-installer/1.0',
                ]);
                $ok       = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                fclose($fp);

                if ($ok && $httpCode === 200 && filesize($pharPath) > 100000) {
                    $downloaded = true;
                    logLine('Download via cURL berhasil ✓', 'success');
                } else {
                    @unlink($pharPath);
                    logLine('cURL gagal (HTTP ' . $httpCode . '), coba file_get_contents...', 'warn');
                }
            }

            if (!$downloaded && ini_get('allow_url_fopen')) {
                logLine('Mencoba download via file_get_contents...');
                $ctx  = stream_context_create(['http' => ['timeout' => 180, 'user_agent' => 'admina-composer-installer/1.0']]);
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
                logLine('GAGAL download otomatis!', 'error');
                logLine('Download manual: https://getcomposer.org/composer-stable.phar', 'warn');
                logLine('Upload ke: ' . BASE_PATH . '/composer.phar', 'warn');
            }
        }

        if (file_exists($pharPath) && filesize($pharPath) > 100000) {
            logLine('composer.phar siap (' . round(filesize($pharPath)/1024) . ' KB) ✓', 'success');
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
            if (!file_exists($pharPath) || filesize($pharPath) < 100000) {
                logLine('composer.phar tidak ditemukan / rusak! Jalankan dulu ⬇️ Download.', 'error');
            } else {
                $phpCli = findPhpCli();

                if (!$phpCli) {
                    logLine('PHP CLI tidak ditemukan! Tidak bisa lanjut.', 'error');
                } else {
                    logLine('PHP CLI          : ' . $phpCli . ' ✓', 'success');

                    $composerHome = getComposerHome();
                    logLine('COMPOSER_HOME    : ' . $composerHome . ' ✓', 'success');

                    $homeEnv = getenv('HOME');
                    if (!$homeEnv) {
                        putenv('HOME=' . $composerHome);
                        logLine('HOME env         : di-set ke ' . $composerHome, 'info');
                    }

                    $cmd = sprintf(
                        'cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1',
                        escapeshellarg(BASE_PATH),
                        escapeshellarg($composerHome),
                        escapeshellarg($composerHome),
                        escapeshellarg($phpCli)
                    );

                    logLine('Menjalankan: composer install --no-dev --optimize-autoloader --no-interaction');
                    logLine('Harap tunggu, proses ini bisa memakan 1-3 menit...');
                    logLine(str_repeat('-', 55));

                    $result = runCmd($cmd);

                    foreach (explode("\n", $result['output']) as $line) {
                        $line = trim($line);
                        if (!$line) continue;
                        $type = 'info';
                        if (preg_match('/error|fatal|fail/i', $line))            $type = 'error';
                        elseif (preg_match('/warning|deprecat/i', $line))         $type = 'warn';
                        elseif (preg_match('/install|generat|writing/i', $line))  $type = 'success';
                        logLine($line, $type);
                    }

                    logLine(str_repeat('-', 55));

                    if ($result['code'] === 0 && is_dir(BASE_PATH . '/vendor')) {
                        logLine('✅ INSTALASI BERHASIL! Folder vendor/ sudah terbuat.', 'success');
                        logLine('🧹 Lanjut: Cleanup Temp → 🗑️ Hapus File Ini', 'warn');
                    } else {
                        logLine('❌ Instalasi gagal (exit code: ' . $result['code'] . ')', 'error');
                        logLine('Coba 🔍 Cek Environment untuk diagnosa.', 'warn');
                    }
                }
            }
        }
    }

    // ========================================================
    // ACTION: CLEANUP
    // ========================================================
    elseif ($action === 'cleanup') {
        logLine('=== CLEANUP TEMPORARY FILES ===');

        $pharPath = BASE_PATH . '/composer.phar';
        if (file_exists($pharPath)) {
            @unlink($pharPath);
            logLine('composer.phar dihapus ✓', 'success');
        } else {
            logLine('composer.phar tidak ada, skip.', 'info');
        }

        $composerHome = BASE_PATH . '/storage/composer-home';
        if (is_dir($composerHome)) {
            $it  = new RecursiveDirectoryIterator($composerHome, RecursiveDirectoryIterator::SKIP_DOTS);
            $itr = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($itr as $f) {
                $f->isDir() ? rmdir($f->getRealPath()) : unlink($f->getRealPath());
            }
            rmdir($composerHome);
            logLine('storage/composer-home/ dihapus ✓', 'success');
        } else {
            logLine('storage/composer-home/ tidak ada, skip.', 'info');
        }

        logLine('Cleanup selesai. Lanjut → 🗑️ Hapus File Ini.', 'success');
    }

    // ========================================================
    // ACTION: DELETE (self-delete)
    // ========================================================
    elseif ($action === 'delete') {
        logLine('=== HAPUS FILE INSTALLER ===');
        if (@unlink(__FILE__)) {
            logLine('composer-install.php berhasil dihapus ✓', 'success');
            logLine('Installer sudah tidak bisa diakses lagi.', 'success');
            echo '</div>';
            echo '<div class="alert alert-success mt-3 fw-bold">✅ File installer sudah dihapus. Aplikasi siap digunakan!</div>';
            echo '</div></div></div></body></html>';
            exit;
        } else {
            logLine('Gagal hapus otomatis. Hapus manual via cPanel File Manager.', 'error');
        }
    }

?>
    </div><!-- /.log-box -->

    <div class="alert alert-warning mt-3 mb-0 small">
        ⚠️ <strong>Keamanan:</strong> Segera hapus file <code>composer-install.php</code> setelah instalasi berhasil!
    </div>

<?php endif; ?>

        </div><!-- /.card-body -->
    </div><!-- /.card -->
</div>
</body>
</html>
