<?php
/**
 * Layout utama — Light Theme, Deep Purple (Ungu Tua)
 * Variabel: $title, $active_menu, $user_name, $slot, $extra_js
 */
$active_menu = $active_menu ?? 'dashboard';
$user_name   = $user_name   ?? 'Admin';
$initials    = strtoupper(substr($user_name,0,1));
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> — Admina</title>

<!-- Fonts: Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tabler CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
/* =============================================================
   DESIGN TOKENS
   Light Theme + Deep Purple (Ungu Tua)
   Sidebar: #4c1d95 (violet-900) — kaya, tua, elegan
   Primary: #6d28d9 (violet-700)
   Accent: #7c3aed  (violet-600)
   Surfaces: putih bersih #ffffff / #f8f7ff
   Text: #1e1b4b (near-black with purple tone)
============================================================= */
:root, [data-theme="light"] {
  --font-body: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;

  /* ---- Type scale ---- */
  --text-xs:   clamp(0.75rem,  0.7rem  + 0.25vw, 0.875rem);
  --text-sm:   clamp(0.8125rem,0.78rem + 0.2vw,  0.9375rem);
  --text-base: clamp(0.9375rem,0.9rem  + 0.2vw,  1rem);
  --text-lg:   clamp(1.0625rem,1rem    + 0.4vw,  1.25rem);
  --text-xl:   clamp(1.375rem, 1.1rem  + 1.2vw,  1.75rem);

  /* ---- Spacing ---- */
  --sp-1:0.25rem; --sp-2:0.5rem;  --sp-3:0.75rem; --sp-4:1rem;
  --sp-5:1.25rem; --sp-6:1.5rem;  --sp-8:2rem;

  /* ---- Radius ---- */
  --r-sm:0.375rem; --r-md:0.625rem; --r-lg:0.875rem;
  --r-xl:1.125rem; --r-2xl:1.5rem;  --r-full:9999px;

  /* ---- Transition ---- */
  --ease: 180ms cubic-bezier(0.16,1,0.3,1);

  /* ---- Page surfaces (putih bersih) ---- */
  --bg:          #f4f2fb;   /* latar halaman: ungu sangat pucat */
  --bg-surface:  #ffffff;   /* kartu & konten utama */
  --bg-card:     #ffffff;
  --bg-card-2:   #f9f8ff;   /* input background */
  --bg-hover:    #f0ebff;   /* row hover */
  --border:      #e5e0f8;   /* border halus */
  --border-md:   #d0c9f2;   /* border form */

  /* ---- Text (berbasis indigo-950 agar kontras tinggi) ---- */
  --text:        #1e1b4b;   /* near-black purple — WCAG AAA pada bg putih */
  --text-muted:  #5b4e9b;   /* medium purple */
  --text-faint:  #a598d8;   /* placeholder, label tersier */

  /* ---- Purple primary palette ---- */
  --primary:       #6d28d9;  /* violet-700 — tombol, link utama */
  --primary-hover: #5b21b6;  /* violet-800 */
  --primary-glow:  rgba(109,40,217,0.15);
  --accent:        #7c3aed;  /* violet-600 */
  --accent-soft:   #ede9fe;  /* violet-100 */

  /* ---- Sidebar (ungu tua, warna dominan) ---- */
  --sidebar-bg:        #3b0764;  /* violet-950 — gelap, tua */
  --sidebar-bg-hover:  #4c1d95;  /* violet-900 */
  --sidebar-bg-active: rgba(167,139,250,0.18);
  --sidebar-text:      #c4b5fd;  /* violet-300 */
  --sidebar-text-muted:#8b7bc8;  /* redup */
  --sidebar-border:    rgba(196,181,253,0.1);
  --sidebar-label:     rgba(196,181,253,0.45);

  /* ---- Semantic colors ---- */
  --success:    #15803d;  --success-lt:  #dcfce7;
  --warning:    #b45309;  --warning-lt:  #fef3c7;
  --danger:     #dc2626;  --danger-lt:   #fee2e2;
  --info:       #0369a1;  --info-lt:     #e0f2fe;

  /* ---- Shadows (purple-tinted, light mode) ---- */
  --shadow-sm:   0 1px 3px rgba(109,40,217,.07), 0 1px 2px rgba(0,0,0,.04);
  --shadow-md:   0 4px 16px rgba(109,40,217,.09), 0 2px 6px rgba(0,0,0,.05);
  --shadow-lg:   0 12px 40px rgba(109,40,217,.13), 0 4px 12px rgba(0,0,0,.06);
  --shadow-glow: 0 0 20px rgba(109,40,217,.2);
}

/* ---- Dark mode (tersedia via toggle) ---- */
[data-theme="dark"] {
  --bg:          #0f0d18;
  --bg-surface:  #15122b;
  --bg-card:     #1c1730;
  --bg-card-2:   #221d38;
  --bg-hover:    #2a2445;
  --border:      rgba(167,139,250,0.12);
  --border-md:   rgba(167,139,250,0.2);
  --text:        #f0eeff;
  --text-muted:  #9b92c8;
  --text-faint:  #5e5687;
  --primary:       #7c3aed;
  --primary-hover: #6d28d9;
  --primary-glow:  rgba(124,58,237,0.25);
  --accent:        #a78bfa;
  --accent-soft:   rgba(167,139,250,0.12);
  --sidebar-bg:        #15122b;
  --sidebar-bg-hover:  #1e1940;
  --sidebar-bg-active: rgba(167,139,250,0.15);
  --sidebar-text:      #c4b5fd;
  --sidebar-text-muted:#7c6faa;
  --sidebar-border:    rgba(167,139,250,0.1);
  --sidebar-label:     rgba(167,139,250,0.35);
  --success:    #22c55e;  --success-lt:  rgba(34,197,94,0.12);
  --warning:    #f59e0b;  --warning-lt:  rgba(245,158,11,0.12);
  --danger:     #ef4444;  --danger-lt:   rgba(239,68,68,0.12);
  --info:       #38bdf8;  --info-lt:     rgba(56,189,248,0.12);
  --shadow-sm:   0 1px 3px rgba(0,0,0,.4), 0 0 0 1px rgba(167,139,250,.06);
  --shadow-md:   0 4px 16px rgba(0,0,0,.5), 0 0 0 1px rgba(167,139,250,.08);
  --shadow-lg:   0 12px 40px rgba(0,0,0,.6), 0 0 0 1px rgba(167,139,250,.1);
  --shadow-glow: 0 0 20px rgba(124,58,237,0.25);
}

/* ---- BASE ---- */
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body {
  font-family:var(--font-body);
  font-size:var(--text-base);
  color:var(--text);
  background:var(--bg);
  min-height:100dvh;
  -webkit-font-smoothing:antialiased;
  transition:background var(--ease), color var(--ease);
}

/* ============================================================
   LAYOUT SHELL
============================================================ */
.admina-wrap { display:flex; min-height:100dvh; }

/* ---- SIDEBAR ---- */
.admina-sidebar {
  width:260px;
  min-height:100dvh;
  background:var(--sidebar-bg);
  border-right:1px solid var(--sidebar-border);
  display:flex;
  flex-direction:column;
  padding:var(--sp-6) var(--sp-4);
  position:fixed;
  top:0; left:0;
  z-index:200;
  transition:transform var(--ease);
  overflow-y:auto;
}
.admina-main {
  margin-left:260px;
  flex:1;
  display:flex;
  flex-direction:column;
  min-height:100dvh;
}

/* Logo */
.sidebar-logo {
  display:flex; align-items:center; gap:var(--sp-3);
  padding:var(--sp-2) var(--sp-3);
  margin-bottom:var(--sp-6);
  text-decoration:none;
}
.sidebar-logo-icon {
  width:38px; height:38px;
  border-radius:var(--r-lg);
  background:linear-gradient(135deg,#7c3aed,#c084fc);
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 4px 14px rgba(124,58,237,.45);
  flex-shrink:0;
}
.sidebar-logo-text {
  font-size:var(--text-lg);
  font-weight:800;
  color:#fff;
  letter-spacing:-.02em;
}

/* Section labels */
.sidebar-label {
  color:var(--sidebar-label);
  font-size:0.6875rem;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.12em;
  padding:var(--sp-2) var(--sp-3);
  margin-top:var(--sp-5);
  margin-bottom:var(--sp-1);
  display:block;
}

/* Nav items */
.sidebar-nav { list-style:none; padding:0; margin:0; }
.sidebar-nav a {
  display:flex; align-items:center; gap:var(--sp-3);
  padding:var(--sp-3) var(--sp-4);
  border-radius:var(--r-lg);
  color:var(--sidebar-text-muted);
  text-decoration:none;
  font-size:var(--text-sm);
  font-weight:500;
  transition:background var(--ease), color var(--ease);
  margin-bottom:2px;
}
.sidebar-nav a:hover {
  background:var(--sidebar-bg-hover);
  color:var(--sidebar-text);
}
.sidebar-nav a.active {
  background:var(--sidebar-bg-active);
  color:#fff;
  font-weight:700;
  box-shadow:inset 3px 0 0 #c084fc;
}
.sidebar-nav a .nav-icon { width:18px; height:18px; flex-shrink:0; opacity:.7; }
.sidebar-nav a.active .nav-icon, .sidebar-nav a:hover .nav-icon { opacity:1; }
.sidebar-divider { height:1px; background:var(--sidebar-border); margin:var(--sp-5) 0; }
.sidebar-footer { margin-top:auto; padding-top:var(--sp-4); }

/* ---- TOPBAR ---- */
.admina-topbar {
  background:var(--bg-surface);
  border-bottom:1px solid var(--border);
  padding:var(--sp-4) var(--sp-6);
  display:flex;
  align-items:center;
  justify-content:space-between;
  position:sticky;
  top:0;
  z-index:100;
  box-shadow:var(--shadow-sm);
}
.topbar-title {
  font-size:var(--text-xl);
  font-weight:800;
  color:var(--text);
  letter-spacing:-.02em;
}
.topbar-right { display:flex; align-items:center; gap:var(--sp-3); }
.topbar-avatar {
  width:36px; height:36px;
  border-radius:var(--r-full);
  background:linear-gradient(135deg,var(--primary),#a855f7);
  color:#fff;
  font-weight:800;
  font-size:var(--text-sm);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  box-shadow:0 2px 8px rgba(109,40,217,.3);
  user-select:none;
}
.topbar-icon-btn {
  width:36px; height:36px;
  border-radius:var(--r-full);
  background:var(--accent-soft);
  border:1px solid var(--border);
  color:var(--primary);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  transition:background var(--ease), box-shadow var(--ease);
}
.topbar-icon-btn:hover { background:#ddd6fe; box-shadow:var(--shadow-glow); }

/* ---- CONTENT & FOOTER ---- */
.admina-content { padding:var(--sp-8) var(--sp-6); flex:1; }
.admina-footer {
  padding:var(--sp-5) var(--sp-6);
  border-top:1px solid var(--border);
  color:var(--text-faint);
  font-size:var(--text-xs);
  text-align:center;
  background:var(--bg-surface);
}

/* ============================================================
   COMPONENT OVERRIDES (Tabler)
============================================================ */
.card {
  background:var(--bg-card) !important;
  border:1px solid var(--border) !important;
  box-shadow:var(--shadow-sm) !important;
  border-radius:var(--r-xl) !important;
  color:var(--text) !important;
}
.card-header {
  border-bottom:1px solid var(--border) !important;
  background:transparent !important;
  padding:var(--sp-5) var(--sp-6) !important;
}
.card-footer { border-top:1px solid var(--border) !important; background:transparent !important; }
.card-title { color:var(--text) !important; font-weight:700 !important; font-size:var(--text-base) !important; }
.card-body { color:var(--text) !important; }
.page-wrapper, .page-body { background:var(--bg) !important; }
.form-control, .form-select {
  background:var(--bg-card-2) !important;
  border:1px solid var(--border-md) !important;
  color:var(--text) !important;
  border-radius:var(--r-lg) !important;
  font-size:var(--text-sm) !important;
  transition:border-color var(--ease), box-shadow var(--ease) !important;
}
.form-control:focus, .form-select:focus {
  border-color:var(--primary) !important;
  box-shadow:0 0 0 3px var(--primary-glow) !important;
  outline:none !important;
}
.form-control::placeholder { color:var(--text-faint) !important; }
.form-label { color:var(--text-muted) !important; font-size:var(--text-sm) !important; font-weight:600 !important; margin-bottom:var(--sp-2) !important; }
.text-muted { color:var(--text-muted) !important; }
.subheader { color:var(--text-muted) !important; font-size:var(--text-xs) !important; font-weight:700 !important; text-transform:uppercase !important; letter-spacing:.08em !important; }
.page-link { background:var(--bg-card) !important; border-color:var(--border) !important; color:var(--text-muted) !important; border-radius:var(--r-md) !important; }
.page-item.active .page-link { background:var(--primary) !important; border-color:var(--primary) !important; color:#fff !important; }
.page-item.disabled .page-link { opacity:.4 !important; }

/* ============================================================
   BUTTONS
============================================================ */
.btn-purple {
  background:linear-gradient(135deg,var(--primary),#a855f7);
  color:#fff;
  border:none;
  border-radius:var(--r-lg);
  padding:var(--sp-3) var(--sp-5);
  font-size:var(--text-sm); font-weight:700;
  cursor:pointer;
  box-shadow:0 3px 12px rgba(109,40,217,.3);
  transition:opacity var(--ease), box-shadow var(--ease), transform var(--ease);
  font-family:var(--font-body);
}
.btn-purple:hover { opacity:.92; box-shadow:0 6px 20px rgba(109,40,217,.4); transform:translateY(-1px); }
.btn-ghost {
  background:var(--accent-soft);
  color:var(--primary);
  border:1px solid #ddd6fe;
  border-radius:var(--r-lg);
  padding:var(--sp-2) var(--sp-4);
  font-size:var(--text-sm); font-weight:600;
  cursor:pointer;
  transition:background var(--ease), box-shadow var(--ease);
  font-family:var(--font-body);
}
.btn-ghost:hover { background:#ddd6fe; box-shadow:var(--shadow-glow); }
.btn-danger-ghost {
  background:var(--danger-lt);
  color:var(--danger);
  border:1px solid rgba(220,38,38,.2);
  border-radius:var(--r-lg);
  padding:5px var(--sp-3);
  font-size:var(--text-xs); font-weight:700;
  cursor:pointer;
  transition:background var(--ease);
  font-family:var(--font-body);
}
.btn-danger-ghost:hover { background:#fecaca; }
.btn-detail-ghost {
  background:var(--accent-soft);
  color:var(--primary);
  border:1px solid #ddd6fe;
  border-radius:var(--r-lg);
  padding:5px var(--sp-3);
  font-size:var(--text-xs); font-weight:700;
  cursor:pointer;
  transition:background var(--ease);
  font-family:var(--font-body);
}
.btn-detail-ghost:hover { background:#ddd6fe; }

/* ============================================================
   BADGES
============================================================ */
.badge-purple { background:#ede9fe; color:#5b21b6; border-radius:var(--r-full); padding:2px 10px; font-size:var(--text-xs); font-weight:700; }
.badge-green  { background:#dcfce7; color:#15803d; border-radius:var(--r-full); padding:2px 10px; font-size:var(--text-xs); font-weight:700; }
.badge-yellow { background:#fef3c7; color:#b45309; border-radius:var(--r-full); padding:2px 10px; font-size:var(--text-xs); font-weight:700; }
.badge-red    { background:#fee2e2; color:#dc2626; border-radius:var(--r-full); padding:2px 10px; font-size:var(--text-xs); font-weight:700; }
.badge-blue   { background:#e0f2fe; color:#0369a1; border-radius:var(--r-full); padding:2px 10px; font-size:var(--text-xs); font-weight:700; }

/* ============================================================
   STAT CARD
============================================================ */
.stat-card {
  background:var(--bg-card);
  border:1px solid var(--border);
  border-radius:var(--r-xl);
  padding:var(--sp-6);
  box-shadow:var(--shadow-sm);
  position:relative; overflow:hidden;
  transition:box-shadow var(--ease), transform var(--ease);
}
.stat-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.stat-card::after {
  content:'';
  position:absolute;
  top:-30px; right:-30px;
  width:90px; height:90px;
  border-radius:var(--r-full);
  background:linear-gradient(135deg,var(--primary),#a855f7);
  opacity:.05;
  pointer-events:none;
}
.stat-icon { width:44px; height:44px; border-radius:var(--r-lg); display:flex; align-items:center; justify-content:center; font-size:1.25rem; margin-bottom:var(--sp-4); }
.stat-label { font-size:var(--text-xs); font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-muted); margin-bottom:var(--sp-2); }
.stat-value { font-size:var(--text-xl); font-weight:800; color:var(--text); margin-bottom:var(--sp-2); letter-spacing:-.03em; }
.stat-delta { font-size:var(--text-xs); font-weight:700; padding:2px 9px; border-radius:var(--r-full); }

/* ============================================================
   PROGRESS
============================================================ */
.admina-progress { height:8px; border-radius:var(--r-full); background:#ede9fe; overflow:hidden; }
.admina-progress-bar { height:100%; border-radius:var(--r-full); background:linear-gradient(90deg,var(--primary),#a855f7); transition:width .9s ease; }

/* ============================================================
   ACTIVITY
============================================================ */
.activity-item { display:flex; align-items:flex-start; gap:var(--sp-4); padding:var(--sp-4) 0; border-bottom:1px solid var(--border); }
.activity-item:last-child { border-bottom:none; }
.activity-dot { width:36px; height:36px; border-radius:var(--r-lg); display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.activity-text { font-size:var(--text-sm); color:var(--text); line-height:1.5; font-weight:500; }
.activity-time { font-size:var(--text-xs); color:var(--text-faint); margin-top:2px; }

/* ============================================================
   SELECT2 OVERRIDES
============================================================ */
.select2-container--bootstrap-5 .select2-selection {
  background:var(--bg-card-2) !important;
  border:1px solid var(--border-md) !important;
  border-radius:var(--r-lg) !important;
  color:var(--text) !important;
  min-height:40px !important;
}
.select2-dropdown {
  background:var(--bg-card) !important;
  border:1px solid var(--border-md) !important;
  border-radius:var(--r-lg) !important;
  box-shadow:var(--shadow-md) !important;
  z-index:9999 !important;
}
.select2-results__option { color:var(--text) !important; font-size:var(--text-sm) !important; }
.select2-results__option--highlighted { background:var(--accent-soft) !important; color:var(--primary) !important; }
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
  background:var(--accent-soft) !important;
  border:1px solid #ddd6fe !important;
  color:var(--primary) !important;
  border-radius:var(--r-full) !important;
  font-weight:600 !important;
}

/* ============================================================
   THEME TOGGLE
============================================================ */
.theme-toggle {
  background:var(--accent-soft);
  border:1px solid var(--border);
  color:var(--primary);
  width:36px; height:36px;
  border-radius:var(--r-full);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  transition:background var(--ease), box-shadow var(--ease);
}
.theme-toggle:hover { background:#ddd6fe; box-shadow:var(--shadow-glow); }

/* ============================================================
   MOBILE
============================================================ */
.sidebar-toggle { display:none; }
@media (max-width:991px) {
  .admina-sidebar { transform:translateX(-100%); box-shadow:var(--shadow-lg); }
  .admina-sidebar.open { transform:translateX(0); }
  .admina-main { margin-left:0; }
  .sidebar-toggle {
    display:flex; align-items:center; justify-content:center;
    width:36px; height:36px;
    border-radius:var(--r-lg);
    background:var(--accent-soft);
    border:1px solid var(--border);
    color:var(--primary);
    cursor:pointer;
  }
  .admina-content { padding:var(--sp-5) var(--sp-4); }
}
@media (max-width:576px) {
  .topbar-title { font-size:var(--text-lg); }
  .stat-value { font-size:var(--text-lg); }
}
</style>
</head>
<body>
<div class="admina-wrap">

<!-- ===== SIDEBAR ===== -->
<aside class="admina-sidebar" id="sidebar">
  <a href="/dashboard" class="sidebar-logo">
    <div class="sidebar-logo-icon">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
        <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
      </svg>
    </div>
    <span class="sidebar-logo-text">Admina</span>
  </a>

  <span class="sidebar-label">Menu Utama</span>
  <ul class="sidebar-nav">
    <li><a href="/dashboard" class="<?= $active_menu==='dashboard'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a></li>
    <li><a href="/users" class="<?= $active_menu==='users'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.85"/></svg>
      Pengguna
    </a></li>
    <li><a href="/products" class="<?= $active_menu==='products'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/><line x1="12" y1="12" x2="20" y2="7.5"/><line x1="12" y1="12" x2="12" y2="21"/><line x1="12" y1="12" x2="4" y2="7.5"/></svg>
      Produk
    </a></li>
    <li><a href="/orders" class="<?= $active_menu==='orders'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3-2l1-7H6.2"/></svg>
      Pesanan
    </a></li>
  </ul>

  <span class="sidebar-label">Analitik</span>
  <ul class="sidebar-nav">
    <li><a href="/reports" class="<?= $active_menu==='reports'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Laporan
    </a></li>
  </ul>

  <span class="sidebar-label">Sistem</span>
  <ul class="sidebar-nav">
    <li><a href="/settings" class="<?= $active_menu==='settings'?'active':'' ?>">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37c1 .608 2.296.07 2.572-1.065z"/></svg>
      Pengaturan
    </a></li>
  </ul>

  <div class="sidebar-divider"></div>
  <div class="sidebar-footer">
    <ul class="sidebar-nav">
      <li><a href="/logout" style="color:#f87171">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </a></li>
    </ul>
    <!-- User info di bawah sidebar -->
    <div style="display:flex;align-items:center;gap:var(--sp-3);margin-top:var(--sp-4);padding:var(--sp-3) var(--sp-3);border-radius:var(--r-lg);background:rgba(196,181,253,.08)">
      <div style="width:34px;height:34px;border-radius:var(--r-full);background:linear-gradient(135deg,#7c3aed,#c084fc);color:#fff;font-weight:800;font-size:var(--text-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= $initials ?></div>
      <div>
        <div style="font-size:var(--text-sm);font-weight:700;color:#e9d5ff"><?= htmlspecialchars($user_name) ?></div>
        <div style="font-size:var(--text-xs);color:var(--sidebar-text-muted)">Administrator</div>
      </div>
    </div>
  </div>
</aside>

<!-- ===== MAIN ===== -->
<div class="admina-main">
  <!-- TOPBAR -->
  <div class="admina-topbar">
    <div style="display:flex;align-items:center;gap:var(--sp-4)">
      <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="Buka sidebar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <span class="topbar-title"><?= htmlspecialchars($title) ?></span>
    </div>
    <div class="topbar-right">
      <button class="theme-toggle" id="themeToggle" aria-label="Ganti tema">
        <!-- Sun icon — default light mode -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
      </button>
      <button class="topbar-icon-btn" title="Notifikasi" aria-label="Notifikasi">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </button>
      <div class="topbar-avatar" title="<?= htmlspecialchars($user_name) ?>"><?= $initials ?></div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="admina-content"><?= $slot ?></div>

  <!-- FOOTER -->
  <div class="admina-footer">
    &copy; <?= date('Y') ?> Admina &mdash; PHP <?= PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION ?> &mdash; Tema Terang Ungu Tua
  </div>
</div>

</div><!-- /.admina-wrap -->

<!-- ===== JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
(function(){
  const root = document.documentElement;
  const btn  = document.getElementById('themeToggle');
  const sunSVG  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>';
  const moonSVG = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
  let light = true;
  if(btn) btn.addEventListener('click', function(){
    light = !light;
    root.setAttribute('data-theme', light ? 'light' : 'dark');
    btn.innerHTML = light ? sunSVG : moonSVG;
  });
  // Mobile sidebar
  document.addEventListener('click', function(e){
    const sb = document.getElementById('sidebar');
    if(sb && sb.classList.contains('open') && !sb.contains(e.target) && !e.target.closest('.sidebar-toggle')){
      sb.classList.remove('open');
    }
  });
})();
</script>
<?php if(!empty($extra_js)): ?><?= $extra_js ?><?php endif; ?>
</body>
</html>
