<html>
<!--ORIGINAL FILE AT BACKUP FILE-->
<?php
session_start();
include("dbconnect.php");
?>
<head>
<title>Payment</title>
  <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

     <link rel="stylesheet" href="css/bootstrap.min.css">
     <link rel="stylesheet" href="css/font-awesome.min.css">
     <link rel="stylesheet" href="css/animate.css">
     <link rel="stylesheet" href="css/owl.carousel.css">
     <link rel="stylesheet" href="css/owl.theme.default.min.css">
     <link rel="stylesheet" href="css/magnific-popup.css">
	
     <!-- MAIN CSS -->
     <link rel="stylesheet" href="css/templatemo-style.css">
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<?php
// success payment

/*
 echo "<pre>";
echo "GET Data";
print_r($_GET);
echo "POST Data";
print_r($_POST);
echo "<pre>";
exit; 
*/


$billcode="";
$order_id="";
$status_id="";

if(isset($_GET['billcode'],$_GET['order_id'],$_GET['status_id'])){
	$billcode = $_GET['billcode'];
	$order_id = $_GET['order_id'];
	$status_id = $_GET['status_id'];
	
	
	$result_getalldata = mysqli_query($conn,"select * from payment_transaction where bill_code = '".$billcode."'");
	$row=mysqli_fetch_assoc($result_getalldata);
	$payment_transactionID = $row['payment_transactionID'];
	$ordercode = $row['ordercode'];
	
	 if($status_id == "1"){
		 
		 
		 mysqli_query($conn,"update payment_transaction set payment_status = 'Paid' where payment_transactionID = $payment_transactionID");
		 unset($_SESSION['customer_code']);
		 	 
		// echo "<script>
			 // swal('Success!', 'Payment is successfull.', 'success').then(okay => {
		   // if (okay) {
					// window.location.href ='https://mookhuthipalace.com/demo/confirmation.php?ordercode=$ordercode';
				// }else{
					// window.location.href ='https://mookhuthipalace.com/demo/profile.php';
				// }
			// });
		 // </script>";
		 
		 
		echo "<script>
			 swal('Success!', 'Payment is successfull.', 'success').then(okay => {
		   if (okay) {
					window.location.href ='http://localhost/mookhuthipalace/confirmation.php?ordercode=$ordercode';
				}else{
					window.location.href ='http://localhost/mookhuthipalace/profile.php';
				}
			});
		 </script>";
		
	
	}else if($status_id == "2"){
		
		mysqli_query($conn,"update payment_transaction set payment_status = 'Pending' where payment_transactionID = $payment_transactionID");
		unset($_SESSION['customer_code']);
		
		// echo "<script>
			// swal('Pending!', 'Payment is panding. Kindly wait for our administrator contact you. Thank you.', 'info').then(okay => {
		   // if (okay) {
					// window.location.href ='https://mookhuthipalace.com/demo/profile.php';
				// }else{
					// window.location.href ='https://mookhuthipalace.com/demo/profile.php';
				// }
			// });
		 // </script>"; 
		
		
		echo "<script>
			swal('Pending!', 'Payment is panding. Kindly wait for our administrator contact you. Thank you.', 'info').then(okay => {
		   if (okay) {
					window.location.href ='http://localhost/mookhuthipalace/profile.php';
				}else{
					window.location.href ='http://localhost/mookhuthipalace/profile.php';
				}
			});
		 </script>";  
	
		
	}else if($status_id == "3"){
		
		mysqli_query($conn,"update payment_transaction set payment_status = 'Failed' where payment_transactionID = $payment_transactionID");
		unset($_SESSION['customer_code']);
		
		// echo "<script>
			// swal('Fail!', 'Trasaction fail.', 'warning').then(okay => {
		   // if (okay) {
					// window.location.href ='https://mookhuthipalace.com/demo/profile.php';
				// }else{
					// window.location.href ='https://mookhuthipalace.com/demo/profile.php';
				// }
			// });
		 // </script>";  
		 
		 echo "<script>
			swal('Fail!', 'Trasaction fail.', 'warning').then(okay => {
		   if (okay) {
					window.location.href ='http://localhost/mookhuthipalace/profile.php';
				}else{
					window.location.href ='http://localhost/mookhuthipalace/profile.php';
				}
			});
		 </script>";  
	
		
	}else{
		
		//echo "<script>location.href='https://mookhuthipalace.com/demo/profile.php'</script>";
		echo "<script>location.href='http://localhost/mookhuthipalace/profile.php'</script>";
	}  
	

}



//server
//echo "<script>location.href='https://www.ezfastbites.com/'</script>";
//'https://www.mahvir.com/index.php'

//local
//http://localhost:8080/mahvir/index.php
//window.location.href ='http://localhost/kpEnterprise/acacia/menu_list.php#menu1';
//window.location.href = 'http://localhost/kpEnterprise/acacia/purchase-order.php';
//or
//echo "<script>location.href='http://localhost/kpEnterprise/acacia/index.php'</script>";
?>

</body>
</html>