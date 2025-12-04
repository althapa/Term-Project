<?php
require __DIR__ . '/includes/init.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: products.php");
    exit;
}
$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT id, name, description, price, image_url, category, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

$page_title = $product['name'] . " - Product Details";
include __DIR__ . '/includes/header.php';
?>
<div class="row">
  <div class="col-md-5">
    <?php if (!empty($product['image_url'])): ?>
      <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid mb-3" alt="Product image">
    <?php else: ?>
      <div class="bg-light border d-flex align-items-center justify-content-center" style="height:250px;">
        <span class="text-muted">No image</span>
      </div>
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <p class="text-muted">Category: <?php echo htmlspecialchars($product['category']); ?></p>
    <p class="fs-4 fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    <p>In stock: <?php echo (int)$product['stock']; ?></p>

    <?php if ($product['stock'] > 0): ?>
      <form method="post" action="cart.php" class="row g-3 mt-3">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <div class="col-auto">
          <label for="quantity" class="col-form-label">Quantity</label>
        </div>
        <div class="col-auto">
          <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo (int)$product['stock']; ?>" class="form-control">
        </div>
        <div class="col-auto">
          <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
        </div>
      </form>
    <?php else: ?>
      <p class="text-danger fw-bold">Out of stock</p>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
