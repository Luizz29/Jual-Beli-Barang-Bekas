<?php
// Koneksi ke database
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil ID produk dari form
    $id = $_POST['id'];

    // Hapus data terkait di tabel order_items
    $sqlOrderItems = "DELETE FROM order_items WHERE product_id = ?";
    $stmtOrderItems = $koneksi->prepare($sqlOrderItems);
    $stmtOrderItems->bind_param("i", $id);
    $stmtOrderItems->execute();

    // Hapus produk di tabel products
    $sqlProducts = "DELETE FROM products WHERE product_id = ?";
    $stmtProducts = $koneksi->prepare($sqlProducts);
    $stmtProducts->bind_param("i", $id);

    if ($stmtProducts->execute()) {
        header('Location: approved_products.php?status=success#');
        exit();
    } else {
        echo "Terjadi kesalahan saat menghapus produk: " . $koneksi->error;
    }
}
?>
