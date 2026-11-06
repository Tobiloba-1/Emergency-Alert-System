<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AdminUserController::edit();
View::render('admin/user-edit', $data);