<?php
session_start();
include '../koneksi.php'; // Pastikan path sudah benar

// Periksa apakah user telah login
if(!isset($_SESSION['user_id'])){
    die('Anda harus login terlebih dahulu.');
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan riwayat order sukses berdasarkan user_id
$query = "SELECT orders.order_id, orders.payment_time, orders.total, orders.status, 
                 GROUP_CONCAT(order_items.nama SEPARATOR ', ') AS produk,
                 GROUP_CONCAT(order_items.harga SEPARATOR ', ') AS harga,
                 SUM(order_items.kuantitas) AS kuantitas
          FROM orders
          JOIN order_items ON orders.order_id = order_items.order_id
          WHERE orders.user_id = ? AND orders.status = 'success'
          GROUP BY orders.order_id
          ORDER BY orders.payment_time DESC";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #4c5b70 100%);
            font-family: 'Poppins', sans-serif;
            color: #f8f9fa;
        }

        .order-history-container {
            margin-top: 50px;
        }

        .order-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            background-color: #1c2833;
            margin-bottom: 30px;
            padding: 20px;
            transition: all 0.3s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .order-info {
            flex-grow: 1;
        }

        .order-info h4 {
            font-weight: 600;
            color: #f39c12;
        }

        .order-info p {
            margin: 0;
            font-weight: 400;
            color: #bdc3c7;
        }

        .order-total {
            font-size: 1.2rem;
            font-weight: 600;
            color: #28a745;
        }

        .btn-custom {
            background-color: #ff5722;
            color: #fff;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #e64a19;
        }

        @media (max-width: 768px) {
            .order-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-card img {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container order-history-container">
        <h2 class="text-center mb-5">Riwayat Order Anda</h2>

        <?php
        // Tampilkan riwayat order
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo '<div class="order-card">';
                echo '<img src="../image/success check.png" alt="Produk">'; // Placeholder gambar produk
                echo '<div class="order-info">';
                echo '<h4>Pesanan #' . htmlspecialchars($row['order_id']) . '</h4>';
                echo '<p class="order-summary">Tanggal Pesanan: ' . htmlspecialchars($row['payment_time']) . '</p>';
                echo '<p class="order-details">Produk: ' . htmlspecialchars($row['produk']) . '</p>'; // Memanggil alias produk
                echo '<p class="order-total">Total: Rp ' . number_format($row['total'], 0, ',', '.') . '</p>';
                echo '</div>';
                echo '<a href="detail_order.php?order_id=' . htmlspecialchars($row['order_id']) . '" class="btn btn-custom mt-3">Detail Pesanan</a>';
                echo '</div>';
            }
        } else {
            echo "<p>Tidak ada riwayat pesanan.</p>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
