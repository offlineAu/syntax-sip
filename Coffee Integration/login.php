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

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($email && $password) {
        $sql = "SELECT id, password, full_name FROM tbl_accounts WHERE email_add = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_fullname"] = $user["full_name"];
            header("Location: /Coffee%20Integration/main.php");
            exit;
        }
        $is_invalid = true;
    } else {
        $is_invalid = true;
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login1.css">
    <title>Sign In / Sign Up</title>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form id="signupForm" action="signup.php" method="POST">
                <h1>Create Account</h1>
                <input type="text" name="txtName" placeholder="Full Name" required />
                <select style="color:grey" name="txtGender" required>
                    <option value="" disabled selected >Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <input type="email" name="txtEmail" placeholder="Email Address" required />
                <input type="text" name="txtPhone" placeholder="Phone Number" required />
                <input type="password" id="password" name="txtPassword" placeholder="Password" required />
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required />
                <div id="passwordMatchError" style="display:none; color:red; font-size: small">Passwords do not match.</div>
                <button type="submit">Sign Up</button>
            </form>
            <div id="signupSuccessMessage" class="center-message">
                <div style="color: green;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M22 4L12 14l-3-3-7 7"></path>
                    </svg>
                    Signup successful!
                </div>
                <a href="login.php">Click here to login</a>
            </div>
        </div>
        <div class="form-container sign-in-container">
            <form action="login.php" method="POST">
                <h1>Sign In</h1>
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
                <?php if ($is_invalid): ?>
                    <div class="alert alert-danger" role="alert">
                        Invalid login credentials.
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src='login.js'></script>
</body>
</html>
