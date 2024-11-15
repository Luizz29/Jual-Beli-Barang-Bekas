<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User ID tidak ditemukan']);
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT saldo FROM users WHERE user_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['saldo' => $row['saldo']]);
} else {
    echo json_encode(['saldo' => 0]);
}
?>
