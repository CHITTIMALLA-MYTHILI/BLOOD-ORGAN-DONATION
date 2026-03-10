<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$city = trim($_POST['location_city'] ?? '');

if ($fullName === '' || $email === '' || $password === '' || $city === '') {
    die('Missing required fields');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (full_name, email, phone, password_hash, role, location_city) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssssss', $fullName, $email, $phone, $hash, $role, $city);
$stmt->execute();
$userId = $stmt->insert_id;

if ($role === 'donor') {
    $defaultType = 'blood';
    $defaultGroup = 'O+';
    $emptyOrgans = json_encode([]);
    $insertDonor = $conn->prepare('INSERT INTO donors (user_id, donor_type, blood_group, organs_available) VALUES (?, ?, ?, ?)');
    $insertDonor->bind_param('isss', $userId, $defaultType, $defaultGroup, $emptyOrgans);
    $insertDonor->execute();
} elseif ($role === 'patient') {
    $defaultGroup = 'O+';
    $urgency = 'medium';
    $insertPatient = $conn->prepare('INSERT INTO patients (user_id, blood_group, urgency_level) VALUES (?, ?, ?)');
    $insertPatient->bind_param('iss', $userId, $defaultGroup, $urgency);
    $insertPatient->execute();
} elseif ($role === 'hospital') {
    $hospitalName = $fullName;
    $insertHospital = $conn->prepare('INSERT INTO hospitals (user_id, hospital_name) VALUES (?, ?)');
    $insertHospital->bind_param('is', $userId, $hospitalName);
    $insertHospital->execute();
}

header('Location: /public/login.php');
exit;
?>
