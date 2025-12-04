<?php
require __DIR__ . '/_auth.php';
$page_title = "Admin Dashboard";
include __DIR__ . '/../includes/header.php';
?>

<style>
    body {
        background: #f5f5f7;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .admin-container {
        max-width: 1000px;
        margin: auto;
    }

    .admin-title {
        font-size: 2.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #1d1d1f;
    }

    .admin-card {
        background: rgba(255, 255, 255, 0.88);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
        border: 1px solid #e0e0e0;
    }

    .admin-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    .admin-card h2 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #1d1d1f;
    }

    .admin-btn {
        display: inline-block;
        background: #0071e3;
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s;
    }

    .admin-btn:hover {
        background: #005bb5;
    }

    .icon {
        font-size: 30px;
        margin-right: 10px;
        vertical-align: middle;
    }

    /* Dark Mode */
    .dark-mode {
        background: #1c1c1e !important;
    }

    .dark-card {
        background: rgba(40, 40, 40, 0.9) !important;
        border-color: #3a3a3c !important;
    }

    #darkToggle {
        position: fixed;
        right: 20px;
        top: 20px;
    }
</style>

<!-- Dark Mode Toggle -->
<button id="darkToggle" class="btn btn-secondary">Dark Mode</button>

<script>
    document.getElementById('darkToggle').onclick = function() {
        document.body.classList.toggle('dark-mode');

        document.querySelectorAll('.admin-card').forEach(card => {
            card.classList.toggle('dark-card');
        });
    };
</script>

<div class="admin-container mt-5">

    <h1 class="admin-title">Admin Dashboard</h1>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="admin-card">
                <h2><span class="icon">📦</span> Manage Products</h2>
                <p>Add, edit, delete products on your store.</p>
                <a href="products.php" class="admin-btn">Go to Products</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="admin-card">
                <h2><span class="icon">🧾</span> Manage Orders</h2>
                <p>View customer orders, items, delivery status.</p>
                <a href="orders.php" class="admin-btn">View Orders</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="admin-card">
                <h2><span class="icon">👤</span> User Accounts</h2>
                <p>View all users and admins (optional feature).</p>
                <a href="users.php" class="admin-btn">View Users</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="admin-card">
                <h2><span class="icon">⚙️</span> Settings</h2>
                <p>Manage store settings, colors, UI, etc.</p>
                <a href="../index.php" class="admin-btn">Back to Home</a>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
