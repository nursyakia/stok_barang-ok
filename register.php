<?php
include 'lib/koneksi.php'; // koneksi ke DB

$username = 'admin';
$password = 'admin123';
$role = 'admin';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // hash password

$sql = "INSERT INTO tbusers (username, password, role) VALUES (:username, :password, :role)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':role', $role);

if ($stmt->execute()) {
    echo "✅ User admin berhasil ditambahkan!";
} else {
    echo "❌ Gagal menambahkan user!";
}
?>
