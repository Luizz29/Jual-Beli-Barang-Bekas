<?php
session_start();
include '../koneksi.php';
require '../vendor/autoload.php'; // Pastikan path ini sesuai

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
include('../assets/phpmailer/src/Exception.php');
include('../assets/phpmailer/src/PHPMailer.php');
include('../assets/phpmailer/src/SMTP.php');

// Set server key
\Midtrans\Config::$serverKey = 'SB-Mid-server-jSnU9XQH-x9wkiS4ymQRrKUr';

$notification = json_decode(file_get_contents('php://input'), true);

// Verifikasi signature
$generatedSignature = hash('sha512', $notification['order_id'] . \Midtrans\Config::$serverKey);
if ($generatedSignature !== $notification['signature_key']) {
    http_response_code(403);
    exit('Unauthorized');
}

// Cek status transaksi
if ($notification['transaction_status'] == 'capture' || $notification['transaction_status'] == 'settlement') {
    $email = $notification['customer']['email'];

    // Kirim email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Ganti dengan SMTP server Anda
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // Ganti dengan email Anda
        $mail->Password   = 'your_email_password'; // Ganti dengan password email Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Penerima
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Transaksi Berhasil';
        $mail->Body    = 'Terima kasih telah melakukan pembelian! Berikut adalah detail transaksi:<br>' .
                         'Order ID: ' . $notification['order_id'] . '<br>' .
                         'Jumlah: ' . $notification['gross_amount'] . '<br>' .
                         'Status: ' . $notification['transaction_status'];

        $mail->send();
        echo 'Email telah dikirim';
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
