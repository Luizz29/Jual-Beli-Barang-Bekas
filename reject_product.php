<?php
include 'koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];

    // Query untuk menghapus produk dari tabel pending_products
    $sql = "DELETE FROM pending_products WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        // Setelah berhasil dihapus, redirect kembali ke halaman utama dengan pesan sukses
        header("Location: home_admin.php?status=rejected_success");
        exit;
    } else {
        // Jika terjadi kesalahan, redirect kembali dengan pesan error
        header("Location: manage_pending_products.php?status=rejected_error");
        exit;
    }

    $stmt->close();
    $koneksi->close();
} else {
    // Jika tidak ada data POST, redirect kembali ke halaman utama
    header("Location: manage_pending_products.php");
    exit;
}
