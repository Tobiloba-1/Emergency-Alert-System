<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = SecurityController::register();
View::render('security/register', $data);