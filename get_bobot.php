<?php
// get_bobot.php
include 'config/db.php';
$id_jabatan = $_GET['id_jabatan'] ?? 0;
$stmt = $pdo->prepare("SELECT 
  IF(
    100 / (COUNT(*) + 1) = FLOOR(100 / (COUNT(*) + 1)),
    CAST(100 / (COUNT(*) + 1) AS UNSIGNED),
    ROUND(100 / (COUNT(*) + 1), 0)
  ) AS jumlah
FROM kpi
WHERE jabatan_id = ?");
$stmt->execute([$id_jabatan]);
$data = $stmt->fetch();
echo json_encode($data);
?>
