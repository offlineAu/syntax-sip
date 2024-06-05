<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) AS total_orders FROM orders";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$totalOrders = $row['total_orders'];

echo json_encode(array("total_orders" => $totalOrders));

$conn->close();
?>
