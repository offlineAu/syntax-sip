<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_accounts";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date("Y-m-d");
$sql = "SELECT COUNT(*) AS new_customers FROM accounts WHERE DATE(created_at) = '$currentDate'";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$newCustomers = $row['new_customers'];

echo json_encode(array("new_customers" => $newCustomers));

$conn->close();
?>
