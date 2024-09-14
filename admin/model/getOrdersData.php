<?php

include_once("../../connection/connection.php");
include_once("Order.php");

$order = new Order($conn);

$orders = $order->getOrdersData();

echo $orders;
