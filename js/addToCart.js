function addToCart(foodItemId, foodName) {
  var quantity = parseInt(
    $("#foodModal" + foodItemId)
      .find(".quantity-input")
      .val()
  );
  var price = parseFloat(
    $("#foodModal" + foodItemId)
      .find(".food-price")
      .text()
      .replace(/[^\d.-]/g, "")
  );
  var name = $("#foodModal" + foodItemId)
    .find(".modal-title")
    .text();

  // Calculate total price
  var totalPrice = price * quantity;

  // Add item to cart
  $.ajax({
    url: "admin/model/temp_cart.php",
    method: "POST",
    data: {
      foodItemId: foodItemId,
      quantity: quantity,
      price: price,
      name: name,
    },
    dataType: "json", // Expect JSON response
    success: function (response) {
      if (response.success) {
        // Show SweetAlert and hide the modal after 2 seconds
        Swal.fire({
          icon: "success",
          title: "Item added to cart!",
          text: "Your item has been successfully added to the cart.",
          timer: 2000, // Alert will be shown for 2 seconds
          timerProgressBar: true,
          showConfirmButton: false,
          didClose: () => {
            $("#foodModal" + foodItemId).modal("hide");
          },
        });
      } else {
        // Handle error
        console.error("Failed to add item to cart:", response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error adding item to cart:", xhr.responseText);
    },
  });
}

function changeQuantity(element, change) {
  var input = $(element).siblings(".quantity-input");
  var currentValue = parseInt(input.val());
  var newValue = currentValue + change;

  if (newValue < 1) {
    newValue = 1;
  }

  input.val(newValue);
}
