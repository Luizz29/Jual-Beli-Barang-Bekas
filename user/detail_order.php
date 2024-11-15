<?php
session_start();
include '../koneksi.php'; // Pastikan path sudah benar

// Periksa apakah user telah login
if (!isset($_SESSION['user_id'])) {
    die('Anda harus login terlebih dahulu.');
}

// Ambil order_id dari URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Query untuk mendapatkan detail pesanan berdasarkan order_id dan user_id
$query = "SELECT orders.order_id, orders.payment_time, orders.total, orders.status, 
                 order_items.nama, order_items.harga, order_items.kuantitas, products.image
          FROM orders
          JOIN order_items ON orders.order_id = order_items.order_id
           JOIN products ON order_items.product_id = products.product_id
          WHERE orders.order_id = ? AND orders.user_id = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    die("Pesanan tidak ditemukan atau Anda tidak memiliki akses.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .detail-container {
            margin-top: 50px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            /* Enable flexbox layout */
            align-items: center;
            /* Vertically center items */
        }

        .product-image {
            width: 50px;
            /* Set image width */
            height: auto;
            /* Maintain aspect ratio */
            margin-right: 20px;
            /* Add space between image and text */
        }

        .product-details {
            flex-grow: 1;
            /* Allow the details to take up the remaining space */
        }

        .product-card h5 {
            font-weight: 600;
            color: #333;
        }

        .product-card p {
            color: #666;
            margin: 0;
        }

        .product-price {
            font-weight: 600;
            /* Adjust font weight for price */
        }

        .product-quantity {
            font-weight: 600;
            color: #007bff;
            /* Adjust color for quantity */
        }
    </style>
</head>

<body>
    <div class="container detail-container">
        <h2 class="text-center mb-5">Detail Pesanan #<?= htmlspecialchars($order['order_id']) ?></h2>

        <p><strong>Tanggal Pesanan:</strong> <?= htmlspecialchars($order['payment_time']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Total Pembayaran:</strong> Rp <?= number_format($order['total'], 0, ',', '.') ?></p>

        <?php
        // Tampilkan tiap item dalam pesanan
        $stmt->execute();
        $result = $stmt->get_result();
        while ($item = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo "<img src='" . $item['image'] . "' class='product-image' alt='Product Image'>";
            echo '<div class="product-details">';
            echo '<p class="product-price">Harga: Rp ' . number_format($item['harga'], 0, ',', '.') . '</p>';
            echo '<p class="product-quantity">Kuantitas: ' . htmlspecialchars($item['kuantitas']) . '</p>';
            echo '</div>'; // Close product-details
            echo '</div>'; // Close product-card

        }
        ?>

        <a href="order_history.php" class="btn btn-secondary mt-3">Kembali ke Riwayat Order</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>