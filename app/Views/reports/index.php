<?php
ob_start();
?>
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#128202; Laporan Penjualan</h3></div>
      <div class="card-body">
        <p class="text-muted">Halaman laporan — grafik dan export akan ditambahkan di sini.</p>
        <button class="btn btn-primary" onclick="Swal.fire('Info','Fitur export laporan PDF &amp; Excel segera hadir!','info')">&#128229; Export Laporan</button>
      </div>
    </div>
  </div>
</div>
<?php
$slot        = ob_get_clean();
$title       = 'Laporan';
$active_menu = 'reports';
$extra_js    = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>';
include __DIR__ . '/../layouts/app.php';
