<?php

class SettingsController
{
    public static function edit(): array
    {
        Session::requireLogin(['admin']);

        $errors = [];
        $notice = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $mailFromName  = trim($_POST['mail_from_name'] ?? '');
                $mailFromEmail = trim($_POST['mail_from_email'] ?? '');
                $emailFooter   = trim($_POST['email_footer'] ?? '');

                if ($mailFromName === '') {
                    $errors[] = 'Sender name is required.';
                }
                if (!filter_var($mailFromEmail, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid sender email address.';
                }

                if (empty($errors)) {
                    Settings::set('mail_from_name', $mailFromName);
                    Settings::set('mail_from_email', $mailFromEmail);
                    Settings::set('email_footer', $emailFooter);
                    $notice = 'Settings saved.';
                }
            }
        }

        return [
            'page_title' => 'System Settings',
            'errors'     => $errors,
            'notice'     => $notice,
            'settings'   => [
                'mail_from_name'  => Settings::get('mail_from_name', MAIL_FROM_NAME),
                'mail_from_email' => Settings::get('mail_from_email', MAIL_FROM),
                'email_footer'    => Settings::get('email_footer', ''),
            ],
        ];
    }
}