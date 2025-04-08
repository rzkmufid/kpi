<?php
include 'config/db.php';

$id = $_GET['id'] ?? 0;

// First, get the jabatan_id related to the KPI to be deleted
$stmt = $pdo->prepare("SELECT jabatan_id FROM kpi WHERE id = ?");
$stmt->execute([$id]);
$jabatanIdData = $stmt->fetch();
$id_jabatan = $jabatanIdData['jabatan_id'] ?? 0;

// Delete the KPI
$stmt = $pdo->prepare("DELETE FROM kpi WHERE id = ?");
$stmt->execute([$id]);

// Update the bobot and skor for all KPIs with the same jabatan_id
if ($id_jabatan) {
    $stmt = $pdo->prepare("SELECT 
      IF(
        100 / COUNT(*) = FLOOR(100 / COUNT(*)),
        CAST(100 / COUNT(*) AS UNSIGNED),
        ROUND(100 / COUNT(*), 0)
      ) AS jumlah
    FROM kpi
    WHERE jabatan_id = ?");
    $stmt->execute([$id_jabatan]);
    $bobotData = $stmt->fetch();
    $newBobot = $bobotData['jumlah'] ?? 0;

    $updateStmt = $pdo->prepare("UPDATE kpi SET bobot = ? WHERE jabatan_id = ?");
    $updateStmt->execute([$newBobot, $id_jabatan]);

    $updateSkorStmt = $pdo->prepare("UPDATE kpi SET skor = (pencapaian / bobot) * 100 WHERE jabatan_id = ?");
    $updateSkorStmt->execute([$id_jabatan]);
}

header('Location: index.php');
?>

