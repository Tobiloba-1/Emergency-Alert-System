<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::profile();
View::render('user/profile', $data);