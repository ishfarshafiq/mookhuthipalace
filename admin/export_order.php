<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if (isset($_GET['orderStatusFilter'],$_GET['paymentStatusFilter'],$_GET['fromDate'],$_GET['toDate'])) {
	
	$filter = "WHERE 1=1";

   
	if (!empty($_GET['fromDate']) && !empty($_GET['toDate'])) {
		
		 $timestamp_fromDate = strtotime($_GET['fromDate']);
		 $timestamp_toDate = strtotime($_GET['toDate']);

        if ($timestamp_fromDate !== false && $timestamp_toDate !== false) {
            $fromDate = date('Y-m-d', $timestamp_fromDate);
			$toDate = date('Y-m-d', $timestamp_toDate);

            
            $filter .= " AND a.payment_date >= '$fromDate 00:00:00' 
						 AND a.payment_date <= '$toDate 23:59:59'";
        }


    } else {
       
        $filter .= " AND MONTH(a.payment_date) = MONTH(CURRENT_DATE()) 
                     AND YEAR(a.payment_date) = YEAR(CURRENT_DATE())";
    }

    // Order Status
    if (!empty($_GET['orderStatusFilter'])) {
        $orderStatus = mysqli_real_escape_string($conn, $_GET['orderStatusFilter']);
        $filter .= " AND a.is_delivered = '$orderStatus'";
    }

    // Payment Status
    if (!empty($_GET['paymentStatusFilter'])) {
        $paymentStatus = mysqli_real_escape_string($conn, $_GET['paymentStatusFilter']);
        $filter .= " AND a.payment_status = '$paymentStatus'";
    }
	
	
	header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="orders_export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen("php://output", "w");
 
    fputcsv($output, [
        'Order Code',
        'Customer',
        'Order Date',
        'Status',
        'Payment Status',
        'Amount',
        'Delivery Method'
    ]);
	
	
	  
	$exportSql= "SELECT 
						a.ordercode,
						ua.name,
						c.delivery_method,
						c.payment_method,
						a.payment_status,
						c.collecting_point,
						a.payment_date,
						a.is_delivered,
						SUM(b.quantity * b.price) AS subtotal
					FROM payment_transaction a
					INNER JOIN orders b ON a.ordercode = b.order_code
					INNER JOIN checkout c ON b.order_code = c.order_code
					INNER JOIN user_account ua ON a.userID = ua.userID
					$filter
					GROUP BY 
						a.ordercode,
						ua.name,
						c.delivery_method,
						c.payment_method,
						a.payment_status,
						c.collecting_point,
						a.payment_date,
						a.is_delivered
					ORDER BY MAX(b.ordersID) DESC;";


    $exportResult = mysqli_query($conn, $exportSql);

    while ($row = mysqli_fetch_assoc($exportResult)) {
		
		$delivery_fee = 0;
		
		$delivery_method_desc = [
								"selfCollect" => "Self Collect",
								"standard" => "Standard",
								"foreign" => "Singapore"
							];
						
		$fees = [
			"standard" => 8,
			"foreign" => 18
		];
		
		$delivery_fee = $fees[$row['delivery_method']] ?? 0;

        fputcsv($output, [
            $row['ordercode'],
            $row['name'],
            $row['payment_date'],
            $row['is_delivered'],
            $row['payment_status'],
            number_format($row['subtotal'] + $delivery_fee, 2),
			$delivery_method_desc[$row['delivery_method']]
        ]);
    }

    fclose($output);
    exit();
}
?>