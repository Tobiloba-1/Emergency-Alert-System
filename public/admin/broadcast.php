<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AlertController::broadcast();
View::render('admin/broadcast', $data);