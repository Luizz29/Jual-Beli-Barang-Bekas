<?php
// Koneksi ke database
include '../koneksi.php';

$message = ""; // Inisialisasi variabel pesan
$alertType = "success"; // Tipe notifikasi default

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];

    // Update status produk menjadi "reject"
    $sql = "UPDATE pending_products SET status = 'reject' WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        $message = "Produk telah ditolak.";
        $alertType = "success"; // Jika sukses, gunakan tipe alert success
    } else {
        $message = "Error saat menolak produk: " . $stmt->error;
        $alertType = "error"; // Jika terjadi error, gunakan tipe alert error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../seller/style.css">
    <title>Penolakan Produk</title>
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tampilkan alert jika pesan ada dan lakukan redirect
        window.onload = function() {
            const message = <?php echo json_encode($message); ?>;
            const alertType = <?php echo json_encode($alertType); ?>;

            if (message) {
                Swal.fire({
                    title: alertType === 'success' ? 'Berhasil' : 'Gagal',
                    text: message,
                    icon: alertType,
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
