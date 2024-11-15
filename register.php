<?php
// Panggil koneksi database
include 'koneksi.php';

// Ambil data dari form register
$username = mysqli_escape_string($koneksi, $_POST['username']);
$password = mysqli_escape_string($koneksi, $_POST['password']);
$nama_lengkap = mysqli_escape_string($koneksi, $_POST['nama_lengkap']);
$email = mysqli_escape_string($koneksi, $_POST['email']); // Tambahkan email
$role = mysqli_escape_string($koneksi, $_POST['role']);  // role: User, Admin, Seller

// Cek apakah username atau email sudah ada di database
$cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
$user_exists = mysqli_num_rows($cek_user);

if ($user_exists > 0) {
    // Jika username atau email sudah terdaftar
    header('location:form_register.php?status=exists');
} else {
    // Jika belum terdaftar, lakukan insert ke tabel users
    $sql = "INSERT INTO users (username, password, nama_lengkap, email, role) VALUES ('$username', '$password', '$nama_lengkap', '$email', '$role')";
    if (mysqli_query($koneksi, $sql)) {
        // Jika insert berhasil, redirect ke halaman login dengan status sukses
        header('location:form_login.php?status=registered');
    } else {
        // Jika gagal insert, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }
}
?>
