<?php

require_once 'config/config.php';
require_once 'helpers.php';
require_once 'models.php';
require_once 'functions.php';
require_once 'data.php';

session_start();

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));
$db_cfg = require_once 'config/db.php';
$db_cfg = array_values($db_cfg);

$con = mysqli_connect(...$db_cfg);
mysqli_set_charset($con, 'utf8mb4');

if (!$con) {
    $error = mysqli_connect_error();
    show_error($error);
}
