<?php

class UserController
{
    public static function dashboard(): array
    {
        Session::requireLogin(['student', 'staff']);

        $user = User::findById((int) $_SESSION['user_id']);

        return [
            'page_title'    => 'My Dashboard',
            'user'          => $user,
            'recent_alerts' => Alert::findForUser($user, 5),
        ];
    }

    public static function alerts(): array
    {
        Session::requireLogin(['student', 'staff']);

        $user = User::findById((int) $_SESSION['user_id']);

        return [
            'page_title' => 'My Alerts',
            'alerts'     => Alert::findForUser($user),
        ];
    }

    public static function alertDetail(): array
    {
        Session::requireLogin(['student', 'staff']);

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $alert = $id > 0 ? Alert::findById($id) : null;

        return [
            'page_title' => $alert ? $alert['title'] : 'Alert Not Found',
            'alert'      => $alert,
        ];
    }

    public static function sosHistory(): array
    {
        Session::requireLogin(['student', 'staff']);

        return [
            'page_title' => 'My SOS Alerts',
            'sos_alerts' => SosAlert::findByStudent((int) $_SESSION['user_id']),
        ];
    }

    public static function preferences(): array
    {
        Session::requireLogin(['student', 'staff']);

        $errors = [];
        $notice = '';
        $user = User::findById((int) $_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $prefs = [
                    'notify_info'     => isset($_POST['notify_info']) ? 1 : 0,
                    'notify_warning'  => isset($_POST['notify_warning']) ? 1 : 0,
                    'notify_critical' => isset($_POST['notify_critical']) ? 1 : 0,
                ];
                User::updatePreferences((int) $_SESSION['user_id'], $prefs);
                $user = User::findById((int) $_SESSION['user_id']);
                $notice = 'Preferences updated.';
            }
        }

        return ['page_title' => 'Notification Preferences', 'errors' => $errors, 'notice' => $notice, 'user' => $user];
    }

    public static function profile(): array
    {
        Session::requireLogin(['student', 'staff']);

        $errors = [];
        $notice = '';
        $user = User::findById((int) $_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $fullName   = trim($_POST['full_name'] ?? '');
                $department = trim($_POST['department'] ?? '');
                $phone      = trim($_POST['phone'] ?? '');

                if ($fullName === '') {
                    $errors[] = 'Full name is required.';
                }

                if (empty($errors)) {
                    User::updateProfile((int) $_SESSION['user_id'], $fullName, $department, $phone);
                    $_SESSION['user_name'] = $fullName;
                    $user = User::findById((int) $_SESSION['user_id']);
                    $notice = 'Profile updated.';
                }
            }
        }

        return ['page_title' => 'My Profile', 'errors' => $errors, 'notice' => $notice, 'user' => $user];
    }
}