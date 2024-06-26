/**
 * Toggles the visibility of the details section of a card.
 *
 * @param {HTMLElement} element - The element that triggered the toggle.
 */
function toggleDetails(element) {
    // Find the closest card element and its details section
    var details = element.closest('.card').querySelector('.details');

    // Toggle the visibility of the details section
    if (details.style.display === 'none' || details.style.display === '') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}

/**
 * Changes the quantity input value by a given delta.
 *
 * @param {HTMLElement} element - The element that triggered the change.
 * @param {number} delta - The amount to change the quantity by.
 */
function changeQuantity(element, delta) {
    // Find the quantity input element within the parent element
    var input = element.parentElement.querySelector('.quantity-input');

    // Get the current value of the quantity input
    var currentValue = parseInt(input.value);

    // Calculate the new value by adding the delta
    var newValue = currentValue + delta;

    // Ensure the new value is at least 1
    if (newValue < 1) newValue = 1;

    // Update the value of the quantity input
    input.value = newValue;
}

/**
 * Adds an item to the cart.
 *
 * @param {number} itemId - The ID of the item to add to the cart.
 */
function addToCart(itemId) {
    // Get the quantity input value within the details section of a card
    var quantity = document.querySelector(`.card-body.details .quantity-input`).value;

    // Log the details of the item being added to the cart
    console.log('Add to Cart:', itemId, 'Quantity:', quantity);

    // Add your AJAX call here to handle the cart addition
}

