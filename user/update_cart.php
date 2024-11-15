<?php
session_start();
include '../koneksi.php';

if (isset($_POST['product_id'], $_POST['action'], $_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'];

    $quantity_change = ($action === 'increase') ? 1 : -1;

    $stmt = $koneksi->prepare("UPDATE carts SET kuantitas = GREATEST(1, kuantitas + ?) WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity_change, $product_id, $user_id);
    $stmt->execute();

    // Calculate new total and quantity to return in JSON response
    $result = $koneksi->query("SELECT SUM(products.harga * carts.kuantitas) AS total FROM carts JOIN products ON carts.product_id = products.product_id WHERE carts.user_id = $user_id");
    $total = $result->fetch_assoc()['total'];

    $stmt = $koneksi->prepare("SELECT kuantitas FROM carts WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $new_quantity = $stmt->get_result()->fetch_assoc()['kuantitas'];

    echo json_encode(['success' => true, 'new_quantity' => $new_quantity, 'new_total' => number_format($total, 0, ',', '.')]);
} else {
    echo json_encode(['success' => false, 'message' => 'Request failed.']);
}
?>
