<?php
// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: form_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = $koneksi->prepare("SELECT products.product_id, products.nama, products.harga, products.image, carts.kuantitas
                          FROM carts
                          JOIN products ON carts.product_id = products.product_id
                          WHERE carts.user_id = ?");
$sql->bind_param("i", $user_id);
if (!$sql->execute()) {
    die("Execute failed: (" . $sql->errno . ") " . $sql->error);
}
$result = $sql->get_result();
$total = 0;
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<style>
body {
    background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    font-family: 'Poppins', sans-serif;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    margin-top: 50px;
    max-width: 1200px;
    padding: 20px;
}

h2 {
    color: #2c3e50;
    font-weight: 700;
    font-size: 36px;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 30px;
    letter-spacing: 2px;
}

.table-dark {
    background-color: rgba(44, 62, 80, 0.9);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.table-dark thead {
    background: linear-gradient(135deg, #16a085, #f4d03f);
    color: white;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 18px;
}

.table-dark th, .table-dark td {
    vertical-align: middle;
    padding: 15px;
    font-size: 16px;
    color: white;
}

.table-dark td {
    border-bottom: 1px solid #34495e;
}
.back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background: #f8f9fa; /* Light background */
            border: 1px solid #007bff; /* Blue border */
            border-radius: 5px; /* Slightly rounded corners */
            color: #007bff; /* Blue text color */
            font-weight: bold;
            font-size: 16px;
            text-decoration: none; /* No underline for link */
            transition: background 0.3s, color 0.3s; /* Smooth transition */
        }

        .back-button:hover {
            background: #007bff; /* Blue background on hover */
            color: white; 
        }

.checkout-button {
    display: block;
    width: 100%;
    padding: 20px;
    background: #27ae60; /* Hijau solid yang modern */
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-top: 30px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); /* Shadow halus */
    transition: all 0.3s ease;
}

.checkout-button:hover {
    background: #2ecc71; /* Sedikit warna hijau lebih cerah saat hover */
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2); /* Shadow lebih dalam */
    transform: translateY(-3px); /* Efek naik sedikit saat di-hover */
}

.card {
    background: linear-gradient(135deg, #ecf0f1, #bdc3c7);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.card h3 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
}

.card p {
    font-size: 18px;
    color: #7f8c8d;
}

.form-control {
    border-radius: 50px;
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #ced4da;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-control:focus {
    border-color: #16a085;
    box-shadow: 0 0 10px rgba(22, 160, 133, 0.5);
}

.total-section {
    text-align: right;
    font-size: 24px;
    font-weight: 700;
    margin-top: 20px;
    color: #27ae60;
    text-transform: uppercase;
}

        </style>
    <div class="container py-5">
        <div class="card">
            <h2 class="text-center">Keranjang Belanja</h2>
            <a href="../index.php" class="back-button mx-auto">Kembali ke Dashboard</a>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-dark table-striped mt-4">
                    <thead>
                        <tr>
                            <th>Gambar Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php 
                                $subtotal = $row['harga'] * $row['kuantitas'];
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="width: 100px; height: auto;"></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm update-quantity" data-product-id="<?= $row['product_id'] ?>" data-action="decrease">-</button>
                                    <span id="quantity-<?= $row['product_id'] ?>"><?= $row['kuantitas'] ?></span>
                                    <button class="btn btn-primary btn-sm update-quantity" data-product-id="<?= $row['product_id'] ?>" data-action="increase">+</button>
                                </td>
                                <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-product" data-product-id="<?= $row['product_id'] ?>">Hapus</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="total-section">
                    <h3>Total: Rp<span id="total"><?= number_format($total, 0, ',', '.') ?></span></h3>
                </div>

                <!-- Form untuk detail pelanggan -->
                <div class="card mt-4 p-4">
                    <h4>Detail Pelanggan</h4>
                    <form action="checkout.php" method="POST">
                        <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <input type="tel" class="form-control" name="no_telp" placeholder="Nomor Telepon" required>
                        <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">

                        <button type="submit" class="checkout-button">Lanjutkan ke Pembayaran</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="text-center mt-4">Keranjang Anda kosong.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
$(document).ready(function() {
    $('.update-quantity').on('click', function() {
        var button = $(this);
        var product_id = button.data('product-id');
        var action = button.data('action');
        var quantityElement = button.siblings('span'); // Ambil elemen kuantitas
        var quantity = parseInt(quantityElement.text());

        // Ubah kuantitas berdasarkan aksi
        if (action === 'increase') {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        }

        // Update jumlah di tampilan
        quantityElement.text(quantity);

        $.ajax({
            url: 'update_quantity.php',
            method: 'POST',
            data: { product_id: product_id, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var newSubtotal = response.price * quantity; // Hitung subtotal baru

                    // Update subtotal di tampilan
                    button.closest('tr').find('td:nth-child(5)').text('Rp' + newSubtotal.toLocaleString('id-ID'));

                    // Update total keseluruhan
                    var newTotal = 0;
                    $('.table-dark tbody tr').each(function() {
                        var subtotalText = $(this).find('td:nth-child(5)').text().replace('Rp', '').replace(/\./g, '');
                        newTotal += parseInt(subtotalText);
                    });
                    $('#total').text(newTotal.toLocaleString('id-ID')); // Update total
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to update quantity.');
            }
        });
    });

    // Delete product
    $('.delete-product').click(function() {
        const productId = $(this).data('product-id');

        $.ajax({
            url: 'delete_cart.php',
            type: 'POST',
            data: { product_id: productId },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert(data.message);
                }
            }
        });
    });
});



            // Delete product
            $('.delete-product').click(function() {
                const productId = $(this).data('product-id');

                $.ajax({
                    url: 'delete_cart.php',
                    type: 'POST',
                    data: { product_id: productId },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            location.reload(); // Reload page to reflect changes
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
        
    </script>
</body>
</html>
