<?php
require __DIR__ . '/_auth.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}
$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products.php");
exit;
