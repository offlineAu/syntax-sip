<?php
session_start();

// Check if user is signed in and get the full name
$full_name = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : 'User';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_orders";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO orders (order_number, products, subtotal, discount, tax, total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdddd", $orderNumber, $products, $subtotal, $discount, $tax, $total);

    // Set parameters and execute
    $orderNumber = $_POST['order_number'];
    $products = implode(', ', json_decode($_POST['products'], true)); // Convert products array to a comma-separated string
    $subtotal = $_POST['subtotal'];
    $discount = $_POST['discount'];
    $tax = $_POST['tax'];
    $total = $_POST['total'];
    
    $stmt->execute();

    echo "<script>
            alert('Order placed successfully!');
            window.location.href = 'menu.php';
          </script>";

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menu.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h2>Welcome, <?php echo htmlspecialchars($full_name); ?></h2>
                <input type="text" class="form-control" placeholder="Search product...">
                <div class="categories mt-3">
                    <button class="btn btn-block btn-light active">All Products</button>
                    <button class="btn btn-block btn-light">Croissant</button>
                    <button class="btn btn-block btn-light">Waffle</button>
                    <button class="btn btn-block btn-light">Coffee</button>
                    <button class="btn btn-block btn-light">Ice Cream</button>
                </div>
            </div>
            <div class="col-md-7 main">
                <div class="row product-grid">
                    <div class="col-md-4 product">
                        <img src="Coffee Bag.png" alt="Syntax.sip Roasted Beans" class="order-bag-img img-fluid">
                        <h3>Roasted Beans</h3>
                        <p>₱299.00 / 1 pc</p>
                        <button class="btn btn-primary" onclick="addToCart('Roasted Beans', 299.00)">Add to Cart</button>
                    </div>
                    <div class="col-md-4 product">
                        <img src="Red Cocktail.png" alt="Hibiscus Tea" class="order-cocktail-img img-fluid">
                        <h3>Hibiscus Tea</h3>
                        <p>₱185.00 / 1 pc</p>
                        <button class="btn btn-primary" onclick="addToCart('Hibiscus Tea', 185.00)">Add to Cart</button>
                    </div>
                    <!-- Repeat similar blocks for other products -->
                </div>
            </div>
            <div class="col-md-3 order-summary">
                <h3>Current Order</h3>
                <p>Order Number: <span id="order-number">N/A</span></p>
                <ul id="order-list" class="list-group"></ul>
                <p>Subtotal: ₱<span id="subtotal">0.00</span></p>
                <p>Discount: ₱<span id="discount">0.00</span></p>
                <p>Total sales tax: ₱<span id="tax">0.00</span></p>
                <p>Total: ₱<span id="total">0.00</span></p>
                <form method="POST" action="" id="order-form">
                    <input type="hidden" name="order_number" id="hidden-order-number">
                    <input type="hidden" name="products" id="hidden-products">
                    <input type="hidden" name="subtotal" id="hidden-subtotal">
                    <input type="hidden" name="discount" id="hidden-discount">
                    <input type="hidden" name="tax" id="hidden-tax">
                    <input type="hidden" name="total" id="hidden-total">
                    <button type="submit" class="btn btn-warning btn-block" id="place-order">Place Order</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="menu.js"></script>
</body>
</html>
