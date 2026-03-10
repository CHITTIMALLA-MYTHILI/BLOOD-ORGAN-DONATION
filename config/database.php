<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'organ_blood_donation';

$conn = new mysqli($host, $user, $pass, $dbName);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
