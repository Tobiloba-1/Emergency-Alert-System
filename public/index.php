<?php
require_once __DIR__ . '/../backend/bootstrap.php';

Router::run(require __DIR__ . '/../backend/config/routes.php');