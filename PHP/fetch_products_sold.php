<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date("Y-m-d");
$sql = "SELECT count(*) AS products_sold FROM orders WHERE DATE(order_date) = '$currentDate'";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$productsSold = $row['products_sold'] ? $row['products_sold'] : 0;

echo json_encode(array("products_sold" => $productsSold));

$conn->close();
?>
