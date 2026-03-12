<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

// $host="localhost";
// $user="mookhuth_mookuthipalace";
// $password="WD;DWd@B-9~6x0%L";
// $database="mookhuth_mookuthipalace";

$host="localhost";
$user="root";
$password="";
$database="mookuthipalace";

$conn=mysqli_connect($host, $user, $password, $database);

// if ($conn->connect_error) {
  // die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";
?>