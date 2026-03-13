<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$date_time = (new \DateTime())->format('Y-m-d H:i:s'); 

$data = json_decode(file_get_contents("php://input"), true);

session_start();

$name  = mysqli_real_escape_string($conn, $data['name'] ?? '');
$email = mysqli_real_escape_string($conn, $data['email'] ?? '');
$google_id = mysqli_real_escape_string($conn, $data['id'] ?? '');

if($email == ''){
    echo json_encode(["status" => "error"]);
    exit;
 }
 
$check = mysqli_query($conn, "SELECT * FROM user_account WHERE email='$email'");

if(mysqli_num_rows($check) > 0){

    $user = mysqli_fetch_assoc($check);
	 if($user['google_id'] == ''){
        mysqli_query($conn, "UPDATE user_account SET google_id='$google_id' WHERE userID=".$user['userID']);
    }
	
    $_SESSION['userID'] = $user['userID'];

} else {

    mysqli_query($conn, "INSERT INTO user_account(role,name,email,status,created_datetime) VALUES('customer','$name','$email','ACTIVE','$date_time')");

    $_SESSION['userID'] = mysqli_insert_id($conn);
}

echo json_encode([
    "status" => "success"
]);
