<?php
ob_start();
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">&#128100; Daftar Pengguna</h3>
    <div class="card-options">
      <button class="btn btn-sm btn-primary" onclick="Swal.fire('Info','Fitur tambah pengguna segera hadir!','info')">&#10133; Tambah</button>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-vcenter card-table">
      <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ([
          [1,'Ahmad Fauzi','ahmad@email.com','Admin','active'],
          [2,'Siti Rahma','siti@email.com','Editor','active'],
          [3,'Budi Santoso','budi@email.com','Viewer','inactive'],
        ] as [$id,$name,$email,$role,$status]): ?>
        <tr>
          <td><?= $id ?></td>
          <td><?= htmlspecialchars($name) ?></td>
          <td><?= htmlspecialchars($email) ?></td>
          <td><span class="badge bg-blue-lt"><?= $role ?></span></td>
          <td><span class="badge bg-<?= $status==='active'?'success':'secondary' ?>-lt"><?= $status==='active'?'Aktif':'Nonaktif' ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$slot        = ob_get_clean();
$title       = 'Pengguna';
$active_menu = 'users';
$extra_js    = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>';
include __DIR__ . '/../layouts/app.php';
