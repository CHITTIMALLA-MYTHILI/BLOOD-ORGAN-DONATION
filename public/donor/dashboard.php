<?php
require_once __DIR__ . '/../../includes/auth.php';
requireLogin(['donor']);
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../config/database.php';

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['donor_type'] ?? 'blood';
    $group = $_POST['blood_group'] ?? 'O+';
    $organs = array_filter(array_map('trim', explode(',', $_POST['organs_available'] ?? '')));
    $organsJson = json_encode(array_values($organs));

    $update = $conn->prepare('UPDATE donors SET donor_type = ?, blood_group = ?, organs_available = ? WHERE user_id = ?');
    $update->bind_param('sssi', $type, $group, $organsJson, $userId);
    $update->execute();
}

$donor = $conn->query("SELECT donor_type, blood_group, organs_available FROM donors WHERE user_id = {$userId} LIMIT 1")->fetch_assoc();
$requests = $conn->query("SELECT id, required_group, units_required, urgency_level, status FROM blood_requests ORDER BY created_at DESC LIMIT 10");

renderHeader('Donor Dashboard');
?>
<h3>Donor Dashboard</h3>
<a class="btn" href="/public/auth/logout.php">Logout</a>
<h4>Update Profile</h4>
<form method="post">
  <label>Donor Type</label>
  <select name="donor_type">
    <option value="blood" <?= ($donor['donor_type'] ?? '') === 'blood' ? 'selected' : '' ?>>Blood</option>
    <option value="organ" <?= ($donor['donor_type'] ?? '') === 'organ' ? 'selected' : '' ?>>Organ</option>
    <option value="both" <?= ($donor['donor_type'] ?? '') === 'both' ? 'selected' : '' ?>>Both</option>
  </select>
  <label>Blood Group</label>
  <input name="blood_group" value="<?= htmlspecialchars($donor['blood_group'] ?? 'O+') ?>">
  <label>Organs Available (comma separated)</label>
  <input name="organs_available" value="<?= htmlspecialchars(implode(',', json_decode($donor['organs_available'] ?? '[]', true))) ?>">
  <button type="submit">Save</button>
</form>

<h4>Latest Donation Requests</h4>
<table class="table">
  <tr><th>ID</th><th>Blood Group</th><th>Units</th><th>Urgency</th><th>Status</th></tr>
  <?php while ($row = $requests->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td><td><?= $row['required_group'] ?></td><td><?= $row['units_required'] ?></td><td><?= $row['urgency_level'] ?></td><td><?= $row['status'] ?></td>
  </tr>
  <?php endwhile; ?>
</table>
<?php renderFooter(); ?>
