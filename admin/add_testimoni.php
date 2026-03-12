<?php
include("dbconnect.php");

$response = ["success" => false, "message" => "Unknown error"];

if(isset($_POST['addTestimoni'])) {
	
$uploadDir = "uploads/testimoni/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$testimoni_image = "";
if (!empty($_FILES['image']['name'])) {
    $testimoni_image = time() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir . $testimoni_image);
}


$sql = "INSERT INTO testimoni (image) VALUES ('$testimoni_image')";

if (mysqli_query($conn, $sql)) {
    $response["success"] = true;
} else {
    $response["message"] = mysqli_error($conn);
}

$response["success"] = true;

if($response["success"] == true)
{
	echo "<script>alert('Testimoni saved!')</script>";
	echo "<script>location.href='testimoni.php'</script>";
}
else
{
	echo "<script>alert('Unable to save testimoni!')</script>";
	echo "<script>location.href='testimoni.php'</script>";
}


//echo json_encode($response);

}
?>
