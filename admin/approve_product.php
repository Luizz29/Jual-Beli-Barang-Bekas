<?php
// Koneksi ke database
include '../koneksi.php';

$message = ""; // Inisialisasi variabel pesan

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $status = $_POST['status']; // Status bisa "approve" atau "reject"

    // Ambil data produk dari pending_products
    $sql = "SELECT * FROM pending_products WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($status === "approve") {
        // Pindahkan produk ke tabel products, termasuk deskripsi, user_id, stok, dan supporting_images
        $sql = "INSERT INTO products (nama, harga, deskripsi, image, supporting_images, user_id, stok) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssssii", $product['nama'], $product['harga'], $product['deskripsi'], $product['image'], $product['supporting_images'], $product['user_id'], $product['stok']);

        if ($stmt->execute()) {
            // Hapus produk dari tabel pending_products
            $sql = "DELETE FROM pending_products WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            $message = "Produk telah disetujui dan dipindahkan ke dashboard dengan stok yang ditentukan.";
        } else {
            $message = "Error saat memindahkan produk: " . $stmt->error;
        }
    } elseif ($status === "reject") {
        // Update status produk menjadi "reject"
        $sql = "UPDATE pending_products SET status = 'reject' WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $productId);
        if ($stmt->execute()) {
            $message = "Produk telah ditolak.";
        } else {
            $message = "Error saat menolak produk: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../seller/style.css">
    <title>Persetujuan Produk</title>
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tampilkan alert jika pesan ada dan lakukan redirect
        window.onload = function() {
            const message = <?php echo json_encode($message); ?>;
            if (message) {
                Swal.fire({
                    title: 'Info',
                    text: message,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = 'product_pending_approval.php'; // Redirect setelah alert ditutup
                });
            }
        }
    </script>
</head>
<body>
    <!-- Opsional: Tambahkan konten HTML di sini jika diperlukan -->
</body>
</html>
