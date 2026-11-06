<?php

class RegisterController
{
    public static function register(): array
    {
        $errors = [];
        $old = ['full_name' => '', 'email' => '', 'role' => 'student', 'department' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $old['full_name']   = trim($_POST['full_name'] ?? '');
                $old['email']       = trim($_POST['email'] ?? '');
                $old['role']        = $_POST['role'] ?? 'student';
                $old['department']  = trim($_POST['department'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm_password'] ?? '';

                if ($old['full_name'] === '') {
                    $errors[] = 'Full name is required.';
                }
                if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                }
                if (!in_array($old['role'], ['student', 'staff'], true)) {
                    $errors[] = 'Select a valid role.';
                }
                if (strlen($password) < 8) {
                    $errors[] = 'Password must be at least 8 characters.';
                }
                if ($password !== $confirm) {
                    $errors[] = 'Passwords do not match.';
                }

                if (empty($errors)) {
                    if (User::findByEmail($old['email'])) {
                        $errors[] = 'An account with that email already exists.';
                    } else {
                        User::create([
                            'full_name'     => $old['full_name'],
                            'email'         => $old['email'],
                            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                            'role'          => $old['role'],
                            'department'    => $old['department'],
                        ]);

                        header('Location: login?registered=1');
                        exit;
                    }
                }
            }
        }

        return ['page_title' => 'Register', 'errors' => $errors, 'old' => $old];
    }
}
