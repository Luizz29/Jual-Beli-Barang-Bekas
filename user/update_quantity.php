<?php
session_start();
include '../koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validasi input
    if (!is_numeric($quantity) || $quantity < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
        exit();
    }

    // Update kuantitas di database
    $sql = $koneksi->prepare("UPDATE carts SET kuantitas = ? WHERE product_id = ? AND user_id = ?");
    $sql->bind_param("iii", $quantity, $product_id, $user_id);
    if ($sql->execute()) {
        // Ambil harga produk untuk menghitung subtotal
        $sql_price = $koneksi->prepare("SELECT harga FROM products WHERE product_id = ?");
        $sql_price->bind_param("i", $product_id);
        $sql_price->execute();
        $result = $sql_price->get_result();

        if ($row = $result->fetch_assoc()) {
            $price = $row['harga'];
            echo json_encode(['success' => true, 'price' => $price, 'quantity' => $quantity]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
    }
}
?>
