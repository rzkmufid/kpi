<?php
include 'config/db.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM jabatan WHERE id = ?");
$stmt->execute([$id]);
header('Location: jabatan.php');
?>