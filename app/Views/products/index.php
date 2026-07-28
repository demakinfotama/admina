<?php
ob_start();
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">&#128230; Daftar Produk</h3>
    <div class="card-options">
      <button class="btn btn-sm btn-primary" onclick="Swal.fire('Info','Fitur tambah produk segera hadir!','info')">&#10133; Tambah</button>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-vcenter card-table">
      <thead><tr><th>SKU</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr></thead>
      <tbody>
        <?php foreach ([
          ['PRD-001','Laptop ASUS VivoBook','Elektronik','Rp 8.500.000',12],
          ['PRD-002','Headphone Sony WH','Elektronik','Rp 1.200.000',45],
          ['PRD-003','Keyboard Mechanical','Aksesoris','Rp 750.000',30],
          ['PRD-004','Monitor LG 27"','Elektronik','Rp 3.200.000',8],
        ] as [$sku,$name,$cat,$price,$stock]): ?>
        <tr>
          <td class="text-muted font-monospace"><?= $sku ?></td>
          <td><?= htmlspecialchars($name) ?></td>
          <td><span class="badge bg-blue-lt"><?= $cat ?></span></td>
          <td class="fw-bold"><?= $price ?></td>
          <td><?= $stock <= 10 ? "<span class='text-danger fw-bold'>$stock</span>" : $stock ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$slot        = ob_get_clean();
$title       = 'Produk';
$active_menu = 'products';
$extra_js    = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>';
include __DIR__ . '/../layouts/app.php';
