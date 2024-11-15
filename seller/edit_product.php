<?php
// Mulai output buffering
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
include '../koneksi.php';

// Ambil ID produk dari URL
$id = $_GET['product_id'];

// Query untuk mendapatkan data produk berdasarkan ID
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data yang diedit dari form
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $imageName = $_FILES['productImage']['name'];
    $imageTmpName = $_FILES['productImage']['tmp_name'];

    // Tentukan direktori upload
    $targetDir = "../upload/";
    $targetFile = $targetDir . basename($imageName);

    // Cek apakah gambar diupdate atau tidak
    if (!empty($imageName)) {
        // Jika ada gambar baru, pindahkan ke folder upload
        if (move_uploaded_file($imageTmpName, $targetFile)) {
            // Update data produk dengan gambar baru
            $sql = "UPDATE products SET nama = ?, harga = ?, image = ? WHERE product_id = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssi", $productName, $productPrice, $targetFile, $id);
        } else {
            echo "Gagal mengunggah gambar baru.";
            exit();
        }
    } else {
        // Jika tidak ada gambar baru, update data produk tanpa mengganti gambar
        $sql = "UPDATE products SET nama = ?, harga = ? WHERE product_id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssi", $productName, $productPrice, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke halaman approved_products.php setelah update berhasil
        header('Location: approved_products.php?status=success');
        exit();
    } else {
        echo "Terjadi kesalahan saat mengupdate produk: " . $koneksi->error;
    }
}

// Mengirim output buffer ke browser
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function previewImage() {
            const file = document.getElementById('productImage').files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();

                reader.onloadend = function () {
                    preview.src = reader.result;
                    preview.style.display = 'block'; // Tampilkan gambar preview
                }

                reader.readAsDataURL(file); // Convert image file to base64 string
            } else {
                preview.src = ""; // Clear preview if no file is selected
                preview.style.display = 'none'; // Sembunyikan preview jika tidak ada file
            }
        }
    </script>
</head>
<body>

<!-- Form Edit Produk -->
<div class="container mt-5">
    <h2>Edit Produk</h2>
    <form action="edit_product.php?product_id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="productName" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['nama']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="productPrice" class="form-label">Harga Produk</label>
            <input type="text" class="form-control" id="productPrice" name="productPrice" value="<?php echo htmlspecialchars($product['harga']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="productImage" class="form-label">Gambar Produk</label>
            <input type="file" class="form-control" id="productImage" name="productImage" onchange="previewImage()">
            <p>Gambar saat ini: <img src="<?php echo htmlspecialchars($product['image']); ?>" width="100" alt="Product Image"></p>
            <p>Preview Gambar Baru:</p>
            <img id="imagePreview" src="" width="100" alt="Image Preview" style="display: none;">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
