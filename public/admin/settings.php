<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = SettingsController::edit();
View::render('admin/settings', $data);