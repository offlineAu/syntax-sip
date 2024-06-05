$(document).ready(function() {
    console.log('Document is ready');

    function updateDashboardData() {
        updateTotalSales();
        updateTotalOrders();
        updateProductsSold();
        updateNewCustomers();
    }

    function updateTotalSales() {
        $.ajax({
            type: 'GET',
            url: 'fetch_total_sales.php',
            success: function(response) {
                console.log('Response:', response);
                let data = JSON.parse(response);
                if (data.total_sales !== undefined) {
                    $('#totalSales').text(`₱${data.total_sales}`);
                } else {
                    console.error('total_sales is undefined in the response:', data);
                    $('#totalSales').text('₱0');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                $('#totalSales').text('₱0');
            }
        });
    }

    function updateTotalOrders() {
        $.ajax({
            type: 'GET',
            url: 'fetch_total_orders.php',
            success: function(response) {
                let data = JSON.parse(response);
                $('#totalOrders').text(data.total_orders);
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    }

    function updateProductsSold() {
        $.ajax({
            type: 'GET',
            url: 'fetch_products_sold.php',
            success: function(response) {
                let data = JSON.parse(response);
                $('#productsSold').text(data.products_sold);
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    }

    function updateNewCustomers() {
        $.ajax({
            type: 'GET',
            url: 'fetch_new_customers.php',
            success: function(response) {
                let data = JSON.parse(response);
                $('#newCustomers').text(data.new_customers);
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    }

    function updateChartData() {
        console.log('updateChartData function called');
        $.ajax({
            type: 'GET',
            url: 'fetch_sales_data.php',
            success: function(response) {
                console.log('Data fetched successfully:', response);
                let dailyTotalSales = JSON.parse(response);

                let chartData = [];
                for (let i = 2; i <= 7; i++) {
                    chartData.push(dailyTotalSales[i] || 0);
                }

                let partialTotal = chartData.reduce((acc, curr) => acc + curr, 0);
                chartData.push(partialTotal);

                updateChartForDailySales(chartData);
                myChart.update();
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    }

    $('a[data-content]').click(function(e) {
        e.preventDefault();
        let contentToShow = $(this).data('content');

        $('#contentArea > div').hide();
        $('#' + contentToShow).show();

        // Remove active class from all links
        $('a.nav-link').removeClass('active');
        // Add active class to the clicked link
        $(this).addClass('active');
    });

    // Handle accept order button click
    $(document).on('click', '.accept-order', function() {
        let orderId = $(this).data('order-id');
        $.ajax({
            type: 'POST',
            url: 'accept_order.php',
            data: { order_id: orderId },
            success: function(response) {
                // Remove the order from the DOM
                $(`button[data-order-id="${orderId}"]`).closest('.order-summary').remove();
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    });

    // Handle get details button click
    $(document).on('click', '.get-details', function() {
        let crewId = $(this).data('crew-id');
        $.ajax({
            type: 'GET',
            url: 'fetch_crew_details.php',
            data: { crew_id: crewId },
            success: function(response) {
                let data = JSON.parse(response);
                $('#payrollDetails').html(`
                    <h3>Details for ${data.first_name} ${data.last_name}</h3>
                    <p><strong>Position:</strong> ${data.position}</p>
                    <p><strong>Salary:</strong> ₱${data.salary} / day</p>
                    <p><strong>Joined:</strong> ${data.joined_date}</p>
                    <button class="btn btn-success generate-payroll" data-crew-id="${crewId}">Generate Payroll</button>
                `);
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    });

    $(document).on('click', '.generate-payroll', function() {
        let crewId = $(this).data('crew-id');
        $.ajax({
            type: 'GET',
            url: 'fetch_payroll.php',
            data: { crew_id: crewId },
            success: function(response) {
                let payrollWindow = window.open('', '_blank', 'width=600,height=400');
                payrollWindow.document.write(response);
                alert('Payroll generated successfully!');
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    });

    // Load dashboard content initially
    $('#contentArea > div').hide();
    $('#dashboardContent').show();

    updateDashboardData();
    updateChartData();
});

function updateChartForDailySales(chartData) {
    var ctx = document.getElementById('TotalRevenue').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Daily Total Sales',
                data: chartData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
