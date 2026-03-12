<?php
//************************ORIGINAL FILE AT BACKUP FILE************************
session_start();
include("dbconnect.php");
$datetimes = date("Y-m-d h:i:s");

$name="";$email="";$contact="";$odrcode="";
$gt=0; 
$billamount= 0;
$submit_to_merchant = 0;
$secret_key=""; $category_code="";
if(isset($_GET['userID'],$_GET['invoiceID'],$_GET['gt'])){
	
 $userID = $_GET['userID'];
 $odrcode = $_GET['invoiceID'];
 $gt = $_GET['gt'];
 $channel = "0";
 //get split amount 
 $billamount = ($gt * 100);
 
 
	//Get User Detail
	$result=mysqli_query($conn,"select * from user_account where userID = $userID");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$name = $row['name'];
		$email = $row['email'];
		$contact = $row['phone'];
	}
 
	//Get key
	$result=mysqli_query($conn,"select * from payment_gateway where status = 'ACTIVE'");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$secret_key = $row['secret_key'];
		$category_code = $row['category_code'];
		$submit_to_merchant = 1;
	}
	
	//Get Channel
	$result=mysqli_query($conn,"SELECT payment_method FROM checkout where order_code = '$odrcode' limit 1");
	if(mysqli_num_rows($result) > 0)
	{
		$row_payment_method = mysqli_fetch_assoc($result);
		$payment_method = $row_payment_method['payment_method'];
		if($payment_method == "CARD")
		{
			$channel = "2";
		}
		
	}
 
 }
 

if($submit_to_merchant == 1)
{
	
	$some_data = array(
    'userSecretKey'=>$secret_key,
    'categoryCode'=>$category_code, 
    'billName'=>'Mookuthi Palace',
    'billDescription'=>'E-Commerce',
    'billPriceSetting'=>1,
    'billPayorInfo'=>0,
    'billAmount'=>$billamount,
    'billReturnUrl'=>'http://localhost/mookhuthipalace/toyyibpay/Response.php',
	'billCallbackUrl'=>'http://localhost/mookhuthipalace/index.php',
	//'billReturnUrl'=>'https://mookhuthipalace.com/demo/toyyibpay/Response.php',
	//'billCallbackUrl'=>'https://mookhuthipalace.com/demo/index.php',
    'billExternalReferenceNo' => $odrcode,
    'billTo'=>$name,
    'billEmail'=>$email,
    'billPhone'=>$contact,
    'billSplitPayment'=>'1',
    'billSplitPaymentArgs'=>'',
    'billPaymentChannel'=> $channel, //'0',
    'billContentEmail'=>'Dear '.$name.', <br/><p>Invoice No:'.$odrcode.',</p><p>Paid Amount:RM'.number_format((float)$gt, 2,'.', '').'</p>Thank you for your payment. <br/> <p>Regards,<br/>Mookuthi Palace</p>',
    'billChargeToCustomer'=>1
  );  

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');
  //curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

  $result = curl_exec($curl);
  $info = curl_getinfo($curl);  
  curl_close($curl);
  $result = json_decode($result, true);
  
  $post_data['billCode'] = $result[0]['BillCode'];
  $post_data['paymentURL'] = 'https://dev.toyyibpay.com/'.$result[0]['BillCode'];
  //$post_data['paymentURL'] = 'https://toyyibpay.com/'.$result[0]['BillCode'];
  
  //insert payment
  $date_time=(new \DateTime())->format('Y-m-d H:i:s'); 
  
  $amount = $billamount / 100;
  $filterbillamount = number_format((float)$amount, 2,'.', '');
  
 
  
  $check_payment_transaction=mysqli_query($conn,"select * from payment_transaction where ordercode = '$odrcode'");
  if(mysqli_num_rows($check_payment_transaction) > 0)
  {
	  if($channel == "0")
	  {
		  $using_channel = "BANK";
	  }
	  else
	  {
		  $using_channel = "CARD";
	  }
	  
	  $sql01 ="update payment_transaction set bill_code = '".$result[0]['BillCode']."', channel = '$using_channel', billamount = '$filterbillamount', payment_date='$datetimes' where ordercode = '$odrcode'";
		if(mysqli_query($conn,$sql01))
		{
			
					 header('Location: '.$post_data['paymentURL']); 
		   
		}
  }
  else
  {
	  
	  if($channel == "0")
	  {
		  $using_channel = "BANK";
	  }
	  else
	  {
		  $using_channel = "CARD";
	  }
	  
	  $sql01 ="Insert into payment_transaction(userID, bill_code, ordercode, name, email, channel, billamount, payment_date) 
		VALUES ($userID,'".$result[0]['BillCode']."','$odrcode','$name','$email','$using_channel','$filterbillamount','$datetimes')";
		if(mysqli_query($conn,$sql01))
		{
			
					 header('Location: '.$post_data['paymentURL']); 
		   
		}
	  
  }
  	
  
	
}
else
{
	echo "<script>alert('Unable to do transaction');</script>";
	echo "<script>location.href='index.php'</script>";
}


	