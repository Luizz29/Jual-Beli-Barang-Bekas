<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    if (!is_numeric($productId)) {
        echo "ID produk tidak valid.";
        exit;
    }
    
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            // Ambil path gambar utama dan gambar pendukung
            $mainImage = $product['image'];
            $supportingImages = explode(',', $product['supporting_images']); // Memisahkan path gambar pendukung
            
            // Tambahkan gambar utama ke array gambar
            array_unshift($supportingImages, $mainImage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Product Detail</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container */
        .product-detail-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 80%;
            max-width: 800px;
            padding: 30px;
            margin: 20px auto;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #ffffff;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background-color: #555;
        }

        /* Product Carousel */
        .product-image-carousel {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .carousel-item img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Product Info */
        .product-info {
            margin-top: 20px;
            text-align: center;
        }

        .product-info h1 {
            font-size: 26px;
            color: #222;
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 16px;
            color: #666;
            line-height: 1.5;
        }

        .product-price {
            font-size: 22px;
            color: #444;
            font-weight: bold;
            margin-top: 10px;
        }
        .product-slider {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .product-slider img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Navigation arrows */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 30px;
            color: #000;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            user-select: none;
            transition: transform 0.2s ease;
        }

        .slider-arrow:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .slider-arrow-left {
            left: 10px;
        }

        .slider-arrow-right {
            right: 10px;
        }

    </style>
</head>
<body>
    <div class="product-detail-container">
        <!-- Back Button -->
        <button onclick="window.history.back()" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
            Back
        </button>

        <!-- Carousel Gambar Produk -->
        <div id="productCarousel" class="carousel slide product-image-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                foreach ($supportingImages as $index => $image) {
                    $activeClass = $index === 0 ? 'active' : ''; // Set gambar pertama sebagai aktif
                    echo "<div class='carousel-item $activeClass'>";
                    echo "<img src='" . htmlspecialchars($image) . "' alt='Product Image'>";
                    echo "</div>";
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
        <!-- Informasi Produk -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['nama']); ?></h1>
            <p class="product-price">Rp<?php echo htmlspecialchars($product['harga']); ?></p>
            <p>Deskripsi: <?php echo htmlspecialchars($product['deskripsi']); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
        } else {
            echo "Produk tidak ditemukan.";
        }
        
        $stmt->close();
    } else {
        echo "Gagal mempersiapkan statement: " . $koneksi->error;
    }
} else {
    echo "ID produk tidak diberikan.";
}
?>
