<?php
include '../koneksi.php'; // Koneksi ke database

// Mengambil produk yang belum disetujui dari tabel pending_products
$sql = "SELECT * FROM pending_products";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Approval</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <style>
        /* CSS reset and basic styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #1c1c1c;
            color: #f1f1f1;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #2e2e2e;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar header {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-weight: bold;
            color: #ffffff;
            /* Mengubah warna menjadi putih */
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
            color: #f1f1f1;
            text-decoration: none;
            font-size: 1rem;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #f8b400;
            color: #1c1c1c;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        /* Main content styling */
        .main-content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #ffffff;
            /* Mengubah warna menjadi putih */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2e2e2e;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #3d3d3d;
        }

        tr:hover {
            background-color: #505050;
        }

        td img {
            border-radius: 5px;
            transition: transform 0.3s;
        }

        td img:hover {
            transform: scale(1.1);
        }

        .btn {
            padding: 8px 12px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
                width: 100%;
            }

            .sidebar {
                display: none;
                /* Hide sidebar on smaller screens */
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <header>Admin Dashboard</header>
        <ul>
            <li><a href="kelola_user.php"><i class="fas fa-user-cog"></i>Kelola User</a></li>
            <li><a href="product_pending_approval.php"><i class="fas fa-link"></i>Approve Product</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Products Pending Approval</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th> <!-- Tambahkan kolom Stok di sini -->
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="product-<?php echo $row['id']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['harga']); ?></td>
                            <td><?php echo htmlspecialchars($row['stok']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="50"></td>
                            <td>
                                <form method="POST" action="approve_product.php" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="status" value="approve">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form method="POST" action="reject_product.php" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No products pending approval.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>