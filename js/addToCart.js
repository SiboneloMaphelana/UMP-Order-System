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
        dataType: 'json', // Expect JSON response
        success: function(response) {
            if (response.success) {
                // Show Bootstrap toast at the top center of the modal
                var toast = `
                    <div class="toast align-items-center text-white bg-success position-absolute top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                Item added to cart successfully.
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                $(toast).appendTo('#foodModal' + foodItemId + ' .modal-dialog').toast('show');

                // Optional: You can also remove the toast after a delay
                setTimeout(function() {
                    $('.toast').toast('hide');
                }, 5000); // Hide toast after 5 seconds (5000 milliseconds)
            } else {
                // Handle error
                console.error('Failed to add item to cart:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error adding item to cart:', xhr.responseText);
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

