<?php

/**
 * Groups an array of orders by their order date, formatted with the day of the week and the full month and day.
 * The resulting array is sorted in descending order based on the actual date values.
 *
 * @param array $orders An array of orders, each containing an 'order_date' key.
 * @return array An array of orders grouped by date, with each group containing an array of orders.
 */
function groupOrdersByDate($orders)
{
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

    // Sort dates in descending order based on actual date values
    uksort($groupedOrders, function ($a, $b) {
        $dateA = strtotime(explode(',', $a)[1]);
        $dateB = strtotime(explode(',', $b)[1]);
        return $dateB - $dateA;
    });

    return $groupedOrders;
}

/**
 * Returns the CSS class for a status badge based on the given status.
 *
 * @param string $status The status of the badge.
 * @return string The CSS class for the status badge.
 */
function getStatusBadgeClass($status)
{
    switch ($status) {
        case 'completed':
            return 'bg-success text-white';
        case 'cancelled':
            return 'bg-danger text-white';
        default:
            return 'bg-secondary text-white';
    }
}
