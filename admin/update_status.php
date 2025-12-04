<?php
require __DIR__ . '/_auth.php';

$order_id = intval($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';

if ($order_id > 0 && ($status == 'Pending' || $status == 'Delivered')) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

header("Location: orders.php");
exit;
