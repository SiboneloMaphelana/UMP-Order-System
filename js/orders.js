$(document).ready(function() {
    // Function to show modal for order cancellation confirmation
    $('.cancel-order-btn').click(function(e) {
        e.preventDefault();
        var orderId = $(this).data('order-id');
        $('#cancelOrderModal').modal('show');

        $('#confirmCancelOrderBtn').click(function() {
            $('#cancelOrderModal').modal('hide');

            // AJAX request to cancel order
            $.ajax({
                url: 'admin/model/cancel_order.php',
                method: 'POST',
                data: { orderId: orderId },
                success: function(response) {
                    showToast('Order ' + orderId + ' has been cancelled successfully.');
                    // Optionally, update UI to reflect cancelled order
                    // Example: Remove the cancelled order card from UI
                    $('.cancel-order-btn[data-order-id="' + orderId + '"]').closest('.card').fadeOut('slow');
                },
                error: function(xhr, status, error) {
                    showToast('Failed to cancel order ' + orderId + '. Please try again.');
                }
            });
        });
    });

    // Function to show toast message
    function showToast(message) {
        var toast = $('#toastMessage');
        toast.find('.toast-body').text(message);
        toast.toast({ delay: 3000 });
        toast.toast('show');
    }
    // Toggle buttons for orders view
    $('#upcomingOrdersBtn').on('click', function() {
        $('#upcomingOrders').show();
        $('#pastOrders').hide();
        $('#canceledOrders').hide();
        $(this).addClass('btn-primary').removeClass('btn-secondary');
        $('#pastOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
        $('#canceledOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
    });

    $('#pastOrdersBtn').on('click', function() {
        $('#upcomingOrders').hide();
        $('#pastOrders').show();
        $('#canceledOrders').hide();
        $(this).addClass('btn-primary').removeClass('btn-secondary');
        $('#upcomingOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
        $('#canceledOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
    });

    $('#canceledOrdersBtn').on('click', function() {
        $('#upcomingOrders').hide();
        $('#pastOrders').hide();
        $('#canceledOrders').show();
        $(this).addClass('btn-primary').removeClass('btn-secondary');
        $('#upcomingOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
        $('#pastOrdersBtn').addClass('btn-secondary').removeClass('btn-primary');
    });
});
