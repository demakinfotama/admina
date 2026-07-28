<?php
ob_start();
?>
<div class="card">
  <div class="card-header"><h3 class="card-title">&#128722; Daftar Pesanan</h3></div>
  <div class="card-body p-0">
    <table class="table table-vcenter card-table">
      <thead><tr><th>#Order</th><th>Pelanggan</th><th>Total</th><th>Kurir</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ([
          ['ORD-001','Budi Santoso','Rp 8.500.000','JNE REG','success','Selesai'],
          ['ORD-002','Siti Rahma','Rp 1.200.000','SICEPAT','warning','Diproses'],
          ['ORD-003','Ahmad Fauzi','Rp 750.000','POS ID','danger','Dibatalkan'],
          ['ORD-004','Dewi Kartika','Rp 3.200.000','JNE YES','info','Dikirim'],
        ] as [$id,$name,$total,$kurir,$badge,$status]): ?>
        <tr>
          <td class="font-monospace text-muted"><?= $id ?></td>
          <td><?= htmlspecialchars($name) ?></td>
          <td class="fw-bold"><?= $total ?></td>
          <td><?= $kurir ?></td>
          <td><span class="badge bg-<?= $badge ?>-lt text-<?= $badge ?>"><?= $status ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$slot        = ob_get_clean();
$title       = 'Pesanan';
$active_menu = 'orders';
$extra_js    = '';
include __DIR__ . '/../layouts/app.php';
