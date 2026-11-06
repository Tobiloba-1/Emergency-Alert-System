<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::alerts();
View::render('user/alerts', $data);