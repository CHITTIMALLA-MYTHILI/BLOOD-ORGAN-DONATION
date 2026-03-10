<?php
require_once __DIR__ . '/../../includes/auth.php';
requireLogin(['admin']);
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../config/database.php';

$counts = [
    'donors' => $conn->query('SELECT COUNT(*) c FROM donors')->fetch_assoc()['c'] ?? 0,
    'patients' => $conn->query('SELECT COUNT(*) c FROM patients')->fetch_assoc()['c'] ?? 0,
    'blood_requests' => $conn->query("SELECT COUNT(*) c FROM blood_requests WHERE status='pending'")->fetch_assoc()['c'] ?? 0,
    'organ_requests' => $conn->query("SELECT COUNT(*) c FROM organ_requests WHERE status='pending'")->fetch_assoc()['c'] ?? 0,
];

renderHeader('Admin Dashboard');
?>
<h3>Admin Dashboard</h3>
<a class="btn" href="/public/auth/logout.php">Logout</a>
<div class="grid">
  <div class="card"><strong>Total Donors:</strong> <?= $counts['donors'] ?></div>
  <div class="card"><strong>Total Patients:</strong> <?= $counts['patients'] ?></div>
  <div class="card"><strong>Pending Blood Requests:</strong> <?= $counts['blood_requests'] ?></div>
  <div class="card"><strong>Pending Organ Requests:</strong> <?= $counts['organ_requests'] ?></div>
</div>
<?php renderFooter(); ?>
