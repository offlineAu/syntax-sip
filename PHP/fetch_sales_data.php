<?php
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

// Fetch all order dates and corresponding total sales for each day of the week
$sql = "SELECT DAYOFWEEK(order_date) as day, SUM(total) AS total_sales
        FROM orders
        GROUP BY DAYOFWEEK(order_date)";
$result = $conn->query($sql);

$dailyTotalSales = array();
while ($row = $result->fetch_assoc()) {
    $dailyTotalSales[$row['day']] = $row['total_sales'];
}

echo json_encode($dailyTotalSales);

$conn->close();
?>