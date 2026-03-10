<?php

function bloodCompatibility(string $donorGroup, string $requiredGroup): bool
{
    $compatible = [
        'O-' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
        'O+' => ['O+', 'A+', 'B+', 'AB+'],
        'A-' => ['A-', 'A+', 'AB-', 'AB+'],
        'A+' => ['A+', 'AB+'],
        'B-' => ['B-', 'B+', 'AB-', 'AB+'],
        'B+' => ['B+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+']
    ];

    return in_array($requiredGroup, $compatible[$donorGroup] ?? [], true);
}

function urgencyScore(string $urgency): int
{
    return match ($urgency) {
        'critical' => 40,
        'high' => 30,
        'medium' => 20,
        default => 10,
    };
}

function locationScore(string $donorCity, string $patientCity): int
{
    return strtolower(trim($donorCity)) === strtolower(trim($patientCity)) ? 20 : 10;
}

function waitingTimeScore(int $days): int
{
    return min(20, max(0, $days));
}

function buildPriorityScore(array $donor, array $request, int $waitingDays): int
{
    $compatibility = bloodCompatibility($donor['blood_group'], $request['required_group']) ? 20 : 0;
    $urgency = urgencyScore($request['urgency_level']);
    $location = locationScore($donor['location_city'], $request['location_city']);
    $waiting = waitingTimeScore($waitingDays);

    return $compatibility + $urgency + $location + $waiting;
}
?>
