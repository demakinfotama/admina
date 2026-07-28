<?php
/**
 * Layout utama semua halaman admin.
 * Gunakan: $this->view('namaview', ['title'=>..., 'active_menu'=>...]);
 * Atau include manual: extract($data); include __DIR__.'/../layouts/app.php';
 *
 * Variabel yang diharapkan:
 *   $title       : judul halaman
 *   $active_menu : slug menu aktif (dashboard, users, products, orders, reports, settings)
 *   $slot        : konten utama (string HTML) — diisi oleh masing-masing view
 */
$active_menu = $active_menu ?? 'dashboard';
$user_name   = $user_name   ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> &mdash; Admina</title>
<!-- Tabler CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<style>
  .navbar-brand-text { font-weight: 800; font-size: 1.25rem; letter-spacing: -.5px; }
  .nav-link-icon .ti { font-size: 1.1rem; }
  .stat-card-icon { width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem; }
  .badge-dot { width:8px;height:8px;border-radius:50%;display:inline-block; }
  .table-avatar { width:32px;height:32px;border-radius:50%;object-fit:cover;background:#e9ecef;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem; }
</style>
</head>
<body class="antialiased">
<div class="wrapper">

  <!-- ===== SIDEBAR ===== -->
  <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a href="/dashboard" class="navbar-brand navbar-brand-autofit">
        <span class="navbar-brand-text">&#9881; Admina</span>
      </a>
      <div class="collapse navbar-collapse" id="sidebar-menu">
        <ul class="navbar-nav pt-lg-3">

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='dashboard' ? 'active' : '' ?>" href="/dashboard">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg></span>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='users' ? 'active' : '' ?>" href="/users">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg></span>
              <span class="nav-link-title">Pengguna</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='products' ? 'active' : '' ?>" href="/products">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/><line x1="12" y1="12" x2="20" y2="7.5"/><line x1="12" y1="12" x2="12" y2="21"/><line x1="12" y1="12" x2="4" y2="7.5"/></svg></span>
              <span class="nav-link-title">Produk</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='orders' ? 'active' : '' ?>" href="/orders">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2"/></svg></span>
              <span class="nav-link-title">Pesanan</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='reports' ? 'active' : '' ?>" href="/reports">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
              <span class="nav-link-title">Laporan</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= $active_menu==='settings' ? 'active' : '' ?>" href="/settings">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><circle cx="12" cy="12" r="3"/></svg></span>
              <span class="nav-link-title">Pengaturan</span>
            </a>
          </li>

          <li class="nav-item mt-auto">
            <a class="nav-link text-danger" href="/logout">
              <span class="nav-link-icon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/><path d="M7 12h14l-3 -3m0 6l3 -3"/></svg></span>
              <span class="nav-link-title">Logout</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </aside>
  <!-- ===== END SIDEBAR ===== -->

  <div class="page-wrapper">
    <!-- Topbar -->
    <div class="navbar-expand-md">
      <div class="collapse navbar-collapse" id="navbar-menu"></div>
    </div>
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h2 class="page-title"><?= htmlspecialchars($title) ?></h2>
          </div>
          <div class="col-auto ms-auto">
            <div class="d-flex align-items-center gap-3">
              <span class="avatar avatar-sm rounded" style="background:var(--tblr-primary);color:#fff;font-weight:700">
                <?= strtoupper(substr($user_name,0,1)) ?>
              </span>
              <div class="d-none d-md-block">
                <div class="fw-bold" style="font-size:.875rem"><?= htmlspecialchars($user_name) ?></div>
                <div class="text-muted" style="font-size:.75rem">Administrator</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <?= $slot ?>
      </div>
    </div>
    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
        <div class="row text-center align-items-center"><div class="col-12 col-lg-auto">
          <p class="text-muted mb-0">&copy; <?= date('Y') ?> Admina &mdash; PHP <?= PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION ?></p>
        </div></div>
      </div>
    </footer>
  </div>
</div>
<!-- Tabler JS -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?php if (!empty($extra_js)): ?><?= $extra_js ?><?php endif; ?>
</body>
</html>
