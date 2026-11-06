<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AlertController::dashboard();
View::render('admin/dashboard', $data);