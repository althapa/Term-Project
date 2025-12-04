<?php
if (!isset($page_title)) { 
    $page_title = "Online Computer Store"; 
}

global $base_url;

// Dark mode flag from session
$dark = !empty($_SESSION['dark_mode']);
$store_name = $_SESSION['store_name'] ?? "Alina Computer Shop";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Apple-style font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>/favicon.ico">
</head>

<body class="d-flex flex-column min-vh-100 
<?php echo $dark ? 'bg-dark text-white apple-dark' : 'bg-light apple-light'; ?>">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg 
<?php echo $dark ? 'navbar-dark bg-black' : 'navbar-light bg-white shadow-sm'; ?> mb-4 apple-navbar">

    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand apple-brand fw-semibold" href="<?php echo $base_url; ?>/index.php">
            <?php echo htmlspecialchars($store_name); ?>
        </a>

        <!-- 🌙 DARK MODE BUTTON (MOBILE VIEW) -->
        <div class="d-flex d-lg-none me-2">
            <a href="<?php echo $base_url; ?>/admin/settings.php"
               class="btn btn-sm <?php echo $dark ? 'btn-light' : 'btn-outline-secondary'; ?>">
                🌙
            </a>
        </div>

        <button class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <!-- LEFT NAV -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 apple-nav-links">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/cart.php">Cart</a>
                </li>
                <li class="nav-item">
    <a class="nav-link" href="<?php echo $base_url; ?>/order_history.php">Order History</a>
</li>


                <?php if (is_admin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>/admin/index.php">Admin</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- RIGHT NAV -->
            <ul class="navbar-nav apple-nav-links">

                <?php if (is_logged_in()): ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Hey, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>/logout.php">Logout</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>/login.php">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>/register.php">Register</a>
                    </li>
                <?php endif; ?>

            </ul>

            <!-- 🌙 DARK MODE BUTTON (DESKTOP VIEW) -->
            <div class="d-none d-lg-flex ms-3">
                <a href="<?php echo $base_url; ?>/admin/settings.php"
                   class="btn btn-sm <?php echo $dark ? 'btn-light' : 'btn-outline-secondary'; ?>">
                    🌙
                </a>
            </div>

        </div><!-- end collapse -->
    </div><!-- end container -->
</nav>

<!-- MAIN CONTENT -->
<main class="container flex-grow-1 mb-5 apple-main">
