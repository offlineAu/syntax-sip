<?php
session_start();
$orderId = $_GET['order_id'];

$servername = "localhost";
$username = "root";
$password = "";

// Connection to db_orders
$conn_orders = new mysqli($servername, $username, $password, "db_orders");

// Check connection for db_orders
if ($conn_orders->connect_error) {
    die("Connection to db_orders failed: " . $conn_orders->connect_error);
}

// Connection to db_accounts
$conn_accounts = new mysqli($servername, $username, $password, "db_accounts");

// Check connection for db_accounts
if ($conn_accounts->connect_error) {
    die("Connection to db_accounts failed: " . $conn_accounts->connect_error);
}

// Connection to db_crew
$conn_crew = new mysqli($servername, $username, $password, "db_crew");

// Check connection for db_crew
if ($conn_crew->connect_error) {
    die("Connection to db_crew failed: " . $conn_crew->connect_error);
}

// Correct the table name if needed
$sql = "SELECT o.order_id, o.order_date, o.order_number, o.products, o.subtotal, o.discount, o.tax, o.total, a.full_name, c.first_name, c.last_name 
        FROM db_orders.orders o 
        JOIN db_accounts.tbl_accounts a ON o.customer_id = a.customer_id 
        JOIN db_crew.crew c ON o.crew_id = c.crew_id
        WHERE o.order_id = ?";
$stmt = $conn_orders->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn_orders->error));
}
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    die("Order not found");
}

$stmt->close();
$conn_orders->close();
$conn_accounts->close();
$conn_crew->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Syntax.sip</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Syntax.sip</h1>
        <h2>Receipt</h2>
        <p>Cashier: <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
        <p>Customer Name: <?php echo htmlspecialchars($order['full_name']); ?></p>
        <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p>Order Number: <?php echo htmlspecialchars($order['order_number']); ?></p>
        <p>Products: <?php echo htmlspecialchars($order['products']); ?></p>
        <p>Subtotal: ₱<?php echo htmlspecialchars($order['subtotal']); ?></p>
        <p>Discount: ₱<?php echo htmlspecialchars($order['discount']); ?></p>
        <p>Tax: ₱<?php echo htmlspecialchars($order['tax']); ?></p>
        <p>Total: ₱<?php echo htmlspecialchars($order['total']); ?></p>
        <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>
