<?php
require __DIR__ . '/_auth.php';

$result = $conn->query("SELECT id, name, price, category, stock FROM products ORDER BY id DESC");
$page_title = "Admin - Products";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">Products</h1>
  <a href="add_product.php" class="btn btn-success">Add Product</a>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td>$<?= number_format($row['price'], 2) ?></td>
        <td><?= (int)$row['stock'] ?></td>
        <td>
          <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this product?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
