<?php
/**
 * Admina - Web-Based Composer Installer
 * Jalankan sekali via browser: https://domain/composer-install.php
 * HAPUS FILE INI setelah instalasi berhasil!
 *
 * Token diambil dari .env: COMPOSER_INSTALL_TOKEN=xxx
 */

define('BASE_PATH', __DIR__);

if (php_sapi_name() === 'cli') exit('Run via browser only.');

ini_set('display_errors', 0);
ini_set('max_execution_time', 300);
set_time_limit(300);

// ============================================================
// Baca COMPOSER_INSTALL_TOKEN dari .env (tanpa vendor)
// ============================================================
function readEnvToken(): string
{
    $envFile = BASE_PATH . '/.env';
    if (!file_exists($envFile)) return '';
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_starts_with($line, 'COMPOSER_INSTALL_TOKEN=')) {
            return trim(substr($line, strlen('COMPOSER_INSTALL_TOKEN=')), "\"' ");
        }
    }
    return '';
}

$secretToken = readEnvToken();
$token       = $_GET['token'] ?? '';
$action      = $_GET['action'] ?? 'check';
$envMissing  = ($secretToken === '');
$isValid     = !$envMissing && hash_equals($secretToken, $token);

// ============================================================
// Helpers
// ============================================================
function logLine(string $msg, string $type = 'info'): void
{
    $colors = ['info' => '#4fc3f7', 'success' => '#81c784', 'error' => '#e57373', 'warn' => '#ffb74d'];
    $color  = $colors[$type] ?? '#d4d4d4';
    echo '<div style="margin:2px 0">';
    echo '<span style="color:#666">[' . date('H:i:s') . ']</span> ';
    echo '<span style="color:' . $color . '">' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>';
    echo '</div>';
    ob_flush(); flush();
}

function canExec(): bool
{
    if (!function_exists('exec')) return false;
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
    return !in_array('exec', $disabled);
}

function canProcOpen(): bool
{
    if (!function_exists('proc_open')) return false;
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
    return !in_array('proc_open', $disabled);
}

function runCmd(string $cmd): array
{
    $output = []; $code = 0;
    exec($cmd . ' 2>&1', $output, $code);
    return ['output' => implode("\n", $output), 'code' => $code];
}

/**
 * Jalankan command via proc_open (lebih reliable di cPanel)
 * Streaming output baris per baris langsung ke browser
 */
function runCmdStreaming(string $cmd): int
{
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $process = proc_open($cmd, $descriptors, $pipes);
    if (!is_resource($process)) {
        logLine('proc_open gagal membuka proses.', 'error');
        return 1;
    }
    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $buffer = '';
    while (!feof($pipes[1]) || !feof($pipes[2])) {
        $out = fread($pipes[1], 4096);
        $err = fread($pipes[2], 4096);
        $buffer .= $out . $err;
        // flush per baris
        while (($pos = strpos($buffer, "\n")) !== false) {
            $line = trim(substr($buffer, 0, $pos));
            $buffer = substr($buffer, $pos + 1);
            if ($line === '') continue;
            $type = 'info';
            if (preg_match('/error|fatal|fail/i', $line))           $type = 'error';
            elseif (preg_match('/warning|deprecat/i', $line))        $type = 'warn';
            elseif (preg_match('/install|generat|writing|lock/i', $line)) $type = 'success';
            logLine($line, $type);
        }
        usleep(50000);
    }
    if ($buffer !== '') logLine(trim($buffer));
    fclose($pipes[1]);
    fclose($pipes[2]);
    return proc_close($process);
}

function getComposerHome(): string
{
    $home = BASE_PATH . '/storage/composer-home';
    if (!is_dir($home)) mkdir($home, 0755, true);
    return $home;
}

/**
 * Cari semua PHP CLI yang tersedia di server
 */
function findAllPhpCli(): array
{
    $candidates = [
        '/usr/local/bin/php',
        '/usr/bin/php',
        '/opt/cpanel/ea-php84/root/usr/bin/php',
        '/opt/cpanel/ea-php83/root/usr/bin/php',
        '/opt/cpanel/ea-php82/root/usr/bin/php',
        '/opt/cpanel/ea-php81/root/usr/bin/php',
        '/opt/cpanel/ea-php80/root/usr/bin/php',
    ];
    $found = [];
    foreach ($candidates as $p) {
        if (file_exists($p) && is_executable($p)) $found[] = $p;
    }
    // tambah dari which
    if (canExec()) {
        $res = runCmd('which php');
        if ($res['code'] === 0) {
            $w = trim($res['output']);
            if ($w && !in_array($w, $found)) $found[] = $w;
        }
    }
    return $found;
}

function findPhpCli(): string
{
    // Utamakan PHP yang versinya sama dengan web server
    $webVer = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    $all    = findAllPhpCli();

    // Coba cari yang versi-nya match
    foreach ($all as $p) {
        if (canExec()) {
            $res = runCmd($p . ' -r "echo PHP_MAJOR_VERSION.\'.\'.\'PHP_MINOR_VERSION;"');
            if ($res['code'] === 0 && trim($res['output']) === $webVer) return $p;
        }
    }
    // Fallback: ambil pertama
    return $all[0] ?? '';
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
        body { background:#f8f9fa; }
        .log-box {
            background:#1e1e1e; color:#d4d4d4; border-radius:8px;
            padding:16px; font-family:monospace; font-size:13px;
            min-height:140px; max-height:540px; overflow-y:auto; line-height:1.65;
        }
    </style>
</head>
<body>
<div class="container py-4" style="max-width:820px">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">⚙️ Admina — Web Composer Installer</h5>
        </div>
        <div class="card-body">

<?php if ($envMissing): ?>
    <div class="alert alert-danger">
        <strong>❌ COMPOSER_INSTALL_TOKEN belum diset!</strong><br>
        Tambahkan ke file <code>.env</code>:
        <pre class="mt-2 mb-0 p-2 bg-dark text-light rounded">COMPOSER_INSTALL_TOKEN=isi_token_rahasia_anda</pre>
    </div>

<?php elseif (!$isValid): ?>
    <p class="text-muted mb-3">Masukkan nilai <code>COMPOSER_INSTALL_TOKEN</code> dari file <code>.env</code>.</p>
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

    <div class="d-flex gap-2 flex-wrap mb-3">
        <a href="?token=<?= urlencode($token) ?>&action=check"   class="btn btn-outline-secondary btn-sm">🔍 Cek Environment</a>
        <a href="?token=<?= urlencode($token) ?>&action=debug"   class="btn btn-outline-info btn-sm">🔧 Debug PHP &amp; Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=download" class="btn btn-outline-primary btn-sm">⬇️ Download Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=install"  class="btn btn-success btn-sm">🚀 Install Dependencies</a>
        <a href="?token=<?= urlencode($token) ?>&action=cleanup"  class="btn btn-warning btn-sm">🧹 Cleanup Temp</a>
        <a href="?token=<?= urlencode($token) ?>&action=delete"   class="btn btn-danger btn-sm">🗑️ Hapus File Ini</a>
    </div>

    <div class="log-box" id="logBox">
<?php

    // ========================================================
    // ACTION: CHECK
    // ========================================================
    if ($action === 'check') {
        logLine('=== CEK ENVIRONMENT ===');
        logLine('.env file             : ' . (file_exists(BASE_PATH.'/.env') ? 'ada ✓' : 'tidak ada ✗'), file_exists(BASE_PATH.'/.env') ? 'success' : 'error');
        logLine('COMPOSER_INSTALL_TOKEN: terbaca dari .env ✓', 'success');

        $phpVer = PHP_VERSION;
        logLine('PHP Version (web)     : ' . $phpVer . (version_compare($phpVer,'8.0','>=') ? ' ✓' : ' ✗'), version_compare($phpVer,'8.0','>=') ? 'success' : 'error');
        logLine('exec()                : ' . (canExec() ? 'tersedia ✓' : 'tidak tersedia ✗'), canExec() ? 'success' : 'error');
        logLine('proc_open()           : ' . (canProcOpen() ? 'tersedia ✓' : 'tidak tersedia'), canProcOpen() ? 'success' : 'warn');

        // Semua PHP CLI yang ditemukan
        $allCli = findAllPhpCli();
        if ($allCli) {
            foreach ($allCli as $p) {
                $ver = '';
                if (canExec()) {
                    $r = runCmd($p . ' -r "echo phpversion();"');
                    if ($r['code'] === 0) $ver = ' (v' . trim($r['output']) . ')';
                }
                logLine('PHP CLI ditemukan     : ' . $p . $ver, 'success');
            }
        } else {
            logLine('PHP CLI               : tidak ditemukan ✗', 'error');
        }

        logLine('allow_url_fopen       : ' . (ini_get('allow_url_fopen') ? 'On ✓' : 'Off ✗'), ini_get('allow_url_fopen') ? 'success' : 'error');
        logLine('cURL                  : ' . (function_exists('curl_init') ? 'tersedia ✓' : 'tidak tersedia'), function_exists('curl_init') ? 'success' : 'warn');

        $homeEnv = getenv('HOME') ?: '';
        logLine('HOME env              : ' . ($homeEnv ?: '(kosong — akan pakai COMPOSER_HOME)'), $homeEnv ? 'success' : 'warn');
        logLine('COMPOSER_HOME         : ' . getComposerHome() . ' ✓', 'success');

        foreach ([BASE_PATH, BASE_PATH.'/vendor', BASE_PATH.'/storage', BASE_PATH.'/storage/composer-home'] as $dir) {
            $w = is_dir($dir) ? is_writable($dir) : is_writable(dirname($dir));
            $l = str_replace(BASE_PATH.'/', '', $dir) ?: 'root';
            logLine('Writable ' . $l . str_repeat(' ', max(1, 22-strlen($l))) . ': ' . ($w ? 'Ya ✓' : 'Tidak ✗'), $w ? 'success' : 'error');
        }

        $freeM = round(disk_free_space(BASE_PATH) / 1024 / 1024);
        logLine('Disk Free             : ' . $freeM . ' MB', $freeM > 100 ? 'success' : 'warn');
        logLine('composer.json         : ' . (file_exists(BASE_PATH.'/composer.json') ? 'ada ✓' : 'tidak ada ✗'), file_exists(BASE_PATH.'/composer.json') ? 'success' : 'error');
        logLine('composer.phar         : ' . (file_exists(BASE_PATH.'/composer.phar') ? 'ada ✓' : 'belum ada'), file_exists(BASE_PATH.'/composer.phar') ? 'success' : 'info');
        logLine('vendor/               : ' . (is_dir(BASE_PATH.'/vendor') ? 'ada ✓' : 'belum ada'), is_dir(BASE_PATH.'/vendor') ? 'success' : 'info');
        logLine('--- Urutan: 🔧 Debug → ⬇️ Download → 🚀 Install → 🧹 Cleanup → 🗑️ Hapus ---');
    }

    // ========================================================
    // ACTION: DEBUG
    // ========================================================
    elseif ($action === 'debug') {
        logLine('=== DEBUG PHP CLI & COMPOSER ===');

        $phpCli       = findPhpCli();
        $composerHome = getComposerHome();
        $pharPath     = BASE_PATH . '/composer.phar';

        if (!$phpCli) {
            logLine('PHP CLI tidak ditemukan!', 'error');
        } else {
            // 1. Versi PHP CLI
            logLine('--- [1] Versi PHP CLI ---');
            $r = runCmd($phpCli . ' --version');
            logLine($r['output'] ?: '(tidak ada output)', $r['code'] === 0 ? 'success' : 'error');

            // 2. Test COMPOSER_HOME
            logLine('--- [2] Test env var COMPOSER_HOME ---');
            $r = runCmd(sprintf('COMPOSER_HOME=%s HOME=%s %s -r "echo getenv(\"COMPOSER_HOME\");"',
                escapeshellarg($composerHome), escapeshellarg($composerHome), escapeshellarg($phpCli)));
            logLine('COMPOSER_HOME output: ' . ($r['output'] ?: '(kosong!)'), $r['output'] ? 'success' : 'error');

            // 3. Test composer.phar --version
            if (file_exists($pharPath)) {
                logLine('--- [3] Test composer.phar --version ---');
                $cmd = sprintf('cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar --version 2>&1',
                    escapeshellarg(BASE_PATH),
                    escapeshellarg($composerHome),
                    escapeshellarg($composerHome),
                    escapeshellarg($phpCli));
                $r = runCmd($cmd);
                logLine($r['output'] ?: '(tidak ada output)', $r['code'] === 0 ? 'success' : 'error');

                // 4. Dry-run install
                logLine('--- [4] Dry-run: composer install --dry-run ---');
                $cmd = sprintf('cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar install --dry-run --no-interaction 2>&1',
                    escapeshellarg(BASE_PATH),
                    escapeshellarg($composerHome),
                    escapeshellarg($composerHome),
                    escapeshellarg($phpCli));
                $r = runCmd($cmd);
                foreach (explode("\n", $r['output']) as $line) {
                    $line = trim($line); if (!$line) continue;
                    $t = preg_match('/error|fatal/i', $line) ? 'error' : (preg_match('/warn/i', $line) ? 'warn' : 'info');
                    logLine($line, $t);
                }
                logLine('Dry-run exit code: ' . $r['code'], $r['code'] === 0 ? 'success' : 'error');
            } else {
                logLine('[3][4] composer.phar belum ada, skip. Jalankan ⬇️ Download dulu.', 'warn');
            }
        }
        logLine('--- Debug selesai ---');
    }

    // ========================================================
    // ACTION: DOWNLOAD
    // ========================================================
    elseif ($action === 'download') {
        logLine('=== DOWNLOAD COMPOSER.PHAR ===');
        $pharPath = BASE_PATH . '/composer.phar';

        if (file_exists($pharPath) && filesize($pharPath) > 100000) {
            logLine('composer.phar sudah ada (' . round(filesize($pharPath)/1024) . ' KB), skip. ✓', 'success');
        } else {
            if (file_exists($pharPath)) @unlink($pharPath);
            $url        = 'https://getcomposer.org/composer-stable.phar';
            $downloaded = false;
            logLine('Mengunduh dari: ' . $url);

            if (function_exists('curl_init')) {
                logLine('Mencoba via cURL...');
                $ch = curl_init($url);
                $fp = fopen($pharPath, 'wb');
                curl_setopt_array($ch, [CURLOPT_FILE=>$fp, CURLOPT_FOLLOWLOCATION=>true,
                    CURLOPT_TIMEOUT=>180, CURLOPT_SSL_VERIFYPEER=>true,
                    CURLOPT_USERAGENT=>'admina-composer-installer/1.0']);
                $ok = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch); fclose($fp);
                if ($ok && $code === 200 && filesize($pharPath) > 100000) {
                    $downloaded = true;
                    logLine('cURL berhasil ✓', 'success');
                } else {
                    @unlink($pharPath);
                    logLine('cURL gagal (HTTP ' . $code . '), coba file_get_contents...', 'warn');
                }
            }
            if (!$downloaded && ini_get('allow_url_fopen')) {
                logLine('Mencoba via file_get_contents...');
                $ctx  = stream_context_create(['http'=>['timeout'=>180,'user_agent'=>'admina-composer-installer/1.0']]);
                $data = @file_get_contents($url, false, $ctx);
                if ($data && strlen($data) > 100000) {
                    file_put_contents($pharPath, $data);
                    $downloaded = true;
                    logLine('file_get_contents berhasil ✓', 'success');
                } else {
                    logLine('file_get_contents juga gagal.', 'error');
                }
            }
            if (!$downloaded) {
                logLine('GAGAL download otomatis!', 'error');
                logLine('Solusi: download manual → https://getcomposer.org/composer-stable.phar', 'warn');
                logLine('lalu upload ke: ' . $pharPath, 'warn');
            }
        }
        if (file_exists($pharPath) && filesize($pharPath) > 100000) {
            logLine('composer.phar siap (' . round(filesize($pharPath)/1024) . ' KB) ✓', 'success');
            logLine('--- Lanjut: 🔧 Debug dulu untuk verifikasi, atau langsung 🚀 Install ---');
        }
    }

    // ========================================================
    // ACTION: INSTALL
    // ========================================================
    elseif ($action === 'install') {
        logLine('=== INSTALL COMPOSER DEPENDENCIES ===');

        $pharPath = BASE_PATH . '/composer.phar';
        if (!file_exists($pharPath) || filesize($pharPath) < 100000) {
            logLine('composer.phar tidak ditemukan / rusak! Jalankan ⬇️ Download dulu.', 'error');
        } elseif (!canExec() && !canProcOpen()) {
            logLine('GAGAL: exec() dan proc_open() tidak tersedia.', 'error');
            logLine('Hubungi hosting provider.', 'warn');
        } else {
            $phpCli       = findPhpCli();
            $composerHome = getComposerHome();

            if (!$phpCli) {
                logLine('PHP CLI tidak ditemukan!', 'error');
            } else {
                logLine('PHP CLI       : ' . $phpCli . ' ✓', 'success');
                logLine('COMPOSER_HOME : ' . $composerHome . ' ✓', 'success');

                $cmd = sprintf(
                    'cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar install --no-dev --optimize-autoloader --no-interaction',
                    escapeshellarg(BASE_PATH),
                    escapeshellarg($composerHome),
                    escapeshellarg($composerHome),
                    escapeshellarg($phpCli)
                );

                logLine('Command: ' . htmlspecialchars($cmd), 'info');
                logLine('Menjalankan install, harap tunggu 1-3 menit...');
                logLine(str_repeat('-', 55));

                $exitCode = 1;
                if (canProcOpen()) {
                    logLine('Menggunakan proc_open (streaming)...');
                    $exitCode = runCmdStreaming($cmd);
                } else {
                    logLine('Menggunakan exec()...');
                    $result   = runCmd($cmd . ' 2>&1');
                    foreach (explode("\n", $result['output']) as $line) {
                        $line = trim($line); if (!$line) continue;
                        $t = preg_match('/error|fatal|fail/i',$line) ? 'error'
                           : (preg_match('/warn|deprecat/i',$line) ? 'warn'
                           : (preg_match('/install|generat|writing|lock/i',$line) ? 'success' : 'info'));
                        logLine($line, $t);
                    }
                    $exitCode = $result['code'];
                }

                logLine(str_repeat('-', 55));
                if ($exitCode === 0 && is_dir(BASE_PATH . '/vendor')) {
                    logLine('✅ INSTALASI BERHASIL! Folder vendor/ sudah terbuat.', 'success');
                    logLine('🧹 Lanjut: Cleanup Temp → 🗑️ Hapus File Ini', 'warn');
                } else {
                    logLine('❌ Instalasi gagal (exit code: ' . $exitCode . ')', 'error');
                    logLine('Coba tombol 🔧 Debug untuk diagnosa lebih lanjut.', 'warn');
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
        if (file_exists($pharPath)) { @unlink($pharPath); logLine('composer.phar dihapus ✓', 'success'); }
        else logLine('composer.phar tidak ada, skip.', 'info');

        $ch = BASE_PATH . '/storage/composer-home';
        if (is_dir($ch)) {
            $it  = new RecursiveDirectoryIterator($ch, RecursiveDirectoryIterator::SKIP_DOTS);
            $itr = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($itr as $f) { $f->isDir() ? rmdir($f->getRealPath()) : unlink($f->getRealPath()); }
            rmdir($ch);
            logLine('storage/composer-home/ dihapus ✓', 'success');
        } else logLine('storage/composer-home/ tidak ada, skip.', 'info');
        logLine('Cleanup selesai. Lanjut → 🗑️ Hapus File Ini.', 'success');
    }

    // ========================================================
    // ACTION: DELETE
    // ========================================================
    elseif ($action === 'delete') {
        logLine('=== HAPUS FILE INSTALLER ===');
        if (@unlink(__FILE__)) {
            logLine('composer-install.php berhasil dihapus ✓', 'success');
            echo '</div><div class="alert alert-success mt-3 fw-bold">';
            echo '✅ File installer sudah dihapus. Aplikasi siap digunakan!';
            echo '</div></div></div></div></body></html>';
            exit;
        } else {
            logLine('Gagal hapus otomatis. Hapus manual via cPanel File Manager.', 'error');
        }
    }

?>
    </div><!-- /.log-box -->
    <div class="alert alert-warning mt-3 mb-0 small">
        ⚠️ <strong>Keamanan:</strong> Segera hapus file ini setelah instalasi berhasil!
    </div>

<?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
