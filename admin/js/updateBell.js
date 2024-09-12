/*
document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById("bell");
    const badge = document.getElementById("badge");

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

    // Initial update
    updateBell();

    // Update the bell icon count every 2 seconds
    setInterval(updateBell, 2000);
});
*/