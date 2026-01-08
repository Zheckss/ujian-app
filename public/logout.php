<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Jalankan fungsi logout
$auth->logout();
?>