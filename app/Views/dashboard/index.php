<?php
ob_start();
?>

<!-- ===== 4 STAT CARDS ===== -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;margin-bottom:1.5rem">
<?php
$stats = [
  ['Total Pengguna','1,284','+12%','&#128100;','var(--accent-soft)','var(--accent)','var(--success-lt)','var(--success)'],
  ['Produk Aktif','342','+5%','&#128230;','rgba(34,197,94,.1)','var(--success)','var(--success-lt)','var(--success)'],
  ['Pesanan Hari Ini','87','+23%','&#128722;','var(--warning-lt)','var(--warning)','var(--warning-lt)','var(--warning)'],
  ['Pendapatan','Rp 24 Jt','+8%','&#128181;','var(--danger-lt)','var(--danger)','var(--success-lt)','var(--success)'],
];
foreach($stats as [$label,$val,$delta,$icon,$ibg,$icol,$dbg,$dcol]): ?>
<div class="stat-card">
  <div class="stat-icon" style="background:<?= $ibg ?>;color:<?= $icol ?>"><?= $icon ?></div>
  <div class="stat-label"><?= $label ?></div>
  <div class="stat-value"><?= $val ?></div>
  <span class="stat-delta" style="background:<?= $dbg ?>;color:<?= $dcol ?>"><?= $delta ?></span>
</div>
<?php endforeach; ?>
</div>

<!-- ===== CAROUSEL ===== -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:1.5rem">
  <div style="padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
    <span style="font-weight:700;font-size:var(--text-base);color:var(--text)">&#128248; Promo &amp; Pengumuman</span>
    <span class="badge-purple">3 slide</span>
  </div>
  <div id="carouselMain" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators" style="bottom:12px">
      <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="0" class="active" style="border-radius:4px;width:24px;background:var(--primary)"></button>
      <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="1" style="border-radius:4px;width:12px;background:var(--border-md)"></button>
      <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="2" style="border-radius:4px;width:12px;background:var(--border-md)"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div style="background:linear-gradient(135deg,#3b1fa8 0%,#7c3aed 50%,#a855f7 100%);height:220px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
          <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 80% 50%,rgba(168,85,247,.25),transparent 70%)"></div>
          <div style="text-align:center;color:#fff;position:relative;z-index:1;padding:0 2rem">
            <div style="font-size:2.5rem;margin-bottom:.5rem">&#127881;</div>
            <h3 style="font-weight:800;font-size:1.35rem;margin-bottom:.35rem">Selamat Datang di Admina!</h3>
            <p style="opacity:.8;font-size:.9rem;margin:0">Panel admin modern — ungu gelap, elegan, dan powerful.</p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div style="background:linear-gradient(135deg,#14532d 0%,#16a34a 60%,#4ade80 100%);height:220px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
          <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 20% 50%,rgba(74,222,128,.2),transparent 70%)"></div>
          <div style="text-align:center;color:#fff;position:relative;z-index:1;padding:0 2rem">
            <div style="font-size:2.5rem;margin-bottom:.5rem">&#128200;</div>
            <h3 style="font-weight:800;font-size:1.35rem;margin-bottom:.35rem">Penjualan Bulan Ini Naik 23%</h3>
            <p style="opacity:.8;font-size:.9rem;margin:0">Performa luar biasa! Teruskan momentum ini.</p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div style="background:linear-gradient(135deg,#7f1d1d 0%,#ef4444 60%,#f87171 100%);height:220px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
          <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 20%,rgba(248,113,113,.2),transparent 70%)"></div>
          <div style="text-align:center;color:#fff;position:relative;z-index:1;padding:0 2rem">
            <div style="font-size:2.5rem;margin-bottom:.5rem">&#128276;</div>
            <h3 style="font-weight:800;font-size:1.35rem;margin-bottom:.35rem">Update Sistem v2.1 Tersedia</h3>
            <p style="opacity:.8;font-size:.9rem;margin:0">Fitur baru: export PDF, email blast, laporan harian.</p>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselMain" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselMain" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
  </div>
</div>

<!-- ===== TABLE + FORM ===== -->
<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;margin-bottom:1.5rem" class="dash-grid">

  <!-- Tabel Pesanan -->
  <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden">
    <div style="padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-weight:700;font-size:var(--text-base);color:var(--text)">&#128203; Pesanan Terkini</span>
      <button class="btn-ghost" onclick="demoExport()">&#128229; Export CSV</button>
    </div>
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr>
            <?php foreach(['#','Pelanggan','Produk','Total','Status','Aksi'] as $h): ?>
            <th style="padding:var(--sp-3) var(--sp-4);text-align:left;font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);border-bottom:1px solid var(--border);background:var(--bg-card-2)"><?= $h ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          $orders = [
            [1,'Budi Santoso','Laptop ASUS','Rp 8.500.000','green','Selesai'],
            [2,'Siti Rahma','Headphone Sony','Rp 1.200.000','yellow','Diproses'],
            [3,'Ahmad Fauzi','Keyboard Mech','Rp 750.000','red','Dibatalkan'],
            [4,'Dewi Kartika','Monitor 27"','Rp 3.200.000','green','Selesai'],
            [5,'Rian Pratama','Mouse Logitech','Rp 450.000','blue','Dikirim'],
          ];
          foreach ($orders as [$no,$name,$product,$total,$badge,$status]): ?>
          <tr style="transition:background var(--ease)" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
            <td style="padding:var(--sp-4);font-size:var(--text-sm);color:var(--text-faint);border-bottom:1px solid var(--border);font-weight:600"><?= $no ?></td>
            <td style="padding:var(--sp-4);border-bottom:1px solid var(--border)">
              <div style="display:flex;align-items:center;gap:var(--sp-3)">
                <div style="width:32px;height:32px;border-radius:var(--r-full);background:var(--accent-soft);color:var(--accent);font-weight:800;font-size:var(--text-xs);display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= strtoupper(substr($name,0,1)) ?></div>
                <span style="font-size:var(--text-sm);font-weight:600;color:var(--text)"><?= htmlspecialchars($name) ?></span>
              </div>
            </td>
            <td style="padding:var(--sp-4);font-size:var(--text-sm);color:var(--text-muted);border-bottom:1px solid var(--border)"><?= htmlspecialchars($product) ?></td>
            <td style="padding:var(--sp-4);font-size:var(--text-sm);font-weight:700;color:var(--text);border-bottom:1px solid var(--border)"><?= $total ?></td>
            <td style="padding:var(--sp-4);border-bottom:1px solid var(--border)"><span class="badge-<?= $badge ?>"><?= $status ?></span></td>
            <td style="padding:var(--sp-4);border-bottom:1px solid var(--border)">
              <div style="display:flex;gap:var(--sp-2)">
                <button class="btn-detail-ghost" onclick="demoDetail(<?= $no ?>)">Detail</button>
                <button class="btn-danger-ghost" onclick="demoDelete(<?= $no ?>)">Hapus</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <div style="padding:var(--sp-4) var(--sp-6);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:var(--sp-4);flex-wrap:wrap">
      <span style="font-size:var(--text-xs);color:var(--text-muted)">Menampilkan <strong style="color:var(--text)">1&ndash;5</strong> dari <strong style="color:var(--text)">128</strong> entri</span>
      <div style="display:flex;gap:var(--sp-2)">
        <?php foreach(['‹','1','2','3','›'] as $i=>$p): ?>
        <button style="width:34px;height:34px;border-radius:var(--r-lg);border:1px solid var(--border);background:<?= $p==='1'?'var(--primary)':'var(--bg-card-2)' ?>;color:<?= $p==='1'?'#fff':'var(--text-muted)' ?>;font-size:var(--text-sm);font-weight:700;cursor:pointer;transition:background var(--ease)"><?= $p ?></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Form Tambah Produk -->
  <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden">
    <div style="padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border)">
      <span style="font-weight:700;font-size:var(--text-base);color:var(--text)">&#10133; Tambah Produk</span>
    </div>
    <div style="padding:var(--sp-6)">
      <form id="formDemo" onsubmit="demoSubmit(event)">
        <div style="margin-bottom:var(--sp-5)">
          <label class="form-label">Nama Produk <span style="color:var(--danger)">*</span></label>
          <input type="text" class="form-control" placeholder="Contoh: Laptop ASUS" required>
        </div>
        <div style="margin-bottom:var(--sp-5)">
          <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
          <select class="form-select select2-multi" multiple="multiple" style="width:100%">
            <option>Elektronik</option><option>Fashion</option>
            <option>Makanan &amp; Minuman</option><option>Olahraga</option>
            <option>Otomotif</option><option>Kesehatan</option>
          </select>
        </div>
        <div style="margin-bottom:var(--sp-5)">
          <label class="form-label">Harga</label>
          <div style="display:flex">
            <span style="padding:var(--sp-3) var(--sp-4);background:var(--bg-card-2);border:1px solid var(--border-md);border-right:0;border-radius:var(--r-lg) 0 0 var(--r-lg);color:var(--text-muted);font-size:var(--text-sm);font-weight:700;white-space:nowrap">Rp</span>
            <input type="number" class="form-control" placeholder="0" style="border-radius:0 var(--r-lg) var(--r-lg) 0 !important">
          </div>
        </div>
        <div style="margin-bottom:var(--sp-5)">
          <label class="form-label">Status</label>
          <select class="form-select select2-single" style="width:100%">
            <option value="">-- Pilih Status --</option>
            <option>Aktif</option><option>Non-aktif</option><option>Stok Habis</option>
          </select>
        </div>
        <div style="margin-bottom:var(--sp-6)">
          <label class="form-label">Deskripsi</label>
          <textarea class="form-control" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
        </div>
        <button type="submit" class="btn-purple" style="width:100%;padding:var(--sp-3) var(--sp-5);font-size:var(--text-sm);border-radius:var(--r-lg)">&#128190; Simpan Produk</button>
      </form>
    </div>
  </div>
</div>

<!-- ===== BOTTOM ROW ===== -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem" class="dash-grid-2">

  <!-- Aktivitas -->
  <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden">
    <div style="padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border)">
      <span style="font-weight:700;font-size:var(--text-base);color:var(--text)">&#9889; Aktivitas Terkini</span>
    </div>
    <div style="padding:var(--sp-4) var(--sp-6)">
      <?php
      $acts = [
        ['&#128100;','Budi Santoso mendaftar sebagai member baru','5 menit lalu','var(--accent-soft)','var(--accent)'],
        ['&#128722;','Pesanan #087 telah dikonfirmasi','12 menit lalu','var(--success-lt)','var(--success)'],
        ['&#9888;','Stok Laptop Gaming tinggal 3 unit','25 menit lalu','var(--warning-lt)','var(--warning)'],
        ['&#128231;','Email blast ke 1.240 subscriber terkirim','1 jam lalu','var(--info-lt)','var(--info)'],
        ['&#128274;','Login gagal dari IP 192.168.1.55','2 jam lalu','var(--danger-lt)','var(--danger)'],
      ];
      foreach($acts as [$icon,$text,$time,$bg,$col]): ?>
      <div class="activity-item">
        <div class="activity-dot" style="background:<?= $bg ?>;color:<?= $col ?>"><?= $icon ?></div>
        <div>
          <div class="activity-text"><?= htmlspecialchars($text) ?></div>
          <div class="activity-time"><?= $time ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Target -->
  <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden">
    <div style="padding:var(--sp-5) var(--sp-6);border-bottom:1px solid var(--border)">
      <span style="font-weight:700;font-size:var(--text-base);color:var(--text)">&#127919; Target Bulan Ini</span>
    </div>
    <div style="padding:var(--sp-6)">
      <?php
      $targets = [
        ['Penjualan','78','linear-gradient(90deg,var(--primary),#a855f7)'],
        ['Pendaftaran Member','52','linear-gradient(90deg,#0ea5e9,#38bdf8)'],
        ['Kepuasan Pelanggan','91','linear-gradient(90deg,#16a34a,#4ade80)'],
        ['Produk Terjual','63','linear-gradient(90deg,#f59e0b,#fde68a)'],
      ];
      foreach($targets as [$label,$pct,$grad]): ?>
      <div style="margin-bottom:var(--sp-5)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--sp-2)">
          <span style="font-size:var(--text-sm);font-weight:600;color:var(--text)"><?= $label ?></span>
          <span style="font-size:var(--text-sm);font-weight:800;color:var(--accent)"><?= $pct ?>%</span>
        </div>
        <div class="admina-progress">
          <div class="admina-progress-bar" style="width:<?= $pct ?>%;background:<?= $grad ?>"></div>
        </div>
      </div>
      <?php endforeach; ?>

      <div style="height:1px;background:var(--border);margin:var(--sp-5) 0"></div>
      <div style="display:flex;gap:var(--sp-3);flex-wrap:wrap">
        <button class="btn-ghost" onclick="demoSwal('success','Laporan berhasil dibuat!')">&#128196; Buat Laporan</button>
        <button class="btn-ghost" style="color:var(--warning);background:var(--warning-lt)" onclick="demoSwal('warning','Fitur sedang dikembangkan.')">&#9888; Notifikasi</button>
        <button class="btn-danger-ghost" style="padding:var(--sp-2) var(--sp-4)" onclick="demoConfirm()">&#128465; Reset</button>
      </div>
    </div>
  </div>

</div>

<style>
@media(max-width:900px){
  .dash-grid  { grid-template-columns: 1fr !important; }
  .dash-grid-2{ grid-template-columns: 1fr !important; }
}
</style>

<?php
$slot        = ob_get_clean();
$title       = $title ?? 'Dashboard';
$active_menu = 'dashboard';
$extra_js    = <<<'JS'
<script>
$(function(){
  $('.select2-multi').select2({ theme:'bootstrap-5', placeholder:'Pilih kategori...', width:'100%' });
  $('.select2-single').select2({ theme:'bootstrap-5', width:'100%' });
});
function demoSwal(icon,msg){
  Swal.fire({icon,title:msg,timer:2000,showConfirmButton:false});
}
function demoDetail(id){
  Swal.fire({
    title:'Detail Pesanan #00'+id,
    background:'var(--bg-card)',color:'var(--text)',
    html:`<div style="text-align:left;font-size:.9rem">
      <b>Status:</b> Diproses<br>
      <b>Kurir:</b> JNE REG<br>
      <b>Resi:</b> JNE${id}2026DEMO
    </div>`,
    icon:'info', confirmButtonText:'Tutup',
    confirmButtonColor:'var(--primary)'
  });
}
function demoDelete(id){
  Swal.fire({
    title:'Hapus pesanan #00'+id+'?',
    text:'Data yang dihapus tidak dapat dikembalikan.',
    icon:'warning', showCancelButton:true,
    confirmButtonColor:'#ef4444',
    cancelButtonText:'Batal',
    confirmButtonText:'Ya, Hapus!',
    background:'var(--bg-card)',color:'var(--text)'
  }).then(r=>{ if(r.isConfirmed) Swal.fire('Dihapus!','Pesanan telah dihapus.','success'); });
}
function demoConfirm(){
  Swal.fire({
    title:'Reset semua data demo?',
    text:'Ini hanya simulasi — tidak ada data nyata yang dihapus.',
    icon:'warning', showCancelButton:true,
    confirmButtonColor:'#ef4444',
    cancelButtonText:'Batal', confirmButtonText:'Reset',
    background:'var(--bg-card)', color:'var(--text)'
  }).then(r=>{ if(r.isConfirmed) Swal.fire('Reset!','Data demo direset.','success'); });
}
function demoExport(){
  Swal.fire({icon:'success',title:'Export berhasil!',text:'File CSV sedang diunduh...',timer:2000,showConfirmButton:false});
}
function demoSubmit(e){
  e.preventDefault();
  Swal.fire({icon:'success',title:'Produk Disimpan!',text:'Data produk baru berhasil ditambahkan.',timer:2000,showConfirmButton:false});
}
</script>
JS;

include __DIR__ . '/../layouts/app.php';
