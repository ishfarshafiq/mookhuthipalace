<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$date_time = (new \DateTime())->format('Y-m-d H:i:s'); 

$data = json_decode(file_get_contents("php://input"), true);

$email = mysqli_real_escape_string($conn, $data['email'] ?? '');
$facebook_id = mysqli_real_escape_string($conn, $data['id'] ?? '');

if($email == ''){
    echo json_encode(["status" => "error"]);
    exit;
}

// Check if user exists with this email + facebook_id
$check = mysqli_query($conn, "SELECT * FROM user_account WHERE email='$email' AND facebook_id='$facebook_id' and status = 'ACTIVE'");

if(mysqli_num_rows($check) > 0){

    $user = mysqli_fetch_assoc($check);
    $_SESSION['userID'] = $user['userID'];

    echo json_encode(["status" => "success"]);

} else {
    echo json_encode(["status" => "not_found"]);
}
?>