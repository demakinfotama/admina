<?php
ob_start();
?>
<div class="row row-cards">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#9881; Pengaturan Umum</h3></div>
      <div class="card-body">
        <form onsubmit="event.preventDefault(); Swal.fire({icon:'success',title:'Disimpan!',timer:1500,showConfirmButton:false})">
          <div class="mb-3"><label class="form-label">Nama Aplikasi</label><input type="text" class="form-control" value="Admina"></div>
          <div class="mb-3"><label class="form-label">Email Admin</label><input type="email" class="form-control" value="admin@example.com"></div>
          <div class="mb-3"><label class="form-label">Zona Waktu</label>
            <select class="form-select">
              <option selected>Asia/Jakarta (WIB)</option>
              <option>Asia/Makassar (WITA)</option>
              <option>Asia/Jayapura (WIT)</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">&#128190; Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
$slot        = ob_get_clean();
$title       = 'Pengaturan';
$active_menu = 'settings';
$extra_js    = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>';
include __DIR__ . '/../layouts/app.php';
