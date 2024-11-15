<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        background-color: #1c1c1c;
        color: white;
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

      .table-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        color: #333;
      }

      .table-container h3 {
        color: #333;
      }

      .table {
        color: #333;
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
      }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
      <header>Admin Dashboard</header>
      <ul>
        <li><a href="kelola_user.php"><i class="fas fa-users"></i>Kelola User</a></li>
        <li><a href="product_pending_approval.php"><i class="fas fa-link"></i>Approve Product</a></li>
     
    
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="table-container">
        <h3>Kelola User</h3>
        <table class="table table-striped table-hover">
          <thead class="table-dark">
              <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Nama Lengkap</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              <?php
                // Database connection
                include '../koneksi.php';
                $sql = "SELECT user_id, username, nama_lengkap, email, role FROM users";
                $result = $koneksi->query($sql);

                while($row = $result->fetch_assoc()) {
              ?>
              <tr>
                  <td><?php echo $row['user_id']; ?></td>
                  <td><?php echo $row['username']; ?></td>
                  <td><?php echo $row['nama_lengkap']; ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['role']; ?></td>
                  <td>
                      <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                      <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                  </td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
