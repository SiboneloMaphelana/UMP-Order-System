function toggleDetails(element) {
    var details = element.closest('.card').querySelector('.details');
    if (details.style.display === 'none' || details.style.display === '') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}

function changeQuantity(element, delta) {
    var input = element.parentElement.querySelector('.quantity-input');
    var currentValue = parseInt(input.value);
    var newValue = currentValue + delta;
    if (newValue < 1) newValue = 1;
    input.value = newValue;
}

function addToCart(itemId) {
    var quantity = document.querySelector(`.card-body.details .quantity-input`).value;
    console.log('Add to Cart:', itemId, 'Quantity:', quantity);
    // Add your AJAX call here to handle the cart addition
}
