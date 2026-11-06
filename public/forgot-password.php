<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$data = PasswordController::forgot();
View::render('forgot-password', $data);
