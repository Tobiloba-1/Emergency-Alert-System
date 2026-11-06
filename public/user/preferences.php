<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::preferences();
View::render('user/preferences', $data);