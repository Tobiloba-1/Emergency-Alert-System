<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$data = AuthController::login();
View::render('login', $data);
