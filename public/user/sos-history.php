<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = UserController::sosHistory();
View::render('user/sos-history', $data);