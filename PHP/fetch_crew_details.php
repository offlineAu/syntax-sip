<?php
if (!isset($_GET['crew_id'])) {
    echo json_encode(['error' => 'Crew ID is required']);
    exit;
}

$crew_id = $_GET['crew_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_crew";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT first_name, last_name, position, salary, joined_date FROM crew WHERE crew_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $crew_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Crew member not found']);
}

$stmt->close();
$conn->close();
?>
