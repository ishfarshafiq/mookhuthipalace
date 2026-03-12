<?php
include("dbconnect.php");

$response = ["success" => false, "message" => "Unknown error"];

if(isset($_POST['addProduct'])) {
	
$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$sku = mysqli_real_escape_string($conn, $_POST['sku']);
$categoryID = mysqli_real_escape_string($conn, $_POST['categoryID']);
$materialID = mysqli_real_escape_string($conn, $_POST['materialID']);
$gemstone = mysqli_real_escape_string($conn, $_POST['gemstone']);
$original_price = $_POST['original_price'];
$price = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];
$width = mysqli_real_escape_string($conn, $_POST['width']);

$stylesInput  = mysqli_real_escape_string($conn, $_POST['styles']);
$stylesArray = array_map('trim', explode(',', $stylesInput));
$stylesJson = json_encode($stylesArray);
$styles = mysqli_real_escape_string($conn, $stylesJson);

$colorsInput  = mysqli_real_escape_string($conn, $_POST['colors']);
$colorsArray = array_map('trim', explode(',', $colorsInput));
$colorsJson = json_encode($colorsArray);
$colors = mysqli_real_escape_string($conn, $colorsJson);

$description = mysqli_real_escape_string($conn, $_POST['description']);
$description = str_replace(["'", "’"], "", $description);
$packaging = mysqli_real_escape_string($conn, $_POST['packaging']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$shipping_description = mysqli_real_escape_string($conn, $_POST['shipping_description']);
$delivery_time_text = mysqli_real_escape_string($conn, $_POST['delivery_time_text']);
$returns_text = mysqli_real_escape_string($conn, $_POST['returns_text']);
$care_instructions = mysqli_real_escape_string($conn, $_POST['care_instructions']);

$uploadDir = "uploads/products/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* MAIN IMAGE */
$main_image = "";
if (!empty($_FILES['imageNameSingle']['name'])) {
    $main_image = time() . "_" . $_FILES['imageNameSingle']['name'];
    move_uploaded_file($_FILES['imageNameSingle']['tmp_name'],$uploadDir . $main_image);
}

$sub_images = [];

if (!empty($_FILES['imageNameMultiple']['name'][0])) {
    foreach ($_FILES['imageNameMultiple']['name'] as $key => $name) {
        $fileName = time() . "_" . $name;
        move_uploaded_file(
            $_FILES['imageNameMultiple']['tmp_name'][$key],
            $uploadDir . $fileName
        );
        $sub_images[] = $fileName;
    }
}

$sub_images_json = !empty($sub_images) ? json_encode($sub_images) : "";

$sql = "INSERT INTO product
(product_name, sku, categoryID, materialID, gemstone, original_price, price, stock_quantity, width, styles, colors, description, packaging, status, shipping_description, delivery_time_text, returns_text, care_instructions, product_image, sub_images)
VALUES
('$product_name','$sku',$categoryID,$materialID,'$gemstone','$original_price','$price','$stock_quantity','$width','$styles','$colors','$description','$packaging','$status','$shipping_description', '$delivery_time_text', '$returns_text', '$care_instructions', '$main_image', '$sub_images_json')";

if (mysqli_query($conn, $sql)) {
    $response["success"] = true;
} else {
    $response["message"] = mysqli_error($conn);
}

$response["success"] = true;

if($response["success"] == true)
{
	echo "<script>alert('Product saved!')</script>";
	echo "<script>location.href='products.php'</script>";
}
else
{
	echo "<script>alert('Unable to save product!')</script>";
	echo "<script>location.href='products.php'</script>";
}


//echo json_encode($response);

}
?>
