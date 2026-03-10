<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/matching.php';

$requests = $conn->query("SELECT br.id, br.patient_id, br.required_group, br.urgency_level, br.created_at, u.location_city
    FROM blood_requests br
    JOIN patients p ON p.id = br.patient_id
    JOIN users u ON u.id = p.user_id
    WHERE br.status = 'pending'");

while ($request = $requests->fetch_assoc()) {
    $requestDate = new DateTime($request['created_at']);
    $today = new DateTime();
    $waitingDays = (int) $today->diff($requestDate)->format('%a');

    $donors = $conn->query("SELECT d.id, d.blood_group, u.location_city
      FROM donors d
      JOIN users u ON u.id = d.user_id
      WHERE d.status='active'");

    $bestDonor = null;
    $bestScore = -1;
    while ($donor = $donors->fetch_assoc()) {
        $score = buildPriorityScore($donor, $request, $waitingDays);
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestDonor = $donor;
        }
    }

    if ($bestDonor !== null) {
        $insert = $conn->prepare('INSERT INTO donations (donor_id, patient_id, blood_request_id, donation_type, compatibility_score, total_priority_score, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $type = 'blood';
        $status = 'proposed';
        $compatibility = bloodCompatibility($bestDonor['blood_group'], $request['required_group']) ? 20 : 0;
        $insert->bind_param('iiisiis', $bestDonor['id'], $request['patient_id'], $request['id'], $type, $compatibility, $bestScore, $status);
        $insert->execute();

        $update = $conn->prepare("UPDATE blood_requests SET status = 'matched' WHERE id = ?");
        $update->bind_param('i', $request['id']);
        $update->execute();
    }
}

echo "Matching complete\n";
?>
