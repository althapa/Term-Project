<?php
require __DIR__ . '/includes/init.php';

// Require login
if (!is_logged_in()) {
    header("Location: login.php?msg=Please login first");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user orders with items
$sql = "
    SELECT 
        o.id AS order_id,
        o.total_price,
        o.order_date,
        o.status,
        oi.product_id,
        oi.quantity,
        p.name AS product_name
    FROM orders o
    LEFT JOIN order_items oi ON oi.order_id = o.id
    LEFT JOIN products p ON p.id = oi.product_id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC, o.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "Order History";
include __DIR__ . "/includes/header.php";
?>

<style>
.order-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

.apple-dark .order-card {
    background: rgba(40,40,40,0.9);
    border: 1px solid #333;
    color: #f2f2f2;
}

.order-title {
    font-size: 1.35rem;
    font-weight: 600;
}

.order-status {
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 0.9rem;
}

.status-delivered {
    background: #34c759;
    color: white;
}

.status-pending {
    background: #ff9f0a;
    color: white;
}

.product-item {
    padding: 8px 0;
}
</style>

<h1 class="mb-4">Your Order History</h1>

<?php
$current_order = -1;

if ($result->num_rows === 0): ?>
    <div class="alert alert-info">You have no orders yet.</div>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()): ?>

    <?php if ($current_order != $row['order_id']): ?>

        <?php if ($current_order != -1): ?>
                </div> <!-- end items -->
            </div> <!-- end card -->
        <?php endif; ?>

        <div class="order-card">
            <div class="order-title">
                Order #<?= $row['order_id'] ?>
            </div>

            <div class="mb-2">Placed on: <strong><?= $row['order_date'] ?></strong></div>
            <div class="mb-2">Total: <strong>$<?= number_format($row['total_price'], 2) ?></strong></div>

            <div class="mb-3">
                Status:

                <?php if ($row['status'] === 'Delivered'): ?>
                    <span class="order-status status-delivered">Delivered</span>
                <?php else: ?>
                    <span class="order-status status-pending">Pending</span>
                <?php endif; ?>
            </div>

            <h5>Items:</h5>
            <div class="ms-3">

    <?php endif; ?>

            <div class="product-item">
                <?= htmlspecialchars($row['product_name']) ?> × <strong><?= $row['quantity'] ?></strong>

                <a href="<?= $base_url ?>/product.php?id=<?= $row['product_id'] ?>" 
                   class="btn btn-sm btn-primary ms-3">
                   View Product
                </a>
            </div>

<?php 
$current_order = $row['order_id'];

endwhile;

if ($current_order != -1): ?>
        </div> <!-- end items -->
    </div> <!-- end card -->
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
