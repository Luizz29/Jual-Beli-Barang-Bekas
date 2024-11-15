<?php
// Memulai session
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak ada session, arahkan ke halaman login
    header("Location: index.php");
    exit;
}

// Ambil data dari session
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>
    
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Second Chance</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/floating-labels/">
<link rel="stylesheet" href="style.css">
    <!-- Bootstrap core CSS -->
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>

  

<style>
      body {
    background-color: #1c1c1c ;
    color: white;
    .card-body{
        background-color: grey;
    }
  }

      body { background-color: #1c1c1c; color: #fff; }
      .thankyou-section { display: flex; justify-content: center; align-items: center; height: 100vh; }
      .thankyou-card { border-radius: 15px; padding: 30px; box-shadow: 0 0 30px rgba(0, 0, 0, 0.1); text-align: center; background-color: #808080; color: #fff; }
      .thankyou-card h1 { font-size: 2.5rem; }
      .thankyou-card p { font-size: 1.2rem; }
      .btn-custom { background-color: #1c1c1c; color: #ddd; font-size: 1.2rem; padding: 10px 20px; border-radius: 25px; transition: all 0.3s ease; text-decoration: none;}
      .btn-custom:hover { background-color: #ddd; color: #28a745; }
      .order-summary { margin-top: 20px; font-size: 1rem; color: #fff; }
      
    </style>
  
  
  <div class="thankyou-section">
      <div class="thankyou-card">

      <img class="mb-4" src="../image/chic (5).png" alt="Logo" width="72" height="72">
      
      <div>
        <a class="navbar-brand" ><strong class="text-danger">Second</strong> <strong>Chance</strong></a>
      </div>
        <p>Berikut profile diri anda.</p>
        <div class="order-summary">
          <p><strong>Username:</strong> <?php echo $username; ?></p>
          <p><strong>Role Anda:</strong>  <?php echo $role; ?></p>
        </div>
        <a href="../index.php" class="btn btn-custom mt-3" >Kembali ke Beranda</a>
        <a href="../change_password.php" class="btn btn-custom mt-3">Ganti detail</a>
      
 
      </div>
    </div>




  


  
</form>


    
  </body>
</html>

 

    <!-- Tambahkan informasi pengguna lainnya di sini -->
</body>
</html>
