<?php
include 'config/db.php';
if ($_POST) {
  $stmt = $pdo->prepare("UPDATE kpi SET jabatan_id=?, kpi=?, jenis_hasil=?, jumlah_dokumen=?, satuan_dokumen=?, bobot=?, pencapaian=?, target=?, skor=? WHERE id=?");
  $stmt->execute([
    $_POST['jabatan_id'], $_POST['kpi'], $_POST['jenis_hasil'], $_POST['jumlah_dokumen'], $_POST['satuan_dokumen'],
    $_POST['bobot'], $_POST['pencapaian'], $_POST['target'], $_POST['skor'], $_POST['id']
  ]);
}
header('Location: index.php');
?>
