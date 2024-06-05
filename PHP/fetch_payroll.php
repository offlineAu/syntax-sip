<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname_orders = "db_orders";
$dbname_crew = "db_crew";

$crew_id = $_GET['crew_id'];

// Create connections
$conn_orders = new mysqli($servername, $username, $password, $dbname_orders);
$conn_crew = new mysqli($servername, $username, $password, $dbname_crew);

if ($conn_orders->connect_error || $conn_crew->connect_error) {
    die("Connection failed: " . $conn_orders->connect_error . $conn_crew->connect_error);
}

// Fetch crew details
$sql_crew = "SELECT first_name, last_name FROM crew WHERE crew_id = ?";
$stmt_crew = $conn_crew->prepare($sql_crew);
$stmt_crew->bind_param("i", $crew_id);
$stmt_crew->execute();
$result_crew = $stmt_crew->get_result();
$crew = $result_crew->fetch_assoc();

// Fetch payroll details
$sql_orders = "SELECT order_date, tip FROM orders WHERE crew_id = ?";
$stmt_orders = $conn_orders->prepare($sql_orders);
$stmt_orders->bind_param("i", $crew_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

$order_dates = [];
$total_tip = 0;
while ($order = $result_orders->fetch_assoc()) {
    $order_dates[] = $order['order_date'];
    $total_tip += $order['tip'];
}

if (count($order_dates) > 0) {
    $start_date = new DateTime($order_dates[0]);
    $end_date = new DateTime(end($order_dates));
    $interval = $start_date->diff($end_date);
    $days_covered = $interval->days;

    // Add one to include the start date itself
    $days_covered += 1;

    $date_covered = $start_date->format("F d") . " - " . $end_date->format("d, Y");
    $rate = 500 * $days_covered;
} else {
    $date_covered = "N/A";
    $days_covered = 0;
    $rate = 0;
}

$salary = $rate;
$received_amount = $salary + $total_tip;

$stmt_crew->close();
$stmt_orders->close();
$conn_crew->close();
$conn_orders->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Payroll Details</h1>
        <h2><?php echo htmlspecialchars($crew['first_name'] . ' ' . $crew['last_name']); ?></h2>
        <p><strong>Date Covered:</strong> <?php echo htmlspecialchars($date_covered); ?></p>
        <p><strong>Rate:</strong> 500x<?php echo htmlspecialchars($days_covered); ?> days ₱<?php echo htmlspecialchars($rate); ?>.00</p>
        <p><strong>Total Tips:</strong> ₱<?php echo htmlspecialchars($total_tip); ?></p>
        <p><strong>Salary:</strong> ₱<?php echo htmlspecialchars($salary); ?></p>
        <p><strong>Total Received Amount:</strong> ₱<?php echo htmlspecialchars($received_amount); ?></p>
        <button class="btn btn-primary" onclick="window.print()">Print Payroll</button>
    </div>
</body>
</html>
