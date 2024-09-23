document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById("bell");
    const badge = document.getElementById("badge");
    const notificationContainer = document.getElementById("notification-container");

    function updateBell() {
        fetch("model/check_stock.php")
            .then(response => response.json())
            .then(data => {
                const lowStockItems = data.filter(product => product.quantity < 10);
                const lowStockCount = lowStockItems.length;
                
                badge.textContent = lowStockCount;
                if (lowStockCount > 0) {
                    bell.classList.add("active");
                } else {
                    bell.classList.remove("active");
                }
            })
            .catch(error => {
                console.error("Error fetching stock data:", error);
            });
    }

    // Function to show notification
    function showNotification(message) {
        notificationContainer.textContent = message;
        notificationContainer.style.display = 'block';  // Show notification

        // Automatically hide notification after 3 seconds
        setTimeout(() => {
            notificationContainer.style.display = 'none';
        }, 3000); // 3 seconds
    }

    // Handle bell click
    bell.addEventListener('click', () => {
        const lowStockMessage = "Stock levels are low!";
        if (parseInt(badge.textContent) > 0) {
            showNotification(lowStockMessage); // Show notification instead of alert
        }
    });

    // Initial update
    updateBell();

    // Update the bell icon count every 2 seconds
    setInterval(updateBell, 2000);
});
