<?php
require_once __DIR__ . '/../../includes/auth.php';
requireLogin(['patient']);
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user']['id'];
$patient = $conn->query("SELECT id, blood_group FROM patients WHERE user_id = {$userId} LIMIT 1")->fetch_assoc();
$patientId = (int) ($patient['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['request_type'] ?? 'blood';
    $urgency = $_POST['urgency_level'] ?? 'medium';

    if ($type === 'blood') {
        $required = $_POST['required_group'] ?? $patient['blood_group'];
        $units = (int) ($_POST['units_required'] ?? 1);
        $stmt = $conn->prepare('INSERT INTO blood_requests (patient_id, required_group, units_required, urgency_level) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('isis', $patientId, $required, $units, $urgency);
        $stmt->execute();
    } else {
        $organ = $_POST['organ_type'] ?? 'kidney';
        $stmt = $conn->prepare('INSERT INTO organ_requests (patient_id, organ_type, urgency_level) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $patientId, $organ, $urgency);
        $stmt->execute();
    }
}

$bloodRequests = $conn->query("SELECT id, required_group, units_required, urgency_level, status, created_at FROM blood_requests WHERE patient_id = {$patientId} ORDER BY created_at DESC");
$organRequests = $conn->query("SELECT id, organ_type, urgency_level, status, created_at FROM organ_requests WHERE patient_id = {$patientId} ORDER BY created_at DESC");

renderHeader('Patient Dashboard');
?>
<h3>Patient Dashboard</h3>
<a class="btn" href="/public/auth/logout.php">Logout</a>
<h4>Create Request</h4>
<form method="post">
  <label>Request Type</label>
  <select name="request_type">
    <option value="blood">Blood</option>
    <option value="organ">Organ</option>
  </select>
  <label>Required Blood Group</label><input name="required_group" value="<?= htmlspecialchars($patient['blood_group'] ?? 'O+') ?>">
  <label>Units Required</label><input name="units_required" type="number" value="1">
  <label>Organ Type</label><input name="organ_type" placeholder="kidney">
  <label>Urgency</label>
  <select name="urgency_level">
    <option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="critical">Critical</option>
  </select>
  <button type="submit">Submit Request</button>
</form>

<h4>Blood Request Status</h4>
<table class="table"><tr><th>ID</th><th>Group</th><th>Units</th><th>Urgency</th><th>Status</th></tr>
<?php while ($r = $bloodRequests->fetch_assoc()): ?><tr><td><?= $r['id'] ?></td><td><?= $r['required_group'] ?></td><td><?= $r['units_required'] ?></td><td><?= $r['urgency_level'] ?></td><td><?= $r['status'] ?></td></tr><?php endwhile; ?>
</table>

<h4>Organ Request Status</h4>
<table class="table"><tr><th>ID</th><th>Organ</th><th>Urgency</th><th>Status</th></tr>
<?php while ($r = $organRequests->fetch_assoc()): ?><tr><td><?= $r['id'] ?></td><td><?= $r['organ_type'] ?></td><td><?= $r['urgency_level'] ?></td><td><?= $r['status'] ?></td></tr><?php endwhile; ?>
</table>
<?php renderFooter(); ?>
