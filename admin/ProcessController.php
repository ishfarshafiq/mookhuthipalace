<?php
include("dbconnect.php");

if(isset($_GET['productID'],$_GET['action'])){
	
	$productID  = (int)$_GET['productID'];
	$action  = $_GET['action'];
    $status  = "success";
    $msg     = "";
    $data    = [];
	
	if($action == "view")
	{
		$query = mysqli_query($conn, "SELECT * FROM product WHERE productID = $productID LIMIT 1");

		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);
			
			$stylesArray = json_decode($row['styles'], true);
			$colorsArray = json_decode($row['colors'], true);

			$data = [
				"productID"   => $row['productID'],
				"product_name"     => $row['product_name'],
				"sku"    => $row['sku'],
				"categoryID"    => $row['categoryID'],
				"materialID"    => $row['materialID'],
				"gemstone"    => $row['gemstone'],
				"original_price"    => $row['original_price'],
				"price"    => $row['price'],
				"stock_quantity"    => $row['stock_quantity'],
				"width"    => $row['width'],
				"styles"    => is_array($stylesArray) ? implode(',', $stylesArray) : '',
				"colors"    => is_array($colorsArray) ? implode(',', $colorsArray) : '',
				"description"    => $row['description'],
				"shipping_description"    => $row['shipping_description'],
				"delivery_time_text"    => $row['delivery_time_text'],
				"returns_text"    => $row['returns_text'],
				"care_instructions"    => $row['care_instructions'],
				"packaging"    => $row['packaging'],
				"product_image" => $row['product_image'],
				"sub_images"    => $row['sub_images'],
				"status"   => $row['status']
			];
		} 
		else 
		{
			$status = "error";
			$msg    = "Product not found";
		}
		
	}
	
    echo json_encode([
        "status" => $status,
        "msg"    => $msg,
        "data"   => $data
    ]);
  
	
}

if (isset($_POST['action'],$_POST['productID'],$_POST['image'])) {

    $productID = (int)$_POST['productID'];
    $image     = $_POST['image'];

    $query = mysqli_query($conn,"SELECT sub_images FROM product WHERE productID = $productID LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);
        $images = json_decode($row['sub_images'], true);

        if (($key = array_search($image, $images)) !== false) {

            unset($images[$key]);
            $images = array_values($images);

            mysqli_query($conn,"UPDATE product SET sub_images = '" . mysqli_real_escape_string($conn, json_encode($images)) . "' WHERE productID = $productID");

            // Delete physical file
            $filePath = "uploads/products/" . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            echo json_encode([
                "status" => "success",
                "msg" => "Image deleted"
            ]);
            exit;
        }
    }

    echo json_encode([
        "status" => "error",
        "msg" => "Image not found"
    ]);
    exit;
}

if (isset($_POST['del_action'],$_POST['productID'])) {

    $productID = (int)$_POST['productID'];

    
    $query = mysqli_query($conn, "SELECT product_image, sub_images FROM product WHERE productID = $productID LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        // Delete main image
        if (!empty($row['product_image'])) {
            $path = "uploads/products/" . $row['product_image'];
            if (file_exists($path)) unlink($path);
        }

        // Delete sub images
        if (!empty($row['sub_images'])) {
            $subImages = json_decode($row['sub_images'], true);
            if ($subImages) {
                foreach ($subImages as $img) {
                    $subPath = "uploads/products/" . $img;
                    if (file_exists($subPath)) unlink($subPath);
                }
            }
        }
    }

    
	$delete = mysqli_query($conn, "update product set is_delete = '1' WHERE productID = $productID");

    if ($delete) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Failed to delete product"]);
    }
    exit;
}


if(isset($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['address'],$_POST['save_admin'])){
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$is_error = 0;
	$status="success";
	
	mysqli_query($conn,"update user_account set name='$name', email='$email', phone='$phone', address='$address' where role = 'admin'");
	$msg="Saved successfully.";
	
	
	echo json_encode(["status" => $status, "msg" => $msg]);
	
}

if (isset($_POST['current_password'])) {

    $current_password = $_POST['current_password'];

    
    $query = mysqli_query($conn, "SELECT * FROM user_account WHERE role = 'admin' and password = '$current_password' LIMIT 1");
    if (mysqli_num_rows($query) > 0) {
        echo json_encode(["status" => "success"]);
    }
	else
	{
		echo json_encode(["status" => "fail"]);
	}

    exit;
}

if(isset($_POST['new_password'])){
	
	$new_password = $_POST['new_password'];
	$is_error = 0;
	$status="success";
	
	mysqli_query($conn,"update user_account set password='$new_password' where role = 'admin'");
	$msg="Password is changed. Please login again!";
	
	
	echo json_encode(["status" => $status, "msg" => $msg]);
	
}

if(isset($_GET['environment'])){
	
	$environment  = $_GET['environment'];
    $status  = "success";
    $msg     = "";
    $data    = [];
	
	$query = mysqli_query($conn, "SELECT * FROM payment_gateway WHERE environment = '$environment' LIMIT 1");

		if (mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);

			$data = [
				"secret_key"   => $row['secret_key'],
				"category_code"     => $row['category_code'],
				"status"     => $row['status'],
			];
		} 
		else 
		{
			$status = "error";
			$msg    = "Payment gateway API key not found";
		}
	
    echo json_encode([
        "status" => $status,
        "msg"    => $msg,
        "data"   => $data
    ]);
  
	
}

if (isset($_POST['secret_key'], $_POST['category_code'], $_POST['status'], $_POST['environment'])) {

    $secret_key     = mysqli_real_escape_string($conn, $_POST['secret_key']);
    $category_code  = mysqli_real_escape_string($conn, $_POST['category_code']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);
    $environment    = mysqli_real_escape_string($conn, $_POST['environment']);

   
    if (!in_array($environment, ['TESTING', 'LIVE'])) {
        echo json_encode(["status" => "error", "msg" => "Invalid environment"]);
        exit;
    }

    
    if ($status == 'INACTIVE') {
        echo json_encode([
            "status" => "error",
            "msg" => "One environment must be ACTIVE. You cannot set both to INACTIVE."
        ]);
        exit;
    }

    // If setting this environment to ACTIVE
    if ($status == 'ACTIVE') {

        // Make the other environment INACTIVE
        $other_env = ($environment == 'TESTING') ? 'LIVE' : 'TESTING';

        mysqli_query($conn, "
            UPDATE payment_gateway 
            SET status = 'INACTIVE' 
            WHERE environment = '$other_env'
        ");
    }

   
    $query = mysqli_query($conn,"SELECT * FROM payment_gateway WHERE environment = '$environment' LIMIT 1");
    if (mysqli_num_rows($query) > 0) {

        mysqli_query($conn, "UPDATE payment_gateway SET secret_key = '$secret_key',category_code = '$category_code', status = '$status' WHERE environment = '$environment'");

    } else {

        mysqli_query($conn, "INSERT INTO payment_gateway (secret_key, category_code, environment, status) VALUES ('$secret_key', '$category_code', '$environment', '$status')");
    }

    echo json_encode(["status" => "success", "msg" => "Saved successfully!"]);
    exit;
}


// if (isset($_POST['secret_key'],$_POST['category_code'],$_POST['status'],$_POST['environment'])) {

    // $secret_key = $_POST['secret_key'];
	// $category_code = $_POST['category_code'];
	// $status = $_POST['status'];
	// $environment = $_POST['environment'];

    
    // $query = mysqli_query($conn, "SELECT * FROM payment_gateway WHERE  environment = '$environment' LIMIT 1");
    // if (mysqli_num_rows($query) > 0) {
		
		// mysqli_query($conn,"update payment_gateway set secret_key='$secret_key', category_code='$category_code', status = '$status' where environment = '$environment'");
    // }
	// else
	// {
		// mysqli_query($conn,"insert into payment_gateway (secret_key, category_code, environment, status) values  ('$secret_key', '$category_code' , '$environment', '$status')");
		
	// }
	
	// $msg="Saved successfully!";
	
	 // echo json_encode(["status" => "success", "msg" => $msg]);

    // exit;
// }

if (isset($_POST['productID'],$_POST['is_bestseller'])) {

    $productID = (int)$_POST['productID'];
    $is_bestseller     = $_POST['is_bestseller'];

    if(mysqli_query($conn,"UPDATE product SET is_bestseller = '$is_bestseller' WHERE productID = $productID")){
		
		echo json_encode([
			"status" => "success",
			"msg" => "success"
		]);
		exit;
	}

	exit;
}

//Category
if(isset($_POST['code'],$_POST['category'])){
	
	$code = $_POST['code'];
	$category = $_POST['category'];
	$is_error = 0;
	$status="success";
	
	$query = mysqli_query($conn, "SELECT * FROM category where code = '$code' and category = '$category'");
    if (mysqli_num_rows($query) > 0) {
		
		echo json_encode(["status" => "success", "msg" => "Category is exist."]);
    }
	else
	{
		mysqli_query($conn,"insert into category(code, category) values ('$code', '$category')");
		echo json_encode(["status" => "success", "msg" => "Category saved!"]);
	}
	
	
}

if(isset($_POST['categoryID'],$_POST['action'])){
	
	$categoryID  = (int)$_POST['categoryID'];
	$action = $_POST['action'];
    $status  = "success";
    $msg     = "";
    $data    = [];
	
	if($action == "view")
	{
		$query = mysqli_query($conn, "SELECT * FROM category WHERE categoryID = $categoryID LIMIT 1");

		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);

			$data = [
				"categoryID"   => $row['categoryID'],
				"code"     => $row['code'],
				"category" => $row['category'],
				"status"   => $row['status']
			];
		} 
		else 
		{
			$status = "error";
			$msg    = "Category not found";
		}
		
	}
	else if($action == "delete")
	{
		$query = $query = mysqli_query($conn, "update category set is_delete = '1' WHERE categoryID = $categoryID"); //mysqli_query($conn, "DELETE FROM category WHERE categoryID = $categoryID");
		$msg = 'Category deleted successfully.';

		if (!$query) {
			$status = 'error';
			$msg    = 'Failed to delete category';
		}

	}
	
    echo json_encode([
        "status" => $status,
        "msg"    => $msg,
        "data"   => $data
    ]);
  
	
}

if(isset($_POST['categoryID'],$_POST['edit_code'],$_POST['edit_category'],$_POST['edit_status'])){
	
	$categoryID = $_POST['categoryID'];
	$code = $_POST['edit_code'];
	$category = $_POST['edit_category'];
	$edit_status = $_POST['edit_status'];
	$is_error = 0;
	$status="success";
	
	$result_check_branch=mysqli_query($conn,"select * from category where categoryID != $categoryID and code = '$code' and category = '$category'");
	if (mysqli_num_rows($result_check_branch) > 0) {
		$is_error = 1;
		$msg="Category is exist.";
	}
	
	if($is_error == 0)
	{
		mysqli_query($conn,"update category set code = '$code', category = '$category', status = '$edit_status' where categoryID = $categoryID limit 1");
		$msg="Category saved successfully.";
	}
	
	echo json_encode(["status" => $status, "msg" => $msg]);
	
}

//Material
if(isset($_POST['code'],$_POST['material'])){
	
	$code = $_POST['code'];
	$material = $_POST['material'];
	$is_error = 0;
	$status="success";
	
	$query = mysqli_query($conn, "SELECT * FROM material where code = '$code' and material = '$material'");
    if (mysqli_num_rows($query) > 0) {
		
		echo json_encode(["status" => "success", "msg" => "Material is exist."]);
    }
	else
	{
		mysqli_query($conn,"insert into material(code, material) values ('$code', '$material')");
		echo json_encode(["status" => "success", "msg" => "Material saved!"]);
	}
	
	
}

if(isset($_POST['materialID'],$_POST['action'])){
	
	$materialID  = (int)$_POST['materialID'];
	$action = $_POST['action'];
    $status  = "success";
    $msg     = "";
    $data    = [];
	
	if($action == "view")
	{
		$query = mysqli_query($conn, "SELECT * FROM material WHERE materialID = $materialID LIMIT 1");

		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);

			$data = [
				"materialID"   => $row['materialID'],
				"code"     => $row['code'],
				"material" => $row['material'],
				"status"   => $row['status']
			];
		} 
		else 
		{
			$status = "error";
			$msg    = "Material not found";
		}
		
	}
	else if($action == "delete")
	{
		$query = mysqli_query($conn, "update material set is_delete = '1' WHERE materialID = $materialID"); //mysqli_query($conn, "DELETE FROM material WHERE materialID = $materialID");
		$msg = 'Material deleted successfully.';

		if (!$query) {
			$status = 'error';
			$msg    = 'Failed to delete material';
		}

	}
	
    echo json_encode([
        "status" => $status,
        "msg"    => $msg,
        "data"   => $data
    ]);
  
	
}

if(isset($_POST['materialID'],$_POST['edit_code'],$_POST['edit_material'],$_POST['edit_status'])){
	
	$materialID = $_POST['materialID'];
	$code = $_POST['edit_code'];
	$material = $_POST['edit_material'];
	$edit_status = $_POST['edit_status'];
	$is_error = 0;
	$status="success";
	
	$result_check_branch=mysqli_query($conn,"select * from material where materialID != $materialID and code = '$code' and material = '$material'");
	if (mysqli_num_rows($result_check_branch) > 0) {
		$is_error = 1;
		$msg="Material is exist.";
	}
	
	if($is_error == 0)
	{
		mysqli_query($conn,"update material set code = '$code', material = '$material', status = '$edit_status' where materialID = $materialID limit 1");
		$msg="Material saved successfully.";
	}
	
	echo json_encode(["status" => $status, "msg" => $msg]);
	
}

if(isset($_POST['ordercode'],$_POST['action'])){
	
	$ordercode = $_POST['ordercode'];
	$action  = $_POST['action'];
    $status  = "success";
	$PickUpDesc="";
	$billing_address="";
	$shipping_address="";
	$delivery_fee = 0;
    $msg     = "";
    $data    = [];
	$products = [];
	
	if($action == "view")
	{
		// $query = mysqli_query($conn, "SELECT a.ordercode, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, SUM(b.quantity * b.price) AS subtotal
										// FROM payment_transaction a
										// INNER JOIN orders b ON a.ordercode = b.order_code
										// INNER JOIN checkout c ON b.order_code = c.order_code
										// INNER JOIN user_account ua ON a.userID = ua.userID
										// WHERE a.ordercode = '$ordercode'
										// GROUP BY a.ordercode, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered");

		$query = mysqli_query($conn, "SELECT a.ordercode, ua.name, ua.phone, c.delivery_method, c.billing_addressID, c.shipping_addressID, c.payment_method, c.recommendedBranch, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, SUM(b.quantity * b.price) AS subtotal,
								CASE 
										WHEN  c.billing_addressID > 0 
											THEN CONCAT(ba.address, ', ', ba.postcode,' ', ba.state, ', ', ba.city) 
										ELSE '' 
									END AS billing_address,
									
								CASE 
									WHEN c.delivery_method = 'standard' AND c.shipping_addressID > 0 
										THEN CONCAT(sa.address_line1, ', ', sa.address_line2, ', ', sa.postcode,' ', sa.state, ', ', sa.city) 
									ELSE '' 
								END AS shipping_address
								FROM payment_transaction a
								INNER JOIN orders b ON a.ordercode = b.order_code
								INNER JOIN checkout c ON b.order_code = c.order_code
								LEFT JOIN billing_address ba ON c.billing_addressID = ba.billing_addressID
								LEFT JOIN shipping_address sa ON c.shipping_addressID = sa.shipping_addressID
								INNER JOIN user_account ua ON a.userID = ua.userID
								WHERE a.ordercode = '$ordercode'
								GROUP BY a.ordercode, ua.name, ua.phone, c.delivery_method, c.payment_method, c.recommendedBranch, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, c.billing_addressID, c.shipping_addressID;");
		
		
		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);
			
			if($row['billing_address']!="")
			{
				$billing_address = $row['billing_address'];
			}
			
			if($row['delivery_method'] == "standard")
			{
				$delivery_fee = 8;
				if($row['shipping_address']!="")
				{
					$shipping_address = $row['shipping_address'];
				}
			}
			
			if($row['delivery_method'] == "foreign")
			{
				$delivery_fee = 18;
				if($row['shipping_address']!="")
				{
					$shipping_address = $row['shipping_address'];
				}
			}
			
			if($row['delivery_method'] == "selfCollect")
			{
				$PickUpDesc = $row['collecting_point'];
			}
			
			$productSql = "SELECT d.product_name, b.style, b.color, b.quantity FROM orders b
										INNER JOIN product d ON b.productID = d.productID
										WHERE b.order_code = '".$row['ordercode']."'";

			$productResult = mysqli_query($conn, $productSql);
			

			while($product = mysqli_fetch_assoc($productResult)) {

				$products[] = [
					"product_name" => $product['product_name'],
					"style"        => $product['style'],
					"color"        => $product['color'],
					"quantity"     => $product['quantity']
				];
			}
			
			$data = [
				"ordercode" => $row['ordercode'],
				"name" => $row['name'],
				"phone" => $row['phone'],
				"order_date" => date("M d, Y \a\t h:i A", strtotime($row['payment_date'])),
				"subtotal"    => number_format((float)$row['subtotal'], 2, '.', ''),
				"delivery_method" => $row['delivery_method'],
				"delivery_fee" => $delivery_fee,
				"PickUpDesc" => $PickUpDesc,
				"billing_address" => $billing_address,
				"shipping_address" => $shipping_address,
				"recommendedBranch" => $row['recommendedBranch'],
				"is_delivered" => $row['is_delivered'],
				"payment_status" => $row['payment_status'],
				"products"       => $products  
			];
		}
		
	}
	
	if($action == "editview")
	{
		
		$query = mysqli_query($conn, "SELECT a.ordercode, a.notes, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, SUM(b.quantity * b.price) AS subtotal
										FROM payment_transaction a
										INNER JOIN orders b ON a.ordercode = b.order_code
										INNER JOIN checkout c ON b.order_code = c.order_code
										INNER JOIN user_account ua ON a.userID = ua.userID
										WHERE a.ordercode = '$ordercode'
										GROUP BY a.ordercode, a.notes, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered");

		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);
			
			$data = [
				"ordercode" => $row['ordercode'],
				"is_delivered" => $row['is_delivered'],
				"payment_status" => $row['payment_status'],
				"notes" => $row['notes']
			];
		}
		
		
	}
	
    echo json_encode([
        "status" => $status,
        "msg"    => $msg,
        "data"   => $data
    ]);
  
	
}

if(isset($_POST['orderId'],$_POST['orderStatus'],$_POST['paymentStatus'],$_POST['notes']))
{
	$orderId = $_POST['orderId'];
	$orderStatus = $_POST['orderStatus'];
	$paymentStatus = $_POST['paymentStatus'];
	$notes = $_POST['notes'];
	
	mysqli_query($conn,"update payment_transaction set payment_status = '$paymentStatus', is_delivered = '$orderStatus', notes = '$notes' where ordercode = '$orderId'");
	
	echo json_encode([
        "status" => "success",
        "msg"    => "success"
    ]);
}

if (isset($_POST['delete_testimoni'],$_POST['testimoniID'])) {

    $testimoniID = (int)$_POST['testimoniID'];

    
    $query = mysqli_query($conn, "SELECT image FROM testimoni WHERE testimoniID  = $testimoniID LIMIT 1");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        // Delete main image
        if (!empty($row['image'])) {
            $path = "uploads/testimoni/" . $row['image'];
            if (file_exists($path)) unlink($path);
        }

    }

	$delete = mysqli_query($conn, "delete from testimoni WHERE testimoniID = $testimoniID");

    if ($delete) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Failed to delete testimoni"]);
    }
    exit;
}
?>