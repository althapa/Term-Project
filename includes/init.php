<?php
// DB + session init
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "alina_computer_shop"; // must match database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// folder name under htdocs
$base_url = "/alina_computer_shop";

function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?>
