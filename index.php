<?php

define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));



$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// ─────────────────────────────────────────────
// 2. STRIP BASE PATH
// ─────────────────────────────────────────────

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

if (
    $basePath !== '' &&
    $basePath !== '/' &&
    str_starts_with($request, $basePath)
) {
    $request = substr($request, strlen($basePath));
}

$request = trim($request, '/');


// ─────────────────────────────────────────────
// 3. DEFINE ROUTES
// ─────────────────────────────────────────────

$routes = [

    // ── PUBLIC FRONTEND ──

    '' => ['file' => 'user/home.php',
        'protected' => false
    ],

    'about' => [
        'file' => 'user/about.php',
        'protected' => false
    ],

    'programmes' => [
        'file' => 'user/programmes.php',
        'protected' => false
    ],

    'board' => [
        'file' => 'user/board.php',
        'protected' => false
    ],

    'contact' => [
        'file' => 'user/contact.php',
        'protected' => false
    ],

    'blogs' => [
        'file' => 'user/blog.php',
        'protected' => false
    ],

    'donate' => [
        'file' => 'user/donate.php',
        'protected' => false
    ],
    'blog_post' => [
        'file' => 'user/blog_post.php',
        'protected' => false
    ],
    'contact_submit' => [
        'file' => 'user/contact_submit.php',
        'protected' => false
    ],
    'feedback-submit' => [
    'file' => 'user/feedback_submit.php',
    'protected' => false
],
    'volunteer-submit' => [
    'file' => 'user/volunteer_submit.php',
    'protected' => false
],


    // ── AUTH ──

    'auth' => [
        'file' => 'auth/auth.php',
        'protected' => false
    ],

    'login' => [
        'file' => 'auth/login.php',
        'protected' => false
    ],

    'logout' => [
        'file' => 'auth/logout.php',
        'protected' => false
    ],

    'register' => [
        'file' => 'auth/auth_register.php',
        'protected' => false
    ],

    'change-password' => [
        'file' => 'auth/change_password.php',
        'protected' => true
    ],


    // ── ADMIN ──

    'dashboard' => [
        'file' => 'admin/dashboard.php',
        'protected' => true
    ],

    'submissions' => [
        'file' => 'admin/submissions.php',
        'protected' => true
    ],

    'leaders' => [
        'file' => 'admin/leadership_manager.php',
        'protected' => true
    ],

    'donate_settings' => [
        'file' => 'admin/donate_settings.php',
        'protected' => true
    ],

    'blog' => [
        'file' => 'admin/blog_manager.php',
        'protected' => true
    ],

    'admins' => [
        'file' => 'admin/admin_roles.php',
        'protected' => true
    ],
    'donations' => [
        'file' => 'admin/donation_settings.php',
        'protected' => true
    ],

];


// ─────────────────────────────────────────────
// 4. AUTH GUARD
// ─────────────────────────────────────────────

function is_admin_authenticated(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return !empty($_SESSION['admin_id']);
}


// ─────────────────────────────────────────────
// 5. ROUTE HANDLING
// ─────────────────────────────────────────────

if (isset($routes[$request])) {

    $route = $routes[$request];

    // ── AUTH CHECK ──

    if ($route['protected'] && !is_admin_authenticated()) {

        header(
            'Location: ' . BASE_URL . '/login?reason=unauthorized'
        );

        exit;
    }


    // ── CHECK FILE ──

    if (!file_exists($route['file'])) {

        http_response_code(500);

        echo '500 — Server Error: Route file not found.';

        exit;
    }


    // ── SERVE FILE ──

    require $route['file'];

    exit;
}


// ─────────────────────────────────────────────
// 6. 404
// ─────────────────────────────────────────────

http_response_code(404);

require 'frontend/views/errors/404.php';

exit;