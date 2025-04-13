<?php
include 'config/db.php';
if ($_POST) {
  $pdo->beginTransaction();
  try {
    // Update all existing records with the same jabatan_id to have the new satuan_dokumen
    $updateStmt = $pdo->prepare("UPDATE kpi SET bobot = ? WHERE jabatan_id = ?");
    $updateStmt->execute([$_POST['bobot'], $_POST['jabatan_id']]);
    
    // Update all existing records with the same jabatan_id to have the new skor
    $updateSkorStmt = $pdo->prepare("UPDATE kpi SET skor = ? * (pencapaian / ?) WHERE jabatan_id = ?");
    $updateSkorStmt->execute([$_POST['bobot'], $_POST['target'], $_POST['jabatan_id']]);
    
    // Insert the new KPI record
    $insertStmt = $pdo->prepare("INSERT INTO kpi (jabatan_id, kpi, jenis_hasil, jumlah_dokumen, satuan_dokumen, bobot, pencapaian, target, skor)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->execute([
      $_POST['jabatan_id'], $_POST['kpi'], $_POST['jenis_hasil'], $_POST['jumlah_dokumen'], $_POST['satuan_dokumen'],
      $_POST['bobot'], $_POST['pencapaian'], $_POST['target'], $_POST['bobot'] * ($_POST['pencapaian'] / $_POST['target'])
    ]);
    
    $pdo->commit();
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
  }
}
header('Location: index.php');
?>

