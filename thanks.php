<?php
session_start();

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek apakah email dan user_id tersedia di session
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
} else {
  die('Email tidak ditemukan dalam session.');
}

if(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
} else {
  die('User ID tidak ditemukan dalam session.');
}

// Mendapatkan total belanja dan order_id dari session
$totalBelanja = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
$order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : 'Unknown';
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Sertakan koneksi database dan PHPMailer
include 'koneksi.php';
require 'assets/PHPMailer/src/Exception.php';
require 'assets/PHPMailer/src/PHPMailer.php';
require 'assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
  // Konfigurasi SMTP PHPMailer
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'bintangalfaluis@gmail.com';
  $mail->Password = 'wstp cmln novy nozy';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = 465;

  // Pengaturan email
  $mail->setFrom('bintangalfaluis@gmail.com', 'Second Chance');
  $mail->addAddress($email);

  // Buat tabel item dari keranjang untuk ditampilkan di email
  $itemTable = '<table border="1" cellpadding="10" cellspacing="0">';
  $itemTable .= '<tr><th>Nama Produk</th><th>Harga</th><th>Kuantitas</th><th>Total</th></tr>';
  foreach ($cart as $item) {
      $itemTotal = $item['price'] * $item['quantity'];
      $itemTable .= '<tr>';
      $itemTable .= '<td>' . htmlspecialchars($item['name']) . '</td>';
      $itemTable .= '<td>Rp ' . number_format($item['price'], 0, ',', '.') . '</td>';
      $itemTable .= '<td>' . htmlspecialchars($item['quantity']) . '</td>';
      $itemTable .= '<td>Rp ' . number_format($itemTotal, 0, ',', '.') . '</td>';
      $itemTable .= '</tr>';
  }
  $itemTable .= '</table>';

  // Isi email
  $mail->isHTML(true);
  $mail->Subject = 'Pembayaran Second Chance';
  $mail->Body = '
    <h1>Terima Kasih atas Pesanan Anda!</h1>
    <p><strong>Order ID:</strong> ' . htmlspecialchars($order_id) . '</p>
    <p><strong>Total Pembayaran:</strong> Rp ' . number_format($totalBelanja, 0, ',', '.') . '</p>
    <h2>Detail Produk:</h2>' . $itemTable . '
    <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>';

  // Kirim email
  $mail->send();
  echo '
  <div class="container email-sent-container">
    <div class="email-sent-card text-center">
      <img src="https://img.icons8.com/ios-filled/100/ffffff/sent.png" alt="Email Sent Icon">
      <h1>Email Terkirim!</h1>
      <p>Detail pesanan Anda telah dikirim ke email Anda. Silakan periksa email untuk konfirmasi lebih lanjut.</p>
      <a href="index.php" class="btn btn-back-home mt-3">Kembali ke Beranda</a>
    </div>
  </div>
';


  // Status order berhasil
  $status = 'success';

  // Simpan order ke database
  $stmt = $koneksi->prepare("INSERT INTO orders (order_id, user_id, total, status, payment_time) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("siss", $order_id, $user_id, $totalBelanja, $status);
  $stmt->execute();
  $stmt->close();

  // Mulai transaksi database
  $koneksi->begin_transaction();

  // Ambil item keranjang berdasarkan user_id
  $query = "SELECT c.product_id, c.kuantitas, p.nama, p.user_id AS seller_id FROM carts c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
  $stmt = $koneksi->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
      $product_id = $row['product_id'];
      $kuantitas = $row['kuantitas'];
      $product_name = $row['nama'];
      $seller_id = $row['seller_id'];

      // Kurangi stok produk
      $updateStokQuery = "UPDATE products SET stok = stok - ? WHERE product_id = ?";
      $updateStokStmt = $koneksi->prepare($updateStokQuery);
      $updateStokStmt->bind_param("ii", $kuantitas, $product_id);
      $updateStokStmt->execute();

      // Pastikan stok tidak negatif
      if ($updateStokStmt->affected_rows < 1) {
          echo "Gagal mengurangi stok produk dengan ID $product_id.";
          $koneksi->rollback();
          exit();
      }

      // Tambahkan item ke order_items
      $productCheckQuery = "SELECT harga FROM products WHERE product_id = ?";
      $productCheckStmt = $koneksi->prepare($productCheckQuery);
      $productCheckStmt->bind_param("i", $product_id);
      $productCheckStmt->execute();
      $productCheckResult = $productCheckStmt->get_result();

      if ($productCheckResult->num_rows > 0) {
          $product = $productCheckResult->fetch_assoc();
          $harga = $product['harga'];

          $insertOrderItemsQuery = "INSERT INTO order_items (order_id, product_id, kuantitas, harga, nama) VALUES (?, ?, ?, ?, ?)";
          $stmtInsert = $koneksi->prepare($insertOrderItemsQuery);
          $stmtInsert->bind_param("siids", $order_id, $product_id, $kuantitas, $harga, $product_name);
          $stmtInsert->execute();

          // Update saldo seller
          $totalPembayaran = $harga * $kuantitas;
          $updateSaldoQuery = "UPDATE users SET saldo = saldo + ? WHERE user_id = ?";
          $updateStmt = $koneksi->prepare($updateSaldoQuery);
          $updateStmt->bind_param("di", $totalPembayaran, $seller_id);
          $updateStmt->execute();
      }
  }

  // Selesaikan transaksi
  $koneksi->commit();

  // Hapus keranjang setelah selesai checkout
  unset($_SESSION['cart']);
  $deleteCartQuery = "DELETE FROM carts WHERE user_id = ?";
  $stmtDeleteCart = $koneksi->prepare($deleteCartQuery);
  $stmtDeleteCart->bind_param("i", $user_id);
  $stmtDeleteCart->execute();
  $stmtDeleteCart->close();

} catch (Exception $e) {
  // Rollback transaksi jika terjadi kesalahan
  $koneksi->rollback();
  echo "Pesan error: {$mail->ErrorInfo}<br>";
  echo "Kesalahan menyimpan order: " . $e->getMessage();
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Pembayaran Berhasil</title>

  <style>
    body { background-color: #1c1c1c; color: #fff; }
    .thankyou-section { display: flex; justify-content: center; align-items: center;}
    .thankyou-card { border-radius: 15px; padding: 30px; box-shadow: 0 0 30px rgba(0, 0, 0, 0.1); text-align: center; background-color: #006400; color: #fff; }
    .thankyou-card h1 { font-size: 2.5rem; }
    .thankyou-card p { font-size: 1.2rem; }
    .btn-custom { background-color: #fff; color: #28a745; font-size: 1.2rem; padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease; }
    .btn-custom:hover { background-color: #ddd; color: #28a745; }
    .order-summary { margin-top: 20px; font-size: 1rem; color: #fff; }

  </style>
</head>
<body>

  <div class="thankyou-section">
    <div class="thankyou-card mb-5">
      <img src="https://img.icons8.com/ios/100/ffffff/checked.png" alt="Success Icon">
      <h1>Terima Kasih!</h1>
      <p>Anda telah berhasil melakukan pembayaran.</p>
      <div class="order-summary">
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
        <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($totalBelanja, 0, ',', '.'); ?></p>
      </div>
      <a href="index.php" class="btn btn-custom mt-3">Kembali ke Beranda</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

