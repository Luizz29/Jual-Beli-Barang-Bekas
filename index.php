<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: form_login.php");
    exit();
}

include 'koneksi.php';

// Jika tombol "Masukan Keranjang" diklik
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;  // Selalu set default jumlah produk yang ditambahkan adalah 1

    // Cek apakah produk sudah ada di keranjang
    $check_cart = "SELECT * FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = $koneksi->query($check_cart);

    if ($result->num_rows > 0) {
        // Jika produk sudah ada di keranjang, update kuantitasnya
        $update_cart = "UPDATE carts SET kuantitas = kuantitas + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
        $koneksi->query($update_cart);
    } else {
        // Jika produk belum ada di keranjang, tambahkan entri baru
        $sql = "INSERT INTO carts (user_id, product_id, kuantitas) VALUES ('$user_id', '$product_id', '$quantity')";
        $koneksi->query($sql);
    }

    // Redirect ke halaman keranjang
    header("Location: index.php");
    exit();
}

// Inisialisasi query default untuk menampilkan semua produk
$sql = "SELECT * FROM products";
$result = $koneksi->query($sql);

// Logika untuk tombol Back
if (isset($_POST['back'])) {
    $sql = "SELECT * FROM products";
    $result = $koneksi->query($sql);
}

// Proses pencarian
if (isset($_POST['cari'])) {
    $keyword = trim($_POST['keyword']);

    if (!empty($keyword)) {
        // Gunakan prepared statement untuk pencarian
        $stmt = $koneksi->prepare("SELECT * FROM products WHERE nama LIKE ? OR deskripsi LIKE ?");
        $searchTerm = "%" . $keyword . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

// Ambil kuantitas produk dalam keranjang
$ambil = "SELECT SUM(kuantitas) AS total_kuantitas FROM carts WHERE user_id = ?";
$stmt_qty = $koneksi->prepare($ambil);
$stmt_qty->bind_param("i", $user_id);
$stmt_qty->execute();
$qty_result = $stmt_qty->get_result();
$qty = $qty_result->fetch_assoc()['total_kuantitas'] ?? 0;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Second Chance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../image/chic (5).png" alt="" width="60" height="60" class="me-2">
            </a>
            <a class="navbar-brand" href="#"><strong class="text-danger">Second</strong> <strong>Chance</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex justify-content-center w-100">
                    <form method="POST" action="" class="d-flex me-auto" role="search">
                        <input type="text" name="keyword" class="form-control me-2" placeholder="Jelajahi barang anda!" aria-label="Search" autocomplete="off">
                        <button class="btn btn-danger" type="submit" name="cari">Search</button>
                    </form>
                </div>
            </div>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="user/order_history.php">Order History</a></li>
                <li class="nav-item"><a class="nav-link" href="user/profile.php">Profile</a></li>
                <li>
                    <a href="../user/cart.php">
                        <button type="button" class="btn btn-secondary position-relative mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $qty ?></span>
                        </button>
                    </a>
                </li>
                <li class="nav-item ms-3"><a href="logout.php" class="btn btn-danger mt-2"><b>Logout</b></a></li>
            </ul>
        </div>
    </nav>

    <div id="carouselExample" class="carousel slide mt-4 mb-3" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="image/2.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="image/2.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="image/2.png" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!--perulangan product-->
    <div class="container py-5">
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4 justify-content-center">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card product-card text-center h-100">
                            <a href="product_detail.php?id=<?php echo $row['product_id']; ?>">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top mx-auto d-block" alt="Product Image">
                                <div class="product-info">
    <p class="product-title"><?php echo htmlspecialchars($row['nama']); ?></p>
    <p class="product-price">Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
    <p class="product-stock">Stok Tersedia : <?php echo number_format($row['stok']); ?></p>

    <?php
    // Limit the description length
    $maxLength = 100; // Set maximum length of the description
    $description = htmlspecialchars($row['deskripsi']);
    if (strlen($description) > $maxLength) {
        $shortDescription = substr($description, 0, $maxLength) . '... ';
        echo $shortDescription;
        echo '<a href="product_detail.php?id=' . $row['product_id'] . '" class="text-danger">Baca selengkapnya</a>';
    } else {
        echo $description;
    }
    ?>
</div>

                            </a>
                            <div class="card-footer bg-transparent border-0">
                                <form action="" method="POST" class="add-to-cart-form mb-2">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <button class="btn btn-danger btn-sm w-100" type="submit" name="add_to_cart">Masukan Keranjang</button>
                                </form>
                                <form action="../user/process_checkout.php" method="GET" class="checkout-form">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-shopping-bag"></i> Pesan Sekarang
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <h5>Tidak ada produk ditemukan!</h5>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($_POST['cari']) && !empty($keyword)): ?>
            <div class="text-center mt-4">
                <form method="POST" action="">
                    <button type="submit" name="back" class="btn btn-secondary">Kembali</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    // Mengambil semua form dengan kelas .add-to-cart-form
    document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form normal

            // Disable tombol untuk mencegah multiple submissions
            let submitButton = this.querySelector('button[name="add_to_cart"]');
            submitButton.disabled = true;

            // Kirim data dengan fetch
            const formData = new FormData(this);

            fetch('user/add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Parsing response ke JSON
                .then(data => {
                    if (data.success) {
                        // Enable the button again after success
                        submitButton.disabled = false;

                        // Update jumlah item keranjang di navbar
                        document.querySelector('.badge').textContent = data.total_kuantitas;

                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Produk telah ditambahkan ke keranjang!',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>


</html>