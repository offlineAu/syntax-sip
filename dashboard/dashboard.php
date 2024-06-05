<?php
session_start();

if (!isset($_SESSION["crew_id"])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_crew";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$crew_id = $_SESSION["crew_id"];
$sql = "SELECT first_name, last_name FROM crew WHERE crew_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $crew_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $_SESSION['cashier_name'] = $firstName . ' ' . $lastName;
} else {
    $_SESSION['cashier_name'] = 'User';
}

$stmt->close();

// Fetch crew members for payroll
$crew_members = [];
$sql = "SELECT crew_id, first_name, last_name FROM crew";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $crew_members[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                <div class="sidebar-sticky">
                    <h2 class="text-white text-center my-4"><?php echo htmlspecialchars($_SESSION['cashier_name']); ?></h2>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#" data-content="dashboardContent">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-content="orderContent">Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-content="payrollContent">Payroll</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-content="salesReportContent">Sales Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-content="messagesContent">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" data-content="settingsContent">Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                            <span data-feather="calendar"></span>
                            This week
                        </button>
                    </div>
                </div>

                <div id="contentArea">
                    <div id="dashboardContent">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Sales</h5>
                                        <p class="card-text" id="totalSales">₱0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Orders</h5>
                                        <p class="card-text" id="totalOrders">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Products Sold</h5>
                                        <p class="card-text" id="productsSold">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">New Customers</h5>
                                        <p class="card-text" id="newCustomers">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Daily Sales</h5>
                                        <div class="chart">
                                            <canvas id="TotalRevenue" width="700" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="orderContent" class="content-section" style="display:none;">
                        <h2>Orders</h2>
                        <div id="order-summary-container"></div>
                    </div>
                    <script>
                    $(document).ready(function() {
                        function fetchOrderSummary() {
                            $.ajax({
                                type: 'GET',
                                url: 'fetch_orders.php',
                                success: function(response) {
                                    $('#order-summary-container').html(response);
                                    // Attach event handlers for accept order and generate receipt buttons
                                    $('.accept-order').click(acceptOrder);
                                    $('.generate-receipt').click(generateReceipt);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching order summary:', error);
                                }
                            });
                        }

                        $('a[data-content="orderContent"]').click(function(e) {
                            e.preventDefault();
                            fetchOrderSummary(); // Fetch order summary when "Order" tab is clicked
                            let contentToShow = $(this).data('content');

                            $('#contentArea > div').hide();
                            $('#' + contentToShow).show();

                            $('a.nav-link').removeClass('active');
                            $(this).addClass('active');
                        });

                        // Load order summary initially if needed
                        if (window.location.hash === '#orderContent') {
                            fetchOrderSummary();
                        }
                    });

                    function acceptOrder() {
                        const orderId = $(this).data('order-id');
                        const button = $(this);
                        $.ajax({
                            type: 'POST',
                            url: 'accept_order.php',
                            data: { order_id: orderId },
                            success: function(response) {
                                alert('Order accepted successfully!');
                                // Change the button to a "Generate Receipt" button
                                button.removeClass('accept-order btn-primary').addClass('generate-receipt btn-success').text('Generate Receipt');
                                // Attach the new event handler
                                button.off('click', acceptOrder).on('click', generateReceipt);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error accepting order:', error);
                            }
                        });
                    }

                    function generateReceipt() {
                        const orderId = $(this).data('order-id');
                        $.ajax({
                            type: 'POST',
                            url: 'generate_receipt.php',
                            data: { order_id: orderId },
                            success: function(response) {
                                window.open('receipt_view.php?order_id=' + orderId, '_blank', 'width=600,height=400');
                                $(`button[data-order-id="${orderId}"]`).closest('.order-summary-item').remove();
                                fetchOrderSummary(); // Refresh order summary
                            },
                            error: function(xhr, status, error) {
                                console.error('Error generating receipt:', error);
                            }
                        });
                    }
                    </script>
                

                    <div id="payrollContent" class="content-section" style="display:none;">
                        <h2>Payroll</h2>
                        <div id="payrollCrewList" class="mt-3">
                            <h5 class="text-dark">Crew Members</h5>
                            <ul class="list-group">
                                <?php foreach ($crew_members as $crew) { ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($crew['first_name'] . ' ' . $crew['last_name']); ?>
                                        <button class="btn btn-primary btn-sm get-details" data-crew-id="<?php echo $crew['crew_id']; ?>">Get Details</button>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div id="payrollDetails"></div>
                    </div>

                    <div id="salesReportContent" class="content-section" style="display:none;">
                        <h2>Sales Report Section</h2>
                        <!-- Sales Report content here -->
                    </div>

                    <div id="messagesContent" class="content-section" style="display:none;">
                        <h2>Messages Section</h2>
                        <!-- Messages content here -->
                    </div>
                    
                    <div id="settingsContent" class="content-section" style="display:none;">
                        <h2>Settings Section</h2>
                        <!-- Settings content here -->
                    </div>
                </div>
            </main>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="dashboard.js"></script>
</body>
</html>
