<?php include 'config/db.php'; ?>
<?php include 'includes/header.php'; ?>

<style media="print">
  * {
    all: unset;
    display: revert;
  }

  body {
    background: #fff !important;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    color: #000;
  }

  .no-print {
    display: none !important;
  }

  .printable {
    margin: 0;
    padding: 0;
    width: 100%;
  }

  h3 {
    font-size: 18pt;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
    border: 1px solid #000;
    font-size: 11pt;
  }

  table th,
  table td {
    border: 1px solid #000;
    padding: 6px;
    text-align: left;
    color: #000;
  }

  table th {
    background-color: #000;
    color: #fff;
    font-weight: bold;
    vertical-align: middle !important;
    text-align: center !important;
  }
  .table-dark tr th{
    background-color: #FFF !important;
    color: #000 !important;
  }

  /* Progress bar dihilangkan saat print */
  .progress {
    display: none !important;
  }
</style>

<div class="container mt-5 printable">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data KPI Trans Padang</h3>
    <div class="no-print">
      <a href="add_kpi.php" class="btn btn-primary me-2">Tambah Data</a>
      <button class="btn btn-secondary" onclick="window.print()">Print</button>
    </div>
  </div>

  <?php
    $stmt = $pdo->query("SELECT k.*, j.nama_jabatan FROM kpi k LEFT JOIN jabatan j ON k.jabatan_id = j.id ORDER BY j.nama_jabatan");
    $data = $stmt->fetchAll();
    $current_jabatan = '';
  ?>

  <table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
      <tr class="text-center" style="vertical-align: middle !important; text-align: center !important;">
        <th>No</th>
        <th>Jabatan</th>
        <th>KPI</th>
        <th>Jenis Hasil</th>
        <th>Jumlah Dokumen</th>
        <th>Bobot (%)</th>
        <th>Pencapaian (%)</th>
        <th>Target (%)</th>
        <th>Skor</th>
        <th class="no-print">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $no = 1;
      foreach ($data as $row):
        $is_first_row_for_jabatan = $row['nama_jabatan'] !== $current_jabatan;
        if ($is_first_row_for_jabatan) {
          $current_jabatan = $row['nama_jabatan'];
          $jabatan_count = count(array_filter($data, fn($item) => $item['nama_jabatan'] === $current_jabatan));
        }

        // Warna progress bar sesuai skor
        $skor = (int)$row['skor'];
        if ($skor >= 80) {
          $bar_class = 'bg-success'; // Hijau
        } elseif ($skor >= 60) {
          $bar_class = 'bg-info'; // Biru
        } elseif ($skor >= 40) {
          $bar_class = 'bg-warning'; // Kuning
        } else {
          $bar_class = 'bg-danger'; // Merah
        }
      ?>
        <tr>
          <?php if ($is_first_row_for_jabatan): ?>
            <td rowspan="<?= $jabatan_count ?>"><?= htmlspecialchars($no++) ?></td>
            <td rowspan="<?= $jabatan_count ?>"><?= htmlspecialchars($row['nama_jabatan']) ?></td>
          <?php endif; ?>
          <td><?= htmlspecialchars($row['kpi']) ?></td>
          <td><?= htmlspecialchars($row['jenis_hasil']) ?></td>
          <td><?= htmlspecialchars($row['jumlah_dokumen'] . ' ' . $row['satuan_dokumen']) ?></td>
          <td><?= htmlspecialchars($row['bobot']) ?></td>
          <td><?= htmlspecialchars($row['pencapaian']) ?></td>
          <td><?= htmlspecialchars($row['target']) ?></td>
          <td>
            <div class="progress" style="height: 20px;">
              <div class="progress-bar <?= $bar_class ?>" role="progressbar"
                style="width: <?= $skor ?>%;"
                aria-valuenow="<?= $skor ?>" aria-valuemin="0" aria-valuemax="100">
                <?= $skor ?>%
              </div>
            </div>
          </td>
          <td class="no-print">
            <a href="edit_kpi.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" style="width: 100%; height: 30px;">Edit</a>
            <a href="delete_kpi.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data KPI <?= htmlspecialchars($row['kpi']) ?>?')" style="width: 100%; height: 30px;">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>
            