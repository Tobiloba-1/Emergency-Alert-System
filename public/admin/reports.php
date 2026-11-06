<?php
require_once __DIR__ . '/../../backend/bootstrap.php';

$data = ReportsController::index();
View::render('admin/reports', $data);