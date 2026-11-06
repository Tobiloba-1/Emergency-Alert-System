<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = SecurityController::dashboard();
View::render('security/dashboard', $data);