// Set to keep track of currently displayed product IDs
/*
const displayedProducts = new Set();

setInterval(() => {
    fetch("model/check_stock.php")
        .then(response => response.json())
        .then(data => {
            const productsContainer = document.getElementById("products");
            const bell = document.getElementById("bell");
            const badge = document.getElementById("badge");
            
            const currentProductIds = new Set(data.map(product => product.id));
            let lowStockCount = 0; // Counter for low stock items

            // Handle new low stock products
            data.forEach(product => {
                if (!displayedProducts.has(product.id)) {
                    if (product.quantity < 10) {
                        // Create and display the new alert
                        const productElement = document.createElement("div");
                        productElement.className = "product-alert warning";
                        productElement.dataset.productId = product.id; // Set data attribute
                        productElement.innerHTML = `
                            <strong>${product.name}</strong>
                            <span>Quantity: ${product.quantity}</span>
                        `;
                        productsContainer.appendChild(productElement);
                        displayedProducts.add(product.id);
                        lowStockCount++; // Increment counter for low stock items
                    }
                } else {
                    // Update existing alerts
                    const productElement = productsContainer.querySelector(`.product-alert[data-product-id="${product.id}"]`);
                    if (productElement) {
                        if (product.quantity < 10) {
                            productElement.querySelector("span").textContent = `Quantity: ${product.quantity}`;
                            lowStockCount++; // Increment counter for low stock items
                        } else {
                            // Product no longer low in stock
                            productElement.remove();
                            displayedProducts.delete(product.id);
                        }
                    }
                }
            });

            // remove  products that are no longer low in stock
            displayedProducts.forEach(productId => {
                if (!currentProductIds.has(productId)) {
                    const productElement = productsContainer.querySelector(`.product-alert[data-product-id="${productId}"]`);
                    if (productElement) {
                        productElement.remove();
                    }
                    displayedProducts.delete(productId);
                }
            });

            // Update the badge with the count of low stock items
            badge.textContent = lowStockCount;
            if (lowStockCount > 0) {
                bell.classList.add("active");
            } else {
                bell.classList.remove("active");
            }
        })
        .catch(error => {
            console.error("Error fetching products:", error);
        });
}, 2000); // Check every 2 seconds
*/