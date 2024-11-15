<?php
// Koneksi ke database
include '../koneksi.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sqlDeleteCart = "DELETE FROM carts WHERE user_id =?";
    $stmtDeleteCart = $koneksi->prepare($sqlDeleteCart);
    $stmtDeleteCart->bind_param("i", $userId);
    $stmtDeleteCart->execute();

    // Hapus data user berdasarkan user_id
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "<script>alert('Data pengguna berhasil dihapus'); window.location.href = 'kelola_user.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "<script>alert('ID pengguna tidak ditemukan'); window.location.href = 'kelola_user.php';</script>";
}

$koneksi->close();
?>
