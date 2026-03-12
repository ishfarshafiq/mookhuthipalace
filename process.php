<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_GET['userID'], $_GET['order_code'])) {
	$_SESSION['userID'] = $_GET['userID'];
	
	$userID = $_GET['userID'];
	$order_code = $_GET['order_code'];
	$oder_sum_subtotal=0;
	$delivery_fee=0;
	
	$result_delivery_method = mysqli_query($conn,"SELECT delivery_method FROM checkout where order_code = '$order_code' limit 1");
	$row_delivery_method = mysqli_fetch_assoc($result_delivery_method);
	if($row_delivery_method['delivery_method'] == "standard")
	{
		$delivery_fee = 8;
	}
	
	$get_payment_method = "SELECT payment_method FROM checkout where order_code = '$order_code' limit 1";
	$result_payment_method = mysqli_query($conn,$get_payment_method);
	$row = mysqli_fetch_assoc($result_payment_method);
	if($row['payment_method'] == "BANK")
	{
		$sql = "SELECT a.price, a.quantity FROM orders a 
				inner join product b on a.productID = b.productID
				inner join checkout c on a.checkoutID = c.checkoutID
				where c.order_code = '$order_code' and b.status in ('ACTIVE','LOW STOCK');";
		$result = mysqli_query($conn,$sql);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$oder_sum_subtotal +=  number_format((float)$row['quantity'] * $row['price'], 2, '.', '');
			}
				
		}
		
		if($oder_sum_subtotal > 0)
		{
			//fpx
			$invoiceID = $order_code;
			$filterbillamount = number_format((float)$oder_sum_subtotal + $delivery_fee, 2,'.', '');
			$grandTotal = $filterbillamount;
			echo "<script>location.href='request.php?userID=$userID&invoiceID=$invoiceID&gt=$grandTotal'</script>";
		}
		
	}
	else if($row['payment_method'] == "CARD")
	{
		echo "<script>alert('Card payment is no more longer active');location.href='login.php'</script>";
	}
	
		
}
?>