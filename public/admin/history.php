<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AlertController::history();
View::render('admin/history', $data);