<?php
// Database connection
include 'koneksi.php';

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Encrypt the password
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Check if username already exists
    $checkUser = $koneksi->query("SELECT * FROM users WHERE username='$username'");
    if ($checkUser->num_rows > 0) {
        // Redirect to the form with a status message if the username exists
        header("Location: register.php?status=exists");
        exit();
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, password, nama_lengkap, email, role) VALUES ('$username', '$password', '$nama_lengkap', '$email', '$role')";
        
        if ($koneksi->query($sql) === TRUE) {
            echo "Registration successful!";
            // Optionally, redirect to a login page or dashboard
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="image/chic (5).png" type="image/x-icon">
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
      body {
    background-color: #1c1c1c;
    color: white;
  }
  .card-body {
    background-color: grey;
  }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center mb-4">Form Registrasi</h3>
                    
                    <!-- Alert jika username sudah ada -->
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'exists'): ?>
                        <div class="alert alert-danger" role="alert">
                            Username sudah terdaftar, silakan pilih username lain!
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form Registrasi -->
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Pilih Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="User">User</option>
                            
                                <option value="Seller">Seller</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                    <!-- End of Form -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
