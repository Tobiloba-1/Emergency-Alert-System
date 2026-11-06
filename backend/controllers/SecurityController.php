<?php

class SecurityController
{
    public static function register(): array
    {
        $errors = [];
        $old = ['full_name' => '', 'email' => '', 'badge_id' => '', 'department' => '', 'phone' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $old['full_name']  = trim($_POST['full_name'] ?? '');
                $old['email']      = trim($_POST['email'] ?? '');
                $old['badge_id']   = trim($_POST['badge_id'] ?? '');
                $old['department'] = trim($_POST['department'] ?? '');
                $old['phone']      = trim($_POST['phone'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm_password'] ?? '';

                if ($old['full_name'] === '') $errors[] = 'Full name is required.';
                if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
                if ($old['badge_id'] === '') $errors[] = 'Badge/ID number is required.';
                if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $old['phone'])) $errors[] = 'Enter a valid phone number.';
                if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
                if ($password !== $confirm) $errors[] = 'Passwords do not match.';

                if (empty($errors)) {
                    if (User::findByEmail($old['email'])) {
                        $errors[] = 'An account with that email already exists.';
                    } else {
                        User::create([
                            'full_name'     => $old['full_name'],
                            'email'         => $old['email'],
                            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                            'role'          => 'security',
                            'department'    => $old['department'],
                            'badge_id'      => $old['badge_id'],
                            'phone'         => $old['phone'],
                        ]);
                        header('Location: ' . APP_URL . '/login?registered=1');
                        exit;
                    }
                }
            }
        }

        return ['page_title' => 'Security Official Registration', 'errors' => $errors, 'old' => $old];
    }

    public static function dashboard(): array
    {
        Session::requireLogin(['security']);

        return ['page_title' => 'Security Dashboard', 'user' => User::findById((int) $_SESSION['user_id'])];
    }

    public static function toggleDuty(): void
    {
        Session::requireLogin(['security']);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!Csrf::verify($input['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid session token.']);
            return;
        }

        $onDuty = !empty($input['on_duty']);
        $lat = isset($input['latitude']) ? (float) $input['latitude'] : null;
        $lng = isset($input['longitude']) ? (float) $input['longitude'] : null;
        $accuracy = isset($input['accuracy']) ? (float) $input['accuracy'] : null;

        User::setOnDuty((int) $_SESSION['user_id'], $onDuty, $lat, $lng, $accuracy);
        echo json_encode(['ok' => true, 'on_duty' => $onDuty]);
    }

    public static function updateLocation(): void
    {
        Session::requireLogin(['security']);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!Csrf::verify($input['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid session token.']);
            return;
        }

        User::updateLocation(
            (int) $_SESSION['user_id'],
            (float) ($input['latitude'] ?? 0),
            (float) ($input['longitude'] ?? 0),
            isset($input['accuracy']) ? (float) $input['accuracy'] : null
        );
        echo json_encode(['ok' => true]);
    }

    public static function activeAlerts(): void
    {
        Session::requireLogin(['security']);
        header('Content-Type: application/json');

        $official = User::findById((int) $_SESSION['user_id']);
        $alerts = SosAlert::allOpen();

        foreach ($alerts as &$alert) {
            if ($official['last_lat'] !== null && $official['last_lng'] !== null) {
                $alert['distance_km'] = round(Geo::distanceKm(
                    (float) $official['last_lat'], (float) $official['last_lng'],
                    (float) $alert['latitude'], (float) $alert['longitude']
                ), 2);
            } else {
                $alert['distance_km'] = null;
            }
        }
        unset($alert);

        usort($alerts, fn($a, $b) => ($a['distance_km'] ?? PHP_FLOAT_MAX) <=> ($b['distance_km'] ?? PHP_FLOAT_MAX));

        echo json_encode(['alerts' => $alerts]);
    }

    public static function acknowledge(): void
    {
        Session::requireLogin(['security']);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!Csrf::verify($input['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid session token.']);
            return;
        }

        $alertId = (int) ($input['alert_id'] ?? 0);
        $official = User::findById((int) $_SESSION['user_id']);
        $changed = SosAlert::acknowledge($alertId, (int) $_SESSION['user_id']);

        // Only email the student if this call is the one that acknowledged it.
        if ($changed) {
            $sos = SosAlert::findById($alertId);
            if ($sos && !empty($sos['student_email'])) {
                $fromName  = Settings::get('mail_from_name', MAIL_FROM_NAME);
                $fromEmail = Settings::get('mail_from_email', MAIL_FROM);

                $body = "Hi {$sos['student_name']},\n\n"
                      . "{$official['full_name']} has acknowledged your SOS and is responding.\n\n"
                      . 'Badge: ' . ($official['badge_id'] ?: 'n/a') . "\n"
                      . 'Phone: ' . ($official['phone'] ?: 'not provided') . "\n\n"
                      . 'If it is safe to do so, stay where you are.';
                @mail($sos['student_email'], 'A security official is responding to your SOS', $body,
                      'From: ' . $fromName . ' <' . $fromEmail . '>');
            }
        }

        echo json_encode(['ok' => true]);
    }

    public static function resolveAlert(): void
    {
        Session::requireLogin(['security']);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        if (!Csrf::verify($input['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid session token.']);
            return;
        }

        SosAlert::resolve((int) ($input['alert_id'] ?? 0));
        echo json_encode(['ok' => true]);
    }
}