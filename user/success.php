<?php
session_start();
include '../koneksi.php';  // Koneksi ke database
require '../vendor/autoload.php'; // Include PHPMailer jika menggunakan Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cek apakah permintaan ini berasal dari Midtrans
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_result = file_get_contents('php://input');
    $result = json_decode($json_result, true);

    // Dapatkan detail transaksi dari Midtrans
    $order_id = $result['order_id'];
    $status_code = $result['status_code'];
    $transaction_status = $result['transaction_status'];
    $gross_amount = $result['gross_amount'];
    $email = $_SESSION['email'];  // Ambil email yang disimpan dalam sesi atau dari database

    // Update status order di database jika pembayaran berhasil
    if ($transaction_status == 'settlement') {
        $status = 'Paid';
        $sql = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";
        if ($koneksi->query($sql) === TRUE) {
            // Kirim email konfirmasi
            $mail = new PHPMailer(true);

            try {
                // Konfigurasi pengiriman email
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Ganti dengan SMTP server yang Anda gunakan
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com'; // Ganti dengan email pengirim
                $mail->Password = 'your_password'; // Ganti dengan password email pengirim
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Pengirim dan penerima email
                $mail->setFrom('your_email@example.com', 'Nama Anda');
                $mail->addAddress($email); // Email pengguna yang diambil dari sesi atau form

                // Konten email
                $mail->isHTML(true);
                $mail->Subject = 'Konfirmasi Pembayaran Berhasil';
                $mail->Body = "
                    <h2>Pembayaran Berhasil!</h2>
                    <p>Terima kasih atas pembayaran Anda.</p>
                    <p>ID Pesanan: $order_id</p>
                    <p>Total Pembayaran: Rp " . number_format($gross_amount, 0, ',', '.') . "</p>
                ";

                // Kirim email
                $mail->send();
                echo 'Konfirmasi pembayaran telah dikirim ke email: ' . $email;
            } catch (Exception $e) {
                echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $koneksi->error;
        }
    } else {
        echo "<h2>Status Pembayaran: " . $transaction_status . "</h2>";
    }
} else {
    echo "<h2>Tidak ada data pembayaran yang diterima.</h2>";
}
