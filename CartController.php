<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");


if(isset($_POST['userID'],$_POST['customer_code'], $_POST['productID'], $_POST['style'], $_POST['color'], $_POST['qty'])){
	
	$userID = !empty($_POST['userID']) ? $_POST['userID'] : "";
	$customer_code = $_POST['customer_code'];
	$productID = $_POST['productID'];
	$style = !empty($_POST['style']) ? $_POST['style'] : "";
	$color = !empty($_POST['color']) ? $_POST['color'] : "";
	$qty_uid = $_POST['qty'];
	$is_error = 0;
	$status="";
	
	//date-time
     $date_time=(new \DateTime())->format('Y-m-d H:i:s'); 
	 
	 
	 if($customer_code == "")
	 { 
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';	
		$random=generate_string($permitted_chars, 8);
		$customer_code="CUST".$random;
		
		$sql="insert into customer(customer_code, created_datetime) values ('$customer_code', '$date_time')";
		if(mysqli_query($conn,$sql))
		{
			$_SESSION['customer_code'] = $customer_code;	
		}
		
	 }
	 else
	 {
		 
		$query=mysqli_query($conn,"Select * from customer where customer_code = '$customer_code'");
		if(mysqli_num_rows($query) > 0)
		{
			$_SESSION['customer_code'] = $customer_code;
		}
		else
		{
			
			$sql="insert into customer(customer_code, created_datetime) values ('$customer_code', '$date_time')";
			if(mysqli_query($conn,$sql))
			{
				$_SESSION['customer_code'] = $customer_code;	
			}
			
		}
		 
		 
	 }
	 
	 
	$query=mysqli_query($conn,"Select * from cart where productID = $productID and style = '$style' and color = '$color' and customer_code = '$customer_code'");
	 if(mysqli_num_rows($query) > 0){
		
			 $result1=mysqli_query($conn,"select * from cart where productID = $productID and style = '$style' and color = '$color' and customer_code = '$customer_code'");
				while($row1=mysqli_fetch_array($result1))
				{
					if($qty_uid>=1){
						
						$qty_uid=$qty_uid+$row1['quantity'];
					}else{
						$qty_uid=$row1['quantity']+1;
					}
					
				} 
				
				$sql="UPDATE cart SET quantity = $qty_uid, updated_datetime = '$date_time' where productID = $productID and style = '$style' and color = '$color' and customer_code = '$customer_code'";
				if(mysqli_query($conn,$sql))
				{
					$status = "success";
					$msg="Added to cart";
				}
		
	} else{
		
		//get price
		$get_price=mysqli_query($conn,"select original_price, price from product where productID = $productID");
		$row=mysqli_fetch_array($get_price);
		
		$original_price = $row['original_price'];
		$price = $row['price'];
		
		if($price != "")
		{
			$prices = $row['price'];	
		}
		else
		{
			$prices = $row['original_price'];
		}
	
		
		if($userID!="")
		{
			$sql="INSERT into cart(productID, customer_code, userID, style, color, quantity, price, created_datetime) VALUES ($productID, '$customer_code', $userID, '$style', '$color', $qty_uid, '$prices','$date_time')";
		}
		else
		{
			$sql="INSERT into cart(productID, customer_code, style, color, quantity, price, created_datetime) VALUES ($productID, '$customer_code', '$style', '$color', $qty_uid, '$prices','$date_time')";
		}
		
		if (mysqli_query($conn, $sql)) {
				$status = "success";
				$msg = "Added to cart";
			} else {
				$status = "error";
				$msg = "Error when save into cart";
			}
			
	}
	
	echo json_encode(["status" => $status, "msg" => $msg, "customer_code" => $customer_code]);
	
}

function generate_string($input, $strength = 16) {
				$input_length = strlen($input);
				$random_string = '';
			for($i = 0; $i < $strength; $i++) {
				$random_character = $input[mt_rand(0, $input_length - 1)];
				$random_string .= $random_character;
				}
			return $random_string;
			}

if(isset($_POST['number_of_products'], $_POST['userID'], $_POST['customer_code']))
{
	$counts_products_incart = 0;
	$customer_code = $_POST['customer_code'];
	$userID = $_POST['userID'];
	$result=mysqli_query($conn,"select count(*) number_of_products_incart from cart where customer_code = '$customer_code' or userID = '$userID'");
	if(mysqli_num_rows($result) > 0)
	{
		$row=mysqli_fetch_array($result);
		$counts_products_incart = $row['number_of_products_incart'];
	}
	
	echo json_encode(["status" => "success", "counts_products_incart" => $counts_products_incart]);
	
}

if(isset($_POST['delete_product_cart'], $_POST['cartID']))
{
	$cartID = $_POST['cartID'];
	if(mysqli_query($conn,"delete from cart where cartID = $cartID"))
	{
		echo json_encode(["status" => "success"]);
	}
	
}

if(isset($_POST['cartID'], $_POST['newQty']))
{
	$cartID = $_POST['cartID'];
	$newQty = $_POST['newQty'];
	if(mysqli_query($conn,"update cart set quantity = $newQty where cartID = $cartID"))
	{
		echo json_encode(["status" => "success"]);
	}
	
}


?>