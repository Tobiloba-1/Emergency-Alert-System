<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$data = RegisterController::register();
View::render('register', $data);
