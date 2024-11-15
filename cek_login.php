<?php
// Panggil koneksi database
include 'koneksi.php';

$password = ($_POST['password']);
$username = mysqli_escape_string($koneksi, $_POST['username']);
$password = mysqli_escape_string($koneksi, $password);
$role = mysqli_escape_string($koneksi, $_POST['role']);

// Cek username telah terdaftar atau tidak
$cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND role = '$role'");
$user_valid = mysqli_fetch_array($cek_user);

// Uji jika username telah terdaftar
if ($user_valid) {
    // Jika username terdaftar
    if ($password == $user_valid['password']) { 
        // Jika password sesuai
        session_start();
        $_SESSION['user_id'] = $user_valid['user_id'];  // Simpan user_id ke dalam sesi
        $_SESSION['username'] = $user_valid['username'];
        $_SESSION['nama_lengkap'] = $user_valid['nama_lengkap'];
        $_SESSION['role'] = $user_valid['role'];

        // Uji level user dan redirect dengan status sukses
        if ($role == "User") {
            header('location:index.php?status=success');
        } else if ($role == "Admin") {
            header('location:/admin/index.php');
        } else if ($role == "Seller") {
            header('location:/seller/index.php?status=success');
        }
    } else {
        // Password tidak sesuai, redirect dengan status gagal
        header('location:form_login.php?status=failed');
    }
} else {
    // Username tidak terdaftar, redirect dengan status gagal
    header('location:form_login.php?status=failed');
}
?>
