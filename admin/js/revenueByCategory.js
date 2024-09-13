document.addEventListener('DOMContentLoaded', () => {
    // Function to fetch and render each chart
    async function fetchAndRenderCharts() {
        await renderRevenueByCategoryChart();
        await renderCheckoutComparisonChart(); // Ensure all charts are rendered
    }

    // Displays revenue by category
    async function renderRevenueByCategoryChart() {
        const data = await fetch('model/revenueByCategory.php').then(response => response.json());
        const ctx = document.getElementById('revenueByCategoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.category),
                datasets: [{
                    data: data.map(item => item.revenue),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const dataset = tooltipItem.dataset;
                                const total = dataset.data.reduce((sum, value) => sum + value, 0);
                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                return `${dataset.label}: $${currentValue.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                }
            }
        });
    }

    // Displays checkout comparison chart
    async function renderCheckoutComparisonChart() {
        const data = await fetch('model/fetch_checkout_comparison.php').then(response => response.json());
        
        // Extract categories and revenue from the data
        const categories = data.map(item => item.category); // Fixed variable name
        const revenues = data.map(item => item.revenue);

        // Create the chart
        const ctx = document.getElementById('checkoutComparisonChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut', // Doughnut chart
            data: {
                labels: categories, // Categories as labels
                datasets: [{
                    label: 'Revenue by Category',
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
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: R${tooltipItem.raw.toLocaleString()}`;
                            }
                        }
                    }
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                elements: {
                    arc: {
                        borderWidth: 1
                    }
                }
            }
        });
    }

    fetchAndRenderCharts();
});
