<?php

session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$shipping_addressID=0;$billing_addressID=0;$bank_card_detailsID=0;
	$userID   = $_POST['userID'] ?? '';
	$customer_code   = $_POST['customer_code'] ?? '';
	$delivery_method   = $_POST['delivery_method'] ?? '';
    $recommendedBranch = $_POST['branch'] ?? '';
	$payment_method = $_POST['payment_method'] ?? '';
	$isShippingDifferent = isset($_POST['isShippingDifferent']);
	$collect_branch = "NONE";
	
	//default
	$billing_first_name = $_POST['billing_first_name'] ?? '';
	$billing_last_name = $_POST['billing_last_name'] ?? '';
	$billing_email = $_POST['billing_email'] ?? '';
	$billing_phone = $_POST['billing_phone'] ?? '';
	$billing_address = $_POST['billing_address'] ?? '';
	$billing_city = $_POST['billing_city'] ?? '';
	$billing_state = $_POST['billing_state'] ?? '';
	$billing_postcode = $_POST['billing_postcode'] ?? '';
	$billing_country = $_POST['billing_country'] ?? '';
	
	if($delivery_method == "selfCollect")
	{
		$collect_branch    = $_POST['collectBranch'] ?? '';
	}
	
	if($delivery_method == "standard" || $delivery_method == "foreign")
	{
		if($isShippingDifferent)
		{
			$shipping_name = $_POST['shipping_name'] ?? '';
			$shipping_email = $_POST['shipping_email'] ?? '';
			$shipping_phone = $_POST['shipping_phone'] ?? '';
			$shipping_address_line1 = $_POST['shipping_address_line1'] ?? '';
			$shipping_address_line2 = $_POST['shipping_address_line2'] ?? '';
			$shipping_city = $_POST['shipping_city'] ?? '';
			$shipping_state = $_POST['shipping_state'] ?? '';
			$shipping_postcode = $_POST['shipping_postcode'] ?? '';
			$shipping_country = $_POST['shipping_country'] ?? '';
			
			if($shipping_email != "" && $shipping_phone != "")
			{
				
				$check_shipping_address = mysqli_query($conn, "select * from shipping_address where email = '$shipping_email' and phone = '$shipping_phone'");
				if(mysqli_num_rows($check_shipping_address) > 0)
				{
						$row=mysqli_fetch_array($check_shipping_address);
						$shipping_addressID = $row['shipping_addressID'];
				}
				else
				{
					//if($shipping_address_line1 != "" && $shipping_city != "")
					//{
						
						$insert_shipping="insert into shipping_address(customer_code, name, email, phone, address_line1, address_line2, city, state, postcode, country) values 
							('$customer_code', '$shipping_name', '$shipping_email', '$shipping_phone', '$shipping_address_line1', '$shipping_address_line2', '$shipping_city', '$shipping_state', '$shipping_postcode', '$shipping_country')";
						if(mysqli_query($conn,$insert_shipping)){
							$shipping_addressID = mysqli_insert_id($conn);
						}
						
					//}
						
				}
				
			}
				
			
		}
		
	}
	
	
	if($payment_method == "card")
	{
		$card_holder_name = $_POST['card_holder_name'] ?? '';
		$card_number = $_POST['card_number'] ?? '';
		$expiry_date = $_POST['expiry_date'] ?? '';
		$cvv = $_POST['cvv'] ?? '';
		
			$insert_shipping="insert into bank_card_details(customer_code, card_holder_name, card_number, expiry_date, cvv) values 
							('$customer_code', '$card_holder_name', '$card_number', '$expiry_date', $cvv)";
			if(mysqli_query($conn,$insert_shipping)){
				$bank_card_detailsID = mysqli_insert_id($conn);
			}
		
	}
	
	
	$check_billing_address= mysqli_query($conn,"Select * from billing_address where email = '$billing_email'");
	if(mysqli_num_rows($check_billing_address) > 0)
	{
		$row=mysqli_fetch_array($check_billing_address);
		$billing_addressID = $row['billing_addressID'];
		
		$update_billing="update billing_address set first_name = '$billing_first_name', last_name = '$billing_last_name', phone = '$billing_phone', address = '$billing_address', city = '$billing_city', state = '$billing_state',  postcode = '$billing_postcode'
						where billing_addressID = $billing_addressID";
		mysqli_query($conn,$update_billing);
		
	}
	else
	{
		$insert_billing="insert into billing_address(customer_code, first_name, last_name, email, phone, address, city, state, postcode, country) values 
			('$customer_code', '$billing_first_name', '$billing_last_name', '$billing_email', '$billing_phone', '$billing_address', '$billing_city', '$billing_state', '$billing_postcode', '$billing_country')";
		if(mysqli_query($conn,$insert_billing)){
			$billing_addressID  = mysqli_insert_id($conn);
		}
	}

	$date_time=(new \DateTime())->format('Y-m-d H:i:s'); 


	$order_code = "ODR" . strtoupper(bin2hex(random_bytes(3)));
	
	// Check if customer already has an order today with same cart
	// $check = mysqli_query($conn, "SELECT checkoutID, order_code FROM checkout WHERE customer_code = '$customer_code' ORDER BY checkoutID DESC LIMIT 1");

	// if(mysqli_num_rows($check) > 0){
		// $row = mysqli_fetch_assoc($check);

		// echo json_encode([
			// "status" => "exists",
			// "order_code" => $row['order_code'],
			// "msg" => "Order already processed"
		// ]);
		// exit;
	// }
	

	$insert="insert into checkout(customer_code, delivery_method, order_code, collecting_point, billing_addressID, shipping_addressID, bank_card_detailsID, payment_method, recommendedBranch, created_datetime) values 
			('$customer_code', '$delivery_method', '$order_code', '$collect_branch', $billing_addressID, $shipping_addressID, $bank_card_detailsID, '$payment_method', '$recommendedBranch', '$date_time')";
	if(mysqli_query($conn,$insert))
	{
		$checkoutID  = mysqli_insert_id($conn);
		
		//insert in orders then clear the cart
		$insert_orders = "
					INSERT INTO orders(order_code, checkoutID, productID, customer_code, style, color, quantity, price, created_datetime)
					SELECT '$order_code', '$checkoutID', productID, customer_code, style, color, quantity, price, '$date_time'
					FROM cart
					WHERE customer_code = '$customer_code' OR userID = '$userID'
					";
		if(mysqli_query($conn,$insert_orders))
		{
			mysqli_query($conn,"delete from cart where customer_code = '$customer_code' or userID = '$userID'");
			
		}

		echo json_encode(["status" => "success", "customer_code" => $customer_code, "order_code" => $order_code, "billing_addressID" => $billing_addressID, "shipping_addressID" => $shipping_addressID]);
	}


}

?>