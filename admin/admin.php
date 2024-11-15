<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar HTML copied from index.html -->
<input type="checkbox" id="check">
<label for="check">
    <i class="fas fa-bars" id="btn"></i>
    <i class="fas fa-times" id="cancel"></i>
</label>
<div class="sidebar">
    <header>My App</header>
    <ul>
        <li><a href="upload_product.php"><i class="fas fa-qrcode"></i>Upload Product</a></li>
        <li><a href="pending_product.php"><i class="fas fa-link"></i>Pending Product</a></li>
        <li><a href="approved_products.php"><i class="fas fa-stream"></i>Product Success</a></li>
        <li><a href="#"><i class="fas fa-calendar-week"></i>Events</a></li>
        <li><a href="#"><i class="far fa-question-circle"></i>About</a></li>
        <li><a href="#"><i class="fas fa-sliders-h"></i>Services</a></li>
        <li><a href="#"><i class="far fa-envelope"></i>Contact</a></li>
    </ul>
</div>

<!-- End of Sidebar HTML -->

    <!-- Main Content -->
    <div class="container mt-5">
        <h2>Products Pending Approval</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data produk yang masih menunggu persetujuan -->
                <tr>
                    <td>1</td>
                    <td>Product 1</td>
                    <td>$50</td>
                    <td><img src="upload/image1.jpg" alt="Product Image" width="50"></td>
                    <td>
                        <form method="POST" action="approve_product.php" class="d-inline">
                            <input type="hidden" name="product_id" value="1">
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form method="POST" action="reject_product.php" class="d-inline">
                            <input type="hidden" name="product_id" value="1">
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Product 2</td>
                    <td>$100</td>
                    <td><img src="upload/image2.jpg" alt="Product Image" width="50"></td>
                    <td>
                        <form method="POST" action="approve_product.php" class="d-inline">
                            <input type="hidden" name="product_id" value="2">
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form method="POST" action="reject_product.php" class="d-inline">
                            <input type="hidden" name="product_id" value="2">
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Q2/h9Uj12JAVtjxgI5OKfFAd3v8P" crossorigin="anonymous"></script>
</body>
</html>
