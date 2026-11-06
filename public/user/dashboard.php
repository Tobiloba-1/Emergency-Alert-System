<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::dashboard();
View::render('user/dashboard', $data);