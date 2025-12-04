<?php
require __DIR__ . '/includes/init.php';

// Get search and category filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build SQL dynamically
if ($search !== '') {
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("
        SELECT id, name, price, image_url, category 
        FROM products
        WHERE name LIKE ? 
           OR category LIKE ?
           OR description LIKE ?
        ORDER BY name
    ");
    $stmt->bind_param("sss", $like, $like, $like);

} elseif ($category !== '') {
    $stmt = $conn->prepare("
        SELECT id, name, price, image_url, category 
        FROM products
        WHERE category = ?
        ORDER BY name
    ");
    $stmt->bind_param("s", $category);

} else {
    $stmt = $conn->prepare("SELECT id, name, price, image_url, category FROM products ORDER BY name");
}

$stmt->execute();
$result = $stmt->get_result();

$page_title = "Products - Online Computer Store";
include __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Browse Products</h1>

<!-- SEARCH + CATEGORY FILTER -->
<div class="filter-box p-4 mb-4 rounded shadow-sm" 
     style="background: <?php echo !empty($_SESSION['dark_mode']) ? '#1c1c1e' : '#ffffff'; ?>">

    <form method="get" class="row g-3">

        <!-- Search Bar -->
        <div class="col-md-5">
            <input type="text" 
                   name="search" 
                   class="form-control"
                   placeholder="Search by name, category, description..."
                   value="<?php echo htmlspecialchars($search); ?>">
        </div>

        <!-- Category Filter -->
        <div class="col-md-4">
            <input type="text" 
                   name="category" 
                   class="form-control"
                   placeholder="Filter by category (e.g., Laptops)"
                   value="<?php echo htmlspecialchars($category); ?>">
        </div>

        <!-- Buttons -->
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-45">Search</button>
            <a href="products.php" class="btn btn-outline-secondary w-45">Clear</a>
        </div>

    </form>
</div>

<!-- SHOW SEARCH TITLE -->
<?php if ($search): ?>
    <h4 class="mb-4">Search results for: “<?php echo htmlspecialchars($search); ?>”</h4>
<?php endif; ?>

<?php if ($category && !$search): ?>
    <h4 class="mb-4">Category: “<?php echo htmlspecialchars($category); ?>”</h4>
<?php endif; ?>

<!-- PRODUCT GRID -->
<div class="row">

<?php if ($result && $result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="col-md-3 mb-4">
      <div class="card h-100 apple-product-card">

        <!-- PRODUCT IMAGE -->
        <?php if (!empty($row['image_url'])): ?>
          <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
               class="card-img-top" 
               alt="<?php echo htmlspecialchars($row['name']); ?>"
               style="height: 200px; object-fit: cover;">
        <?php endif; ?>

        <div class="card-body d-flex flex-column">

          <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>

          <p class="card-text mb-1">
            <small class="text-muted"><?php echo htmlspecialchars($row['category']); ?></small>
          </p>

          <p class="card-text fw-bold mb-3">
            $<?php echo number_format($row['price'], 2); ?>
          </p>

          <a href="product.php?id=<?php echo $row['id']; ?>" 
             class="btn btn-primary mt-auto">
             View Details
          </a>

        </div>

      </div>
    </div>
  <?php endwhile; ?>

<?php else: ?>
  <p>No products found.</p>
<?php endif; ?>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>


<style>
/* Apple-style product card */
.apple-product-card {
    border-radius: 18px;
    overflow: hidden;
    transition: 0.3s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.apple-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.15);
}

.apple-dark .card {
    background: #2c2c2e !important;
    border: 1px solid #3a3a3c;
    color: #f2f2f2 !important;
}

.apple-dark .text-muted {
    color: #b0b0b0 !important;
}

.filter-box input {
    border-radius: 14px;
    padding: 10px 14px;
}

.filter-box button {
    border-radius: 14px;
}

.w-45 {
    width: 48%;
}
</style>
