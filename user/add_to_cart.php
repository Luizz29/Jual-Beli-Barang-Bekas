<?php
session_start();
include '../koneksi.php'; // Koneksi database
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");


$user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    // Cek apakah produk sudah ada di keranjang
    $check_cart = "SELECT * FROM carts WHERE user_id = $user_id AND product_id = $product_id";
    $result = $koneksi->query($check_cart);

    if ($result->num_rows > 0) {
        // Jika produk sudah ada, tambahkan kuantitas
        $update_cart = "UPDATE carts SET kuantitas = kuantitas + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $koneksi->query($update_cart);
    } else {
        // Tambahkan entri baru jika produk belum ada di keranjang
        $sql = "INSERT INTO carts (user_id, product_id, kuantitas) VALUES ('$user_id', '$product_id', '$quantity')";
        $koneksi->query($sql);
    }

    // Ambil total kuantitas terbaru dari keranjang
    $ambil = "SELECT SUM(kuantitas) AS total_kuantitas FROM carts WHERE user_id = $user_id";
    $hasil = $koneksi->query($ambil);
    $halo = $hasil->fetch_assoc();
    $total_kuantitas = $halo['total_kuantitas'];

    // Debugging: Pastikan semua data dikirimkan dengan benar
    error_log("Product ID: " . $product_id);
    error_log("User ID: " . $user_id);
    error_log("Total Kuantitas: " . $total_kuantitas);

    // Kirimkan respons dalam format JSON
    echo json_encode([
        'success' => true,
        'total_kuantitas' => $total_kuantitas
    ]);
} else {
    // Jika product_id tidak ada, kembalikan pesan gagal
    echo json_encode([
        'success' => false,
        'message' => 'Produk tidak ditemukan.'
    ]);
}
?>
