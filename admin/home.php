<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection/connection.php");
include_once("model/Order.php");

$role = $_SESSION['role'] ? $_SESSION['role'] : "staff";
if ($role === 'staff') {
    $_SESSION['error'] = "Access denied. You are not authorized to view the page.";
    header("Location: orders.php");
    exit();
}
$order = new Order($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/stocks.css">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        /* Card with gradient background */
        .card {
            background: linear-gradient(135deg, darkslateblue, mediumslateblue);
            color: white;
            border: none;
            border-radius: 10px;
            /* Rounded corners */
        }

        /* Card Title Text */
        .card-title {
            font-size: 1rem;
            font-weight: bold;
        }

        .orders {
            font-size: 1.2rem;
            /* Smaller orders number */
            font-weight: bold;
        }

        /* Icon container colors */
        .icon-container {
            font-size: 1.5rem;
            color: white;
            /* White icon to blend with the card */
            background: lightskyblue;
            /* Soft blue background */
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /* Rounded shape */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Slight shadow for elevation */
        }

        /* Percentage Change with */
        .percentage-change {
            font-size: 0.9rem;
            font-weight: bold;
        }

        /* Positive percentage */
        .percentage-positive {
            color: #4CAF50;
        }

        /* Negative percentage */
        .percentage-negative {
            color: #F44336;
        }

        #notification-container {
            /* Set text color to red */
            color: #FF0000;
            padding: 10px;
            font-size: 20px;
            z-index: 1000;
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <?php include('partials/sidebar.php'); ?>

    <div id="content">
        <div class="container mt-2">
            <div class="notification-bell" id="bell" title="Low stocks">
                <i class="bi bi-bell fs-1 text-primary bell"></i>
                <span class="badge" id="badge">0</span>
            </div>
            <div id="notification-container" class="text-center" style="display: none;"></div>

            <h1>Welcome to the Admin Dashboard</h1>
            <p>Your central hub for managing the application.</p>

            <!-- KPIs Overview -->
            <div class="row mb-4">
                <div class="row">
                    <!-- Today Orders -->
                    <div class="col-md-4">
                        <div class="card mb-4"> <!-- position-relative to position icon -->
                            <div class="card-body position-relative">
                                <h5 class="card-title">Today's Orders</h5>
                                <div class="icon-container position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <p id="todayOrders" class="orders mb-0"></p> <!-- For displaying the orders -->
                                <!-- Percentage Change just below title and orders -->
                                <span id="todayChange" class="percentage-change mt-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- This Week Orders -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body position-relative">
                                <h5 class="card-title">This Week's Orders</h5>
                                <div class="icon-container position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <p id="weekOrders" class="orders"></p> <!-- For displaying the orders -->
                                <span id="weekChange" class="percentage-change"></span>
                            </div>
                        </div>
                    </div>

                    <!-- This Month Orders -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body position-relative">
                                <h5 class="card-title">This Month's Orders</h5>
                                <div class="icon-container position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <p id="monthOrders" class="orders"></p> <!-- For displaying the orders -->
                                <span id="monthChange" class="percentage-change"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue Today -->
                    <div class="col-md-4">
                        <div class="card mb-4 position-relative">
                            <div class="icon-container position-absolute top-0 end-0 p-2">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Today's Revenue</h5>
                                <p class="card-text">R<span id="todayRevenue"></span></p>
                                <span id="revenueChange1" class="percentage-change mt-2"></span>
                            </div>
                        </div>
                    </div>


                    <!-- Total Revenue This Week -->
                    <div class="col-md-4">
                        <div class="card mb-4 position-relative">
                            <div class="icon-container position-absolute top-0 end-0 p-2">
                                <i class="fas fa-money-bill-alt"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">This Week's Revenue</h5>
                                <p class="card-text">R<span id="weekRevenue"></span></p>
                                <span id="revenueChange2" class="percentage-change mt-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue This Month -->
                    <div class="col-md-4">
                        <div class="card mb-4 position-relative">
                            <div class="icon-container position-absolute top-0 end-0 p-2">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="card-body">
                                <span class="card-title">This Month's Revenue</span>
                                <p class="card-text">R<span id="monthRevenue"></span></p>
                                <p id="revenueChange3" class="percentage-change mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Average Monthly Order Price -->
                    <div class="col-md-4">
                        <div class="card mb-4 position-relative">
                            <div class="card-body">
                                <div class="icon-container position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <h5 class="card-title">Monthly Average</h5>
                                <p id="averageOrderValue" class="card-text"></p> <!-- For displaying the average value -->
                                <span id="averageOrderChange" class="percentage-change mt-2"></span> <!-- For displaying the percentage change -->
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateBell.js"></script>
    <!-- Chart.js to create the Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Order Difference -->
    <script>
        async function fetchOrdersData() {
            const response = await fetch('model/getOrdersData.php');
            const data = await response.json();
            return data;
        }

        // Calculate percentage change
        function calculatePercentageChange(current, previous) {
            if (previous === 0) {
                return current > 0 ? 100 : 0; // division by zero
            }
            return ((current - previous) / previous * 100).toFixed(2);
        }

        // Update display with amount and percentage change
        function updateDisplay(amountElementId, percentageElementId, current, previous) {
            const percentageChange = calculatePercentageChange(current, previous);
            const amountElement = document.getElementById(amountElementId);
            const percentageElement = document.getElementById(percentageElementId);

            // Display the current amount
            amountElement.innerText = `${current}`;

            // Handle Infinity case
            if (!isFinite(percentageChange)) {
                percentageElement.innerHTML = `<span class="percentage-na">No change</span>`;
                return;
            }

            // Handle NaN case (invalid percentage change)
            if (isNaN(percentageChange)) {
                percentageElement.innerHTML = `<span class="percentage-na">No orders were made yesterday</span>`;
                return;
            }



            // Display the percentage change with trend indicator
            if (percentageChange > 0) {
                percentageElement.innerHTML = `<span class="percentage-positive"><i class="fas fa-angle-double-up"></i> ${percentageChange}%</span>`;
            } else if (percentageChange < 0) {
                percentageElement.innerHTML = `<span class="percentage-negative"><i class="fas fa-angle-double-down"></i> ${Math.abs(percentageChange)}%</span>`;
            } else {
                percentageElement.innerHTML = `<span>${percentageChange}%</span>`;
            }
        }

        // Render the data
        async function renderData() {
            const ordersData = await fetchOrdersData();

            // Update today's orders
            updateDisplay('todayOrders', 'todayChange', ordersData.today.current, ordersData.today.previous);

            // Update this week's and month's orders similarly
            updateDisplay('weekOrders', 'weekChange', ordersData.week.current, ordersData.week.previous);
            updateDisplay('monthOrders', 'monthChange', ordersData.month.current, ordersData.month.previous);
        }

        renderData();
    </script>

    <!-- Revenue Difference -->
    <script>
        async function fetchRevenueData() {
            try {
                const response = await fetch('model/getRevenueData.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                //console.log('Fetched revenue data:', data);
                return data;
            } catch (error) {
                //console.error('Error fetching revenue data:', error);
                return null; // Handle fetch failure
            }
        }

        // Calculate percentage change
        function calculatePercentageChange(current, previous) {
            if (previous === 0) {
                return current > 0 ? 100 : 0; // division by zero
            }
            return ((current - previous) / previous * 100).toFixed(2);
        }

        // Update percentage display with trend indicator
        function updatePercentageDisplay(elementId, current, previous) {
            const percentageChange = calculatePercentageChange(current, previous);
            //console.log(`Updating ${elementId} with percentage change: ${percentageChange}`);

            const element = document.getElementById(elementId);
            if (!element) {
                //console.error(`Element with ID ${elementId} not found.`);
                return;
            }

            element.innerHTML = `<span class="${percentageChange > 0 ? 'percentage-positive' : (percentageChange < 0 ? 'percentage-negative' : '')}">
        
                <i class="${percentageChange > 0 ? 'fas fa-angle-double-up' : (percentageChange < 0 ? 'fas fa-angle-double-down' : '')}"></i> ${Math.abs(percentageChange)}%
                </span>`;

        }



        // Render the revenue data 
        function renderRevenueData() {
            fetchRevenueData().then(revenueData => {
                //console.log('Revenue data:', revenueData);

                if (!revenueData) {
                    console.error('Failed to load revenue data.');
                    return;
                }

                // Update today's revenue
                if (revenueData.today) {
                    document.getElementById('todayRevenue').innerText = revenueData.today.current.toFixed(2);
                    if (revenueData.today.previous !== undefined) {
                        updatePercentageDisplay('revenueChange1', revenueData.today.current, revenueData.today.previous);
                    } else {
                        //console.error('Previous data for today is missing.');
                    }
                } else {
                    //console.error('Today’s revenue data is missing.');
                }

                // Update this week's revenue
                if (revenueData.week) {
                    document.getElementById('weekRevenue').innerText = revenueData.week.current.toFixed(2);
                    if (revenueData.week.previous !== undefined) {
                        updatePercentageDisplay('revenueChange2', revenueData.week.current, revenueData.week.previous);
                    } else {
                        //console.error('Previous data for this week is missing.');
                    }
                } else {
                    //console.error('This week’s revenue data is missing.');
                }

                // Update this month's revenue
                if (revenueData.month) {
                    document.getElementById('monthRevenue').innerText = revenueData.month.current.toFixed(2);
                    if (revenueData.month.previous !== undefined) {
                        updatePercentageDisplay('revenueChange3', revenueData.month.current, revenueData.month.previous);
                    } else {
                       // console.error('Previous data for this month is missing.');
                    }
                } else {
                    //console.error('This month’s revenue data is missing.');
                }
            });
        }
        renderRevenueData();
    </script>

    <!-- Average Order Difference -->
    <script>
        async function fetchAverageOrderValue() {
            try {
                const response = await fetch('model/getAverageOrderPrice.php');
                const data = await response.json();

                // Check if currentMonthAverage is a valid number before using toFixed
                let currentAverage = parseFloat(data.currentMonthAverage);
                if (isNaN(currentAverage)) {
                    currentAverage = 0; // Default to 0 if not a valid number
                }

                // Display the current month's average order value
                document.getElementById('averageOrderValue').innerText = `R${currentAverage.toFixed(2)}`;

                // Display the percentage change with a trend indicator
                const changeElement = document.getElementById('averageOrderChange');
                const percentageChange = parseFloat(data.percentageChange);

                if (percentageChange > 0) {
                    changeElement.innerHTML = `<span class="percentage-positive"><i class="fas fa-angle-double-up"></i> ${percentageChange}%</span>`;
                } else if (percentageChange < 0) {
                    changeElement.innerHTML = `<span class="percentage-negative"><i class="fas fa-angle-double-down"></i> ${Math.abs(percentageChange)}%</span>`;
                } else {
                    changeElement.innerHTML = `<span>${percentageChange}%</span>`;
                }
            } catch (error) {
                //console.error('Error fetching average order value:', error);
            }
        }


        // Call the function to fetch and display the data
        fetchAverageOrderValue();
    </script>



</body>

</html>