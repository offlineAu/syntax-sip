<?php
session_start();
$customerId = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders WHERE customer_id = ? AND status != 'completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();

$orderSummaryHtml = '';
while ($row = $result->fetch_assoc()) {
    $orderSummaryHtml .= '<div class="order-summary-item">';
    $orderSummaryHtml .= '<h4>Order Number: ' . htmlspecialchars($row['order_number']) . '</h4>';
    $orderSummaryHtml .= '<p>Order Date: ' . htmlspecialchars($row['order_date']) . '</p>';
    $products = json_decode($row['products'], true);
    if ($products) {
        foreach ($products as $product) {
            $orderSummaryHtml .= '<p>' . htmlspecialchars($product['name']) . ' - ₱' . htmlspecialchars($product['price']) . '</p>';
        }
    }
    $orderSummaryHtml .= '<p>Subtotal: ₱' . htmlspecialchars($row['subtotal']) . '</p>';
    $orderSummaryHtml .= '<p>Discount: ₱' . htmlspecialchars($row['discount']) . '</p>';
    $orderSummaryHtml .= '<p>Tax: ₱' . htmlspecialchars($row['tax']) . '</p>';
    $orderSummaryHtml .= '<p>Total: ₱' . htmlspecialchars($row['total']) . '</p>';
    if ($row['status'] == 'pending') {
        $orderSummaryHtml .= '<button class="btn btn-success accept-order" data-order-id="' . htmlspecialchars($row['order_id']) . '">Accept</button>';
    } else {
        $orderSummaryHtml .= '<button class="btn btn-primary generate-receipt" data-order-id="' . htmlspecialchars($row['order_id']) . '">Generate Receipt</button>';
    }
    $orderSummaryHtml .= '</div>';
}

echo $orderSummaryHtml;

$stmt->close();
$conn->close();
?>
