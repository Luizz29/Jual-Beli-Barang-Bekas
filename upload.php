<?php
// Koneksi ke database
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $imageName = $_FILES['productImage']['name'];
    $imageTmpName = $_FILES['productImage']['tmp_name'];

    // Upload gambar ke folder upload
    $targetDir = "upload/";
    $targetFile = $targetDir . basename($imageName);

    if (move_uploaded_file($imageTmpName, $targetFile)) {
        // Simpan data produk ke tabel pending_products
        $sql = "INSERT INTO pending_products (name, price, image) VALUES ('$productName', '$productPrice', '$targetFile')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Produk telah diunggah dan menunggu persetujuan.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah gambar.";
    }
}
?>
