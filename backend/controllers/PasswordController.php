<?php

class PasswordController
{
    public static function forgot(): array
    {
        $errors = [];
        $notice = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $email = trim($_POST['email'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                } else {
                    $user = User::findByEmail($email);
                    $notice = 'If that email is registered, a reset link has been sent.';

                    if ($user) {
                        $token = PasswordReset::create((int) $user['id'], PASSWORD_RESET_TOKEN_TTL_MINUTES);
                        $resetLink = APP_URL . '/reset-password?token=' . $token;

                        $fromName  = Settings::get('mail_from_name', MAIL_FROM_NAME);
                        $fromEmail = Settings::get('mail_from_email', MAIL_FROM);

                        $subject = 'Reset your ' . APP_NAME . ' password';
                        $body = "Hi {$user['full_name']},\n\nReset your password here (expires in "
                              . PASSWORD_RESET_TOKEN_TTL_MINUTES . " minutes):\n{$resetLink}\n\n"
                              . "If you didn't request this, ignore this email.";
                        @mail($email, $subject, $body, 'From: ' . $fromName . ' <' . $fromEmail . '>');
                    }
                }
            }
        }

        return ['page_title' => 'Forgot Password', 'errors' => $errors, 'notice' => $notice];
    }

    public static function reset(): array
    {
        $errors = [];
        $token = $_GET['token'] ?? ($_POST['token'] ?? '');
        $validReset = $token !== '' ? PasswordReset::findValidByToken($token) : null;

        if ($token === '' || !$validReset) {
            $errors[] = 'This reset link is invalid or has expired. Please request a new one.';
        }

        if ($validReset && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 8) {
                    $errors[] = 'Password must be at least 8 characters.';
                }
                if ($password !== $confirm) {
                    $errors[] = 'Passwords do not match.';
                }

                if (empty($errors)) {
                    User::updatePassword((int) $validReset['user_id'], password_hash($password, PASSWORD_DEFAULT));
                    PasswordReset::markUsed((int) $validReset['id']);

                    header('Location: ' . APP_URL . '/login?reset=1');
                    exit;
                }
            }
        }

        return [
            'page_title'  => 'Reset Password',
            'errors'      => $errors,
            'token'       => $token,
            'valid_reset' => $validReset,
        ];
    }
}