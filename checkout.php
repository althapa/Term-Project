<?php
require __DIR__ . '/includes/init.php';

if (!is_logged_in()) {
    header("Location: login.php?msg=Please+login+to+checkout");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $pid = $row['id'];
    $qty = $_SESSION['cart'][$pid];

    if ($qty > $row['stock']) {
        $qty = $row['stock'];
    }

    $row['quantity'] = $qty;
    $row['subtotal'] = $row['price'] * $qty;
    $total += $row['subtotal'];

    $items[] = $row;
}

//
// PROCESS ORDER ON SUBMIT
//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {

    // (Safe) Mask card number
    $masked_card = "**** **** **** " . substr($_POST['card_number'], -4);

    // Billing information (optional to save)
    $billing_name     = $_POST['billing_name'];
    $billing_email    = $_POST['billing_email'];
    $billing_phone    = $_POST['billing_phone'];
    $billing_address  = $_POST['billing_address'];
    $billing_city     = $_POST['billing_city'];
    $billing_province = $_POST['billing_province'];
    $billing_postal   = $_POST['billing_postal'];
    $billing_country  = $_POST['billing_country'];

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_price, status) 
        VALUES (?, ?, 'Pending')
    ");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();

    $order_id = $stmt->insert_id;

    $stmt_item = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt_stock = $conn->prepare("
        UPDATE products SET stock = stock - ? WHERE id = ?
    ");

    foreach ($items as $it) {
        $pid = $it['id'];
        $qty = $it['quantity'];
        $price = $it['price'];

        $stmt_item->bind_param("iiid", $order_id, $pid, $qty, $price);
        $stmt_item->execute();

        $stmt_stock->bind_param("ii", $qty, $pid);
        $stmt_stock->execute();
    }

    $_SESSION['cart'] = [];

    header("Location: index.php?order=success");
    exit;
}

$page_title = "Checkout";
include __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Checkout</h1>

<style>
.payment-box {
    background: <?php echo !empty($_SESSION['dark_mode']) ? '#1c1c1e' : '#ffffff'; ?>;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}
.apple-input {
    border-radius: 14px;
    padding: 12px;
    border: 1px solid #ccc;
}
.apple-dark .apple-input {
    background: #2c2c2e;
    border: 1px solid #3a3a3c;
    color: white;
}
.apple-btn {
    border-radius: 14px;
    padding: 12px 20px;
}
</style>

<!-- ORDER SUMMARY -->
<table class="table">
    <thead>
        <tr>
            <th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($items as $it): ?>
        <tr>
            <td><?php echo htmlspecialchars($it['name']); ?></td>
            <td>$<?php echo number_format($it['price'], 2); ?></td>
            <td><?php echo (int)$it['quantity']; ?></td>
            <td>$<?php echo number_format($it['subtotal'], 2); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="text-end fs-5 fw-bold mb-4">
    Total: $<?php echo number_format($total, 2); ?>
</div>

<!-- BILLING + PAYMENT FORM -->
<div class="payment-box">

    <h4 class="mb-3">Billing Details</h4>

    <form method="post" id="paymentForm">

        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="billing_name" class="form-control apple-input" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="billing_email" class="form-control apple-input" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="billing_phone" maxlength="15"
                   class="form-control apple-input" placeholder="+1 647 555 1234" required>
        </div>

        <div class="mb-3">
            <label>Street Address</label>
            <input type="text" name="billing_address" class="form-control apple-input" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>City</label>
                <input type="text" name="billing_city" class="form-control apple-input" required>
            </div>

            <div class="col-md-6 mb-3">
    <label>Province</label>
    <select name="billing_province" class="form-control apple-input" required>
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


        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Postal Code</label>
                <input type="text" name="billing_postal" class="form-control apple-input" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Country</label>
                <input type="text" name="billing_country" class="form-control apple-input" value="Canada" required>
            </div>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Payment Information</h4>

        <div class="mb-3">
            <label>Cardholder Name</label>
            <input type="text" name="card_name" class="form-control apple-input" required>
        </div>

        <div class="mb-3">
            <label>Card Number</label>
            <input type="text" name="card_number"
                   class="form-control apple-input"
                   maxlength="16" pattern="[0-9]{16}"
                   placeholder="1234123412341234" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Expiry (MM/YY)</label>
                <input type="text" name="expiry"
                       class="form-control apple-input"
                       maxlength="5" pattern="[0-9]{2}/[0-9]{2}"
                       placeholder="04/27" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>CVV</label>
                <input type="password" name="cvv"
                       class="form-control apple-input"
                       maxlength="3" pattern="[0-9]{3}" required>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" name="confirm_order"
                    class="btn btn-success btn-lg apple-btn">
                Confirm Order
            </button>

            <a href="cart.php" class="btn btn-outline-secondary apple-btn">
                Back to Cart
            </a>
        </div>

    </form>
</div>

<script>
document.getElementById("paymentForm").addEventListener("submit", function(e) {
    let card = document.querySelector("[name=card_number]").value;
    if (card.length !== 16) {
        alert("Invalid card number");
        e.preventDefault();
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
