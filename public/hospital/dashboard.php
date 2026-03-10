<?php
require_once __DIR__ . '/../../includes/auth.php';
requireLogin(['hospital']);
require_once __DIR__ . '/../../includes/layout.php';
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = (int) ($_POST['request_id'] ?? 0);
    $requestType = $_POST['request_type'] ?? 'blood';
    $status = $_POST['status'] ?? 'approved';

    if ($requestType === 'blood') {
        $stmt = $conn->prepare('UPDATE blood_requests SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $requestId);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare('UPDATE organ_requests SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $requestId);
        $stmt->execute();
    }
}

$blood = $conn->query("SELECT id, required_group, urgency_level, status FROM blood_requests ORDER BY created_at DESC LIMIT 20");
$organ = $conn->query("SELECT id, organ_type, urgency_level, status FROM organ_requests ORDER BY created_at DESC LIMIT 20");

renderHeader('Hospital Dashboard');
?>
<h3>Hospital Dashboard</h3>
<a class="btn" href="/public/auth/logout.php">Logout</a>
<p>Verify donor and patient details, then approve/reject compatibility.</p>

<h4>Blood Requests</h4>
<table class="table"><tr><th>ID</th><th>Group</th><th>Urgency</th><th>Status</th><th>Action</th></tr>
<?php while ($r = $blood->fetch_assoc()): ?>
<tr><td><?= $r['id'] ?></td><td><?= $r['required_group'] ?></td><td><?= $r['urgency_level'] ?></td><td><?= $r['status'] ?></td><td>
<form method="post"><input type="hidden" name="request_type" value="blood"><input type="hidden" name="request_id" value="<?= $r['id'] ?>"><select name="status"><option>approved</option><option>rejected</option><option>matched</option></select><button>Update</button></form>
</td></tr>
<?php endwhile; ?>
</table>

<h4>Organ Requests</h4>
<table class="table"><tr><th>ID</th><th>Organ</th><th>Urgency</th><th>Status</th><th>Action</th></tr>
<?php while ($r = $organ->fetch_assoc()): ?>
<tr><td><?= $r['id'] ?></td><td><?= $r['organ_type'] ?></td><td><?= $r['urgency_level'] ?></td><td><?= $r['status'] ?></td><td>
<form method="post"><input type="hidden" name="request_type" value="organ"><input type="hidden" name="request_id" value="<?= $r['id'] ?>"><select name="status"><option>approved</option><option>rejected</option><option>matched</option></select><button>Update</button></form>
</td></tr>
<?php endwhile; ?>
</table>
<?php renderFooter(); ?>
