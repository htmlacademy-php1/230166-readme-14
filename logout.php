<?php
session_start();

$_SESSION = [];
header("Location: main.php");
