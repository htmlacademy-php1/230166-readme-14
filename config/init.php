<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once 'vendor/autoload.php';

require_once 'config/config.php';
require_once 'helpers.php';
require_once 'models.php';
require_once 'functions.php';
require_once 'data.php';

session_start();

$current_user = $_SESSION['current_user'] ?? null;

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
