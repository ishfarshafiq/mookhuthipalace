<?php
if(isset($_SESSION["userID"]))
{
	$datetimes = date("Y-m-d h:i:s");
	$userID = $_SESSION["userID"];
	$name="";$email="";$role="";$phone="";$address="";$password="";
	$result=mysqli_query($conn,"select * from user_account where role = 'admin' limit 1");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$name = $row['name'];
		$email = $row['email'];
		$role = $row['role'];
		$phone = $row['phone'];
		$address = $row['address'];
		$password = $row['password'];
		
		$result_new_this_month=mysqli_query($conn,"SELECT COUNT(DISTINCT a.userID) as new_this_month FROM payment_transaction a INNER JOIN user_account b ON a.userID = b.userID WHERE a.payment_status = 'Paid' and MONTH(b.created_datetime) = MONTH(CURRENT_DATE()) AND  YEAR(b.created_datetime) = YEAR(CURRENT_DATE());");
		$row_new_this_month = mysqli_fetch_assoc($result_new_this_month);
		$new_this_month = $row_new_this_month['new_this_month'];
		
		$result_ttl_cust=mysqli_query($conn,"SELECT COUNT(DISTINCT a.userID) as ttl_customer FROM payment_transaction a INNER JOIN user_account b ON a.userID = b.userID where a.payment_status = 'Paid'");
		$row_ttl_cust = mysqli_fetch_assoc($result_ttl_cust);
		$ttl_customer = $row_ttl_cust['ttl_customer'];
		
		//Total Sales Of The Month
		$result_sales=mysqli_query($conn,"SELECT sum(billamount) as total_sales FROM payment_transaction WHERE payment_status in ('Paid') and MONTH(payment_date) = MONTH(CURRENT_DATE()) AND  YEAR(payment_date) = YEAR(CURRENT_DATE());");
		$row_ttl_sales = mysqli_fetch_assoc($result_sales);
		$total_sales = number_format((float)$row_ttl_sales['total_sales'], 2, '.', '');
		
		//Total Orders Of The Month
		$result_total_orders=mysqli_query($conn,"select count(ordercode) as total_orders from payment_transaction WHERE payment_status in ('Paid', 'Pending') and MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE());");
		$row_total_orders = mysqli_fetch_assoc($result_total_orders);
		$total_orders = $row_total_orders['total_orders'];
		
		//New Customer This Month
		$result_total_cust=mysqli_query($conn,"select COUNT(*) AS total_cust from user_account where role = 'customer' and MONTH(created_datetime) = MONTH(CURRENT_DATE()) AND YEAR(created_datetime) = YEAR(CURRENT_DATE());");
		$row_total_cust = mysqli_fetch_assoc($result_total_cust);
		$total_cust = $row_total_cust['total_cust'];
		
		//Total Product In Stock
		$result_total_product=mysqli_query($conn,"SELECT count(*) as total_product FROM product where status = 'ACTIVE';");
		$row_total_product = mysqli_fetch_assoc($result_total_product);
		$total_product = $row_total_product['total_product'];
		 
	}
}
else
{
	header("Location: logout.php");
	exit();
}

?>