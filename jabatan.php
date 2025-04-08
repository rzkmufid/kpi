<?php
include 'config/db.php';
include 'includes/header.php';

$deleteError = false;
$relatedKPI = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM jabatan WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: jabatan.php');
        exit;
    } catch (PDOException $e) {
        // Cek jika error karena constraint
        if ($e->getCode() === '23000') {
            $deleteError = true;

            // Ambil data KPI yang menggunakan jabatan ini
            $stmt = $pdo->prepare("SELECT id, kpi FROM kpi WHERE jabatan_id = ?");
            $stmt->execute([$id]);
            $relatedKPI = $stmt->fetchAll();
        } else {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
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
          echo "<tr><td>{$row['nama_jabatan']}</td>
                <td><a href='jabatan.php?id={$row['id']}' onclick=\"return confirm('Yakin hapus?')\" class='btn btn-sm btn-danger'>Hapus</a></td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<?php if ($deleteError): ?>
<!-- Modal Error -->
<div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Gagal Menghapus Jabatan</h5>
      </div>
      <div class="modal-body">
        <p>Jabatan ini sedang digunakan oleh data KPI berikut:</p>
        <ul>
          <?php foreach ($relatedKPI as $kpi): ?>
            <li><?= htmlspecialchars($kpi['kpi']) ?> (ID: <?= $kpi['id'] ?>)</li>
          <?php endforeach; ?>
        </ul>
        <p>Anda dapat membatalkan atau melakukan force delete (hapus juga semua KPI terkait).</p>
      </div>
      <div class="modal-footer">
        <a href="jabatan.php" class="btn btn-secondary">Batal</a>
        <a href="force_delete_jabatan.php?id=<?= htmlspecialchars($_GET['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin melakukan force delete?')">Force Delete</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
