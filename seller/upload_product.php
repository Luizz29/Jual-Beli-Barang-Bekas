<?php
// Koneksi ke database
include '../koneksi.php';

$message = ""; // Variabel untuk pesan alert

// Pastikan sesi dimulai
session_start();
$user_id = $_SESSION['user_id']; // Ambil user_id dari session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data dari formulir
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productDescription = $_POST['productDescription'];
    $productStock = $_POST['productStock']; // Ambil data stok
    $mainImageName = $_FILES['mainImage']['name'];
    $mainImageTmpName = $_FILES['mainImage']['tmp_name'];
    
    // Tentukan direktori upload
    $targetDir = "../upload/";
    $mainImageFile = $targetDir . basename($mainImageName);

    // Pindahkan gambar utama ke folder upload
    if (move_uploaded_file($mainImageTmpName, $mainImageFile)) {
        // Proses gambar pendukung
        $supportingImagePaths = []; // Array untuk menyimpan path gambar pendukung
        
        if (isset($_FILES['supportingImages'])) {
            $supportingImages = $_FILES['supportingImages'];
            for ($i = 0; $i < count($supportingImages['name']); $i++) {
                $supportingImageName = $supportingImages['name'][$i];
                $supportingImageTmpName = $supportingImages['tmp_name'][$i];
                $supportingImageFile = $targetDir . basename($supportingImageName);

                // Pindahkan gambar pendukung ke folder upload
                if (move_uploaded_file($supportingImageTmpName, $supportingImageFile)) {
                    $supportingImagePaths[] = $supportingImageFile; // Simpan path ke array
                }
            }
        }

        // Gabungkan semua path gambar pendukung menjadi string
        $supportingImagesString = implode(',', $supportingImagePaths);

        // Simpan data produk ke tabel pending_products
        $sql = "INSERT INTO pending_products (nama, harga, deskripsi, stok, image, supporting_images, status, user_id) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssssss", $productName, $productPrice, $productDescription, $productStock, $mainImageFile, $supportingImagesString, $user_id);

        if ($stmt->execute()) {
            $message = "Produk telah diunggah dan menunggu persetujuan.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Terjadi kesalahan saat mengunggah gambar.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <style>
        #mainImagePreview, #supportingImagePreview {
            display: none;
            width: 150px;
            height: 150px;
            margin-top: 10px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #ffc107;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }
        .sidebar header { font-size: 1.5rem; margin-bottom: 30px; text-transform: uppercase; font-weight: bold; }
        .sidebar ul { list-style-type: none; width: 100%; }
        .sidebar ul li { margin: 15px 0; width: 100%; }
        .sidebar ul li a { color: #ffffff; text-decoration: none; font-size: 1rem; padding: 10px; display: flex; align-items: center; transition: 0.3s; border-radius: 5px; }
        .sidebar ul li a:hover { background-color: #1e2124; }
        .sidebar ul li a i { margin-right: 10px; }
        .container { margin-left: 270px; padding: 40px; width: calc(100% - 270px); color: white; }
        body { background-color: #1c1c1c; color: white; }
        h2 { font-size: 2.5rem; color: #f8f9fa; margin-bottom: 30px; }
        .form-label { font-size: 1.1rem; color: #adb5bd; }
        .form-control { background-color: #343a40; color: #f8f9fa; border: 1px solid #495057; padding: 10px; border-radius: 5px; }
        .form-control:focus { background-color: #343a40; color: #f8f9fa; border-color: #ffc107; box-shadow: none; }
        .btn-primary { background-color: #ffc107; border: none; padding: 10px 20px; border-radius: 5px; transition: 0.3s; }
        .btn-primary:hover { background-color: #e0a800; color: #fff; }
        @media (max-width: 768px) { .container { margin-left: 0; padding: 20px; width: 100%; } .sidebar { display: none; } }
    </style>
</head>
<body>
<div class="sidebar">
    <header>Second Chance</header>
    <ul>
        <li><a href="upload_product.php"><i class="fas fa-qrcode"></i>Upload Produk</a></li>
        <li><a href="pending_product.php"><i class="fas fa-link"></i>Produk Pending</a></li>
        <li><a href="approved_products.php"><i class="fas fa-stream"></i>Produk Disetujui</a></li>
        <li><a href="saldo.php"><i class="fas fa-calendar-week"></i>Saldo</a></li>
       
    </ul>
</div>

<div class="container mt-5">
    <h2>Upload Produk</h2>
    <form action="upload_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="productName" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
        </div>
        <div class="mb-3">
            <label for="productPrice" class="form-label">Harga Produk</label>
            <input type="text" class="form-control" id="productPrice" name="productPrice" required>
        </div>
        <div class="mb-3">
            <label for="productDescription" class="form-label">Deskripsi Produk</label>
            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="productStock" class="form-label">Stok Produk</label>
            <input type="number" class="form-control" id="productStock" name="productStock" min="1" required>
        </div>
        <div class="mb-3 row">
            <label for="mainImage" class="form-label col-md-2">Gambar Utama</label>
            <div class="col-md-10">
                <input type="file" class="form-control" id="mainImage" name="mainImage" accept="image/*" onchange="previewMainImage(event)" required>
                <img id="mainImagePreview" src="" alt="Preview Gambar Utama" style="display:none; width:150px; height:150px; margin-top:10px; border-radius:10px; border:2px solid #ffc107; object-fit:cover;">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="supportingImages" class="form-label col-md-2">Gambar Pendukung (maksimal 5)</label>
            <div class="col-md-10">
                <input type="file" class="form-control" id="supportingImages" name="supportingImages[]" accept="image/*" multiple onchange="previewSupportingImages(event)">
                <div id="supportingImagesPreview" class="d-flex flex-wrap" style="margin-top:10px;"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <?php if ($message): ?>
        <div class="alert alert-info mt-3"><?= $message; ?></div>
    <?php endif; ?>
</div>


<script>
    function previewMainImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('mainImagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    function previewSupportingImages(event) {
        var previewContainer = document.getElementById('supportingImagesPreview');
        previewContainer.innerHTML = "";
        for (var i = 0; i < event.target.files.length; i++) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.marginRight = '10px';
                img.style.marginBottom = '10px';
                img.style.objectFit = 'cover';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(event.target.files[i]);
        }
    }
</script>

</body>
</html>
