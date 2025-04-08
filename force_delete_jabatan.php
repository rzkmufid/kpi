<?php
include 'config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Hapus dulu data dari KPI yang menggunakan jabatan ini
        $stmt = $pdo->prepare("DELETE FROM kpi WHERE jabatan_id = ?");
        $stmt->execute([$id]);

        // Hapus jabatan setelah relasi KPI dihapus
        $stmt = $pdo->prepare("DELETE FROM jabatan WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: jabatan.php');
        exit;
    } catch (PDOException $e) {
        echo "Gagal force delete: " . $e->getMessage();
    }
}
?>
