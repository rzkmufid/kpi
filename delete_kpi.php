<?php
include 'config/db.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM kpi WHERE id = ?");
$stmt->execute([$id]);
header('Location: index.php');
?>