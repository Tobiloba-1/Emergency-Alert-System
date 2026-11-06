<?php
/**
 * Central configuration. Update to match your local WAMP setup.
 */

// --- Database ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'eas_db');
define('DB_USER', 'eas_user');
define('DB_PASS', 'eas_password'); // Replace with actual password
define('DB_CHARSET', 'utf8mb4');

// --- App ---
define('APP_NAME', 'Campus Emergency Alert System');
define('APP_URL', 'http://localhost/eass/public'); // adjust to your local path

// --- Email (Phase 1: placeholder mail() call) ---
// TODO: Replace with PHPMailer + real SMTP credentials in the Admin/alert-dispatch phase.
define('MAIL_FROM', 'no-reply@eas.local');
define('MAIL_FROM_NAME', APP_NAME);

// --- Security ---
define('PASSWORD_RESET_TOKEN_TTL_MINUTES', 30);

// --- Error reporting (turn off display_errors in production) ---
error_reporting(E_ALL);
ini_set('display_errors', '1');
