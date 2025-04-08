<?php
include 'config/db.php';
include 'includes/header.php';
?>
<div class="container mt-4">
  <h4>Manajemen Jabatan</h4>
  <form action="insert_jabatan.php" method="POST" class="mb-3">
    <div class="input-group">
      <input type="text" name="nama_jabatan" class="form-control" placeholder="Nama Jabatan" required>
      <button class="btn btn-primary">Tambah</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead><tr><th>Nama Jabatan</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php
        $data = $pdo->query("SELECT * FROM jabatan")->fetchAll();
        foreach ($data as $row) {
          echo "<tr><td>{$row['nama_jabatan']}</td><td><a href='delete_jabatan.php?id={$row['id']}' onclick=\"return confirm('Yakin hapus?')\" class='btn btn-sm btn-danger'>Hapus</a></td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
?>