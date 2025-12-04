<?php
require __DIR__ . '/_auth.php';

// Fetch order + items + product info
$orders = $conn->query("
    SELECT 
        o.id AS order_id,
        o.user_id,
        u.name AS user_name,
        o.total_price,
        o.order_date,
        o.status,
        oi.product_id,
        oi.quantity,
        p.name AS product_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON oi.order_id = o.id
    LEFT JOIN products p ON p.id = oi.product_id
    ORDER BY o.id DESC, oi.product_id ASC
");

$page_title = "Admin - Orders";
include __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-4">Orders</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Total</th>
            <th>Date</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Update</th>
        </tr>
    </thead>

    <tbody>
    <?php 
    $last_order = -1;
    while ($o = $orders->fetch_assoc()):
    ?>

        <tr>
            <!-- Show order information ONLY for first row of each order -->
            <?php if ($last_order != $o['order_id']): ?>
                <td rowspan="1"><?= $o['order_id'] ?></td>
                <td rowspan="1"><?= htmlspecialchars($o['user_name']) ?></td>
                <td rowspan="1">$<?= number_format($o['total_price'], 2) ?></td>
                <td rowspan="1"><?= $o['order_date'] ?></td>
            <?php else: ?>
                <td></td><td></td><td></td><td></td>
            <?php endif; ?>

            <!-- Product name -->
            <td>
                <?= htmlspecialchars($o['product_name']) ?>
                <?php if (!empty($o['product_id'])): ?>
                    <a href="/alina_computer_shop/product.php?id=<?= $o['product_id'] ?>"
                       class="btn btn-sm btn-primary ms-2">
                       View
                    </a>
                <?php endif; ?>
            </td>

            <!-- Quantity -->
            <td><?= $o['quantity'] ?></td>

            <!-- Status only on the first row -->
            <?php if ($last_order != $o['order_id']): ?>
                <td rowspan="1"><?= $o['status'] ?></td>
                <td rowspan="1">
                    <a href="update_status.php?id=<?= $o['order_id'] ?>&status=Pending" class="btn btn-warning btn-sm">Pending</a>
                    <a href="update_status.php?id=<?= $o['order_id'] ?>&status=Delivered" class="btn btn-success btn-sm">Delivered</a>
                </td>
            <?php else: ?>
                <td></td><td></td>
            <?php endif; ?>
        </tr>

    <?php 
    $last_order = $o['order_id'];
    endwhile;
    ?>
    </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
