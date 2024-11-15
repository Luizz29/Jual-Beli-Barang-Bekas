<?php
session_start();
include '../koneksi.php';
require_once '../midtrans/examples/snap/checkout-process-simple-version.php';

// Panggil library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
include('../assets/phpmailer/src/Exception.php');
include('../assets/phpmailer/src/PHPMailer.php');
include('../assets/phpmailer/src/SMTP.php');

// Set your server key and client key
\Midtrans\Config::$serverKey = 'SB-Mid-server-jSnU9XQH-x9wkiS4ymQRrKUr';
\Midtrans\Config::$isProduction = false; // false untuk sandbox mode
\Midtrans\Config::$isSanitized = \Midtrans\Config::$is3ds = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $total = $_POST['total'];

    // Simpan total belanja dan order_id ke dalam session
    $_SESSION['total'] = $total;
    $order_id = 'ORDER-' . rand(); // Generate order ID secara random
    $_SESSION['order_id'] = $order_id;

    // Simpan email ke dalam session
    $_SESSION['email'] = $email;



    // Ambil data produk dari keranjang
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT products.nama, products.harga, carts.kuantitas
            FROM carts
            JOIN products ON carts.product_id = products.product_id
            WHERE carts.user_id = '$user_id'";
    $result = $koneksi->query($sql);

    $item_details = [];
    while ($row = $result->fetch_assoc()) {
        $item_details[] = array(
            'id' => $row['nama'],
            'price' => $row['harga'],
            'quantity' => $row['kuantitas'],
            'name' => $row['nama']
        );
    }

    // Simpan detail keranjang ke dalam session
    $_SESSION['cart'] = $item_details;

    // Siapkan detail transaksi
    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $total // total pembayaran
    );

    // Siapkan detail pelanggan
    $customer_details = array(
        'first_name' => $nama,
        'email' => $email,
        'phone' => $no_telp,
    );

    // Buat array transaksi untuk dikirim ke Midtrans
    $transaction = array(
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
    );

    try {
        // Dapatkan Snap Token
        $snap_token = \Midtrans\Snap::getSnapToken($transaction);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // Ambil URL ngrok terbaru
    $ngrokApiUrl = 'http://127.0.0.1:4040/api/tunnels';
    $response = file_get_contents($ngrokApiUrl);
    $tunnels = json_decode($response, true);
    $ngrokUrl = $tunnels['tunnels'][0]['public_url']; // Ambil URL publik
}
function saveOrder($order_id, $user_id, $total, $status, $payment_time, $order_items, $koneksi)
{
    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Insert ke tabel orders
        $stmt = $koneksi->prepare("INSERT INTO orders (order_id, user_id, total, status, payment_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidsi", $order_id, $user_id, $total, $status, $payment_time);
        $stmt->execute();
        $stmt->close();

        // Menyimpan detail produk dan update saldo seller
        foreach ($order_items as $item) {
            // Insert ke tabel order_items
            $stmt = $koneksi->prepare("INSERT INTO order_items (order_id, product_id, nama, harga, kuantitas, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdiss", $order_id, $item['product_id'], $item['name'], $item['price'], $item['quantity'], $item['image']);
            $stmt->execute();

            // Ambil user_id (seller) dari produk
            $product_id = $item['product_id'];
            $stmtSeller = $koneksi->prepare("SELECT user_id FROM products WHERE product_id = ?");
            $stmtSeller->bind_param("i", $product_id);
            $stmtSeller->execute();
            $result = $stmtSeller->get_result();
            $seller = $result->fetch_assoc();
            $seller_id = $seller['user_id'];

            // Update saldo seller
            $stmtUpdate = $koneksi->prepare("UPDATE users SET saldo = saldo + ? WHERE user_id = ?");
            $stmtUpdate->bind_param("di", $item['price'], $seller_id); // Asumsikan price adalah harga per item
            $stmtUpdate->execute();
            $stmtUpdate->close();
            $stmtSeller->close();

            // Kurangi stok produk berdasarkan kuantitas yang dibeli
            $stmtStock = $koneksi->prepare("UPDATE products SET stok = stok - ? WHERE product_id = ?");
            $stmtStock->bind_param("ii", $item['quantity'], $product_id);
            $stmtStock->execute();
            $stmtStock->close();
        }

        // Commit transaksi
        $koneksi->commit();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $koneksi->rollback();
        throw $e;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>

    <!-- Include Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for styling -->
    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            /* Background gradient */
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }

        .payment-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
        }

        #pay-button {
            background: #28a745;
            /* Bootstrap success green */
            border: none;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 50px;
            transition: 0.3s ease;
            cursor: pointer;
        }

        #pay-button:hover {
            background: #218838;
            /* Darker success green on hover */
            transform: scale(1.05);
        }

        .thanks-message {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <h2>Terima kasih, <?php echo htmlspecialchars($nama); ?>!</h2>
        <p>Silakan klik tombol di bawah ini untuk melanjutkan pembayaran Anda.</p>
        <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>

        <div class="thanks-message">
            <p>Total Pembayaran: <strong>Rp <?php echo number_format($total); ?></strong></p>
        </div>
    </div>

    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo \Midtrans\Config::$clientKey; ?>"></script>

    <!-- Include Bootstrap JS for better functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        // Ambil URL ngrok dari PHP
        var ngrokUrl = '<?php echo $ngrokUrl; ?>';

        document.getElementById('pay-button').onclick = function() {
            // SnapToken dari Midtrans
            snap.pay('<?php echo $snap_token; ?>', {
                onSuccess: function(result) {
                    console.log(result);
                    window.location.href = ngrokUrl + '/thanks.php'; // Redirect jika sukses
                },
                onPending: function(result) {
                    console.log(result);
                    window.location.href = ngrokUrl + '/pending.php'; // Redirect jika pending
                },
                onError: function(result) {
                    console.log(result);
                    window.location.href = ngrokUrl + '/error.php'; // Redirect jika gagal
                }
            });
        };
    </script>
</body>

</html>