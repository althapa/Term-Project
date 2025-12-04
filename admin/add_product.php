<?php
require __DIR__ . '/_auth.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $category = trim($_POST['category']);
    $stock = (int) $_POST['stock'];

    // Handle image upload
    $image_url = "";
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $tmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {
            $new_name = "prod_" . time() . "_" . rand(1000,9999) . "." . $ext;
            $dest = $upload_dir . $new_name;
            move_uploaded_file($tmp, $dest);
            $image_url = "uploads/" . $new_name;
        }
    }

    // Insert into DB
    $stmt = $conn->prepare(
        "INSERT INTO products (name, description, price, image_url, category, stock)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssdssi", $name, $description, $price, $image_url, $category, $stock);
    $stmt->execute();

    header("Location: products.php");
    exit;
}

$page_title = "Add Product";
include __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-4">Add New Product</h1>

<form method="POST" enctype="multipart/form-data" class="col-md-6">
  <div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input type="number" name="stock" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Product Image</label>
    <input type="file" name="image" class="form-control">
  </div>

  <button type="submit" class="btn btn-success">Add Product</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
