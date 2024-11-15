<?php
// Koneksi ke database
include '../koneksi.php';

// Query untuk mengambil produk yang sudah di-approve
$sqlApproved = "SELECT * FROM products WHERE status = 'approve'";
$resultApproved = $koneksi->query($sqlApproved);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Approved</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <style>
        /* Body styling */
        body {
            background-color: #1c1c1c; /* Warna background sama dengan halaman pending */
            color: #ffffff; /* Warna teks putih agar kontras */
        }

        /* Sidebar styling */
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

        .sidebar ul li a i {
            margin-right: 10px;
        }

        /* Main content styling */
        .container {
            margin-left: 260px;
            padding: 40px;
            width: calc(100% - 260px);
        }

        h2 {
            font-size: 2.5rem;
            color: #ffffff; /* Mengubah warna judul menjadi putih */
            margin-bottom: 30px;
        }

        /* Product card styling */
        .card {
            background-color: #282828; /* Warna kartu gelap untuk kontras */
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            color: #ffffff; /* Mengubah warna teks produk menjadi putih */
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1rem;
            color: #dddddd; /* Warna teks produk yang lebih cerah */
            margin-bottom: 15px;
        }

        .btn {
            margin: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
                width: 100%;
            }

            .sidebar {
                display: none;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <header>Second Chance</header>
    <ul>
        <li><a href="upload_product.php"><i class="fas fa-qrcode"></i>Upload Product</a></li>
        <li><a href="pending_product.php"><i class="fas fa-link"></i>Pending Product</a></li>
        <li><a href="approved_products.php"><i class="fas fa-stream"></i>Product Success</a></li>
        <li><a href="saldo.php"><i class="fas fa-calendar-week"></i>Saldo</a></li>
      
    </ul>
</div>

<!-- Main Content -->
<div class="container">
    <h2>Produk yang Sudah Di-approve</h2>
    <div class="row" id="approved-products">
        <?php if ($resultApproved->num_rows > 0): ?>
            <?php while ($row = $resultApproved->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card mb-4">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['nama']); ?></h5>
                            <p class="card-text">Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>

                            <!-- Form untuk Edit -->
                            <form action="edit_product.php" method="get">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="btn btn-warning">Edit</button>
                            </form>

                            <!-- Form untuk Hapus -->
                            <form action="delete_product.php" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                <input type="hidden" name="id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Tidak ada produk yang sudah di-approve.</p>
        <?php endif; ?>
    </div>
</div>

</body>

</html>
