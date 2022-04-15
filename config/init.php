<?php
require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';

$link = mysqli_connect($user['host'], $user['user'], $user['password'], $user['database']);
mysqli_set_charset($link, 'utf8mb4');

