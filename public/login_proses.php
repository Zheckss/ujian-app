<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php'; // Panggil manual biar aman

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

if ($_POST) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($auth->login($user, $pass)) {
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: index.php?error=1");
        exit;
    }
}
?>