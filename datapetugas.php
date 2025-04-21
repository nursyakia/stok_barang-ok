<?php
include 'lib/koneksi.php'; // Koneksi ke database

$message = ''; // untuk alert

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user baru ke database, dengan role tetap 'petugas'
    $sql = "INSERT INTO tbusers (username, password, role) VALUES (:username, :password, 'petugas')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success mt-3">✅ Registrasi berhasil sebagai petugas!</div>';
    } else {
        $message = '<div class="alert alert-danger mt-3">❌ Registrasi gagal!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .register-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="register-container">
    <div class="register-card">
        <h3 class="text-center mb-4">Register Petugas</h3>
        <form method="POST">
            <input type="text" name="username" required class="form-control mb-3" placeholder="Username">
            <input type="password" name="password" required class="form-control mb-3" placeholder="Password">
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <?php echo $message; ?>
    </div>
</div>
</body>
</html>
