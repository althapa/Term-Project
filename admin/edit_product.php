<?php
require __DIR__ . '/_auth.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}
$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $category = trim($_POST['category']);
    $stock = (int) $_POST['stock'];

    $image_url = $product['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {
            $new_name = "prod_" . time() . "_" . rand(1000,9999) . "." . $ext;
            move_uploaded_file($tmp, $upload_dir . $new_name);
            $image_url = "uploads/" . $new_name;
        }
    }

    $stmt = $conn->prepare("
        UPDATE products
        SET name=?, description=?, price=?, image_url=?, category=?, stock=?
        WHERE id=?
    ");
    $stmt->bind_param("ssdssii", $name, $description, $price, $image_url, $category, $stock, $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}

$page_title = "Edit Product";
include __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-4">Edit Product</h1>

<form method="POST" enctype="multipart/form-data" class="col-md-6">

  <div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control"
           value="<?= htmlspecialchars($product['name']) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" class="form-control"
           value="<?= $product['price'] ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control"
           value="<?= htmlspecialchars($product['category']) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input type="number" name="stock" class="form-control"
           value="<?= $product['stock'] ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Current Image</label><br>
    <?php if ($product['image_url']): ?>
      <img src="../<?= $product['image_url'] ?>" width="150" class="mb-2">
    <?php endif; ?>
    <input type="file" name="image" class="form-control">
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
