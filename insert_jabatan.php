<?php
include 'config/db.php';
if ($_POST['nama_jabatan']) {
  $stmt = $pdo->prepare("INSERT INTO jabatan (nama_jabatan) VALUES (?)");
  $stmt->execute([$_POST['nama_jabatan']]);
}
header('Location: add_kpi.php');
