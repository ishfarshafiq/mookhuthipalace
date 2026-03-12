<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

$name=""; $email=""; $phone="";$dob="";$member_since="";$password="";
$billing_first_name = ""; $billing_last_name = ""; $billing_phone = ""; $billing_address = ""; $billing_city = ""; $billing_state = ""; $billing_postcode = "";
$has_billing_address = 0;$is_profile=0;
if(isset($_SESSION['userID']))
{
	$userID = $_SESSION['userID'];
	$status="INACTIVE";
	$result=mysqli_query($conn,"select * from user_account where userID = $userID and role = 'customer' and status = 'ACTIVE'");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$name = $row['name'];
		$email = $row['email'];
		$phone = $row['phone'];
		$dob = $row['dob'];
		$password = $row['password'];
		if($row['profile']!="")
		{
			$is_profile=1;
		}
		$profile = !empty($row['profile'])  ? "uploads/".$row['profile'] : "img/happy.png";
		
		
		
		$status = $row['status'];
		$member_since = $row['created_datetime'];
	
		$check_billing_address=mysqli_query($conn,"select * from billing_address where email = '$email'");
		if(mysqli_num_rows($check_billing_address) > 0)
		{
			$has_billing_address  = 1;
			$row_billing_address = mysqli_fetch_assoc($check_billing_address);
			$billing_first_name = $row_billing_address['first_name'];
			$billing_last_name = $row_billing_address['last_name'];
			$billing_phone = $row_billing_address['phone'];
			$billing_address = $row_billing_address['address'];
			$billing_city = $row_billing_address['city'];
			$billing_state = $row_billing_address['state'];
			$billing_postcode = $row_billing_address['postcode'];
		}
		
	
	}
	else
	{
		echo "<script>window.location.href = 'logout.php';</script>";
	}
}
else
{
	echo "<script>window.location.href = 'logout.php';</script>";
}

$customer_code=""; 
if(isset($_SESSION['customer_code']))
{ 
	$customer_code = $_SESSION['customer_code']; 
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/profile.js"></script>
	
	<link href="css/style_profile.css" rel="stylesheet" type="text/css" />
	
	<style>
	.profile-avatar {
			width: 120px;
			height: 120px;
			margin: auto;
		}

		.profile-img {
			width: 100%;
			height: 100%;
			border-radius: 50%;
			object-fit: cover;   /* 🔥 prevents stretching */
			border: 3px solid #fff; /* optional nice border */
		}
	</style>
  <script>
	function handleLogout() {
		window.location.href = "logout.php";
	}
	
	function previewImages(event) {
		var file = event.target.files[0];

		if (file) {
			var output = document.getElementById('outputPreview1');
			output.src = URL.createObjectURL(file);
			output.onload = function () {
				URL.revokeObjectURL(output.src); // free memory
			}
		}
	}
</script>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
   
   <?php include_once('includes/side_bar.php'); ?>
   
	<!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">👤 My Profile</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="padding-bottom: 5rem;">
	
		<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
		<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
	
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
					<img class="profile-img" width="120" src="<?php echo $profile;?>">
				</div>
                <div class="profile-name"><?php echo $name;?></div>
                <div class="profile-email"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                        data-cfemail="e09092899981ce938881928d81a0858d81898cce838f8d"><?php echo $email; ?></a></div>

                <ul class="sidebar-menu">
                    <li><button class="menu-btn active" data-section="account"><i
                                class="fas fa-user-circle me-2"></i>Account Details</button></li>
                    <li><button class="menu-btn" data-section="purchases"><i class="fas fa-shopping-bag me-2"></i>My
                            Purchases</button></li>
                    <li><button class="menu-btn" data-section="billing"><i
                                class="fas fa-map-marker-alt me-2"></i>Addresses</button></li>
                </ul>

                <button class="logout-btn" onclick="handleLogout()">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </div>

            <!-- Content Area -->
            <div class="profile-content">
                <!-- Account Details -->
                <div class="content-section active" id="account">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Account Details
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value"><?php echo $name;?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="9fefedf6e6feb1ecf7feedf2fedffaf2fef6f3b1fcf0f2"><?php echo $email; ?></a>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value"><?php echo $phone; ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Date of Birth</div>
                        <div class="detail-value"><?php if($dob!=""){ echo date("d F Y", strtotime($dob)); } ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value"><?php if($member_since!=""){ echo date("F Y", strtotime($member_since)); } ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Account Status</div>
						<div class="detail-value" style="color: <?= ($status == 'ACTIVE') ? '#4ade80' : '#ef4444'; ?>;">
									<i class="fas <?= ($status == 'ACTIVE') ? 'fa-check-circle' : 'fa-times-circle'; ?> me-2"></i>
									<?= ($status == 'ACTIVE') ? 'Active' : 'Inactive'; ?>
								</div>
                    </div>

                    <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editAccountModal"> <i class="fas fa-edit me-2"></i> Edit Account Details</button>
                </div>

                <!-- My Purchases -->
                <div class="content-section" id="purchases">
                    <div class="section-title">
                        <i class="fas fa-shopping-history"></i> My Purchases
                    </div>
					
					 <?php
						$oder_sum_subtotal=0;$is_delivered="";
						$sql = "
								SELECT a.ordercode,
									   c.delivery_method,
									   c.payment_method,
									   c.collecting_point,
									   a.payment_date,
									   a.is_delivered,
									   SUM(b.quantity * b.price) AS order_total,
									   SUM(b.quantity) AS total_items,
									   MAX(b.ordersID) AS last_orderID
								FROM payment_transaction a
								INNER JOIN orders b ON a.ordercode = b.order_code
								INNER JOIN checkout c ON b.order_code = c.order_code
								WHERE a.userID = $userID
								GROUP BY a.ordercode,
										 c.delivery_method,
										 c.payment_method,
										 c.collecting_point,
										 a.payment_date,
										 a.is_delivered
								ORDER BY last_orderID DESC
								";
						$result = mysqli_query($conn,$sql);
						while($row = mysqli_fetch_assoc($result)) {
							$delivery_fee = 0;
							$order_total = $row['order_total'];
							$total_items = $row['total_items'];
							$payment_date = $row['payment_date'];
							if($row['is_delivered'] == "Delivered")
							{
								$is_delivered = "<div class='purchase-status status-delivered'><i class='fas fa-check-circle me-1'></i>Delivered</div>";
							}
							else if($row['is_delivered'] == "Shipped")
							{
								$is_delivered = "<div class='purchase-status status-processing'><i class='fas fa-shipping-fast me-1'></i>Shipped</div>";
							}
							else if($row['is_delivered'] == "Cancelled")
							{
								$is_delivered = "<div class='purchase-status status-cancelled'><i class='fas fa-times-circle text-danger me-1'></i>Cancelled</div>";
							}
							else
							{
								$is_delivered = "<div class='purchase-status status-processing'><i class='fas fa-shipping-fast me-1'></i>Processing</div>";
							}
							
							if($row['delivery_method'] == "standard")
							{
								$delivery_fee = 8;
							}
							
					?>

						<div class="purchase-item">
							<div class="purchase-header">
								<div class="purchase-id">Order #<?php echo $row['ordercode'];?></div>
								<?php echo $is_delivered;?>
							</div>
							<div class="purchase-details">
								<div><strong>Date:</strong> <?php echo date("F ,d  Y", strtotime($payment_date))?></div>
								<div><strong>Amount:</strong> RM <?php echo number_format((float)$order_total + $delivery_fee, 2, '.', '');?></div>
								<div><strong>Items:</strong> <?php echo $total_items;?> Products</div>
								<div><strong>Delivery Method:</strong> <?php echo $row['delivery_method'];?></div>
							</div>
							<button class="btn-view-order" onclick="viewOrders('<?php echo $row['ordercode']; ?>')">
										<i class="fas fa-eye me-1"></i>View Order
									</button>
							
						</div>
					<?php
						}
					?>
                </div>

                <!-- Addresses (Combined Billing & Shipping) -->
                <div class="content-section" id="billing">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Manage Addresses
                    </div>

                    <!-- Address Tabs -->
                    <ul class="nav nav-tabs" id="addressTabs" role="tablist"
                        style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.25); margin-bottom: 2rem;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="billing-tab" data-bs-toggle="tab"
                                data-bs-target="#billingTab" type="button" role="tab" aria-controls="billingTab"
                                aria-selected="true"
                                style="color: var(--mp-text); border: none; border-bottom: 2px solid transparent; padding: 0.75rem 1.5rem; transition: all 0.3s ease;">
                                <i class="fas fa-credit-card me-2"></i>Billing Address
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab"
                                data-bs-target="#shippingTab" type="button" role="tab" aria-controls="shippingTab"
                                aria-selected="false"
                                style="color: var(--mp-text); border: none; border-bottom: 2px solid transparent; padding: 0.75rem 1.5rem; transition: all 0.3s ease;">
                                <i class="fas fa-truck me-2"></i>Shipping Address
                            </button>
                        </li>
                    </ul>

                    <style>
                        .nav-link.active {
                            color: var(--mp-gold) !important;
                            border-bottom-color: var(--mp-gold) !important;
                        }

                        .nav-link:hover {
                            color: var(--mp-gold-soft) !important;
                        }
                    </style>

                    <!-- Tab Content -->
                    <div class="tab-content" id="addressTabsContent">
                       
					   <!-- Billing Address Tab -->
                        <div class="tab-pane fade show active" id="billingTab" role="tabpanel"
                            aria-labelledby="billing-tab">
                            <div class="address-grid">
                                <?php
								if($has_billing_address == 1)
								{
									?>
									<div class="address-card">
										<div class="address-title"><i class="fas fa-map-pin"></i> Billing Address
										</div>
										<div class="address-text">
											<?php echo $billing_first_name." ".$billing_last_name; ?><br>
											<?php echo $billing_address.",<br>".$billing_postcode." ".$billing_city.", <br>".$billing_state.","; ?><br>
											Malaysia<br>
											<?php echo $billing_phone; ?>
										</div>
										<div class="address-actions">
											<button class="btn-small" data-bs-toggle="modal" data-bs-target="#editAddressModal">
												<i class="fas fa-edit me-1"></i>Edit
											</button>
										</div>
									</div>
									<?php
								}
								else
								{
									?>
									 <div class="address-card">
										<div class="address-title"><i class="fas fa-plus"></i> Add New Address</div>
										<div class="address-text">
											Click the button below to add a new billing address for your account.
										</div>
										<div class="address-actions">
											<button class="btn-small" data-bs-toggle="modal" data-bs-target="#addBillingAddressModal" onclick="loadBillingAddress(<?php echo $userID;?>)"><i
													class="fas fa-plus me-1"></i>Add Address
											</button>
										</div>
									</div>
									<?php
								}
								?>
								

                               
                            
							</div>
                        </div>

                        <!-- Shipping Address Tab -->
                        <div class="tab-pane fade" id="shippingTab" role="tabpanel" aria-labelledby="shipping-tab">
                            <div class="address-grid">
                               
							   <?php
							   $i=1;
							   $result_shipping_list = mysqli_query($conn, "SELECT b.* FROM customer a 
																inner join shipping_address b on a.customer_code = b.customer_code
																where a.userID = $userID and b.is_delete = '0';");

								if(mysqli_num_rows($result_shipping_list) > 0)
								{
									while($row_shipping = mysqli_fetch_assoc($result_shipping_list)){
									$shipping_addressID = $row_shipping['shipping_addressID'];
									$shipping_name = $row_shipping['name'];
									$shipping_address = $row_shipping['address_line1'].",".$row_shipping['address_line2'];
									$shipping_postcode = $row_shipping['postcode'];
									$shipping_city = $row_shipping['city'];
									$shipping_state = $row_shipping['state'];
									$shipping_phone = $row_shipping['phone'];
									$is_active = $row_shipping['is_active'];
							   ?>
								   <div class="address-card">
    
										<div class="address-title d-flex justify-content-between align-items-center">
											<div>
												<input type="radio" onchange="updateActiveShippingAddress(this, <?= $shipping_addressID ?>)" name="selected_shipping_address" value="<?php echo $shipping_addressID; ?>" style="margin-right:8px;" <?php if($is_active == "1") echo "checked"; ?>>
												<i class="fas fa-map-pin"></i> Shipping Address <?php echo $i; ?>
											</div>
										</div>

										<div class="address-text">
											<?php echo $shipping_name; ?><br>
											<?php echo $shipping_address.", <br>".$shipping_postcode." ".$shipping_city.", <br>".$shipping_state; ?><br>
											Malaysia<br>
											<?php echo $shipping_phone;?>
										</div>

										<div class="address-actions">
											<button class="btn-small" 
													data-bs-toggle="modal" 
													data-bs-target="#editShippingAddressModal" 
													onclick="openShippingEditAddress(<?php echo $shipping_addressID; ?>)">
												<i class="fas fa-edit me-1"></i>Edit
											</button>

											<button class="btn-small" 
													onclick="deleteShippingAddress(<?php echo $shipping_addressID; ?>)">
												<i class="fas fa-trash me-1"></i>Delete
											</button>
										</div>

									</div>


								<?php
										$i++;
									}
									
									
								
								}
								?>
								<div class="address-card">
										<div class="address-title"><i class="fas fa-plus"></i> Add New Address</div>
										<div class="address-text">
											Click the button below to add a new shipping address for your account.
										</div>
										<div class="address-actions">
											<button class="btn-small" data-bs-toggle="modal" data-bs-target="#addShippingAddressModal">
												<i class="fas fa-plus me-1"></i>Add Address
											</button>
										</div>
									</div>
                            </div>
                        </div>
						
						
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Details Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAccountLabel"><i class="fas fa-user-circle me-2"></i>Edit Account Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editAccountForm" enctype="multipart/form-data">
					
                        <div class="mb-3">
                            <label for="fullName" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $name;?>"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone;?>"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php if($dob != ""){ echo date("Y-m-d", strtotime($dob)); } ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						
						<div class="mb-3">
							<label for="password" class="form-label" style="color: var(--mp-muted); font-weight: 600;"> Password</label>

							<div class="input-group">
								<input type="password" class="form-control" id="password" name="password" value="<?php echo $password;?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px 0 0 6px; padding: 0.75rem;">

								<span class="input-group-text" id="togglePassword" style="cursor: pointer; background: white; border: 0.5px solid rgba(212, 175, 55, 0.25); border-left: none;">
									<i class="fas fa-eye"></i>
								</span>
							</div>
						</div>
						
						<div class="mb-3"><!--New upload profile-->
                            <label for="profile" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Profile Picture</label>
                            <input type="file" onchange="previewImages(event)" class="form-control" id="profile" name="profile" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						
						<div class="mb-3">
							<div class="profile-avatar">
								<img class="profile-img" id="outputPreview1" width="120" src="<?php echo $profile;?>">
							</div>
							
							<?php
							if($is_profile == 1){
							?>
								<center><a style="color:red;" onclick="deleteProfileImage(<?php echo $userID;?>)">Delete</a></center>
							<?php
							}
							?>
						</div>
						
						
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAccountDetails()"style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
	
	 <!-- Add Billing Address Modal -->
    <div class="modal fade" id="addBillingAddressModal" tabindex="-1" aria-labelledby="addBillingAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAddressLabel"><i class="fas fa-edit me-2"></i>Add Billing Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editAddressForm">
                        <div class="mb-3">
                            <label for="billing_first_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">First Name</label>
							<input type="text" class="form-control" id="addbilling_first_name" name="addbilling_first_name" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						 <div class="mb-3">
                            <label for="billing_last_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Last Name</label>
							<input type="text" class="form-control" id="addbilling_last_name" name="addbilling_last_name" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="billing_address" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address</label>
                            <input type="text" class="form-control" id="addbilling_address" name="addbilling_address">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label" style="color: var(--mp-muted); font-weight: 600;">City</label>
                                <input type="text" class="form-control" id="addbilling_city" name="addbilling_city" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_state" class="form-label" style="color: var(--mp-muted); font-weight: 600;">State</label>
								  <select class="form-control" id="addbilling_state" name="addbilling_state">
									<option value="">-- Select State --</option>
									<option value="Johor">Johor</option>
									<option value="Kedah">Kedah</option>
									<option value="Kelantan">Kelantan</option>
									<option value="Melaka">Melaka</option>
									<option value="Negeri Sembilan">Negeri Sembilan</option>
									<option value="Pahang">Pahang</option>
									<option value="Perak">Perak</option>
									<option value="Perlis">Perlis</option>
									<option value="Pulau Pinang">Pulau Pinang</option>
									<option value="Sabah">Sabah</option>
									<option value="Sarawak">Sarawak</option>
									<option value="Selangor">Selangor</option>
									<option value="Terengganu">Terengganu</option>
									<option value="Kuala Lumpur">Kuala Lumpur (FT)</option>
									<option value="Putrajaya">Putrajaya (FT)</option>
									<option value="Labuan">Labuan (FT)</option>
								</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_postcode" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Postcode</label>
                                <input type="text" class="form-control" id="addbilling_postcode" name="addbilling_postcode" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Country</label>
                                <input type="text" class="form-control" id="addcountry" name="addcountry" value="Malaysia" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;" readonly>
                            </div>
                        </div>
						
                        <div class="mb-3">
                            <label for="billing_phone" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="addbilling_phone" name="addbilling_phone" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;" >
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addBillingAddress()" style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Address
					</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Billing Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAddressLabel"><i class="fas fa-edit me-2"></i>Edit Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editAddressForm">
                        <div class="mb-3">
                            <label for="billing_first_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">First Name</label>
							<input type="text" class="form-control" id="billing_first_name" name="billing_first_name" value="<?php echo $billing_first_name; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						 <div class="mb-3">
                            <label for="billing_last_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Last Name</label>
							<input type="text" class="form-control" id="billing_last_name" name="billing_last_name" value="<?php echo $billing_last_name; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="billing_address" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address</label>
                            <input type="text" class="form-control" id="billing_address" name="billing_address" value="<?php echo $billing_address; ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label" style="color: var(--mp-muted); font-weight: 600;">City</label>
                                <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo $billing_city; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_state" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">State</label>
                                <!--<input type="text" class="form-control" id="billing_state" name="billing_state" value="<?php //echo $billing_state; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">-->
                            
							 <select class="form-control" id="billing_state" name="billing_state">
                                <option value="">-- Select State --</option>
                                <option value="Johor" <?php if($billing_state == "Johor") echo "selected"; ?> >Johor</option>
                                <option value="Kedah" <?php if($billing_state == "Kedah") echo "selected"; ?> >Kedah</option>
                                <option value="Kelantan" <?php if($billing_state == "Kelantan") echo "selected"; ?> >Kelantan</option>
                                <option value="Melaka" <?php if($billing_state == "Melaka") echo "selected"; ?> >Melaka</option>
                                <option value="Negeri Sembilan" <?php if($billing_state == "Negeri Sembilan") echo "selected"; ?> >Negeri Sembilan</option>
                                <option value="Pahang" <?php if($billing_state == "Pahang") echo "selected"; ?> >Pahang</option>
                                <option value="Perak" <?php if($billing_state == "Perak") echo "selected"; ?> >Perak</option>
                                <option value="Perlis" <?php if($billing_state == "Perlis") echo "selected"; ?> >Perlis</option>
                                <option value="Pulau Pinang" <?php if($billing_state == "Pulau Pinang") echo "selected"; ?> >Pulau Pinang</option>
                                <option value="Sabah" <?php if($billing_state == "Sabah") echo "selected"; ?> >Sabah</option>
                                <option value="Sarawak" <?php if($billing_state == "Sarawak") echo "selected"; ?> >Sarawak</option>
                                <option value="Selangor" <?php if($billing_state == "Selangor") echo "selected"; ?> >Selangor</option>
                                <option value="Terengganu" <?php if($billing_state == "Terengganu") echo "selected"; ?> >Terengganu</option>
                                <option value="Kuala Lumpur" <?php if($billing_state == "Kuala Lumpur") echo "selected"; ?> >Kuala Lumpur (FT)</option>
                                <option value="Putrajaya" <?php if($billing_state == "Putrajaya") echo "selected"; ?> >Putrajaya (FT)</option>
                                <option value="Labuan" <?php if($billing_state == "Labuan") echo "selected"; ?> >Labuan (FT)</option>
                            </select>
							
							</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_postcode" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Postcode</label>
                                <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" value="<?php echo $billing_postcode; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="Malaysia" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="billing_phone" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo $billing_phone; ?>" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveBillingAddress()" style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Address
					</button>
                </div>
            </div>
        </div>
    </div>
	
	   <!-- Edit Shipping Address Modal -->
    <div class="modal fade" id="editShippingAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAddressLabel"><i class="fas fa-edit me-2"></i>Edit Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editShippingAddressForm">
					
						<input type="hidden" class="form-control" id="shipping_addressID" name="shipping_addressID" value="" /> 
					
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Name</label>
							<input type="text" class="form-control" id="shipping_name" name="shipping_name" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address1" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address Line 1</label>
                            <input type="text" class="form-control" id="shipping_address1" name="shipping_address1" value="">
                        </div>
						<div class="mb-3">
                            <label for="shipping_address2" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address Line 2</label>
                            <input type="text" class="form-control" id="shipping_address2" name="shipping_address2" value="">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_city" class="form-label" style="color: var(--mp-muted); font-weight: 600;">City</label>
                                <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_state" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">State</label>
                                <input type="text" class="form-control" id="shipping_state" name="shipping_state" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_postcode" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Postcode</label>
                                <input type="text" class="form-control" id="shipping_postcode" name="shipping_postcode" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="Malaysia" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveShippingAddress()" style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Address
					</button>
                </div>
            </div>
        </div>
    </div>


  <!-- Add Shipping Address Modal -->
    <div class="modal fade" id="addShippingAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAddressLabel"><i class="fas fa-edit me-2"></i>Add Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="addShippingAddressForm">
				
					
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Name</label>
							<input type="text" class="form-control" id="add_shipping_name" name="add_shipping_name" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address1" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address Line 1</label>
                            <input type="text" class="form-control" id="add_shipping_address1" name="add_shipping_address1" value="">
                        </div>
						<div class="mb-3">
                            <label for="shipping_address2" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Address Line 2</label>
                            <input type="text" class="form-control" id="add_shipping_address2" name="add_shipping_address2" value="">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_city" class="form-label" style="color: var(--mp-muted); font-weight: 600;">City</label>
                                <input type="text" class="form-control" id="add_shipping_city" name="add_shipping_city" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_state" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">State</label>
                                <input type="text" class="form-control" id="add_shipping_state" name="add_shipping_state" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_postcode" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Postcode</label>
                                <input type="text" class="form-control" id="add_shipping_postcode" name="add_shipping_postcode" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Country</label>
                                <input type="text" class="form-control" id="add_country" name="add_country" value="Malaysia" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        
						<div class="mb-3">
                            <label for="add_shipping_email" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Email</label>
                            <input type="email" class="form-control" id="add_shipping_email" name="add_shipping_email" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						
						<div class="mb-3">
                            <label for="shipping_phone" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="add_shipping_phone" name="add_shipping_phone" value="" style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
						
						
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addShippingAddress()" style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Address
					</button>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <div>
                        <h5 class="modal-title" style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700; margin-bottom: 0.25rem;font-size: 1.4rem;" id="orderDetailsLabel">Order Details</h5>
                        <p style="color: var(--mp-muted); font-size: 1.5rem; margin: 0;" id="modalOrderNumber"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text); max-height: 70vh; overflow-y: auto;">
                    <!-- Order Status -->
                    <div
                        style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem; font-size: 1.4rem;">Order Status</h6>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div id="modalStatusIcon"
                                style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            </div>
                            <div>
                                <p style="margin: 0; font-weight: 600; font-size: 1.4rem;" id="modalStatusText"></p>
                                <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.9rem;" id="modalStatusDate"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1.1rem; font-size: 1.4rem;">Items Ordered</h6>
                        <div id="modalOrderItems" style="display: flex; flex-direction: column; gap: 1rem; font-size: 1.4rem;"></div>
                    </div>

                    <!-- Order Summary -->
                    <div style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1.1rem; font-size: 1.4rem;">Order Summary</h6>
                        <div style="display: grid; gap: 0.75rem; font-size: 1.4rem;">
                            <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);font-size: 1.4rem;">
                                <span style="color: var(--mp-muted);">Subtotal</span>
                                <span id="modalSubtotal" style="font-weight: 600; font-size: 1.4rem;"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);font-size: 1.4rem;">
                                <span style="color: var(--mp-muted);">Shipping</span>
                                <span id="modalShipping" style="font-weight: 600; font-size: 1.4rem;"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                                <span style="color: var(--mp-muted);">Tax</span>
                                <span id="modalTax" style="font-weight: 600; font-size: 1.4rem;"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 0.75rem; color: var(--mp-gold); font-weight: 700; font-size: 1.4rem;">
                                <span>Total Amount</span>
                                <span id="modalTotalAmount"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem; font-size: 1.4rem;">Delivery Information
                        </h6>
						
						<div>
                            <p style="color: var(--mp-gold); margin: 0 0 0.5rem 0; font-size: 1.4rem;">Delivery Type</p>
                            <p style="margin: 0; line-height: 1.6; font-size: 1.4rem;" id="modalDeliveryType"></p>
                        </div>
						<br/>
                        <div>
                            <p style="color: var(--mp-gold); margin: 0 0 0.5rem 0;font-size: 1.4rem;">Delivery Address</p>
                            <p style="margin: 0; line-height: 1.6; font-size: 1.4rem;" id="modalDeliveryAddress"></p>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Close</button>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>About Us</h5>
                    <p style="color: #bbb; font-size: 0.95rem;">Mookhuthi Palace - Your destination for authentic,
                        luxurious Indian nose pins since 2015.</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="products.php">Shop</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>Customer Service</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Track Order</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section">
                    <h5>Connect With Us</h5>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#"><i class="fab fa-facebook-f"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-instagram"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-youtube"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                    </div>
                </div>
            </div>
            <div
                style="border-top: 0.5px solid var(--mp-border); padding-top: 2rem; margin-top: 2rem; text-align: center; color: var(--mp-muted); font-size: 0.9rem;">
                <p>&copy; 2026 Mookhuthi Palace. All rights reserved. | Privacy Policy | Terms & Conditions</p>
            </div>
        </div>
    </footer>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	
	
	
	
    <script>
        // Toggle Menu Functionality
        const toggleMenu = document.getElementById('toggleMenu');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        toggleMenu.addEventListener('click', () => {
            toggleMenu.classList.toggle('active');
            sideMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
        });

        menuOverlay.addEventListener('click', () => {
            toggleMenu.classList.remove('active');
            sideMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        const sideMenuLinks = sideMenu.querySelectorAll('a');
        sideMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                toggleMenu.classList.remove('active');
                sideMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });
        });

        // Profile Section Navigation
        const menuButtons = document.querySelectorAll('.menu-btn');
        const contentSections = document.querySelectorAll('.content-section');

        menuButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and sections
                menuButtons.forEach(btn => btn.classList.remove('active'));
                contentSections.forEach(section => section.classList.remove('active'));

                // Add active class to clicked button and corresponding section
                button.classList.add('active');
                const sectionId = button.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
            });
        });

       

        // ===== ADDRESS FUNCTIONS =====
        let currentAddressType = '';
        let currentAddressId = '';

        function openEditAddress(type, addressId) {
            currentAddressType = type;
            currentAddressId = addressId;
        }

      

        function openAddAddress(type) {
            currentAddressType = type;
            document.getElementById('addAddressForm').reset();
        }

        function addNewAddress() {
            const type = document.getElementById('addressType').value;
            const name = document.getElementById('newAddressName').value;
            const address = document.getElementById('newAddress').value;
            const city = document.getElementById('newCity').value;
            const state = document.getElementById('newState').value;
            const postal = document.getElementById('newPostal').value;
            const country = document.getElementById('newCountry').value;
            const phone = document.getElementById('newAddressPhone').value;

            if (name && address && city && state && postal && country && phone) {
                alert('New address added successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
                modal.hide();
            } else {
                alert('Please fill in all required fields');
            }
        }

        // ===== ORDER DETAILS DATA =====
        const orderData = {
            1: {
                orderId: '#MP-2024-00891',
                status: 'Delivered',
                statusIcon: 'fas fa-check-circle',
                statusColor: '#4ade80',
                statusDate: 'Delivered on January 26, 2026',
                items: [
                    { name: 'Gold Plated Kundan Nose Pin', quantity: 1, price: 'RM 199.99' },
                    { name: 'Emerald Stone Nose Pin', quantity: 1, price: 'RM 249.99' },
                    { name: 'Diamond Shape Nose Pin', quantity: 1, price: 'RM 209.99' }
                ],
                subtotal: 'RM 659.97',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 659.97',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
               
            },
            2: {
                orderId: '#MP-2024-00852',
                status: 'Processing',
                statusIcon: 'fas fa-shipping-fast',
                statusColor: '#d4af37',
                statusDate: 'Est. Delivery: January 28, 2026',
                items: [
                    { name: 'Pearl Studded Nose Pin', quantity: 1, price: 'RM 179.99' },
                    { name: 'Ruby Red Nose Pin', quantity: 1, price: 'RM 269.99' }
                ],
                subtotal: 'RM 449.98',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 449.98',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
                timeline: [
                    { date: 'January 18, 2026', event: 'Order Placed', completed: true },
                    { date: 'January 19, 2026', event: 'Order Confirmed', completed: true },
                    { date: 'January 25, 2026', event: 'In Transit', completed: false },
                    { date: 'January 28, 2026', event: 'Expected Delivery', completed: false }
                ]
            },
            3: {
                orderId: '#MP-2024-00810',
                status: 'Delivered',
                statusIcon: 'fas fa-check-circle',
                statusColor: '#4ade80',
                statusDate: 'Delivered on January 10, 2026',
                items: [
                    { name: 'Emerald Kundan Nose Pin', quantity: 1, price: 'RM 299.99' }
                ],
                subtotal: 'RM 299.99',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 299.99',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
                timeline: [
                    { date: 'January 5, 2026', event: 'Order Placed', completed: true },
                    { date: 'January 6, 2026', event: 'Order Confirmed', completed: true },
                    { date: 'January 8, 2026', event: 'Shipped', completed: true },
                    { date: 'January 10, 2026', event: 'Delivered', completed: true }
                ]
            }
        };

        // ===== VIEW ORDER DETAILS FUNCTION =====
        function viewOrderDetails(orderId) {
            const order = orderData[orderId];

            // Set order number
            document.getElementById('modalOrderNumber').textContent = `Order ${order.orderId}`;

            // Set status
            const statusIcon = document.getElementById('modalStatusIcon');
            statusIcon.innerHTML = `<i class="${order.statusIcon}" style="font-size: 1.8rem; color: ${order.statusColor};"></i>`;
            statusIcon.style.background = order.statusColor === '#4ade80' ? 'rgba(74, 222, 128, 0.2)' : 'rgba(212, 175, 55, 0.2)';

            document.getElementById('modalStatusText').textContent = order.status;
            document.getElementById('modalStatusDate').textContent = order.statusDate;

            // Set order items
            const itemsContainer = document.getElementById('modalOrderItems');
            itemsContainer.innerHTML = order.items.map(item => `
                <div style="padding: 1rem; background: rgba(212, 175, 55, 0.05); border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; font-weight: 600;">${item.name}</p>
                        <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.85rem;">Quantity: ${item.quantity}</p>
                    </div>
                    <span style="color: var(--mp-gold); font-weight: 600;">${item.price}</span>
                </div>
            `).join('');

            // Set order summary
            document.getElementById('modalSubtotal').textContent = order.subtotal;
            document.getElementById('modalShipping').textContent = order.shipping;
            document.getElementById('modalTax').textContent = order.tax;
            document.getElementById('modalTotalAmount').textContent = order.total;

            // Set delivery address
            document.getElementById('modalDeliveryAddress').innerHTML = order.address;

            // Set order timeline
            const timelineContainer = document.getElementById('modalOrderTimeline');
            timelineContainer.innerHTML = order.timeline.map((item, index) => `
                <div style="margin-bottom: ${index === order.timeline.length - 1 ? '0' : '1.5rem'}; display: flex; gap: 1rem;">
                    <div style="width: 16px; height: 16px; border-radius: 50%; background: ${item.completed ? 'var(--mp-gold)' : 'rgba(212, 175, 55, 0.3)'}; margin-top: 0.25rem; flex-shrink: 0;"></div>
                    <div>
                        <p style="margin: 0; font-weight: 600; color: var(--mp-text);">${item.event}</p>
                        <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.9rem;">${item.date}</p>
                    </div>
                </div>
            `).join('');
        }

        // ===== FORM INPUT STYLING =====
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.style.borderColor = 'var(--mp-gold)';
                this.style.boxShadow = '0 0 8px rgba(212, 175, 55, 0.3)';
            });
            input.addEventListener('blur', function () {
                this.style.borderColor = 'rgba(212, 175, 55, 0.25)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>

</html>