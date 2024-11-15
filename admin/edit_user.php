<?php
// Koneksi ke database
include '../koneksi.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Ambil data user berdasarkan user_id
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Update data user jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $namaLengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = ?, nama_lengkap = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssi", $username, $namaLengkap, $email, $role, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('Data pengguna berhasil diperbarui'); window.location.href = 'kelola_user.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1c1c;
            font-family: Arial, sans-serif;
            color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 500px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            background-color: #333;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #4b79a1, #283e51);
            color: white;
            text-align: center;
            padding: 1.5rem;
        }
        .form-label {
            font-weight: bold;
            color: #ddd;
        }
        .form-control {
            border-radius: 10px;
            box-shadow: none;
            border: 1px solid #555;
            background-color: #222;
            color: #f1f1f1;
        }
        .form-control:focus {
            border-color: #4b79a1;
            box-shadow: 0 0 5px rgba(75, 121, 161, 0.3);
        }
        .btn-primary {
            background-color: #4b79a1;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #283e51;
        }
        .d-grid {
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Edit User</h3>
        </div>
        <div class="card-body p-4">
            <form action="edit_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select form-control" id="role" name="role" required>
                        <option value="User" <?php if($user['role'] == 'User') echo 'selected'; ?>>User</option>
                        <option value="Admin" <?php if($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                        <option value="Seller" <?php if($user['role'] == 'Seller') echo 'selected'; ?>>Seller</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$koneksi->close();
?>
