<?php

class AlertController
{
    public static function dashboard(): array
    {
        Session::requireLogin(['admin']);

        return [
            'page_title'     => 'Admin Dashboard',
            'total_alerts'   => Alert::countAll(),
            'total_students' => User::countStudents(),
            'total_staff'    => User::countStaff(),
            'recent_alerts'  => Alert::latest(5),
        ];
    }

    public static function broadcast(): array
    {
        Session::requireLogin(['admin']);

        $errors = [];
        $old = [
            'title'             => '',
            'message'           => '',
            'severity'          => 'info',
            'target_role'       => 'all',
            'target_department' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $old['title']             = trim($_POST['title'] ?? '');
                $old['message']           = trim($_POST['message'] ?? '');
                $old['severity']          = $_POST['severity'] ?? 'info';
                $old['target_role']       = $_POST['target_role'] ?? 'all';
                $old['target_department'] = trim($_POST['target_department'] ?? '');

                if ($old['title'] === '') {
                    $errors[] = 'Title is required.';
                }
                if ($old['message'] === '') {
                    $errors[] = 'Message is required.';
                }
                if (!in_array($old['severity'], ['info', 'warning', 'critical'], true)) {
                    $errors[] = 'Select a valid severity.';
                }
                if (!in_array($old['target_role'], ['all', 'student', 'staff'], true)) {
                    $errors[] = 'Select a valid audience.';
                }

                if (empty($errors)) {
                    $recipients = User::findEmailsByTarget(
                        $old['target_role'],
                        $old['target_department'] ?: null,
                        $old['severity']
                    );

                    $fromName  = Settings::get('mail_from_name', MAIL_FROM_NAME);
                    $fromEmail = Settings::get('mail_from_email', MAIL_FROM);
                    $footer    = Settings::get('email_footer', '');

                    $sent = 0;
                    $failed = 0;

                    foreach ($recipients as $recipient) {
                        $subject = '[' . strtoupper($old['severity']) . '] ' . $old['title'];
                        $body = "Hi {$recipient['full_name']},\n\n{$old['message']}\n\n— " . APP_NAME;
                        if ($footer) {
                            $body .= "\n\n" . $footer;
                        }

                        $ok = @mail($recipient['email'], $subject, $body, 'From: ' . $fromName . ' <' . $fromEmail . '>');
                        $ok ? $sent++ : $failed++;
                    }

                    $alertId = Alert::create([
                        'title'             => $old['title'],
                        'message'           => $old['message'],
                        'severity'          => $old['severity'],
                        'target_role'       => $old['target_role'],
                        'target_department' => $old['target_department'] ?: null,
                        'created_by'        => $_SESSION['user_id'],
                        'recipients_count'  => count($recipients),
                        'sent_count'        => $sent,
                        'failed_count'      => $failed,
                    ]);

                    header('Location: ' . APP_URL . '/admin/history?sent=' . $alertId);
                    exit;
                }
            }
        }

        return ['page_title' => 'Broadcast Alert', 'errors' => $errors, 'old' => $old];
    }

    public static function history(): array
    {
        Session::requireLogin(['admin']);

        return [
            'page_title' => 'Alert History',
            'alerts'     => Alert::all(),
            'just_sent'  => isset($_GET['sent']) ? (int) $_GET['sent'] : null,
        ];
    }
}