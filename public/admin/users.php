<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AdminUserController::index();
View::render('admin/users', $data);