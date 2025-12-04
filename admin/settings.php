<?php
require __DIR__ . '/_auth.php';
$page_title = "Admin - Settings";

if (!isset($_SESSION['dark_mode'])) {
    $_SESSION['dark_mode'] = false;
}

// Handle settings form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Dark Mode toggle
    $_SESSION['dark_mode'] = isset($_POST['dark_mode']);

    // Store Name
    $_SESSION['store_name'] = trim($_POST['store_name'] ?? "Alina Computer Shop");

    $message = "Settings updated successfully!";
}

include __DIR__ . '/../includes/header.php';
?>

<style>
    body {
        background: #f5f5f7;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .settings-container {
        max-width: 900px;
        margin: auto;
    }

    .settings-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1d1d1f;
    }

    .settings-card {
        background: rgba(255,255,255,0.88);
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    }

    .form-check-label {
        font-size: 1.1rem;
    }

    .btn-apple {
        background: #0071e3;
        color: white;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 12px;
    }

    .btn-apple:hover {
        background: #055fc7;
    }

    /* DARK MODE PREVIEW */
    .dark-preview {
        margin-top: 15px;
        padding: 15px;
        background: #1c1c1e;
        border-radius: 12px;
        color: white;
        display: none;
    }
</style>

<div class="settings-container mt-5">

    <h1 class="settings-title">Settings</h1>

    <?php if (!empty($message)): ?>
    <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <div class="settings-card">

        <form method="POST">

            <!-- Store Name -->
            <div class="mb-4">
                <label class="form-label"><strong>Store Name</strong></label>
                <input type="text" name="store_name" class="form-control"
                       value="<?= $_SESSION['store_name'] ?? 'Alina Computer Shop' ?>"
                       required>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       id="darkModeSwitch"
                       name="dark_mode"
                       <?php if ($_SESSION['dark_mode']) echo "checked"; ?>>
                <label class="form-check-label" for="darkModeSwitch">
                    Enable Dark Mode
                </label>
            </div>

            <!-- Dark Mode Preview -->
            <div class="dark-preview" id="darkPreview">
                <p>This is how dark mode will look.</p>
            </div>

            <button type="submit" class="btn btn-apple mt-3">Save Settings</button>
        </form>

    </div>

</div>

<script>
document.getElementById("darkModeSwitch").addEventListener("change", function() {
    const preview = document.getElementById("darkPreview");
    preview.style.display = this.checked ? "block" : "none";
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
