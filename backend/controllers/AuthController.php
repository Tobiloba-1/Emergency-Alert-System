<?php

class AuthController
{
    public static function login(): array
    {
        if (Session::isLoggedIn()) {
            header('Location: ' . APP_URL . '/');
            exit;
        }

        $errors = [];
        $old = ['email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } elseif (RateLimiter::tooManyAttempts('login')) {
                $errors[] = 'Too many attempts. Please wait a few minutes and try again.';
            } else {
                $old['email'] = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $user = User::findByEmail($old['email']);

                if ($user && password_verify($password, $user['password_hash'])) {
                    if ((int) $user['is_active'] === 0) {
                        $errors[] = 'This account has been deactivated. Contact an administrator.';
                    } else {
                        RateLimiter::reset('login');
                        Session::login($user);
                        header('Location: ' . APP_URL . '/index.php');
                        exit;
                    }
                } else {
                    $errors[] = 'Incorrect email or password.';
                }
            }
        }

        return ['page_title' => 'Log In', 'errors' => $errors, 'old' => $old];
    }

    public static function logout(): void
    {
        Session::destroy();
        header('Location: ' . APP_URL . '/login');
        exit;
    }
}