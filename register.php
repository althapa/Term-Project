<?php
require __DIR__ . '/includes/init.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $phone       = trim($_POST['phone']);
    $address     = trim($_POST['address']);
    $city        = trim($_POST['city']);
    $province    = trim($_POST['province']);
    $postal_code = trim($_POST['postal_code']);
    $country     = trim($_POST['country']);

    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (name, email, password, phone, address, city, province, postal_code, country)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssssss",
            $name, $email, $hash,
            $phone, $address, $city,
            $province, $postal_code, $country
        );

        if ($stmt->execute()) {
            header("Location: login.php?msg=Registration+successful.+Please+login.");
            exit;
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

$page_title = "Register";
include __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Create Account</h1>

<?php if ($message): ?>
<div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" class="col-md-6">

    <h4 class="mb-3">Account Details</h4>

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control apple-input" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control apple-input" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control apple-input" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control apple-input" required>
    </div>

    <hr class="my-4">

    <h4 class="mb-3">Billing Address</h4>

    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control apple-input" placeholder="+1 647 555 1234" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Street Address</label>
        <input type="text" name="address" class="form-control apple-input" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control apple-input" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Province</label>
            <select name="province" class="form-control apple-input" required>
                <option value="">Select Province</option>
                <option value="Ontario">Ontario</option>
                <option value="Quebec">Quebec</option>
                <option value="British Columbia">British Columbia</option>
                <option value="Alberta">Alberta</option>
                <option value="Manitoba">Manitoba</option>
                <option value="Saskatchewan">Saskatchewan</option>
                <option value="Nova Scotia">Nova Scotia</option>
                <option value="New Brunswick">New Brunswick</option>
                <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                <option value="Prince Edward Island">Prince Edward Island</option>
                <option value="Northwest Territories">Northwest Territories</option>
                <option value="Yukon">Yukon</option>
                <option value="Nunavut">Nunavut</option>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal_code" class="form-control apple-input" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" value="Canada" class="form-control apple-input" required>
        </div>

    </div>

    <button type="submit" class="btn btn-success apple-btn mt-3">Create Account</button>

</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
