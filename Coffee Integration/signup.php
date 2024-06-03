<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection code
$con = mysqli_connect('localhost', 'root', '', 'db_accounts');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$response = array("success" => false, "message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $txtName = $_POST['txtName'];
    $txtEmail = $_POST['txtEmail'];
    $txtPhone = $_POST['txtPhone'];
    $txtPassword = $_POST['txtPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $txtGender = $_POST['txtGender'];

    if ($txtPassword !== $confirmPassword) {
        $response["message"] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);

    $query = "INSERT INTO tbl_accounts (full_name, email_add, phone_num, password, gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $txtName, $txtEmail, $txtPhone, $hashedPassword, $txtGender);

    if (mysqli_stmt_execute($stmt)) {
        $response["success"] = true;
        $response["message"] = "Signup successful!";
    } else {
        $response["message"] = "Error: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
echo json_encode($response);
?>
