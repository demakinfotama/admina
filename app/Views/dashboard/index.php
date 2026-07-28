<?php
// Collect slot output
ob_start();
?>

<!-- ===== STAT CARDS ===== -->
<div class="row row-deck row-cards mb-4">
  <?php
  $stats = [
    ['label'=>'Total Pengguna',   'value'=>'1,284',  'delta'=>'+12%',  'color'=>'blue',   'icon'=>'&#128100;'],
    ['label'=>'Produk Aktif',     'value'=>'342',    'delta'=>'+5%',   'color'=>'green',  'icon'=>'&#128230;'],
    ['label'=>'Pesanan Hari Ini', 'value'=>'87',     'delta'=>'+23%',  'color'=>'orange', 'icon'=>'&#128722;'],
    ['label'=>'Pendapatan',       'value'=>'Rp 24 Jt','delta'=>'+8%', 'color'=>'purple', 'icon'=>'&#128181;'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader"><?= $s['label'] ?></div>
          <div class="ms-auto"><span class="badge bg-<?= $s['color'] ?>-lt"><?= $s['delta'] ?></span></div>
        </div>
        <div class="h1 mb-0"><?= $s['value'] ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ===== CAROUSEL ===== -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#128248; Promo &amp; Pengumuman</h3></div>
      <div class="card-body p-0">
        <div id="carouselDemo" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselDemo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselDemo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselDemo" data-bs-slide-to="2"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div style="background:linear-gradient(135deg,#206bc4,#4dabf7);height:200px;display:flex;align-items:center;justify-content:center;border-radius:0 0 4px 4px">
                <div class="text-center text-white">
                  <div style="font-size:2.5rem">&#127881;</div>
                  <h3>Selamat Datang di Admina!</h3>
                  <p class="mb-0 opacity-75">Panel admin ringan, cepat, dan mudah dikustomisasi.</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div style="background:linear-gradient(135deg,#2fb344,#74c69d);height:200px;display:flex;align-items:center;justify-content:center;">
                <div class="text-center text-white">
                  <div style="font-size:2.5rem">&#128200;</div>
                  <h3>Penjualan Bulan Ini Naik 23%</h3>
                  <p class="mb-0 opacity-75">Terus pertahankan performa yang luar biasa!</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div style="background:linear-gradient(135deg,#d63939,#f86a6a);height:200px;display:flex;align-items:center;justify-content:center;">
                <div class="text-center text-white">
                  <div style="font-size:2.5rem">&#128276;</div>
                  <h3>Update Sistem v2.1 Tersedia</h3>
                  <p class="mb-0 opacity-75">Fitur baru: export PDF, email blast, laporan harian.</p>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselDemo" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselDemo" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== TABLE + FORM ROW ===== -->
<div class="row row-cards mb-4">

  <!-- Tabel Pesanan Terkini -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">&#128203; Pesanan Terkini</h3>
        <div class="card-options">
          <button class="btn btn-sm btn-outline-primary" onclick="demoExport()">&#128229; Export</button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table table-hover">
            <thead><tr>
              <th>#</th><th>Pelanggan</th><th>Produk</th><th>Total</th><th>Status</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php
            $orders = [
              [1,'Budi Santoso','Laptop ASUS','Rp 8.500.000','success','Selesai'],
              [2,'Siti Rahma','Headphone Sony','Rp 1.200.000','warning','Diproses'],
              [3,'Ahmad Fauzi','Keyboard Mech','Rp 750.000','danger','Dibatalkan'],
              [4,'Dewi Kartika','Monitor 27"','Rp 3.200.000','success','Selesai'],
              [5,'Rian Pratama','Mouse Logitech','Rp 450.000','info','Dikirim'],
            ];
            foreach ($orders as [$no,$name,$product,$total,$badge,$status]): ?>
            <tr>
              <td class="text-muted"><?= $no ?></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="avatar avatar-xs rounded bg-<?= $badge ?>-lt text-<?= $badge ?> fw-bold"><?= strtoupper(substr($name,0,1)) ?></span>
                  <?= htmlspecialchars($name) ?>
                </div>
              </td>
              <td><?= htmlspecialchars($product) ?></td>
              <td class="fw-bold"><?= $total ?></td>
              <td><span class="badge bg-<?= $badge ?>-lt text-<?= $badge ?>"><?= $status ?></span></td>
              <td>
                <button class="btn btn-sm btn-ghost-secondary" onclick="demoDetail(<?= $no ?>)">Detail</button>
                <button class="btn btn-sm btn-ghost-danger" onclick="demoDelete(<?= $no ?>)">Hapus</button>
              </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Pagination -->
      <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">Menampilkan <strong>1&ndash;5</strong> dari <strong>128</strong> entri</p>
        <ul class="pagination m-0 ms-auto">
          <li class="page-item disabled"><a class="page-link" href="#">&#8249;</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">&#8250;</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Form Tambah Produk -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#10133; Tambah Produk</h3></div>
      <div class="card-body">
        <form id="formDemo" onsubmit="demoSubmit(event)">
          <div class="mb-3">
            <label class="form-label required">Nama Produk</label>
            <input type="text" class="form-control" placeholder="Contoh: Laptop ASUS" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Kategori</label>
            <select class="form-select select2-demo" multiple="multiple" style="width:100%">
              <option value="elektronik">Elektronik</option>
              <option value="fashion">Fashion</option>
              <option value="makanan">Makanan &amp; Minuman</option>
              <option value="olahraga">Olahraga</option>
              <option value="otomotif">Otomotif</option>
              <option value="kesehatan">Kesehatan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" class="form-control" placeholder="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" class="form-control" placeholder="0" min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select select2-single" style="width:100%">
              <option value="">-- Pilih Status --</option>
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Non-aktif</option>
              <option value="habis">Stok Habis</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100">&#128190; Simpan Produk</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ===== BOTTOM ROW: Activity + Quick Stats ===== -->
<div class="row row-cards">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#9889; Aktivitas Terkini</h3></div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          <?php
          $activities = [
            ['&#128100;','Budi Santoso mendaftar sebagai member baru','5 menit lalu','blue'],
            ['&#128722;','Pesanan #087 telah dikonfirmasi','12 menit lalu','green'],
            ['&#9888;','Stok Laptop Gaming tinggal 3 unit','25 menit lalu','orange'],
            ['&#128231;','Email blast dikirim ke 1.240 subscriber','1 jam lalu','purple'],
            ['&#128274;','Login gagal dari IP 192.168.1.55','2 jam lalu','red'],
          ];
          foreach ($activities as [$icon,$text,$time,$color]): ?>
          <li class="d-flex align-items-start gap-3 mb-3">
            <span class="badge bg-<?= $color ?>-lt" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0"><?= $icon ?></span>
            <div>
              <div style="font-size:.875rem"><?= htmlspecialchars($text) ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= $time ?></div>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">&#127919; Target Bulan Ini</h3></div>
      <div class="card-body">
        <?php
        $targets = [
          ['Penjualan','78%','success'],
          ['Pendaftaran Member','52%','info'],
          ['Kepuasan Pelanggan','91%','success'],
          ['Produk Terjual','63%','warning'],
        ];
        foreach ($targets as [$label,$pct,$color]): ?>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <span style="font-size:.875rem"><?= $label ?></span>
            <span class="text-<?= $color ?> fw-bold" style="font-size:.875rem"><?= $pct ?></span>
          </div>
          <div class="progress progress-sm">
            <div class="progress-bar bg-<?= $color ?>" style="width:<?= $pct ?>"></div>
          </div>
        </div>
        <?php endforeach; ?>

        <hr class="my-3">
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-sm btn-success" onclick="demoSwal('success','Laporan berhasil dibuat!')">📄 Buat Laporan</button>
          <button class="btn btn-sm btn-warning" onclick="demoSwal('warning','Fitur sedang dalam pengembangan.')">&#9888; Notifikasi</button>
          <button class="btn btn-sm btn-danger"  onclick="demoConfirm()">&#128465; Reset Data</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$slot = ob_get_clean();

$title       = $title ?? 'Dashboard';
$active_menu = 'dashboard';
$extra_js = <<<'JS'
<script>
// Select2
$(function(){
  $('.select2-demo').select2({
    theme: 'bootstrap-5',
    placeholder: 'Pilih kategori...',
    width: '100%',
  });
  $('.select2-single').select2({
    theme: 'bootstrap-5',
    width: '100%',
  });
});

// SweetAlert helpers
function demoSwal(icon, msg) {
  Swal.fire({ icon, title: msg, timer: 2000, showConfirmButton: false });
}
function demoDetail(id) {
  Swal.fire({
    title: 'Detail Pesanan #00' + id,
    html: `<div class="text-start"><b>Status:</b> Diproses<br><b>Kurir:</b> JNE REG<br><b>Resi:</b> JNE${id}2026DEMO</div>`,
    icon: 'info',
    confirmButtonText: 'Tutup'
  });
}
function demoDelete(id) {
  Swal.fire({
    title: 'Hapus pesanan #00' + id + '?',
    text: 'Data yang dihapus tidak dapat dikembalikan.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonText: 'Batal',
    confirmButtonText: 'Ya, Hapus!'
  }).then(r => { if(r.isConfirmed) Swal.fire('Dihapus!','Pesanan telah dihapus.','success'); });
}
function demoConfirm() {
  Swal.fire({
    title: 'Reset semua data demo?',
    text: 'Ini hanya simulasi — tidak ada data yang benar-benar dihapus.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonText: 'Batal',
    confirmButtonText: 'Reset'
  }).then(r => { if(r.isConfirmed) Swal.fire('Reset!','Data demo direset.','success'); });
}
function demoExport() {
  Swal.fire({ icon:'success', title:'Export berhasil!', text:'File CSV sedang diunduh...', timer:2000, showConfirmButton:false });
}
function demoSubmit(e) {
  e.preventDefault();
  Swal.fire({ icon:'success', title:'Produk Disimpan!', text:'Data produk baru berhasil ditambahkan.', timer:2000, showConfirmButton:false });
}
</script>
JS;

include __DIR__ . '/../layouts/app.php';
