<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Approval Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" href="../image/chic (5).png" type="image/x-icon">
    <style>
      /* CSS reset and basic styling */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }

      body {
        display: flex;
        min-height: 100vh;
        background-color: #f4f4f9;
      }

      /* Sidebar styling */
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

      .sidebar header {
        font-size: 1.5rem;
        margin-bottom: 30px;
        text-transform: uppercase;
        font-weight: bold;
      }

      .sidebar ul {
        list-style-type: none;
        width: 100%;
      }

      .sidebar ul li {
        margin: 15px 0;
        width: 100%;
      }

      .sidebar ul li a {
        color: #ffffff;
        text-decoration: none;
        font-size: 1rem;
        padding: 10px;
        display: flex;
        align-items: center;
        transition: 0.3s;
        border-radius: 5px;
      }

      .sidebar ul li a:hover {
        background-color: #1e2124;
      }

      .sidebar ul li a i {
        margin-right: 10px;
      }

      /* Main content styling */
      .main-content {
        margin-left: 260px;
        padding: 40px;
        width: calc(100% - 260px);
      }

      .welcome-section {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .welcome-section h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 10px;
      }

      .welcome-section p {
        font-size: 1.2rem;
        color: #555;
      }

      /* Dynamic cards for sections */
      .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
      }

      .card {
        background-color: #ffffff;
        flex: 1 1 200px;
        min-width: 200px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
        text-align: center;
      }

      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      }

      .card i {
        font-size: 2rem;
        color: #343a40;
        margin-bottom: 10px;
      }

      .card h3 {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 10px;
      }

      .card p {
        font-size: 1rem;
        color: #555;
      }

      /* Responsive Design */
      @media (max-width: 768px) {
        .main-content {
          margin-left: 0;
          padding: 20px;
          width: 100%;
        }

        .sidebar {
          display: none; /* Hide sidebar on smaller screens */
        }

        .card-container {
          flex-direction: column;
        }
      }
    </style>
  </head>
  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <header>Admin Dashboard</header>
      <ul> Admin
        <li><a href="kelola_user.php"><i class="fas fa-user-cog"></i>Kelola User</a></li>
        <li><a href="product_pending_approval.php"><i class="fas fa-link"></i>Approve Product</a></li>
      </ul>
        <ul> Seller
      
        <li><a href="../seller/upload_product.php"><i class="fas fa-link"></i>Upload Seller</a></li>
      
        <li><a href="../seller/pending_product.php"><i class="fas fa-link"></i>Pending Seller</a></li>

        <li><a href="../seller/approved_products.php"><i class="fas fa-link"></i>Approve Seller</a></li>

        <li><a href="../seller/saldo.php"><i class="fas fa-link"></i>Saldo</a></li>
        </ul>
        <ul> User
        <li><a href="../user/profile.php"><i class="fas fa-link"></i>Profile User</a></li>
        <li><a href="../user/order_history.php"><i class="fas fa-link"></i>Order User</a></li>
        <li><a href="../user/cart.php"><i class="fas fa-link"></i>User Cart</a></li>
</ul>

      
      
      
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="welcome-section">
        <h1>Selamat Datang di Halaman Persetujuan Admin!</h1>
        <p>
          Di sini Anda dapat mengelola persetujuan produk, melihat produk yang sudah disetujui, dan melakukan manajemen user. Terima kasih atas partisipasi Anda dalam menjaga kualitas marketplace.
        </p>
      </div>

      <div class="card-container">
        <div class="card">
          <i class="fas fa-user-cog"></i>
          <h3>Kelola User</h3>
          <p>Lihat dan kelola akun pengguna yang terdaftar di platform.</p>
        </div>
        <div class="card">
          <i class="fas fa-hourglass-half"></i>
          <h3>Persetujuan Produk</h3>
          <p>Periksa produk yang menunggu persetujuan untuk diaktifkan di marketplace.</p>
        </div>
       
      </div>
    </div>
  </body>
</html>
