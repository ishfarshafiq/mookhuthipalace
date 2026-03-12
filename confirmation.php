<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");


$userID=""; $name=""; $is_billing = 0; $is_profile=0;
if(isset($_SESSION['userID']))
{
	$userID = $_SESSION['userID'];
	$status="INACTIVE";
	$result=mysqli_query($conn,"select * from user_account where userID = $userID and status = 'ACTIVE'");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$name = $row['name'];
		$email = $row['email'];
		
		if($row['profile']!="")
		{
			$is_profile=1;
		}
		$profile = !empty($row['profile'])  ? "uploads/".$row['profile'] : "img/happy.png";
		
		
	}
	else
	{
		echo "<script>window.location.href = 'logout.php';</script>";
	}
}

$full_name=""; $phone=""; $address="";
if(isset($_GET['ordercode']))
{
	$ordercode = $_GET['ordercode'];
	$collecting_point_address="";
	
	//Get Shipping address ID from checkout
	$result=mysqli_query($conn,"SELECT shipping_addressID FROM checkout where order_code = '$ordercode' and (shipping_addressID is not null and shipping_addressID != '')");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$shipping_addressID = $row['shipping_addressID'];
		
		//Get shipping details
		$result_shipping_address=mysqli_query($conn,"SELECT * FROM shipping_address where shipping_addressID = $shipping_addressID");
		$row_shipping_address = mysqli_fetch_assoc($result_shipping_address);
		$full_name = $row_shipping_address['name'];
		$phone = $row_shipping_address['phone'];
		$address = $row_shipping_address['address_line1'].",".$row_shipping_address['address_line2'].",".$row_shipping_address['postcode'].",".$row_shipping_address['city'].",".$row_shipping_address['state']."Malaysia";
		
	}
	else
	{
		$is_billing = 1;
		
		//Get Billing address ID from checkout
		$result=mysqli_query($conn,"SELECT billing_addressID FROM checkout where order_code = '$ordercode' and (billing_addressID is not null and billing_addressID != '')");
		if(mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_assoc($result);
			$billing_addressID = $row['billing_addressID'];
			
			//Get billing details
			$result_billing_address=mysqli_query($conn,"SELECT * FROM billing_address where billing_addressID = $billing_addressID");
			$row_billing_address = mysqli_fetch_assoc($result_billing_address);
			$full_name = $row_billing_address['first_name']." ".$row_billing_address['last_name'];
			$phone = $row_billing_address['phone'];
			$address = $row_billing_address['address'].",<br>".$row_billing_address['postcode']." ".$row_billing_address['city'].", <br>".$row_billing_address['state'].",<br> Malaysia";
		}
		
	}
	
	//Get Delivery Method
	$delivery_fee = 0;
	$result_delivery_method=mysqli_query($conn,"select delivery_method from checkout where order_code = '$ordercode'");
	$row_delivery_method = mysqli_fetch_assoc($result_delivery_method);
	$delivery_method = $row_delivery_method['delivery_method'];
	
	$fees = [
		"standard" => 8,
		"foreign" => 18
	];
	
	$delivery_fee = $fees[$delivery_method] ?? 0;
	
	
	//Get Self Collect
	$result=mysqli_query($conn,"SELECT collecting_point FROM checkout where order_code = '$ordercode' and collecting_point != 'NONE'");
	if(mysqli_num_rows($result) > 0)
	{
		$row_collecting_point = mysqli_fetch_assoc($result);
		$collecting_point = $row_collecting_point['collecting_point'];
		if($collecting_point == "KUALA LUMPUR")
		{
			$collecting_point_address = "LOT 75-G MEDAN BUNUS, OFF JALAN MASJID INDIA, 50100 KUALA LUMPUR, WILAYAH PERSEKUTUAN";
			
		}
		else if($collecting_point == "TAIPING")
		{
			$collecting_point_address = "NO. 136, JALAN PASAR, 34000 TAIPING, PERAK";
		}
		else
		{
			$collecting_point_address = "271, JALAN SILIBIN, TAMAN ALKAFF, 30100 IPOH, PERAK";
		}
	}
	else
	{
		
		//Get paid date
		$result_payment_date=mysqli_query($conn,"select payment_date from payment_transaction where ordercode = '$ordercode'");
		$row_payment_date = mysqli_fetch_assoc($result_payment_date);
		$payment_date = $row_payment_date['payment_date'];
		$date_start = new DateTime($payment_date);
		$date_end = new DateTime($payment_date);
		$date_start->modify('+3 days');
		$date_end->modify('+5 days');
		$estimated_delivery_date =  $date_start->format('d M Y') . " - " . $date_end->format('d M Y');
		
		
	}	
	
	
	
	
	
	
	
}
else
{
	echo "<script>window.location.href = 'profile.php';</script>";
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/style_confirmation.css" rel="stylesheet" type="text/css" />

</head>

<body>
  

	<nav class="navbar-custom">
	<div class="navbar-container">
		<a href="index.php" class="navbar-brand"><img src="img/mookuthi-palace-logo.png" width="100"></a>

		<!-- Desktop Navigation -->
		<div class="nav-desktop">
			<ul class="nav-menu">
				<li><a href="index.php">Home</a></li>
				<li><a href="products.php">Shop Online</a></li>
				<li><a href="about.php">Our Story</a></li>
				<li><a href="branches.php">Branches</a></li>
			</ul>
		</div>

		<!-- Nav Icons & Toggle -->
		<div style="display: flex; align-items: center; gap: 1rem; flex-shrink: 0;">
			<ul class="nav-icons">
				<li>
				
				<?php if($is_profile == 1) { ?>
							<a href="login.php" title="login" class="gapicon"><img src="<?php echo $profile;?>" width="40" height="40" style="border-radius:50%;object-fit:cover;display:block;"></a> <?= ($name != "") ? "Hi, ".$name : "" ?>
					<?php } else { ?>
						<a href="login.php" title="login" class="gapicon"><i class="bi bi-person-bounding-box fa-2x"></i></a> <?= ($name != "") ? "Hi, ".$name : "" ?>
					<?php } ?>
					</li>
			</ul>
			<button class="toggle-menu" id="toggleMenu">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</div>
</nav>
   
	<?php include_once('includes/side_bar.php'); ?>


  <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Success Section -->
    <div class="success-section">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="success-title">Order Confirmed!</h1>
        <p class="success-message">Thank you for your purchase. Your order has been successfully placed.</p>
        <div class="order-number">
            <div class="order-number-label">Order Number</div>
            <div class="order-number-value">#<?php echo $ordercode;?></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container confirmation-container">
        <div class="row g-4">
            <!-- Information Sections -->
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> <?php echo ($is_billing == 1) ? "Billing Information" : "Shipping Information"; ?>
                    </h3>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-user"></i></div>
                            <div class="info-content">
                                <h4>Recipient</h4>
                                <p><?php echo strtoupper($full_name);?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-phone"></i></div>
                            <div class="info-content">
                                <h4>Contact</h4>
                                <p><?php echo $phone;?></p>
                            </div>
                        </div>
                    </div>
                    <div class="info-item" style="margin-bottom: 0;">
                        <div class="info-icon"><i class="fas fa-map"></i></div>
                        <div class="info-content">
                            <h4>Address</h4>
                            <p><?php echo strtoupper($address);?></p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck"></i> Delivery Details
                    </h3>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-shipping-fast"></i></div>
                            <div class="info-content">
                                <h4>Shipping Method</h4>
								<?php if($delivery_method == "standard" || $delivery_method == "foreign") {?>	
									<p>Standard Delivery (3-5 business days)</p>
								<?php } else {?>
									<p>Self Collect</p>
								<?php } ?>
							</div>
                        </div>
						
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-calendar"></i></div>
                            <div class="info-content">
							<?php if($delivery_method == "standard" || $delivery_method == "foreign") {?>
                                <h4>Expected Delivery</h4>
                                <p><?php echo $estimated_delivery_date;?></p>
							<?php } else {?>
								<h4>Collecting Address</h4>
								<p><?php echo $collecting_point_address;?></p>
								<?php } ?>
                            </div>
                        </div>
						
                    </div>
                </div>

                <!-- Branch Recommendation Section -->
               <!-- <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-store"></i> Branch That Referred You
                    </h3>
                    <div id="branchInfoContainer" style="background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; padding: 1.5rem;">
                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-store"></i></div>
                                <div class="info-content">
                                    <h4>Branch Name</h4>
                                    <p id="branchName">Loading...</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-phone"></i></div>
                                <div class="info-content">
                                    <h4>Contact Number</h4>
                                    <p id="branchPhone">Loading...</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-item" style="margin-bottom: 0;">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="info-content">
                                    <h4>Branch Address</h4>
                                    <p id="branchAddress">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

                <!-- Order Items -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-box"></i> Order Items
                    </h3>
                    <div class="order-items">
						<?php
					$oder_subtotal=0; $oder_sum_subtotals=0;
					$sql = "SELECT b.*, c.product_name, c.product_image, c.width FROM payment_transaction a 
							inner join orders b on a.ordercode = b.order_code
							inner join product c on b.productID = c.productID
							where a.ordercode = '$ordercode';";
					$result = mysqli_query($conn,$sql);
					while($row = mysqli_fetch_assoc($result)) {
						$oder_subtotal =  number_format((float)$row['quantity'] * $row['price'], 2, '.', '');
						
					?>  
					   <div class="order-item">
							<?php
							if($row['product_image'] != ""){
							?>
							<div class="item-image">
								<img src="admin/uploads/products/<?php echo $row['product_image'];?>" class="img-fluid">
							</div>
						 <?php }?> 
							
							<div class="item-details">
                                <h5><?php echo $row['product_name'];?></h5>
                                <p>Size: <?php echo $row['width'];?> | <?php echo "Color: " . (!empty($row['color']) ? $row['color'] : ''); ?> | <?php echo "Qty: " . (!empty($row['quantity']) ? $row['quantity'] : ''); ?></p>
                            </div>
                            <div class="item-price">
                                <div class="item-price-label">RM <?php echo  number_format((float)$oder_subtotal, 2, '.', '');?></div>
                            </div>
                        </div>
						
						<?php
						
						$oder_sum_subtotals +=  number_format((float)$row['quantity'] * $row['price'], 2, '.', '');
					}
					?>
                    
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i> Payment Information
                    </h3>
					
					<?php
					$card_number = "";$last4="";
					$sql = "SELECT b.*, c.card_number, a.payment_status FROM payment_transaction a 
							inner join checkout b on a.ordercode = b.order_code
							left join bank_card_details c on b.bank_card_detailsID = c.bank_card_detailsID
							where a.ordercode = '$ordercode';";
					$result = mysqli_query($conn,$sql);
					$row = mysqli_fetch_assoc($result);	
					
					if($row['payment_method']=="CARD" && $row['card_number']!="")
					{
						$card_number = $row['card_number'];
						$last4 = substr($card_number, -4);
					}
					
					?>
					
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-credit-card"></i></div>
                            <div class="info-content">
                                <h4>Payment Method</h4>
                                <p>
									<?php		
									if($row['payment_method']=="BANK")
									{
										echo "Bank";
									}
									else
									{
										echo "Credit Card ending in" . $last4;;
									}
									?>
								</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="info-content">
                                <h4>Status</h4>
								<?php		
								if($row['payment_status']=="Paid")
								{
									?>
									<p style="color: #4ade80;">Payment Successful</p>
									<?php
								}
								else
								{
									?>
									<p style="color: #ffd966;">Payment Unsuccessful</p>
									<?php
								}
								?>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="#" onclick="downloadInvoice(event)" class="btn-primary-gold">
                        <i class="fas fa-file-pdf"></i> Download Invoice
                    </a>
                    <a href="products.php" class="btn-secondary">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="col-lg-4">
                <div class="summary-section">
                    <h3 class="section-title" style="margin-bottom: 1.5rem;">Order Summary</h3>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>RM <?php echo  number_format((float)$oder_sum_subtotals, 2, '.', '');?></span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span><?php echo  number_format((float)$delivery_fee, 2, '.', '');?></span>
                    </div>

                    <div class="summary-row">
                        <span>Tax</span>
                        <span>RM 0.00</span>
                    </div>

                    <div class="summary-row total">
                        <span class="label">Total</span>
                        <span class="value">RM <?php echo  number_format((float)$oder_sum_subtotals + $delivery_fee, 2, '.', '');?></span>
                    </div>

                    <div
                        style="background: rgba(76, 222, 128, 0.1); border: 2px solid #4ade80; border-radius: 8px; padding: 1.5rem; text-align: center; margin-top: 1.5rem;">
                        <p style="color: #4ade80; font-weight: 700; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i> Confirmed
                        </p>
                        <p style="color: #ddd; font-size: 0.9rem;">A confirmation email has been sent to your email
                            address.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
   <?php include_once('includes/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Branch information database
        const branchInfo = {
            'IPOH': {
                name: 'Mookhuthi Palace - IPOH',
                phone: '+60 5-2427-8888',
                address: '123 Jalan Sultan Ismail, 30250 Ipoh, Perak, Malaysia',
                hours: 'Mon - Sun: 10:00 AM - 8:00 PM'
            },
            'TAIPING': {
                name: 'Mookhuthi Palace - TAIPING',
                phone: '+60 5-8068-8888',
                address: '456 Jalan Kebun, 34000 Taiping, Perak, Malaysia',
                hours: 'Mon - Sun: 10:00 AM - 8:00 PM'
            },
            'KUALA_LUMPUR': {
                name: 'Mookhuthi Palace - KUALA LUMPUR',
                phone: '+60 3-2161-8888',
                address: '789 Jalan Merdeka, 50050 Kuala Lumpur, Malaysia',
                hours: 'Mon - Sun: 10:00 AM - 9:00 PM'
            }
        };

        // Display branch information on page load
        function displayBranchInfo() {
            // Get branch from localStorage (set from checkout page)
            const selectedBranch = localStorage.getItem('selectedBranch');
            
            if (selectedBranch && branchInfo[selectedBranch]) {
                const branch = branchInfo[selectedBranch];
                document.getElementById('branchName').textContent = branch.name;
                document.getElementById('branchPhone').textContent = branch.phone;
                document.getElementById('branchAddress').textContent = branch.address;
            } else {
                // Default to KUALA_LUMPUR if no branch selected
                const branch = branchInfo['KUALA_LUMPUR'];
                document.getElementById('branchName').textContent = branch.name;
                document.getElementById('branchPhone').textContent = branch.phone;
                document.getElementById('branchAddress').textContent = branch.address;
            }
        }

        // Generate and download invoice as PDF
        function downloadInvoice(event) {
            event.preventDefault();
            
            // Get order data
            const selectedBranch = localStorage.getItem('selectedBranch') || 'KUALA_LUMPUR';
            const branch = branchInfo[selectedBranch];
            
            // Create invoice content
            const invoiceContent = `
                <div style="font-family: Arial, sans-serif; padding: 40px; max-width: 900px; margin: 0 auto;">
                    <!-- Header -->
                    <div style="text-align: center; margin-bottom: 40px; border-bottom: 2px solid #d4af37; padding-bottom: 20px;">
                        <h1 style="color: #d4af37; font-size: 32px; margin: 0; font-weight: bold;">MOOKHUTHI PALACE</h1>
                        <p style="color: #666; margin: 5px 0; font-size: 14px;">Premium Indian Jewelry</p>
                    </div>

                    <!-- Invoice Info -->
                    <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
                        <div>
                            <h3 style="color: #333; font-size: 16px; margin-bottom: 10px;">INVOICE</h3>
                            <p style="margin: 5px 0; color: #666;"><strong>Invoice #:</strong> INV-20260127-001</p>
                            <p style="margin: 5px 0; color: #666;"><strong>Date:</strong> January 27, 2026</p>
                            <p style="margin: 5px 0; color: #666;"><strong>Order #:</strong> ORD-20260127-001</p>
                        </div>
                        <div style="text-align: right;">
                            <h3 style="color: #333; font-size: 16px; margin-bottom: 10px;">BILL TO</h3>
                            <p style="margin: 5px 0; color: #666;">Customer Name</p>
                            <p style="margin: 5px 0; color: #666;">customer@email.com</p>
                            <p style="margin: 5px 0; color: #666;">+60 123456789</p>
                        </div>
                    </div>

                    <!-- Branch Info -->
                    <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #d4af37; margin-bottom: 30px;">
                        <p style="margin: 5px 0; color: #666;"><strong>Referred by Branch:</strong> ${branch.name}</p>
                        <p style="margin: 5px 0; color: #666;"><strong>Branch Contact:</strong> ${branch.phone}</p>
                        <p style="margin: 5px 0; color: #666;"><strong>Branch Address:</strong> ${branch.address}</p>
                    </div>

                    <!-- Items Table -->
                    <table style="width: 100%; margin-bottom: 30px; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #d4af37; color: white;">
                                <th style="padding: 10px; text-align: left; font-weight: bold;">Item</th>
                                <th style="padding: 10px; text-align: center; font-weight: bold;">Quantity</th>
                                <th style="padding: 10px; text-align: right; font-weight: bold;">Unit Price</th>
                                <th style="padding: 10px; text-align: right; font-weight: bold;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;">Royal Pearl Nose Pin</td>
                                <td style="padding: 10px; text-align: center;">1</td>
                                <td style="padding: 10px; text-align: right;">RM 189.99</td>
                                <td style="padding: 10px; text-align: right;">RM 189.99</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;">Maharaja Gold</td>
                                <td style="padding: 10px; text-align: center;">2</td>
                                <td style="padding: 10px; text-align: right;">RM 249.99</td>
                                <td style="padding: 10px; text-align: right;">RM 499.98</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;">Diamond Dazzle</td>
                                <td style="padding: 10px; text-align: center;">1</td>
                                <td style="padding: 10px; text-align: right;">RM 219.99</td>
                                <td style="padding: 10px; text-align: right;">RM 219.99</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div style="text-align: right; margin-bottom: 30px;">
                        <table style="width: 300px; margin-left: auto; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 8px; text-align: left; color: #666;">Subtotal:</td>
                                <td style="padding: 8px; text-align: right; color: #666;">RM 909.96</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 8px; text-align: left; color: #666;">Shipping:</td>
                                <td style="padding: 8px; text-align: right; color: #666;">FREE</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 8px; text-align: left; color: #666;">Tax:</td>
                                <td style="padding: 8px; text-align: right; color: #666;">RM 0.00</td>
                            </tr>
                            <tr style="background: #d4af37;">
                                <td style="padding: 12px; text-align: left; font-weight: bold; color: white;">TOTAL:</td>
                                <td style="padding: 12px; text-align: right; font-weight: bold; color: white; font-size: 18px;">RM 909.96</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div style="border-top: 2px solid #d4af37; padding-top: 20px; text-align: center; color: #666; font-size: 12px;">
                        <p style="margin: 5px 0;">Mookhuthi Palace (M) Sdn. Bhd. | Registration: 202501029797 (1631209-P)</p>
                        <p style="margin: 5px 0;">Thank you for your purchase! For inquiries, contact us at our branches.</p>
                        <p style="margin: 5px 0; color: #999;">Invoice generated on January 27, 2026</p>
                    </div>
                </div>
            `;

            // Create PDF
            const element = document.createElement('div');
            element.innerHTML = invoiceContent;
            
            const opt = {
                margin: 10,
                filename: 'Mookhuthi-Palace-Invoice-20260127.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
            };

            html2pdf().set(opt).from(element).save();
        }

        // Toggle Menu Functionality
        const toggleMenu = document.getElementById('toggleMenu');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        // Toggle menu open/close
        toggleMenu.addEventListener('click', () => {
            toggleMenu.classList.toggle('active');
            sideMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
        });

        // Close menu when clicking overlay
        menuOverlay.addEventListener('click', () => {
            toggleMenu.classList.remove('active');
            sideMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        // Close menu when clicking a link
        const sideMenuLinks = sideMenu.querySelectorAll('a');
        sideMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                toggleMenu.classList.remove('active');
                sideMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });
        });

        // Initialize branch info on page load
        window.addEventListener('load', displayBranchInfo);
    </script>
</body>

</html>