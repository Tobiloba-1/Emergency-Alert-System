<?php

class SosController
{
    public static function trigger(): void
    {
        Session::requireLogin(['student', 'staff']);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!Csrf::verify($input['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid session token.']);
            return;
        }

        $lat = isset($input['latitude']) ? (float) $input['latitude'] : null;
        $lng = isset($input['longitude']) ? (float) $input['longitude'] : null;
        $accuracy = isset($input['accuracy']) ? (float) $input['accuracy'] : null;

        if ($lat === null || $lng === null) {
            http_response_code(422);
            echo json_encode(['error' => 'Location is required to send an SOS.']);
            return;
        }

        $student = User::findById((int) $_SESSION['user_id']);

        // Find the nearest on-duty official first, so we can record who was notified.
        $officials = User::findOnDutyOfficials();
        $nearest = null;
        $nearestDistance = null;

        foreach ($officials as $official) {
            if ($official['last_lat'] === null || $official['last_lng'] === null) {
                continue;
            }
            $distance = Geo::distanceKm((float) $official['last_lat'], (float) $official['last_lng'], $lat, $lng);
            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearest = $official;
            }
        }

        $alertId = SosAlert::create(
            (int) $_SESSION['user_id'],
            $lat,
            $lng,
            $accuracy,
            $nearest ? (int) $nearest['id'] : null
        );

        if ($nearest) {
            $fromName  = Settings::get('mail_from_name', MAIL_FROM_NAME);
            $fromEmail = Settings::get('mail_from_email', MAIL_FROM);
            $headers   = 'From: ' . $fromName . ' <' . $fromEmail . '>';
            $mapsLink  = "https://maps.google.com/?q={$lat},{$lng}";
            $accuracyNote = $accuracy !== null ? ' (accurate to ~' . round($accuracy) . 'm)' : '';

            // Email to the official.
            $subject = 'SOS: ' . $student['full_name'] . ' needs help now';
            $body = "SOS triggered by {$student['full_name']} ({$student['department']}).\n"
                  . 'Student phone: ' . ($student['phone'] ?: 'not provided') . "\n\n"
                  . "Location: {$mapsLink}{$accuracyNote}\n"
                  . 'Distance from you: ' . round($nearestDistance, 2) . " km\n"
                  . 'Time: ' . date('Y-m-d H:i:s') . "\n\n"
                  . 'Log in to your dashboard to acknowledge and respond.';
            @mail($nearest['email'], $subject, $body, $headers);

            // Email to the student, with the official's details.
            $studentBody = "Hi {$student['full_name']},\n\n"
                         . "Your SOS was received and the nearest on-duty security official has been notified.\n\n"
                         . "Official: {$nearest['full_name']}\n"
                         . 'Badge: ' . ($nearest['badge_id'] ?: 'n/a') . "\n"
                         . 'Phone: ' . ($nearest['phone'] ?: 'not provided') . "\n"
                         . 'Distance from you: ' . round($nearestDistance, 2) . " km\n\n"
                         . "You'll get another email when they acknowledge your SOS. "
                         . 'If you are in immediate danger, also call campus security directly.';
            @mail($student['email'], 'Your SOS was received', $studentBody, $headers);
        }

        echo json_encode([
            'ok'                           => true,
            'alert_id'                     => $alertId,
            'notified_officials'           => count($officials),
            'nearest_official_distance_km' => $nearestDistance !== null ? round($nearestDistance, 2) : null,
            'official'                     => $nearest ? [
                'name'        => $nearest['full_name'],
                'badge_id'    => $nearest['badge_id'],
                'phone'       => $nearest['phone'],
                'distance_km' => round($nearestDistance, 2),
            ] : null,
        ]);
    }
}