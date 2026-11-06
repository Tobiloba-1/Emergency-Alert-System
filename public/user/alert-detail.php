<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::alertDetail();
View::render('user/alert-detail', $data);