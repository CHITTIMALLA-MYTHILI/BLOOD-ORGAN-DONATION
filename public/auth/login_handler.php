<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user['password_hash'])) {
    die('Invalid credentials');
}

$_SESSION['user'] = [
    'id' => $user['id'],
    'name' => $user['full_name'],
    'email' => $user['email'],
    'role' => $user['role']
];

$map = [
    'admin' => '/public/admin/dashboard.php',
    'donor' => '/public/donor/dashboard.php',
    'patient' => '/public/patient/dashboard.php',
    'hospital' => '/public/hospital/dashboard.php'
];

header('Location: ' . ($map[$user['role']] ?? '/public/index.php'));
exit;
?>
