<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ? $_SESSION['role'] : "staff";
if ($role === 'staff') {
    $_SESSION['error'] = "Access denied. You are not authorized to view the page.";
    header("Location: orders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        canvas {
            min-height: 250px;
            height: 250px;
            max-height: 250px;
            max-width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-lg-6 {
            flex-basis: 50%;
        }

        .chart-container {
            margin: 20px;
        }

        .tab-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .tab-button {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
        }

        .tab-button.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>

    <?php include_once("partials/sidebar.php"); ?>
    <div id="content" class="container mt-4">
        <div class="row">
            <!-- Revenue By Category -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card">
                    <h4 class="card-header">Revenue By Category</h4>
                    <div class="card-body">
                        <canvas id="revenueByCategoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Comparison Chart -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card">
                    <h4 class="card-header">Monthly Orders</h4>
                    <div class="card-body">
                        <canvas id="orderComparisonChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Frequency Chart with Tabs -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card">
                    <h4 class="card-header">Order Frequency</h4>
                    <div class="card-body">
                        <div class="tab-container">
                            <button class="tab-button active" data-filter="today">Today</button>
                            <button class="tab-button" data-filter="week">This Week</button>
                            <button class="tab-button" data-filter="month">This Month</button>
                        </div>
                        <canvas id="orderFrequencyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Chart -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card">
                    <h4 class="card-header">Revenue By Payment Method</h4>
                    <div class="card-body">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Checkout guest vs registered -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <h4 class="card-header">Monthly Checkout</h4>
                    <div class="card-body">
                        <canvas id="checkoutComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Trends Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <h4 class="card-header">Sales Performance</h4>
                    <div class="card-body">
                        <canvas id="salesTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.min.js"></script>

    <!-- Chart.js to create the Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Revenue By Category -->
    <script>
        // Function to fetch revenue by category
        async function fetchRevenueData() {
            const response = await fetch('model/revenueByCategory.php');
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderChart() {
            const revenueData = await fetchRevenueData();

            // Extract categories and revenue from the data
            const categories = revenueData.map(item => item.category);
            const revenues = revenueData.map(item => item.revenue);

            // Create the chart
            const ctx = document.getElementById('revenueByCategoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', // Donut chart
                data: {
                    labels: categories, // Categories as labels
                    datasets: [{
                        data: revenues, // Revenue as data
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(99, 192, 152, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const category = tooltipItem.label;
                                    const revenue = tooltipItem.raw;
                                    return `${category}: R${revenue.toLocaleString()}`;
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${tooltipItem.label}: R${tooltipItem.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    elements: {
                        arc: {
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        // Call the function to render the chart
        renderChart();
    </script>

    <!-- Checkout comparison guest vs registered -->
    <script>
        // Function to fetch checkout comparison data
        async function fetchCheckoutComparisonData() {
            const response = await fetch('model/fetch_checkout_comparison.php');
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderCheckoutComparisonChart() {
            const checkoutData = await fetchCheckoutComparisonData();

            // Extract months, guest checkouts, registered user checkouts, and total orders from the data
            const months = checkoutData.map(item => item.month);
            const guestCheckouts = checkoutData.map(item => item.guest_checkouts);
            const registeredUserCheckouts = checkoutData.map(item => item.registered_user_checkouts);
            const totalOrders = checkoutData.map(item => item.total_orders);

            // Create the dual line chart
            const ctx = document.getElementById('checkoutComparisonChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', // Line chart
                data: {
                    labels: months, // Months as labels
                    datasets: [{
                            label: 'Guest Checkouts',
                            data: guestCheckouts, // Guest checkouts data
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Registered User Checkouts',
                            data: registeredUserCheckouts, // Registered user checkouts data
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: false,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Total Orders',
                            data: totalOrders, // Total orders data
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: false,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: false,
                            text: 'Monthly Checkout Comparison (Completed Orders)'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Orders'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Render the chart on page load
        renderCheckoutComparisonChart();
    </script>

    <!-- Order comparison canceled vs completed -->
    <script>
        // Function to fetch order comparison data
        async function fetchOrderComparisonData() {
            const response = await fetch('model/fetch_order_comparison.php');
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderOrderComparisonChart() {
            const orderData = await fetchOrderComparisonData();

            // Extract months, completed orders, and cancelled orders from the data
            const months = orderData.map(item => item.month);
            const completedOrders = orderData.map(item => item.completed_orders);
            const cancelledOrders = orderData.map(item => item.cancelled_orders);

            // Create the chart
            const ctx = document.getElementById('orderComparisonChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Bar chart
                data: {
                    labels: months, // Months as labels
                    datasets: [{
                            label: 'Completed Orders',
                            data: completedOrders, // Completed orders data
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Cancelled Orders',
                            data: cancelledOrders, // Cancelled orders data
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: false,
                            text: 'Monthly Order Comparison'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Render the chart on page load
        renderOrderComparisonChart();
    </script>

    <!-- Sales Trends -->
    <script>
        // Function to fetch sales trends data
        async function fetchSalesTrendsData() {
            const response = await fetch('model/fetch_sales_trends.php');
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderSalesTrendsChart() {
            const salesData = await fetchSalesTrendsData();

            // Extract the date and total sales from the data
            const dates = salesData.map(item => item.date);
            const sales = salesData.map(item => item.total_sales);

            // Create the chart
            const ctx = document.getElementById('salesTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', // Line chart
                data: {
                    labels: dates, // Date as labels (X-axis)
                    datasets: [{
                        label: 'Sales Trends',
                        data: sales, // Total sales (Y-axis)
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue background
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue border
                        borderWidth: 2,
                        fill: true, // Fill under the line
                        tension: 0.4 // Make the line smooth
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `R${tooltipItem.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Sales'
                            }
                        }
                    }
                }
            });
        }

        // Call the function to render the chart
        renderSalesTrendsChart();
    </script>

    <!-- Order Frequency -->
    <script>
        let orderChart;

        // Function to fetch order frequency data
        async function fetchOrderFrequencyData(filter) {
            const response = await fetch(`model/fetch_order_frequency.php?filter=${filter}`);
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderOrderFrequencyChart(filter) {
            const orderData = await fetchOrderFrequencyData(filter);

            // Extract labels (dates) and order frequency
            const dates = orderData.map(item => item.date);
            const orders = orderData.map(item => item.order_count);

            const ctx = document.getElementById('orderFrequencyChart').getContext('2d');

            // If the chart already exists, destroy it to render a new one
            if (orderChart) {
                orderChart.destroy();
            }

            orderChart = new Chart(ctx, {
                type: 'bar', // Bar chart to display frequency
                data: {
                    labels: dates, // Dates as labels
                    datasets: [{
                        label: 'Order Frequency',
                        data: orders, // Order count as data
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Orders'
                            }
                        }
                    }
                }
            });
        }

        // Event listener for tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remove 'active' class from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));

                // Add 'active' class to the clicked button
                this.classList.add('active');

                // Fetch and render the chart based on selected filter
                const filter = this.getAttribute('data-filter');
                renderOrderFrequencyChart(filter);
            });
        });

        // Initial load with "Today" data
        renderOrderFrequencyChart('today');
    </script>

    <!-- Revenue by Payment Method payfast vs cash -->
    <script>
        // Function to fetch revenue data for payment methods
        async function fetchPaymentMethodRevenueData() {
            const response = await fetch('model/fetch_popular_products.php');
            const data = await response.json();
            return data;
        }

        // Function to render the chart
        async function renderPaymentMethodChart() {
            const paymentMethodData = await fetchPaymentMethodRevenueData();

            // Extract payment methods and revenue from the data
            const paymentMethods = paymentMethodData.map(item => item.payment_method);
            const revenues = paymentMethodData.map(item => parseFloat(item.revenue));

            // Calculate total revenue
            const totalRevenue = revenues.reduce((acc, curr) => acc + curr, 0);

            // Use two colors for the payment methods
            const colors = ['rgba(54, 255, 235, 0.6)', 'rgba(245, 231, 132, 0.6)'];
            const borderColors = ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'];

            // Create the donut chart
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', // Donut pie chart
                data: {
                    labels: paymentMethods, // Payment methods as labels
                    datasets: [{
                        data: revenues, // Revenue as data
                        backgroundColor: colors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        title: {
                            display: false,
                            text: 'Revenue by Payment Method'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const revenue = revenues[tooltipItem.dataIndex];
                                    const percentage = ((revenue / totalRevenue) * 100).toFixed(2);
                                    return `${paymentMethods[tooltipItem.dataIndex]}: R${revenue.toFixed(2)} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, context) => {
                                const total = context.chart._metasets[0].total;
                                const percentage = ((value / total) * 100).toFixed(2) + '%';
                                return percentage;
                            },
                            display: true,
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            });
        }

        // Render the chart on page load
        renderPaymentMethodChart();
    </script>
</body>

</html>