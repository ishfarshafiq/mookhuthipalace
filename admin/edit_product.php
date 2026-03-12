<?php

include("dbconnect.php");

if ($_POST['action'] === 'update_product') {

    $productID = (int)$_POST['productID'];

    $product_name = mysqli_real_escape_string($conn, $_POST['edit_product_name']);
    $sku = mysqli_real_escape_string($conn, $_POST['edit_sku']);
    $categoryID = $_POST['edit_categoryID'];
    $materialID = $_POST['edit_materialID'];
    $gemstone = $_POST['edit_gemstone'];
	$original_price = $_POST['edit_original_price'];
    $price = $_POST['edit_price'];
    $stock = $_POST['edit_stock_quantity'];
    $width = $_POST['edit_width'];
	
	
	$stylesInput = $_POST['edit_styles'] ?? '';
	$stylesArray = array_filter(
				array_map('trim', explode(',', $stylesInput)),
				fn($v) => $v !== ''
			);

	$styles = count($stylesArray) > 0 ? json_encode(array_values($stylesArray)) : "";
	
    // $stylesInput = $_POST['edit_styles'];
	// $stylesArray = array_map('trim', explode(',', $stylesInput));
	// $styles = json_encode($stylesArray);
	
	$colorsInput = $_POST['edit_colors'] ?? '';
	$colorsArray = array_filter(
				array_map('trim', explode(',', $colorsInput)),
				fn($v) => $v !== ''
			);

	$colors = count($colorsArray) > 0 ? json_encode(array_values($colorsArray)) : "";
    
	// $colorsInput = $_POST['edit_colors'];
	// $colorsArray = array_map('trim', explode(',', $colorsInput));
	// $colors = json_encode($colorsArray);
    
	$description = $_POST['edit_description'];
	$description = str_replace(["'", "’"], "", $description);
	
	$shipping_description = $_POST['edit_shipping_description'];
	$shipping_description = str_replace(["'", "’"], "", $shipping_description);
	
	$delivery_time_text = $_POST['edit_delivery_time_text'];
	$delivery_time_text = str_replace(["'", "’"], "", $delivery_time_text);
	
	$returns_text = $_POST['edit_returns_text'];
	$returns_text = str_replace(["'", "’"], "", $returns_text);
	
	$care_instructions = $_POST['edit_care_instructions'];
	$care_instructions = str_replace(["'", "’"], "", $care_instructions);
    
	
	$packaging = $_POST['edit_packaging'];
    $status = $_POST['edit_status'];

    // ---------- MAIN IMAGE ----------
   if (!empty($_FILES['edit_imageNameSingle']['name'])) {
		$query = mysqli_query($conn,"SELECT product_image FROM product WHERE productID = $productID LIMIT 1");

		if ($query && mysqli_num_rows($query) > 0) {
			$row = mysqli_fetch_assoc($query);
			$oldImage = $row['product_image'];

			if (!empty($oldImage)) {
				$oldPath = "uploads/products/" . $oldImage;
				if (file_exists($oldPath)) {
					unlink($oldPath);
				}
			}
		}

		$mainImage = time() . "_" . basename($_FILES['edit_imageNameSingle']['name']);
		move_uploaded_file($_FILES['edit_imageNameSingle']['tmp_name'],"uploads/products/" . $mainImage);

		
		mysqli_query($conn,"UPDATE product SET product_image='$mainImage' WHERE productID=$productID");
	}

    // ---------- SUB IMAGES ----------
    if (!empty($_FILES['edit_imageNameMultiple']['name'][0])) {

        $query = mysqli_query($conn,"SELECT sub_images FROM product WHERE productID=$productID");
        $row = mysqli_fetch_assoc($query);

        $existingImages = json_decode($row['sub_images'], true) ?? [];

        foreach ($_FILES['edit_imageNameMultiple']['name'] as $key => $name) {
            $fileName = time() . "_" . $name;
            move_uploaded_file($_FILES['edit_imageNameMultiple']['tmp_name'][$key],"uploads/products/" . $fileName);
            $existingImages[] = $fileName;
        }

        $jsonImages = mysqli_real_escape_string($conn,json_encode($existingImages));

        mysqli_query($conn,"UPDATE product SET sub_images='$jsonImages' WHERE productID=$productID");
    }

    
    mysqli_query($conn, "
        UPDATE product SET
            product_name='$product_name',
            sku='$sku',
            categoryID=$categoryID,
            materialID=$materialID,
            gemstone='$gemstone',
			original_price='$original_price',
            price='$price',
            stock_quantity='$stock',
            width='$width',
            styles='$styles',
            colors='$colors',
            description='$description',
            packaging='$packaging',
            status='$status',
			shipping_description = '$shipping_description',
			delivery_time_text = '$delivery_time_text',
			returns_text = '$returns_text',
			care_instructions = '$care_instructions'
        WHERE productID=$productID
    ");

    echo json_encode([
        "status" => "success",
        "msg" => "Product updated"
    ]);
    exit;
}
?>