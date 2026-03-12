<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_POST['ordercode']))
{
	$ordercode = $_POST['ordercode'];
	
	$sql="SELECT a.ordercode, c.delivery_method, c.billing_addressID, c.shipping_addressID, c.payment_method, c.collecting_point, a.payment_date, a.is_delivered, d.product_name, b.style, b.color, b.quantity, b.price,
	 ba.first_name, ba.last_name, ba.email, ba.phone, ba.address, ba.city, ba.state, ba.postcode, ba.country, sa.address_line1 as sa_address_line1, sa.address_line2 as sa_address_line2, sa.postcode as sa_postcode, sa.city as sa_city, sa.state as sa_state
									FROM payment_transaction a
									INNER JOIN orders b ON a.ordercode = b.order_code
                                    INNER JOIN checkout c ON b.order_code = c.order_code
                                    INNER JOIN product d ON b.productID = d.productID
									LEFT JOIN billing_address ba ON c.billing_addressID = ba.billing_addressID
									LEFT JOIN shipping_address sa ON c.shipping_addressID = sa.shipping_addressID
									WHERE a.ordercode = '$ordercode'";
	
	$result = mysqli_query($conn, $sql);
	
	  $items = [];
	  $subtotal = 0;
	  
	    $billing_address = null;
		$delivery_method = null;
		$payment_method = null;
		$collecting_point = null;
		$payment_date = null;
		$is_delivered = null;
	   while($row = mysqli_fetch_assoc($result)) {
		   
		   
		    if($delivery_method === null){
				$delivery_method = $row['delivery_method'];
				$payment_method = $row['payment_method'];
				$collecting_point = $row['collecting_point'];
				$payment_date = $row['payment_date'];
				$is_delivered = $row['is_delivered'];
			}
		   
		    // Billing address only if standard delivery
			$delivery_fee = 0;
			if($delivery_method == "standard")
			{
				$delivery_fee = 8;
				if($row['shipping_addressID'] > 0)
				{
					
					$shipping_address = $row['sa_address_line1'].",".$row['sa_address_line2'].", <br>".$row['sa_postcode']." ".$row['sa_city'].", <br>".$row['sa_state'].", <br>Malaysia";
					
					$billing_address = [
							"address"    => $shipping_address
						];
					
					
				}
				else
				{
					$billing_address = [
							"address"    => $row['address'].",  <br>".$row['postcode']." ".$row['city'].",  <br>".$row['state'].",  <br> Malaysia"
						];
					
				}
				
				
			}
			else
			{
				//selfcollect
				$billing_address = [
					"address"    => $collecting_point
				];
				
			}
		   
		   
			$items[] = [
				"product_name" => $row['product_name'],
				"style" => $row['style'],
				"color" => $row['color'],
				"quantity" => $row['quantity'],
				"price" => $row['price']
			];
			
			$subtotal += $row['quantity'] * $row['price'];
		}
	  
	  
	
	  echo json_encode([
        "status" => "success",
        "ordercode" => $ordercode,
        "delivery_method" => ($delivery_method == "standard") ? "Delivery" : "Self Collect",
		"delivery_fee" => $delivery_fee,
        "payment_method" => $payment_method,
        "payment_date" => $payment_date,
        "is_delivered" => $is_delivered,
        "billing_address" => $billing_address,
        "items" => $items,
        "subtotal" => number_format($subtotal,2,'.',''),
        "total" => number_format($subtotal,2,'.','')
    ]);
    exit;
	
}
?>