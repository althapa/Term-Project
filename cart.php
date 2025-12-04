<?php
require __DIR__ . '/includes/init.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $pid => $qty) {
            $pid = (int) $pid;
            $qty = (int) $qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$pid]);
            } else {
                $_SESSION['cart'][$pid] = $qty;
            }
        }
    }
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pid = $row['id'];
        $qty = $_SESSION['cart'][$pid];
        $row['quantity'] = $qty;
        $row['subtotal'] = $row['price'] * $qty;
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
}

$page_title = "Your Cart";
include __DIR__ . '/includes/header.php';
?>
<h1 class="mb-4">Shopping Cart</h1>
<?php if (empty($cart_items)): ?>
  <p>Your cart is empty.</p>
  <a href="products.php" class="btn btn-primary">Browse Products</a>
<?php else: ?>
  <form method="post">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Product</th>
          <th style="width:120px;">Price</th>
          <th style="width:120px;">Quantity</th>
          <th style="width:140px;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>
              <input type="number" class="form-control" name="qty[<?php echo $item['id']; ?>]" value="<?php echo (int)$item['quantity']; ?>" min="0">
            </td>
            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <button type="submit" name="update_cart" class="btn btn-outline-secondary">Update Cart</button>
        <a href="products.php" class="btn btn-outline-primary">Continue Shopping</a>
      </div>
      <div class="fs-5 fw-bold">
        Total: $<?php echo number_format($total, 2); ?>
      </div>
    </div>
  </form>
  <div class="mt-4 text-end">
    <a href="checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
