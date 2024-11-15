<?php
// Koneksi ke database
include '../koneksi.php';

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil produk yang belum disetujui
$sql = "SELECT * FROM pending_products";
$result = $koneksi->query($sql);

// Periksa apakah query berhasil
if (!$result) {
    die("Error dalam menjalankan query: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Pending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <style>
        body {
            background-color: #1c1c1c;
            color: white;
        }
        .img-carousel {
    max-width: 60%;  /* Mengatur gambar agar tidak terlalu besar, beri ruang untuk panah */
    height: auto;    /* Menjaga rasio gambar */
    margin: 0 auto;  /* Memastikan gambar terpusat secara horizontal */
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

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .container {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }

        .card {
            background-color: #343a40;
            border: none;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2.5rem;
            color: #f8f9fa;
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #495057;
            color: #f8f9fa;
        }

        .card-body {
            background-color: #343a40;
            color: #f8f9fa;
        }

        @media (min-width: 768px) {
            .card {
                width: 300px; /* Atur lebar card pada perangkat yang lebih besar */
            }
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
                width: 100%;
            }

            .sidebar {
                display: none;
            }

            .card {
                width: 100%; /* Lebar penuh pada perangkat kecil */
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <header>Second Chance</header>
    <ul>
        <li><a href="upload_product.php"><i class="fas fa-qrcode"></i>Unggah Produk</a></li>
        <li><a href="pending_product.php"><i class="fas fa-link"></i>Produk Pending</a></li>
        <li><a href="approved_products.php"><i class="fas fa-stream"></i>Produk Disetujui</a></li>
        <li><a href="saldo.php"><i class="fas fa-calendar-week"></i>Saldo</a></li>
    </ul>
</div>

<!-- Konten Utama -->
<div class="container mt-5">
    <h2>Produk Pending</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo htmlspecialchars($row['nama']); ?></h5>
                        </div>
                        <div id="carousel<?php echo $row['id']; ?>" class="carousel slide">
    <div class="carousel-inner">
        <!-- Gambar utama -->
        <div class="carousel-item active">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Gambar Utama" class="d-block w-100 img-carousel">
        </div>
        <?php
        // Ambil gambar pendukung jika ada
        $supportingImages = explode(',', $row['supporting_images']);
        foreach ($supportingImages as $image):
            if (!empty(trim($image))): ?>
                <div class="carousel-item">
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Gambar Pendukung" class="d-block w-100 img-carousel">
                </div>
            <?php endif;
        endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $row['id']; ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sebelumnya</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $row['id']; ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Selanjutnya</span>
    </button>
</div>


                        <div class="card-body">
                            <p><strong>Harga:</strong> <?php echo htmlspecialchars($row['harga']); ?></p>
                            <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada produk yang pending.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
