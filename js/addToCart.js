function addToCart(foodItemId, foodName) {
    var quantity = parseInt($('#foodModal' + foodItemId).find('.quantity-input').val());
    var price = parseFloat($('#foodModal' + foodItemId).find('.food-price').text().replace(/[^\d.-]/g, '')); 
    var name = $("#foodModal" + foodItemId).find('.modal-title').text();

    // Calculate total price
    var totalPrice = price * quantity;

    // Add item to cart
    $.ajax({
        url: 'admin/model/temp_cart.php', 
        method: 'POST',
        data: {
            foodItemId: foodItemId,
            quantity: quantity,
            price: totalPrice,
            name: name
        },
        success: function(response) {
            alert(response); // Show success or error message
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); // Log any errors to the console
        }
    });
}

function changeQuantity(element, change) {
    var input = $(element).siblings('.quantity-input');
    var currentValue = parseInt(input.val());
    var newValue = currentValue + change;

    if (newValue < 1) {
        newValue = 1;
    }

    input.val(newValue);
}