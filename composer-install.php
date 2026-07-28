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

function runCmdStreaming(string $cmd): int
{
    $descriptors = [0 => ['pipe','r'], 1 => ['pipe','w'], 2 => ['pipe','w']];
    $process = proc_open($cmd, $descriptors, $pipes);
    if (!is_resource($process)) { logLine('proc_open gagal.', 'error'); return 1; }
    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);
    $buffer = '';
    while (!feof($pipes[1]) || !feof($pipes[2])) {
        $buffer .= fread($pipes[1], 4096) . fread($pipes[2], 4096);
        while (($pos = strpos($buffer, "\n")) !== false) {
            $line = trim(substr($buffer, 0, $pos));
            $buffer = substr($buffer, $pos + 1);
            if ($line === '') continue;
            $t = preg_match('/error|fatal|fail/i',$line) ? 'error'
               : (preg_match('/warning|deprecat/i',$line) ? 'warn'
               : (preg_match('/install|updat|generat|writing|lock/i',$line) ? 'success' : 'info'));
            logLine($line, $t);
        }
        usleep(50000);
    }
    if (trim($buffer)) logLine(trim($buffer));
    fclose($pipes[1]); fclose($pipes[2]);
    return proc_close($process);
}

function getComposerHome(): string
{
    $home = BASE_PATH . '/storage/composer-home';
    if (!is_dir($home)) mkdir($home, 0755, true);
    return $home;
}

function findAllPhpCli(): array
{
    $candidates = [
        '/usr/local/bin/php', '/usr/bin/php',
        '/opt/cpanel/ea-php84/root/usr/bin/php',
        '/opt/cpanel/ea-php83/root/usr/bin/php',
        '/opt/cpanel/ea-php82/root/usr/bin/php',
        '/opt/cpanel/ea-php81/root/usr/bin/php',
        '/opt/cpanel/ea-php80/root/usr/bin/php',
    ];
    $found = [];
    foreach ($candidates as $p) { if (file_exists($p) && is_executable($p)) $found[] = $p; }
    if (canExec()) {
        $res = runCmd('which php');
        if ($res['code'] === 0) { $w = trim($res['output']); if ($w && !in_array($w,$found)) $found[] = $w; }
    }
    return $found;
}

function findPhpCli(): string
{
    $webVer = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    $all    = findAllPhpCli();
    foreach ($all as $p) {
        if (canExec()) {
            $res = runCmd($p . ' -r "echo PHP_MAJOR_VERSION.\'.\'.\'PHP_MINOR_VERSION;"');
            if ($res['code'] === 0 && trim($res['output']) === $webVer) return $p;
        }
    }
    return $all[0] ?? '';
}

/**
 * Jalankan composer command (install atau update) dengan env vars yang benar.
 * Mengembalikan exit code.
 */
function runComposer(string $phpCli, string $composerCmd): int
{
    $composerHome = getComposerHome();
    $cmd = sprintf(
        'cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar %s',
        escapeshellarg(BASE_PATH),
        escapeshellarg($composerHome),
        escapeshellarg($composerHome),
        escapeshellarg($phpCli),
        $composerCmd
    );
    logLine('Command: ' . $cmd, 'info');
    logLine(str_repeat('-', 55));
    $exitCode = canProcOpen() ? runCmdStreaming($cmd) : (function() use ($cmd) {
        $r = runCmd($cmd . ' 2>&1');
        foreach (explode("\n", $r['output']) as $l) {
            $l = trim($l); if (!$l) continue;
            $t = preg_match('/error|fatal|fail/i',$l) ? 'error' : (preg_match('/warn|deprecat/i',$l) ? 'warn' : (preg_match('/install|updat|generat|writing|lock/i',$l) ? 'success' : 'info'));
            logLine($l, $t);
        }
        return $r['code'];
    })();
    logLine(str_repeat('-', 55));
    return $exitCode;
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
        <a href="?token=<?= urlencode($token) ?>&action=check"    class="btn btn-outline-secondary btn-sm">🔍 Cek Environment</a>
        <a href="?token=<?= urlencode($token) ?>&action=debug"    class="btn btn-outline-info btn-sm">🔧 Debug PHP &amp; Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=download"  class="btn btn-outline-primary btn-sm">⬇️ Download Composer</a>
        <a href="?token=<?= urlencode($token) ?>&action=install"   class="btn btn-success btn-sm">🚀 Install Dependencies</a>
        <a href="?token=<?= urlencode($token) ?>&action=update"    class="btn btn-primary btn-sm">🔄 Update Dependencies</a>
        <a href="?token=<?= urlencode($token) ?>&action=cleanup"   class="btn btn-warning btn-sm">🧹 Cleanup Temp</a>
        <a href="?token=<?= urlencode($token) ?>&action=delete"    class="btn btn-danger btn-sm">🗑️ Hapus File Ini</a>
    </div>

    <div class="alert alert-info py-2 small mb-3">
        💡 <strong>🚀 Install</strong> = dari lock file &nbsp;|&nbsp;
           <strong>🔄 Update</strong> = resolve ulang semua versi + regenerasi lock file
           <em>(gunakan jika lock file tidak sinkron dengan composer.json)</em>
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
        foreach (findAllPhpCli() as $p) {
            $ver = '';
            if (canExec()) { $r = runCmd($p . ' -r "echo phpversion();"'); if ($r['code']===0) $ver = ' (v'.trim($r['output']).')'; }
            logLine('PHP CLI ditemukan     : ' . $p . $ver, 'success');
        }
        if (!findAllPhpCli()) logLine('PHP CLI               : tidak ditemukan ✗', 'error');
        logLine('allow_url_fopen       : ' . (ini_get('allow_url_fopen') ? 'On ✓' : 'Off ✗'), ini_get('allow_url_fopen') ? 'success' : 'error');
        logLine('cURL                  : ' . (function_exists('curl_init') ? 'tersedia ✓' : 'tidak tersedia'), function_exists('curl_init') ? 'success' : 'warn');
        logLine('COMPOSER_HOME         : ' . getComposerHome() . ' ✓', 'success');
        foreach ([BASE_PATH, BASE_PATH.'/vendor', BASE_PATH.'/storage'] as $dir) {
            $w = is_dir($dir) ? is_writable($dir) : is_writable(dirname($dir));
            $l = str_replace(BASE_PATH.'/', '', $dir) ?: 'root';
            logLine('Writable ' . $l . str_repeat(' ', max(1,22-strlen($l))) . ': ' . ($w ? 'Ya ✓' : 'Tidak ✗'), $w ? 'success' : 'error');
        }
        $freeM = round(disk_free_space(BASE_PATH)/1024/1024);
        logLine('Disk Free             : ' . $freeM . ' MB', $freeM>100 ? 'success' : 'warn');
        logLine('composer.json         : ' . (file_exists(BASE_PATH.'/composer.json') ? 'ada ✓' : 'tidak ada ✗'), file_exists(BASE_PATH.'/composer.json') ? 'success' : 'error');
        logLine('composer.lock         : ' . (file_exists(BASE_PATH.'/composer.lock') ? 'ada ✓' : 'tidak ada (akan dibuat saat install/update)'), file_exists(BASE_PATH.'/composer.lock') ? 'success' : 'warn');
        logLine('composer.phar         : ' . (file_exists(BASE_PATH.'/composer.phar') ? 'ada ✓' : 'belum ada'), file_exists(BASE_PATH.'/composer.phar') ? 'success' : 'info');
        logLine('vendor/               : ' . (is_dir(BASE_PATH.'/vendor') ? 'ada ✓' : 'belum ada'), is_dir(BASE_PATH.'/vendor') ? 'success' : 'info');
        logLine('--- Urutan normal: ⬇️ Download → 🚀 Install ---');
        logLine('--- Lock tidak sinkron: 🔄 Update (hapus lock lama & resolve ulang) ---');
    }

    // ========================================================
    // ACTION: DEBUG
    // ========================================================
    elseif ($action === 'debug') {
        logLine('=== DEBUG PHP CLI & COMPOSER ===');
        $phpCli = findPhpCli();
        $composerHome = getComposerHome();
        $pharPath = BASE_PATH . '/composer.phar';
        if (!$phpCli) {
            logLine('PHP CLI tidak ditemukan!', 'error');
        } else {
            logLine('--- [1] Versi PHP CLI ---');
            $r = runCmd($phpCli . ' --version');
            logLine($r['output'] ?: '(tidak ada output)', $r['code']===0 ? 'success' : 'error');
            logLine('--- [2] Test env var COMPOSER_HOME ---');
            $r = runCmd(sprintf('COMPOSER_HOME=%s HOME=%s %s -r "echo getenv(\"COMPOSER_HOME\");"',
                escapeshellarg($composerHome), escapeshellarg($composerHome), escapeshellarg($phpCli)));
            logLine('COMPOSER_HOME output: ' . ($r['output'] ?: '(kosong!)'), $r['output'] ? 'success' : 'error');
            if (file_exists($pharPath)) {
                logLine('--- [3] Test composer.phar --version ---');
                $r = runCmd(sprintf('cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar --version 2>&1',
                    escapeshellarg(BASE_PATH), escapeshellarg($composerHome), escapeshellarg($composerHome), escapeshellarg($phpCli)));
                logLine($r['output'] ?: '(tidak ada output)', $r['code']===0 ? 'success' : 'error');
                logLine('--- [4] Dry-run: composer install --dry-run ---');
                $r = runCmd(sprintf('cd %s && COMPOSER_HOME=%s HOME=%s %s composer.phar install --dry-run --no-interaction 2>&1',
                    escapeshellarg(BASE_PATH), escapeshellarg($composerHome), escapeshellarg($composerHome), escapeshellarg($phpCli)));
                foreach (explode("\n", $r['output']) as $line) {
                    $line = trim($line); if (!$line) continue;
                    $t = preg_match('/error|fatal/i',$line) ? 'error' : (preg_match('/warn/i',$line) ? 'warn' : 'info');
                    logLine($line, $t);
                }
                logLine('Dry-run exit code: ' . $r['code'], $r['code']===0 ? 'success' : 'error');
                if ($r['code'] !== 0) logLine('Jika lock tidak sinkron, gunakan 🔄 Update Dependencies.', 'warn');
            } else {
                logLine('[3][4] composer.phar belum ada. Jalankan ⬇️ Download dulu.', 'warn');
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
            $url = 'https://getcomposer.org/composer-stable.phar';
            $downloaded = false;
            logLine('Mengunduh dari: ' . $url);
            if (function_exists('curl_init')) {
                logLine('Mencoba via cURL...');
                $ch = curl_init($url); $fp = fopen($pharPath, 'wb');
                curl_setopt_array($ch, [CURLOPT_FILE=>$fp, CURLOPT_FOLLOWLOCATION=>true,
                    CURLOPT_TIMEOUT=>180, CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_USERAGENT=>'admina-installer/1.0']);
                $ok = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch); fclose($fp);
                if ($ok && $code===200 && filesize($pharPath)>100000) { $downloaded=true; logLine('cURL berhasil ✓','success'); }
                else { @unlink($pharPath); logLine('cURL gagal (HTTP '.$code.'), coba file_get_contents...','warn'); }
            }
            if (!$downloaded && ini_get('allow_url_fopen')) {
                logLine('Mencoba via file_get_contents...');
                $ctx = stream_context_create(['http'=>['timeout'=>180,'user_agent'=>'admina-installer/1.0']]);
                $data = @file_get_contents($url, false, $ctx);
                if ($data && strlen($data)>100000) { file_put_contents($pharPath,$data); $downloaded=true; logLine('Berhasil ✓','success'); }
                else logLine('Gagal juga.','error');
            }
            if (!$downloaded) {
                logLine('GAGAL download otomatis!','error');
                logLine('Download manual: https://getcomposer.org/composer-stable.phar','warn');
            }
        }
        if (file_exists($pharPath) && filesize($pharPath)>100000)
            logLine('composer.phar siap ('.round(filesize($pharPath)/1024).' KB) ✓','success');
    }

    // ========================================================
    // ACTION: INSTALL
    // ========================================================
    elseif ($action === 'install') {
        logLine('=== INSTALL COMPOSER DEPENDENCIES ===');
        $pharPath = BASE_PATH . '/composer.phar';
        if (!file_exists($pharPath) || filesize($pharPath)<100000) {
            logLine('composer.phar tidak ditemukan! Jalankan ⬇️ Download dulu.','error');
        } elseif (!canExec() && !canProcOpen()) {
            logLine('GAGAL: exec() dan proc_open() tidak tersedia.','error');
        } else {
            $phpCli = findPhpCli();
            if (!$phpCli) { logLine('PHP CLI tidak ditemukan!','error'); }
            else {
                logLine('PHP CLI       : ' . $phpCli . ' ✓','success');
                logLine('COMPOSER_HOME : ' . getComposerHome() . ' ✓','success');
                logLine('Menjalankan composer install, harap tunggu...');
                $code = runComposer($phpCli, 'install --no-dev --optimize-autoloader --no-interaction');
                if ($code===0 && is_dir(BASE_PATH.'/vendor'))
                    logLine('✅ INSTALASI BERHASIL! vendor/ terbuat.','success');
                else { logLine('❌ Gagal (exit code: '.$code.')','error'); logLine('Coba 🔄 Update jika error lock tidak sinkron.','warn'); }
            }
        }
    }

    // ========================================================
    // ACTION: UPDATE (hapus lock lama, resolve ulang)
    // ========================================================
    elseif ($action === 'update') {
        logLine('=== UPDATE COMPOSER DEPENDENCIES ===');
        logLine('Ini akan menghapus composer.lock lama dan resolve ulang semua versi.','warn');
        $pharPath = BASE_PATH . '/composer.phar';
        if (!file_exists($pharPath) || filesize($pharPath)<100000) {
            logLine('composer.phar tidak ditemukan! Jalankan ⬇️ Download dulu.','error');
        } elseif (!canExec() && !canProcOpen()) {
            logLine('GAGAL: exec() dan proc_open() tidak tersedia.','error');
        } else {
            $phpCli = findPhpCli();
            if (!$phpCli) { logLine('PHP CLI tidak ditemukan!','error'); }
            else {
                // Hapus composer.lock lama
                $lockPath = BASE_PATH . '/composer.lock';
                if (file_exists($lockPath)) {
                    @unlink($lockPath);
                    logLine('composer.lock lama dihapus ✓','success');
                } else {
                    logLine('composer.lock tidak ada (fresh install).','info');
                }

                logLine('PHP CLI       : ' . $phpCli . ' ✓','success');
                logLine('COMPOSER_HOME : ' . getComposerHome() . ' ✓','success');
                logLine('Menjalankan composer update, harap tunggu (lebih lama dari install)...');

                $code = runComposer($phpCli, 'update --no-dev --optimize-autoloader --no-interaction');
                if ($code===0 && is_dir(BASE_PATH.'/vendor')) {
                    logLine('✅ UPDATE BERHASIL! vendor/ & composer.lock sudah diperbarui.','success');
                    logLine('🧹 Lanjut: Cleanup Temp → 🗑️ Hapus File Ini','warn');
                } else {
                    logLine('❌ Update gagal (exit code: '.$code.')','error');
                    logLine('Coba 🔧 Debug untuk melihat detail error.','warn');
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
        if (file_exists($pharPath)) { @unlink($pharPath); logLine('composer.phar dihapus ✓','success'); }
        else logLine('composer.phar tidak ada, skip.','info');
        $ch = BASE_PATH . '/storage/composer-home';
        if (is_dir($ch)) {
            $it = new RecursiveDirectoryIterator($ch, RecursiveDirectoryIterator::SKIP_DOTS);
            $itr = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($itr as $f) { $f->isDir() ? rmdir($f->getRealPath()) : unlink($f->getRealPath()); }
            rmdir($ch);
            logLine('storage/composer-home/ dihapus ✓','success');
        } else logLine('storage/composer-home/ tidak ada, skip.','info');
        logLine('Cleanup selesai. Lanjut → 🗑️ Hapus File Ini.','success');
    }

    // ========================================================
    // ACTION: DELETE
    // ========================================================
    elseif ($action === 'delete') {
        logLine('=== HAPUS FILE INSTALLER ===');
        if (@unlink(__FILE__)) {
            logLine('composer-install.php berhasil dihapus ✓','success');
            echo '</div><div class="alert alert-success mt-3 fw-bold">✅ File installer sudah dihapus. Aplikasi siap digunakan!</div></div></div></div></body></html>';
            exit;
        } else {
            logLine('Gagal hapus otomatis. Hapus manual via cPanel File Manager.','error');
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
