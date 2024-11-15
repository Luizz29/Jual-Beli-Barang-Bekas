<?php
session_start();
include '../koneksi.php';

if (isset($_POST['product_id'], $_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $koneksi->prepare("DELETE FROM carts WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Request failed.']);
}
?>
