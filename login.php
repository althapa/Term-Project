<?php
require __DIR__ . '/includes/init.php';

$message = isset($_GET['msg']) ? $_GET['msg'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $email_db, $hash, $is_admin);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (!empty($hash) && password_verify($password, $hash)) {
            $_SESSION['user_id']   = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['is_admin']  = $is_admin;

            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}

$page_title = "Login";
include __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Login</h1>

<?php if ($message): ?>
  <div class="alert alert-warning"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="post" class="col-md-4">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  <button type="submit" class="btn btn-primary">Login</button>
  <a href="register.php" class="btn btn-link">Register</a>
</form>

<div class="mt-4">
    <a href="admin/index.php" 
       class="btn btn-dark px-4 py-2"
       style="border-radius: 12px; font-weight: 500;">
       Go to Admin Panel
    </a>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
