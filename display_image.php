
<?php
include 'koneksi.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$product_id = $_GET['id'];

$sql = "SELECT image FROM products WHERE product_id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

if (!$image) {
    die("Image not found or error in fetching image.");
}

header("Content-Type: image/png");
echo $image;
?>
