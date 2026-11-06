<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$data = PasswordController::reset();
View::render('reset-password', $data);
