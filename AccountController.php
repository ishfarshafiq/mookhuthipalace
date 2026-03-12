<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$date_time = (new \DateTime())->format('Y-m-d H:i:s'); 

if(isset($_POST['customer_code'], $_POST['order_code'], $_POST['billing_addressID'])){
	
	$customer_code = $_POST['customer_code'];
	$order_code = $_POST['order_code'];
	$billing_addressID = $_POST['billing_addressID'];
	
	$query=mysqli_query($conn,"Select * from billing_address where billing_addressID = '$billing_addressID' and email is not null");
	if(mysqli_num_rows($query) > 0)
	{
		$row=mysqli_fetch_array($query);
		$name = $row['first_name']." ".$row['last_name'];
		$email = $row['email'];
		$phone = $row['phone'];
		
		$check_user_account= mysqli_query($conn,"Select * from user_account where email = '$email' and role = 'customer'");
		if(mysqli_num_rows($check_user_account) > 0)
		{
			//Existing user account
			$row=mysqli_fetch_array($check_user_account);
			$userID = $row['userID'];
			
			if(mysqli_query($conn,"update customer set userID = $userID where customer_code = '$customer_code' and userID is null"))
			{
				echo json_encode(["status" => "success", "user_account" => "existing", "userID" => $userID, "message" => "Please login for customer verification!"]);	
			}
			
		}
		else
		{
			//New user account
			if(mysqli_query($conn,"insert into user_account(role, status, created_datetime) values ('customer', 'INACTIVE', '$date_time')"))
			{
				$userID = mysqli_insert_id($conn);
				if(mysqli_query($conn,"update customer set userID = $userID where customer_code = '$customer_code' and userID is null"))
				{
					echo json_encode(["status" => "success", "user_account" => "new_account", "userID" => $userID, "message" => "New account created."]);	
				}
			}
		}
		
	}
	else
	{
		echo json_encode(["status" => "success", "message" => "Billing details not found"]);
	}
	
	
}


if (isset($_POST['userID'], $_POST['orderCode'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['password'])) {

    // Database connection assumed as $conn

    // Sanitize input
    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $order_code = mysqli_real_escape_string($conn, $_POST['orderCode']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if this is signup with an existing order
    if (!empty($userID) && !empty($order_code)) {

        
        $check_user_account = mysqli_query($conn, "SELECT * FROM user_account WHERE userID = '$userID' AND role = 'customer'");
        if (mysqli_num_rows($check_user_account) > 0) {
            mysqli_query($conn, "UPDATE user_account SET name='$name', email='$email', phone='$phone', password='$password', status='ACTIVE' WHERE userID='$userID' AND status='INACTIVE'");
        }

        
        $order_sum_subtotal = 0;
        $get_payment_method = "SELECT payment_method FROM checkout WHERE order_code='$order_code' LIMIT 1";
        $result_payment_method = mysqli_query($conn, $get_payment_method);
        $row_payment = mysqli_fetch_assoc($result_payment_method);

        if ($row_payment['payment_method'] === "BANK") {

            $sql = "SELECT a.price, a.quantity FROM orders a
                    INNER JOIN product b ON a.productID = b.productID
                    INNER JOIN checkout c ON a.checkoutID = c.checkoutID
                    WHERE c.order_code='$order_code' AND b.status IN ('ACTIVE','LOW STOCK')";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $order_sum_subtotal += (float)$row['quantity'] * (float)$row['price'];
            }

            if ($order_sum_subtotal > 0) {
				
				$_SESSION['userID'] = $userID;
				
                $grandTotal = number_format($order_sum_subtotal, 2, '.', '');
                
				echo json_encode([
                    "status" => "success",
					"signup_orders" => 1,
                    "orders" => 1,
                    "invoice_created" => 1,
                    "gt" => $grandTotal
                ]);
				
            } else {
                
				echo json_encode([
                    "status" => "success",
					"signup_orders" => 1,
                    "orders" => 1,
                    "invoice_created" => 0
                ]);
            }

        } else if ($row_payment['payment_method'] === "CARD") {
            
			echo json_encode([
                "status" => "success",
				"signup_orders" => 1,
                "orders" => 1,
                "invoice_created" => 1
            ]);
        }

    } else {
		
        // Normal signup
        $check_user_account = mysqli_query($conn, "SELECT * FROM user_account WHERE email='$email' AND role='customer'");
        if (mysqli_num_rows($check_user_account) == 0) {
            mysqli_query($conn, "INSERT INTO user_account(role, name, email, phone, password, status, created_datetime) VALUES ('customer', '$name', '$email', '$phone', '$password', 'ACTIVE', '$date_time')");
            
			echo json_encode([
                "status" => "success",
				"signup_orders" => 0,
                "orders" => 0
            ]);
			
        } else {
            
			echo json_encode([
                "status" => "error",
				"signup_orders" => 0,
                "message" => "User already exists"
            ]);
			
        }
    }

}



if (isset($_POST['userID'], $_POST['fullName'], $_POST['email'], $_POST['phone'], $_POST['dob'], $_POST['password'])) {

    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $name   = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob    = mysqli_real_escape_string($conn, $_POST['dob']);
	$password    = mysqli_real_escape_string($conn, $_POST['password']);
	$security_changed = 0;
    
	
	$profile_image = "";
	
	
	// Get existing profile image first
	$get_user = mysqli_query($conn, "SELECT profile FROM user_account WHERE userID='$userID' AND role='customer'");
	$user_data = mysqli_fetch_assoc($get_user);
	$current_profile = $user_data['profile'];

	$profile_image = $current_profile;
	

	// Check if file uploaded
	if(isset($_FILES['profile']) && $_FILES['profile']['error'] == 0)
	{
		$target_dir = "uploads/";

		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		$file_name = time() . "_" . basename($_FILES["profile"]["name"]);
		$target_file = $target_dir . $file_name;
		$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		$allowed = array("jpg","jpeg","png","webp");

		if(in_array($file_type, $allowed))
		{
			if(move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file))
			{
				// Delete old image if exists
				if(!empty($current_profile) && file_exists($target_dir.$current_profile)) {
					unlink($target_dir.$current_profile);
				}

				$profile_image = $file_name;
			}
		}
	}
	
	
	// Check if user exists
    $check_user = mysqli_query($conn,"SELECT * FROM user_account WHERE userID = '$userID' AND role='customer'");

    if(mysqli_num_rows($check_user) > 0)
    {
		
		$result = mysqli_query($conn, "SELECT email, password FROM user_account WHERE userID = '$userID' LIMIT 1");

		if(mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_assoc($result);

			if($row['email'] !== $email || $row['password'] !== $password)
			{
				$security_changed = 1;
			}
		}
		
        mysqli_query($conn," UPDATE billing_address c 
							INNER JOIN checkout b ON b.billing_addressID = c.billing_addressID 
							INNER JOIN payment_transaction a ON a.ordercode = b.order_code 
							SET c.email = '$email' WHERE a.userID = '$userID'");

        
        mysqli_query($conn,"UPDATE user_account SET name='$name', email='$email', phone='$phone', dob='$dob', password = '$password', profile = '$profile_image' WHERE userID='$userID' AND role='customer'");

        echo json_encode([
            "status" => "success",
            "security_changed" => $security_changed,
            "msg" => "Successfully updated!"
        ]);
    }
}

if (isset($_POST['userID'], $_POST['deleteProfileImg'])) {

    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $target_dir = "uploads/";
	
	// Get existing profile image first
	$get_user = mysqli_query($conn, "SELECT profile FROM user_account WHERE userID='$userID' AND role='customer'");
	$user_data = mysqli_fetch_assoc($get_user);
	$current_profile = $user_data['profile'];
	if(!empty($current_profile) && file_exists($target_dir.$current_profile)) {
		unlink($target_dir.$current_profile);
	}
	
	mysqli_query($conn,"UPDATE user_account SET  profile = null WHERE userID='$userID' AND role='customer'");
        
		
	echo json_encode([
		"status" => "success",
		"msg" => "Profile picture deleted!"
	]);
    
}


if (isset($_POST['email'], $_POST['billing_first_name'], $_POST['billing_last_name'], $_POST['billing_address'], $_POST['billing_city'], $_POST['billing_state'], $_POST['billing_postcode'], $_POST['billing_phone'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $first_name   = mysqli_real_escape_string($conn, $_POST['billing_first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['billing_last_name']);
    $address  = mysqli_real_escape_string($conn, $_POST['billing_address']);
    $city    = mysqli_real_escape_string($conn, $_POST['billing_city']);
	$state    = mysqli_real_escape_string($conn, $_POST['billing_state']);
	$postcode    = mysqli_real_escape_string($conn, $_POST['billing_postcode']);
	$phone    = mysqli_real_escape_string($conn, $_POST['billing_phone']);
  
	mysqli_query($conn,"UPDATE billing_address SET first_name='$first_name', 
						last_name='$last_name', 
						phone='$phone', 
						city='$city', 
						address = '$address',
						state='$state',
						postcode='$postcode' 
						WHERE email='$email'");

	echo json_encode([
		"status" => "success",
		"msg" => "Successfully updated!"
	]);
    
}

if (isset($_POST['shipping_addressID'])) {

    $shipping_addressID = (int)$_POST['shipping_addressID'];
	$data    = [];
	
	$query = mysqli_query($conn, "SELECT * FROM shipping_address WHERE shipping_addressID =  $shipping_addressID LIMIT 1");
	$row = mysqli_fetch_assoc($query);
	
	$data = [
		"name"   => $row['name'],
		"phone" => $row['phone'],
		"address_line1" => $row['address_line1'],
		"address_line2" => $row['address_line2'],
		"city" => $row['city'],
		"state" => $row['state'],
		"postcode" => $row['postcode'],
		"is_active" => $row['is_active']
	];
		
	

	echo json_encode([
		"status" => "success",
		"data"   => $data
	]);
    
}

if (isset($_POST['shipping_addressIDs'],$_POST['shipping_name'],$_POST['shipping_phone'],$_POST['shipping_address1'],$_POST['shipping_address2'],$_POST['shipping_city'],$_POST['shipping_state'],$_POST['shipping_postcode'],$_POST['is_active'])) {

    $shipping_addressID = (int)$_POST['shipping_addressIDs'];
	$shipping_name = mysqli_real_escape_string($conn, $_POST['shipping_name']);
	$shipping_phone = mysqli_real_escape_string($conn, $_POST['shipping_phone']);
	$shipping_address1 = mysqli_real_escape_string($conn, $_POST['shipping_address1']);
	$shipping_address2 = mysqli_real_escape_string($conn, $_POST['shipping_address2']);
	$shipping_city = mysqli_real_escape_string($conn, $_POST['shipping_city']);
	$shipping_state = mysqli_real_escape_string($conn, $_POST['shipping_state']);
	$shipping_postcode = mysqli_real_escape_string($conn, $_POST['shipping_postcode']);
	$is_active = mysqli_real_escape_string($conn, $_POST['is_active']);
	
	if(mysqli_query($conn, "update shipping_address set is_active = '0' WHERE name = '$shipping_name' and phone = '$shipping_phone'"))
	{
		
		mysqli_query($conn, "update shipping_address
								set name = '$shipping_name',
								phone = '$shipping_phone',
								address_line1 = '$shipping_address1',
								address_line2 = '$shipping_address2',
								city = '$shipping_city',
								state = '$shipping_state',
								postcode = '$shipping_postcode',
								is_active = '$is_active'
								WHERE shipping_addressID = $shipping_addressID");
		
	}
	
	
	
	echo json_encode([
		"status" => "success"
	]);
    
}

//Add Billing Address
if (isset(
$_POST['userID'],
$_POST['addbilling_first_name'],
$_POST['addbilling_last_name'],
$_POST['addbilling_address'],
$_POST['addbilling_city'],
$_POST['addbilling_state'],
$_POST['addbilling_postcode'],
$_POST['addbilling_phone'])) {

	$userID = (int)$_POST['userID'];
	$first_name = mysqli_real_escape_string($conn, $_POST['addbilling_first_name']);
	$last_name = mysqli_real_escape_string($conn, $_POST['addbilling_last_name']);
	$address = mysqli_real_escape_string($conn, $_POST['addbilling_address']);
	$phone = mysqli_real_escape_string($conn, $_POST['addbilling_phone']);
	$city = mysqli_real_escape_string($conn, $_POST['addbilling_city']);
	$state = mysqli_real_escape_string($conn, $_POST['addbilling_state']);
	$postcode = mysqli_real_escape_string($conn, $_POST['addbilling_postcode']);
	$phone = mysqli_real_escape_string($conn, $_POST['addbilling_phone']);
	
	//get email
	 $query = mysqli_query($conn, "select email from user_account where userID = $userID limit 1");
	 $row = mysqli_fetch_assoc($query);
	 $email = $row['email'];
	
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';	
	$random=generate_string($permitted_chars, 8);
	$customer_code="CUST".$random;
	mysqli_query($conn, "insert into customer (userID, customer_code) value ($userID, '$customer_code')");
	
	
	mysqli_query($conn, "insert into billing_address (customer_code, first_name, last_name, email, phone, address, city, state, postcode, country) 
						values 
						('$customer_code','$first_name', '$last_name', '$email','$phone','$address','$city','$state','$postcode', 'MY')");

	echo json_encode([
		"status" => "success"
	]);
    
}

if (isset(
$_POST['userID'],
$_POST['add_shipping_name'],
$_POST['add_shipping_email'],
$_POST['add_shipping_phone'],
$_POST['add_shipping_address1'],
$_POST['add_shipping_address2'],
$_POST['add_shipping_city'],
$_POST['add_shipping_state'],
$_POST['add_shipping_postcode'])) {

	$userID = (int)$_POST['userID'];
	$shipping_name = mysqli_real_escape_string($conn, $_POST['add_shipping_name']);
	$shipping_email = mysqli_real_escape_string($conn, $_POST['add_shipping_email']);
	$shipping_phone = mysqli_real_escape_string($conn, $_POST['add_shipping_phone']);
	$shipping_address1 = mysqli_real_escape_string($conn, $_POST['add_shipping_address1']);
	$shipping_address2 = mysqli_real_escape_string($conn, $_POST['add_shipping_address2']);
	$shipping_city = mysqli_real_escape_string($conn, $_POST['add_shipping_city']);
	$shipping_state = mysqli_real_escape_string($conn, $_POST['add_shipping_state']);
	$shipping_postcode = mysqli_real_escape_string($conn, $_POST['add_shipping_postcode']);
	
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';	
	$random=generate_string($permitted_chars, 8);
	$customer_code="CUST".$random;
	mysqli_query($conn, "insert into customer (userID, customer_code) value ($userID, '$customer_code')");
	
	
	mysqli_query($conn, "insert into shipping_address (customer_code, name, email, phone, address_line1, address_line2, city, state, postcode, country) 
						values 
						('$customer_code','$shipping_name', '$shipping_email', '$shipping_phone','$shipping_address1','$shipping_address2','$shipping_city','$shipping_state','$shipping_postcode', 'MY')");

	echo json_encode([
		"status" => "success"
	]);
    
}

if (isset($_POST['del_shipping_addressID'])) {

	$shipping_addressID = (int)$_POST['del_shipping_addressID'];
	
	mysqli_query($conn, "update shipping_address set is_delete = '1' where shipping_addressID = $shipping_addressID");
	
	echo json_encode([
		"status" => "success"
	]);
    
}


if(isset($_POST['activ_shipping_addressID'],$_POST['userID'])) {

    $shipping_addressID = (int)$_POST['activ_shipping_addressID'];    
    $userID = (int)$_POST['userID'];
	
     mysqli_query($conn, "update customer a 
							inner join shipping_address b on a.customer_code = b.customer_code
							set b.is_active = '0'
							where a.userID = $userID and b.is_delete = '0';");
							
	 mysqli_query($conn, "update customer a 
								inner join shipping_address b on a.customer_code = b.customer_code
								set b.is_active = '1'
								where a.userID = $userID and b.shipping_addressID = $shipping_addressID and b.is_delete = '0';");
    

    echo json_encode(["status" => "success"]);
    exit;
}

if(isset($_POST['userID'],$_POST['active_shipping_addressID'])) {
    
   $userID = (int)$_POST['userID'];

    $query = mysqli_query($conn, "
        SELECT b.* 
        FROM customer a 
        INNER JOIN shipping_address b ON a.customer_code = b.customer_code
        WHERE a.userID = $userID 
        AND b.is_active = '1'
        LIMIT 1
    ");

    if(mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);

        $data = [
            "name"   => $row['name'],
            "phone"  => $row['phone'],
            "email"  => $row['email'],
            "address_line1" => $row['address_line1'],
            "address_line2" => $row['address_line2'],
            "city"   => $row['city'],
            "state"  => $row['state'],
            "postcode" => $row['postcode']
        ];

        echo json_encode([
            "status" => "success",
            "data"   => $data
        ]);
        exit;

    } 
	else {
        echo json_encode(["status" => "no data"]);
        exit;
    }
}

if(isset($_POST['validate_email'])){
	
	$email = $_POST['validate_email'];
	
	$check_user_account= mysqli_query($conn,"Select * from user_account where email = '$email' and role = 'customer' and status = 'ACTIVE'");
		if(mysqli_num_rows($check_user_account) > 0)
		{
			//Existing user account
			$row=mysqli_fetch_array($check_user_account);
			$userID = $row['userID'];
			
			echo json_encode(["status" => "success", "user_account_status" => "Active", "userID" => $userID]);
			
		}
		else
		{
			echo json_encode(["status" => "fail"]);
		}
	
	
}

if(isset($_POST['changepass_email'],$_POST['changepass_password'])){
	
	$email = $_POST['changepass_email'];
	$password = $_POST['changepass_password'];
	
	$check_user_account= mysqli_query($conn,"Select * from user_account where email = '$email' and role = 'customer' and status = 'ACTIVE'");
		if(mysqli_num_rows($check_user_account) > 0)
		{
			//Existing user account
			$row=mysqli_fetch_array($check_user_account);
			$userID = $row['userID'];
			mysqli_query($conn,"update user_account set password = '$password', updated_datetime = '$date_time' where email = '$email' and role = 'customer' and status = 'ACTIVE'");
			
			echo json_encode(["status" => "success"]);
			
		}
		else
		{
			echo json_encode(["status" => "fail"]);
		}
	
	
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
?>