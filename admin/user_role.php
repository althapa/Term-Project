<?php
require __DIR__ . '/_auth.php';

$user_id = intval($_GET['id'] ?? 0);
$set = intval($_GET['set'] ?? 0);

// Prevent removing YOURSELF from admin
if ($user_id == $_SESSION['user_id']) {
    die("You cannot change your own admin status.");
}

$stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
$stmt->bind_param("ii", $set, $user_id);
$stmt->execute();

header("Location: users.php");
exit;
