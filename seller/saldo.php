<?php
session_start();

// Aktifkan error reporting untuk debugging (Hapus atau nonaktifkan di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan user_id tersedia dalam session
if (!isset($_SESSION['user_id'])) {
    die('User ID tidak ditemukan dalam session.');
}

$user_id = $_SESSION['user_id'];

// Sertakan koneksi database
include '../koneksi.php'; // Pastikan path benar

// Query untuk mengambil saldo dari tabel users dengan role 'seller'
$query = "SELECT saldo FROM users WHERE user_id = ? AND role = 'seller'";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Ambil saldo
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $saldo = $row['saldo'];
} else {
    $saldo = 0; // Default saldo jika tidak ditemukan atau bukan seller
}

/// Query untuk mengambil riwayat keuangan dari tabel order_items sesuai dengan user_id seller
$query_orders = "
SELECT oi.order_id, oi.nama, oi.harga * oi.kuantitas AS total, o.payment_time 
FROM order_items oi
JOIN products p ON oi.product_id = p.product_id
JOIN orders o ON oi.order_id = o.order_id
WHERE p.user_id = ?
ORDER BY o.payment_time DESC
";

$stmt_orders = $koneksi->prepare($query_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <title>Halaman Seller</title>

    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .sidebar header {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .sidebar ul {
            list-style-type: none;
            width: 100%;
        }

        .sidebar ul li {
            margin: 15px 0;
            width: 100%;
        }

        .sidebar ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 1rem;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #1e2124;
        }

        .saldo-card {
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: #ffffff;
            margin-left: 270px;
            /* Menambahkan margin untuk menghindari sidebar */
            position: relative;
        }

        .saldo-card h1 {
            font-size: 2rem;
        }

        .saldo-card p {
            font-size: 1.5rem;
        }

        .icon-saldo {
            font-size: 3rem;
            color: #007bff;
        }

        .saldo-info {
            font-size: 1rem;
            color: #666;
        }

        .history-table {
            margin-left: 270px;
            /* Menambahkan margin untuk menghindari sidebar */
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .saldo-card,
            .history-table {
                margin-left: 0;
                /* Mengubah margin saat tampilan kecil */
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <header>My App</header>
        <ul>
            <li><a href="upload_product.php"><i class="fas fa-qrcode"></i> Upload Product</a></li>
            <li><a href="pending_product.php"><i class="fas fa-link"></i> Pending Product</a></li>
            <li><a href="approved_products.php"><i class="fas fa-stream"></i> Product Success</a></li>
            <li><a href="saldo.php"><i class="fas fa-calendar-week"></i> Saldo</a></li>
        </ul>
    </div>

    <div class="container mt-5">
        <div class="saldo-card mb-5">
            <i class="fas fa-wallet icon-saldo"></i>
            <h1>Saldo Anda</h1>
            <p>Rp <?php echo number_format($saldo, 0, ',', '.'); ?></p>
            <div class="saldo-info">
            </div>
        </div>

        <div class="history-table">
            <h2>Riwayat Keuangan</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Nama Produk</th> <!-- Tambahkan kolom Nama Produk -->
                        <th>Total (Rp)</th>
                        <th>Waktu Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_order = $result_orders->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row_order['order_id']; ?></td>
                            <td><?php echo $row_order['nama']; ?></td> <!-- Tampilkan Nama Produk -->
                            <td><?php echo number_format($row_order['total'], 0, ',', '.'); ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row_order['payment_time'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>