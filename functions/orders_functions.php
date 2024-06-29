<?php

// Function to group orders by date
function groupOrdersByDate($orders) {
    $groupedOrders = [];
    foreach ($orders as $order) {
        $date = $order['order_date'];
        // Format date to include day of the week
        $day = date('l', strtotime($date)); // Get full textual representation of the day
        $formattedDate = $day . ', ' . date('F j, Y', strtotime($date));
        
        if (!isset($groupedOrders[$formattedDate])) {
            $groupedOrders[$formattedDate] = [];
        }
        $groupedOrders[$formattedDate][] = $order;
    }
    return $groupedOrders;
}

// Function to get status badge class based on order status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'completed':
            return 'bg-success text-white';
        case 'cancelled':
            return 'bg-danger text-white';
        default:
            return 'bg-secondary text-white';
    }
}

?>
