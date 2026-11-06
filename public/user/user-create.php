<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AdminUserController::create();
View::render('admin/user-create', $data);