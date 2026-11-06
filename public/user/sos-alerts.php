<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = AdminSosController::index();
View::render('admin/sos-alerts', $data);