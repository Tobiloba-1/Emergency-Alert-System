<?php

class AdminUserController
{
    private const ROLES = ['student', 'staff', 'security', 'admin'];

    /** User list, plus handling for the "Add user" modal's POST. */
    public static function index(): array
    {
        Session::requireLogin(['admin']);

        $errors = [];
        $showModal = false;
        $old = [
            'full_name' => '', 'email' => '', 'role' => 'student',
            'department' => '', 'phone' => '', 'badge_id' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $showModal = true; // If anything fails, reopen the modal with errors.

            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $old['full_name']  = trim($_POST['full_name'] ?? '');
                $old['email']      = trim($_POST['email'] ?? '');
                $old['role']       = $_POST['role'] ?? 'student';
                $old['department'] = trim($_POST['department'] ?? '');
                $old['phone']      = trim($_POST['phone'] ?? '');
                $old['badge_id']   = trim($_POST['badge_id'] ?? '');
                $password = $_POST['password'] ?? '';

                if ($old['full_name'] === '') {
                    $errors[] = 'Full name is required.';
                }
                if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                }
                if (strlen($password) < 8) {
                    $errors[] = 'Temporary password must be at least 8 characters.';
                }
                $errors = array_merge(
                    $errors,
                    self::validateRoleFields($old['role'], $old['phone'], $old['badge_id'], true)
                );

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
                            'badge_id'      => $old['badge_id'] ?: null,
                            'phone'         => $old['phone'] ?: null,
                        ]);

                        header('Location: ' . APP_URL . '/admin/users?created=1');
                        exit;
                    }
                }
            }
        }

        return [
            'page_title' => 'User Management',
            'users'      => User::search(null, null),
            'errors'     => $errors,
            'old'        => $old,
            'show_modal' => $showModal,
        ];
    }

    public static function edit(): array
    {
        Session::requireLogin(['admin']);

        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $user = User::findById($id);
        $errors = [];
        $notice = '';

        if (!$user) {
            return ['page_title' => 'User Not Found', 'user' => null, 'errors' => [], 'notice' => ''];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Your session expired. Please try again.';
            } else {
                $role = $_POST['role'] ?? $user['role'];
                $department = trim($_POST['department'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $badgeId = trim($_POST['badge_id'] ?? '');
                $isActive = isset($_POST['is_active']) ? 1 : 0;

                if ((int) $user['id'] === (int) $_SESSION['user_id'] && $isActive === 0) {
                    $errors[] = 'You cannot deactivate your own account.';
                }
                // Phone and badge are only enforced for security officials who stay active.
                $errors = array_merge($errors, self::validateRoleFields($role, $phone, $badgeId, $isActive === 1));

                if (empty($errors)) {
                    User::adminUpdate($user['id'], $role, $department, $isActive, $phone ?: null, $badgeId ?: null);
                    $user = User::findById($user['id']);
                    $notice = 'User updated.';
                }
            }
        }

        return ['page_title' => 'Edit User', 'user' => $user, 'errors' => $errors, 'notice' => $notice];
    }

    private static function validateRoleFields(string $role, string $phone, string $badgeId, bool $enforceSecurityFields): array
    {
        $errors = [];

        if (!in_array($role, self::ROLES, true)) {
            $errors[] = 'Select a valid role.';
        }
        if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
            $errors[] = 'Enter a valid phone number.';
        }
        if ($role === 'security' && $enforceSecurityFields) {
            if ($badgeId === '') {
                $errors[] = 'Badge / ID number is required for security officials.';
            }
            if ($phone === '') {
                $errors[] = 'Phone number is required for security officials.';
            }
        }

        return $errors;
    }
}