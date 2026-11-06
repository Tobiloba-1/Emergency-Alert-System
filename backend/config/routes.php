<?php
/**
 * Route table: every URL the app responds to, in one place.
 *
 *   'url/path' => [
 *       'controller' => class that handles the request,
 *       'action'     => static method on that class,
 *       'view'       => template in frontend/views (leave out for JSON/redirect-only routes),
 *       'roles'      => roles allowed in (leave out for public routes),
 *   ]
 *
 * URLs are written without ".php". A request for "login.php" is treated as "login".
 */

$admin    = ['admin'];
$member   = ['student', 'staff'];
$security = ['security'];

return [

    // ── Public ──
    ''                  => ['controller' => 'HomeController',     'action' => 'index',    'view' => 'home'],
    'login'             => ['controller' => 'AuthController',     'action' => 'login',    'view' => 'login'],
    'logout'            => ['controller' => 'AuthController',     'action' => 'logout'],
    'register'          => ['controller' => 'RegisterController', 'action' => 'register', 'view' => 'register'],
    'forgot-password'   => ['controller' => 'PasswordController', 'action' => 'forgot',   'view' => 'forgot-password'],
    'reset-password'    => ['controller' => 'PasswordController', 'action' => 'reset',    'view' => 'reset-password'],
    'security/register' => ['controller' => 'SecurityController', 'action' => 'register', 'view' => 'security/register'],

    // ── Admin ──
    'admin/dashboard'   => ['controller' => 'AlertController',     'action' => 'dashboard', 'view' => 'admin/dashboard',  'roles' => $admin],
    'admin/broadcast'   => ['controller' => 'AlertController',     'action' => 'broadcast', 'view' => 'admin/broadcast',  'roles' => $admin],
    'admin/history'     => ['controller' => 'AlertController',     'action' => 'history',   'view' => 'admin/history',    'roles' => $admin],
    'admin/users'       => ['controller' => 'AdminUserController', 'action' => 'index',     'view' => 'admin/users',      'roles' => $admin],
    'admin/user-edit'   => ['controller' => 'AdminUserController', 'action' => 'edit',      'view' => 'admin/user-edit',  'roles' => $admin],
    'admin/settings'    => ['controller' => 'SettingsController',  'action' => 'edit',      'view' => 'admin/settings',   'roles' => $admin],
    'admin/reports'     => ['controller' => 'ReportsController',   'action' => 'index',     'view' => 'admin/reports',    'roles' => $admin],
    'admin/sos-alerts'  => ['controller' => 'AdminSosController',  'action' => 'index',     'view' => 'admin/sos-alerts', 'roles' => $admin],

    // ── Student / staff ──
    'user/dashboard'    => ['controller' => 'UserController', 'action' => 'dashboard',   'view' => 'user/dashboard',    'roles' => $member],
    'user/alerts'       => ['controller' => 'UserController', 'action' => 'alerts',      'view' => 'user/alerts',       'roles' => $member],
    'user/alert-detail' => ['controller' => 'UserController', 'action' => 'alertDetail', 'view' => 'user/alert-detail', 'roles' => $member],
    'user/sos-history'  => ['controller' => 'UserController', 'action' => 'sosHistory',  'view' => 'user/sos-history',  'roles' => $member],
    'user/preferences'  => ['controller' => 'UserController', 'action' => 'preferences', 'view' => 'user/preferences',  'roles' => $member],
    'user/profile'      => ['controller' => 'UserController', 'action' => 'profile',     'view' => 'user/profile',      'roles' => $member],
    'sos/trigger'       => ['controller' => 'SosController',   'action' => 'trigger',    'roles' => $member],

    // ── Security officials ──
    'security/dashboard'       => ['controller' => 'SecurityController', 'action' => 'dashboard',      'view' => 'security/dashboard', 'roles' => $security],
    'security/toggle-duty'     => ['controller' => 'SecurityController', 'action' => 'toggleDuty',     'roles' => $security],
    'security/update-location' => ['controller' => 'SecurityController', 'action' => 'updateLocation', 'roles' => $security],
    'security/active-alerts'   => ['controller' => 'SecurityController', 'action' => 'activeAlerts',   'roles' => $security],
    'security/acknowledge'     => ['controller' => 'SecurityController', 'action' => 'acknowledge',    'roles' => $security],
    'security/resolve'         => ['controller' => 'SecurityController', 'action' => 'resolveAlert',   'roles' => $security],
];