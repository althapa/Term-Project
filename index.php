<?php
require __DIR__ . '/includes/init.php';
$page_title = "Home - Alina Computer Shop (Apple Style)";

$sql = "SELECT id, name, price, image_url FROM products ORDER BY id DESC LIMIT 4";
$result = $conn->query($sql);

include __DIR__ . '/includes/header.php';
?>

<!-- ============================= -->
<!-- APPLE VISION PRO STYLE HERO   -->
<!-- ============================= -->

<div class="apple-hero-container mb-5">

    <!-- Video Background -->
    <video class="apple-hero-video" autoplay muted loop playsinline>
        <source src="hero.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <!-- Dark gradient overlay -->
    <div class="apple-hero-overlay"></div>

    <!-- Center text -->
    <div class="apple-hero-text">
        <h1 class="fw-bold">Welcome to Alina Computer Shop</h1>
        <p class="subtitle">Where performance meets elegance.</p>

        <a href="products.php" class="btn apple-hero-btn">
            Shop Now
        </a>
    </div>
</div>

<!-- Apple-style Search Bar -->
<div class="search-container mb-5">
    <form action="products.php" method="GET" class="d-flex justify-content-center">
        <input type="text" 
               name="search" 
               class="form-control search-box"
               placeholder="Search for laptops, desktops, accessories…"
               required>
        <button class="btn btn-primary search-btn">Search</button>
    </form>
</div>

<style>
.search-container {
    max-width: 600px;
    margin: 0 auto;
}

.search-box {
    border-radius: 14px;
    padding: 12px 18px;
    font-size: 1rem;
    margin-right: 10px;
    border: 1px solid #ccc;
}

.search-btn {
    border-radius: 14px;
    padding: 12px 20px;
}

.apple-dark .search-box {
    background: #2c2c2e;
    border: 1px solid #444;
    color: white;
}
</style>


<!-- ============================= -->
<!-- FEATURED PRODUCTS             -->
<!-- ============================= -->

<h2 class="text-center mb-4 fw-bold">Featured Products</h2>

<div class="row justify-content-center">
<?php if ($result && $result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="col-md-3 mb-4">
      <div class="card h-100 apple-card">
        <?php if (!empty($row['image_url'])): ?>
          <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="">
        <?php endif; ?>
        <div class="card-body text-center d-flex flex-column">
          <h5 class="fw-semibold"><?php echo htmlspecialchars($row['name']); ?></h5>
          <p class="fw-bold mb-3">$<?php echo number_format($row['price'], 2); ?></p>
          <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto">View Details</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p class="text-center">No products yet. Add some through Admin Panel.</p>
<?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
