<?php 
session_start();
// Mencegah caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


?>





<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <title>Second Change</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/floating-labels/">
<link rel="stylesheet" href="style.css">
    

    <!-- Bootstrap core CSS -->
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

 

    
    <!-- Custom styles for this template -->
    <link href="assets/dist/css/floating-labels.css" rel="stylesheet">
  </head>
  <body>
  <style>
  body {
    background-color: #1c1c1c ;
    color: white;
  }
</style>

  
<form class="form-signin" method="POST"  action="cek_login.php">
  <div class="text-center mb-4">
    <img class="mb-4" src="image/chic (5).png" alt="Logo" width="72" height="72">
    <link rel="icon" href="image/chic (5).png" type="image/x-icon"> 
    <h1 class="h3 mb-3 font-weight-normal">Form Login</h1>
    <p>Masukan Username dan Password Dengan Benar!
    </p>
  </div>

  <div class="form-label-group">
    <input type="text"  class="form-control" name="username"   placeholder="Masukan Username Anda" required autofocus>
    <label >Masukan Username Anda!</label>
  </div>

  <div class="form-label-group">
    <input type="password" name="password" class="form-control" placeholder="Masukan Username Anda" required>
    <label >Masukan Password Anda!</label>
  </div>

  <div class="form-label-group">
    <select class="form-control" name="role">
      <option value="User">User</option>
      <option value="Admin">Admin</option>
      <option value="Seller">Seller</option>

    </select>
  </div>


  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

  <div class="mt-3 text-center">
        <p>Belum mempunyai akun? <a href="form_register.php" class="text-primary">Daftar disini</a></p>
      </div>
  <p class="mt-5 mb-3 text-muted text-center">&copy; Alfaluis 2024</p>

  
</form>

<div>
  
</div>


    
  </body>
</html>
