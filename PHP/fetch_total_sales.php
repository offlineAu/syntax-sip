<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current date
$currentDate = date('Y-m-d');

// Get the start of the current week (assuming week starts on Monday)
$startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));

// Get the end of the current week (Sunday)
$endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($currentDate)));

// Update the SQL query to get the total sales for the entire week
$sql = "SELECT SUM(total) AS total_sales FROM orders WHERE order_date BETWEEN '$startOfWeek' AND '$endOfWeek'";
$result = $conn->query($sql);

$totalSales = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalSales = $row['total_sales'];
}

echo json_encode(['total_sales' => $totalSales]);

$conn->close();
?>
