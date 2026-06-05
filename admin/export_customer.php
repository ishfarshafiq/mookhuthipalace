<?php
session_start();
include("dbconnect.php");

if (isset($_GET['export']) && $_GET['export'] == "1") {

	if (ob_get_length()) {
		ob_end_clean();
	}
	
	$limit = $_GET['limit'];
	$offset = $_GET['offset'];
	$search = $_GET['search'];

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="customer_orders_export.csv"');
	header('Pragma: no-cache');
	header('Expires: 0');
   
    $output = fopen("php://output", "w");
	
    // CSV Column Headers
    fputcsv($output, [
        'Customer',
        'Email',
        'Phone',
        'Total Orders',
        'Total Spent',
        'Last Order'
    ]);

	$exportSql = "SELECT userID, name, profile, email, phone, SUM(total_spent) AS total_spent_by_customer, MAX(payment_date) AS last_order, COUNT(ordercode) AS total_orders
									FROM (
										SELECT 
											a.ordercode, 
											ua.userID,
											ua.name,
											ua.profile,
											ua.email,
											ua.phone,
											a.payment_date,
											SUM(b.quantity * b.price) + 
											(CASE 
												WHEN LOWER(c.delivery_method) = 'standard' THEN 8
												WHEN LOWER(c.delivery_method) = 'foreign' THEN 18
												ELSE 0
											END) AS total_spent
										FROM payment_transaction a
										INNER JOIN orders b ON a.ordercode = b.order_code
										INNER JOIN checkout c ON b.order_code = c.order_code
										INNER JOIN user_account ua ON a.userID = ua.userID
										WHERE a.payment_status = 'Paid'";
										if (!empty($search)) {
											$search_safe = mysqli_real_escape_string($conn, $search);
											 $exportSql .= " AND (
															ua.name LIKE '%$search_safe%' 
															OR ua.email LIKE '%$search_safe%' 
															OR ua.phone LIKE '%$search_safe%'
														  )";
										}
										
										$exportSql .= "GROUP BY 
															a.ordercode,
															ua.userID,
															ua.name,
															ua.profile,
															ua.email,
															ua.phone,
															a.payment_date,
															c.delivery_method
														) x
														GROUP BY
															userID,												
															name,
															profile,
															email,
															phone
														ORDER BY total_spent_by_customer DESC
														LIMIT $limit OFFSET $offset ";
	
	$exportResult = mysqli_query($conn, $exportSql);

    while ($row = mysqli_fetch_assoc($exportResult)) {

        fputcsv($output, [
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['total_orders'],
            number_format($row['total_spent_by_customer'], 2),
			$row['last_order']
        ]);
    }

    fclose($output);
    exit();
}
?>