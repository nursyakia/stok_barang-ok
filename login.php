<?php
session_start();
include 'lib/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form login
    $username = trim($_POST['username']);  // Menghapus spasi ekstra dari username
    $password = $_POST['password'];  // Mengambil password dari input

    // Debugging: Memeriksa data yang dikirimkan dari form
    var_dump($_POST);  // Memeriksa apakah data yang dikirimkan sudah benar

    // Query untuk mengambil data user berdasarkan username
    $sql = "SELECT * FROM tbusers WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Memeriksa data user yang diambil dari database
    var_dump($user);

    // Cek apakah user ditemukan dan password valid menggunakan password_verify
    if ($user && password_verify($password, $user['password'])) {  // Verifikasi password
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['user'] = $user['role']; // Simpan role ke session

        // Debugging: Memeriksa hasil password_verify
        var_dump(password_verify($password, $user['password']));  // Memeriksa apakah password valid

        // Redirect berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
            exit();
        } elseif ($user['role'] == 'petugas') {
            header("Location: petugas.php");
            exit();
        } else {
            // Jika role tidak dikenali
            echo "<script>alert('Role tidak dikenali.'); window.location='login.php';</script>";
        }
    } else {
        // Jika username atau password salah
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .login-card .card-body {
            padding: 30px;
        }

        .login-card .form-control {
            height: 45px;
            margin-bottom: 20px;
        }

        .login-card .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="assets/img/stok.jpg" alt="Login Image">
            <div class="card-body">
                <h3 class="text-center mb-4">Login</h3>
                <form method="POST" action="login.php">
                <input type="text" name="username" required class="form-control" placeholder="Username">
                <input type="password" name="password" required class="form-control" placeholder="Password">
                <button type="submit" class="btn btn-primary w-100">Login</button>
               </form>
        </div>
    </div>
</body>
</html>
