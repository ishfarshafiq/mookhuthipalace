<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

$name="";
$email = "";
$phone = "";
$first_name = "";
$last_name = "";
$address = "";
$city = "";
$state = "";
$postcode = "";

$customer_code = $_SESSION['customer_code'] ?? '';
$userID = $_SESSION['userID'] ?? '';

 $result = mysqli_query($conn,"SELECT * FROM cart where customer_code = '$customer_code' or userID = '$userID'");
 if(mysqli_num_rows($result) == 0) {
	 echo "<script>window.location.href = 'cart.php';</script>";
 }

$is_profile=0;
if(!empty($userID)) {

    $userID = (int)$userID; // prevent injection

    $result = mysqli_query($conn,"SELECT * FROM user_account WHERE userID = $userID AND status = 'ACTIVE'");

    if(mysqli_num_rows($result) > 0) {

        $user_row = mysqli_fetch_assoc($result);

        $name  = $user_row['name'];
        $email = $user_row['email'];
        $phone = $user_row['phone'];
		if($user_row['profile']!="")
		{
			$is_profile=1;
		}
		$profile = !empty($user_row['profile'])  ? "uploads/".$user_row['profile'] : "img/happy.png";

        $safe_email = mysqli_real_escape_string($conn, $email);
		
		//echo $email;

        $result_billing_address = mysqli_query($conn,"SELECT * FROM billing_address WHERE email = '$email' LIMIT 1");
        if(mysqli_num_rows($result_billing_address) > 0) {
            $billing_row = mysqli_fetch_assoc($result_billing_address);

            $first_name = $billing_row['first_name'];
            $last_name  = $billing_row['last_name'];
            $address    = $billing_row['address'];
            $city       = $billing_row['city'];
            $state      = $billing_row['state'];
            $postcode   = $billing_row['postcode'];
			$phone 		= $billing_row['phone'];
        }
		

    } else {
        echo "<script>window.location.href = 'logout.php';</script>";
        exit;
    }
}


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link href="css/style_checkout.css" rel="stylesheet" type="text/css" />
    
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
   
	<?php include_once('includes/side_bar.php'); ?>
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Secure Checkout</h1>
        </div>
    </div>



    <!-- Checkout Form -->
	<form id="checkoutForm">
    <div class="container checkout-container">
        <div class="row g-4">
		
		<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
		<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
		
            <!-- Main Content -->
            <div class="col-lg-8">

                <!-- Delivery Method -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck"></i> Delivery Method
                    </h3>

                    <div class="option-group">
                        <div class="option-card active" onclick="selectDelivery(this, 'selfCollect')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-store"></i> Self Collect</h4>
                                <p>Pick up at our store (11:00 AM - 6:00 PM)</p>
                            </div>
                            <div class="option-price">FREE</div>
                        </div>

                        <div class="option-card" onclick="selectDelivery(this, 'standard')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-truck"></i> Standard Delivery</h4>
                                <p>Delivery in 3-5 business days</p>
                            </div>
                            <div class="option-price">RM 8.00</div>
                        </div>
                    </div>
					
					<input type="hidden" name="delivery_method" id="delivery_method" value="selfCollect">
                </div>

                <!-- Self Collect Branch Selection -->
                <div class="form-section" id="selfCollectSection" style="display: block;">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Select Collection Point
                    </h3>

                    <div class="option-group" style="flex-direction: column; gap: 1.5rem;">
                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="KUALA LUMPUR" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Kuala Lumpur</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">LOT 75-G Medan Bunus, Off Jalan Masjid India, 50100 Kuala Lumpur, WILAYAH PERSEKUTUAN</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="TAIPING" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Taiping</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">No. 136, Jalan Pasar, 34000 Taiping, Perak</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="IPOH" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Ipoh</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">271, Jalan Silibin, Taman Alkaff, 30100 Ipoh, Perak</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Billing Address
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="billing_first_name" name="billing_first_name" value="<?php echo $first_name; ?>" placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="billing_last_name" name="billing_last_name" value="<?php echo $last_name; ?>" placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="billing_email" name="billing_email" value="<?php echo $email; ?>" <?php if (!empty($email)) echo 'readonly'; ?> placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo $phone; ?>" placeholder="+60 1234567890" >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Street Address *</label>
                        <input type="text" class="form-control" id="billing_address" name="billing_address" value="<?php echo $address; ?>" placeholder="House,Condo, Apartment, suite, unit, etc.">
                    </div>

                    <div class="form-row">
					
						<div class="form-group">
                            <label class="form-label">State *</label>
                            <select class="form-control" id="billing_state" name="billing_state">
                                <option value="">-- Select State --</option>
                                <option value="Johor" <?php if($state == "Johor") echo "selected"; ?> >Johor</option>
                                <option value="Kedah" <?php if($state == "Kedah") echo "selected"; ?> >Kedah</option>
                                <option value="Kelantan" <?php if($state == "Kelantan") echo "selected"; ?> >Kelantan</option>
                                <option value="Melaka" <?php if($state == "Melaka") echo "selected"; ?> >Melaka</option>
                                <option value="Negeri Sembilan" <?php if($state == "Negeri Sembilan") echo "selected"; ?> >Negeri Sembilan</option>
                                <option value="Pahang" <?php if($state == "Pahang") echo "selected"; ?> >Pahang</option>
                                <option value="Perak" <?php if($state == "Perak") echo "selected"; ?> >Perak</option>
                                <option value="Perlis" <?php if($state == "Perlis") echo "selected"; ?> >Perlis</option>
                                <option value="Pulau Pinang" <?php if($state == "Pulau Pinang") echo "selected"; ?> >Pulau Pinang</option>
                                <option value="Sabah" <?php if($state == "Sabah") echo "selected"; ?> >Sabah</option>
                                <option value="Sarawak" <?php if($state == "Sarawak") echo "selected"; ?> >Sarawak</option>
                                <option value="Selangor" <?php if($state == "Selangor") echo "selected"; ?> >Selangor</option>
                                <option value="Terengganu" <?php if($state == "Terengganu") echo "selected"; ?> >Terengganu</option>
                                <option value="Kuala Lumpur" <?php if($state == "Kuala Lumpur") echo "selected"; ?> >Kuala Lumpur (FT)</option>
                                <option value="Putrajaya" <?php if($state == "Putrajaya") echo "selected"; ?> >Putrajaya (FT)</option>
                                <option value="Labuan" <?php if($state == "Labuan") echo "selected"; ?> >Labuan (FT)</option>
                            </select>
                        </div>
					
                        <div class="form-group">
                            <label class="form-label">City/Town *</label>
                            <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo $city; ?>" placeholder="Enter city">
                        </div>
                        

                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" value="<?php echo $postcode; ?>" placeholder="Enter postal code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country *</label>
                            <select class="form-control" id="billing_country" name="billing_country">
                                <option value="MY">Malaysia</option>

                            </select>
                        </div>
                    </div>

                    <!-- Branch Recommendation Section -->
                    <div class="form-group"
                        style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--mp-border);">
                        <label class="form-label">Which Branch Recommended This Website? *</label>
                        <p style="color: var(--mp-muted); font-size: 0.9rem; margin-bottom: 1rem;">Please select the
                            branch that referred you to us</p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <label style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="IPOH"
                                    style="margin-right: 0.75rem; cursor: pointer;">
                                <span>IPOH</span>
                            </label>
                            <label style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="TAIPING"
                                    style="margin-right: 0.75rem; cursor: pointer;">
                                <span>TAIPING</span>
                            </label>
                            <label style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="KUALA LUMPUR"
                                    style="margin-right: 0.75rem; cursor: pointer;">
                                <span>KUALA LUMPUR</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Toggle -->
                <div style="display: flex; align-items: center; gap: 1rem; margin: 2rem 0 0 0; padding-bottom: 2rem; border-bottom: 1px solid var(--mp-border);">
                    <label for="shippingToggle" style="margin: 0; font-weight: 500; color: var(--mp-text);">
                        Shipping Address Different from Billing
                    </label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="shippingToggle" onchange="toggleShippingAddress()">
                        <label for="shippingToggle" class="toggle-label"></label>
                    </div>
                </div>

                <!-- Shipping Address Section (Hidden by default) -->
                <div id="shippingAddressSection" class="form-section" style="display: none; margin-top: 2rem;">
                    <h3 class="section-title d-flex justify-content-between align-items-center">
						<div>
							<i class="fas fa-map-marker-alt"></i> Shipping Address
						</div>

						<button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetShippingAddress()" title="Reset Shipping Address">
							<i class="fas fa-undo"></i>
						</button>
					</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control shipping-input" id="shipping_name" name="shipping_name" placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control shipping-input" id="shipping_email" name="shipping_email" placeholder="Enter email address">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control shipping-input" id="shipping_phone" name="shipping_phone" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address Line 1 *</label>
                            <input type="text" class="form-control shipping-input" id="shipping_address_line1" name="shipping_address_line1" placeholder="Street address">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" class="form-control shipping-input" id="shipping_address_line2" name="shipping_address_line2" placeholder="Apartment, suite, etc.">
                        </div>
						
						<div class="form-group">
                            <label class="form-label">State *</label>
                            <select class="form-control shipping-input" id="shipping_state" name="shipping_state">
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

                    <div class="form-row">
					
						<div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control shipping-input" id="shipping_city" name="shipping_city" placeholder="Enter city">
                        </div>
					
                        
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postal Code *</label>
                            <input type="text" class="form-control shipping-input" id="shipping_postcode" name="shipping_postcode" placeholder="Enter postal code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country *</label>
                            <select class="form-control shipping-input" id="shipping_country" name="shipping_country">
                                <option value="">Select Country</option>
                                <option value="MY">Malaysia</option>
                            </select>
                        </div>
                    </div>
                </div>
              

                <!-- Payment Method -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i> Payment Method
                    </h3>

                    <div class="option-group">
                        <div class="option-card active" onclick="selectPayment(this, 'card')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-credit-card"></i> Credit/Debit Card</h4>
                                <p>Visa, MasterCard, Amex</p>
                            </div>
                        </div>
                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" style="margin-top: 2rem;">
                            <div class="form-group">
                                <label class="form-label">Card Holder Name *</label>
                                <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="Enter cardholder name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Card Number *</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date *</label>
                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="" maxlength="3" >
                                </div>
                            </div>
                        </div>
						
                        <div class="option-card" onclick="selectPayment(this, 'bank')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-university"></i> Bank Transfer</h4>
                                <p>Direct bank transfer</p>
                            </div>
                        </div>

						<input type="hidden" name="payment_method" id="payment_method" value="card">
                    </div>


                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; margin-bottom: 3rem;">
                    <a href="cart.php" class="btn-back" style="flex: 1;">← Back to Cart</a>
                    <button type="submit" class="btn-place-order" style="flex: 1;" id="placeOrder" name="placeOrder">
                        <i class="fas fa-lock"></i> Place Order
                    </button>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>
					
					<?php
					$oder_sum_subtotal=0;
					$sql = "SELECT a.cartID, b.product_image, b.product_name, a.color, a.style, b.original_price, b.price, a.quantity FROM cart a 
								inner join product b on a.productID = b.productID
								where (a.customer_code = '$customer_code' or a.userID = '$userID') and b.status in ('ACTIVE','LOW STOCK');";
					$result = mysqli_query($conn,$sql);
					
					if(mysqli_num_rows($result) > 0)
					{
						while($row = mysqli_fetch_assoc($result)) {
							
							if($row['price'] != "")
							{
								$prices = $row['price'];
							}
							else
							{
								$prices = $row['original_price'];
							}
							
							$oder_sum_subtotal +=  number_format((float)$row['quantity'] * $prices, 2, '.', '');
							
					?>

                    <!-- Items -->
                    <div class="order-item">
                        <?php
							if($row['product_image'] != ""){
							?>
							<div class="item-image">
								<img src="admin/uploads/products/<?php echo $row['product_image'];?>" class="img-fluid">
							</div>
						 <?php }?> 
                        <div class="order-item-details">
                            <div class="order-item-name"><?php echo $row['product_name'];?></div>
                            <div class="order-item-meta">Qty: <?php echo $row['quantity'];?></div>
                        </div>
                        <div class="order-item-price">RM <?php echo number_format((float)$prices * $row['quantity'], 2, '.', '');?></div>
                    </div>
					
					<?php
							   }
					}
					?>

                    
                    <!-- Totals -->
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span id="subtotal" data-value="<?php echo $oder_sum_subtotal; ?>">RM <?php echo number_format((float)$oder_sum_subtotal, 2, '.', '');?></span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span id="shippingCost">FREE</span>
                    </div>

                    <div class="summary-item">
                        <span>Tax</span>
                        <span>RM 0.00</span>
                    </div>

                    <div class="summary-item total">
                        <span class="label">Total</span>
                        <span class="value" id="totalAmount">RM <?php echo number_format((float)$oder_sum_subtotal, 2, '.', '');?></span>
                    </div>

                    <!-- Security Info -->
                    <div
                        style="padding: 1rem; background: rgba(212, 175, 55, 0.1); border-radius: 8px; text-align: center;">
                        <p style="font-size: 0.85rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-lock"></i> 100% Secure Checkout
                        </p>
                        <p style="font-size: 0.8rem; color: #999;">
                            Your payment information is encrypted and secure
                        </p>
                    </div>
                </div>
            </div>
        
		</div>
    </div>
	
	</form>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/checkout.js"></script>   


   <script>
        let selectedDelivery = 'standard';
        let selectedPayment = 'card';

        // Toggle Shipping Address Section
        // function toggleShippingAddress() {
            // const shippingSection = document.getElementById('shippingAddressSection');
            // const shippingInputs = document.querySelectorAll('.shipping-input');
            // const toggle = document.getElementById('shippingToggle');

            // if (toggle.checked) {
                // shippingSection.style.display = 'block';
                // // Make shipping inputs required
                // shippingInputs.forEach(input => {
                    // if (input.type !== 'email' && input.type !== 'tel' && input.getAttribute('placeholder') !== 'Apartment, suite, etc.') {
                        // input.setAttribute('required', 'required');
                    // }
                // });
            // } else {
                // shippingSection.style.display = 'none';
                // // Remove required attribute
                // shippingInputs.forEach(input => {
                    // input.removeAttribute('required');
                    // input.value = '';
                // });
            // }
        // }

        function selectDelivery(element, type) {
            document.querySelectorAll('[onclick^="selectDelivery"]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            selectedDelivery = type;

            // Show/hide Self Collect section
            const selfCollectSection = document.getElementById('selfCollectSection');
            if (type === 'selfCollect') {
                selfCollectSection.style.display = 'block';
            } else {
                selfCollectSection.style.display = 'none';
            }
			
            let cost = 'FREE';
            if (type === 'express') cost = 'RM 25.00';
            if (type === 'overnight') cost = 'RM 50.00';
			if (type === 'standard') cost = 'RM 8.00';
			
			if(type === 'standard')
			{
				let subtotal = document.getElementById("subtotal").dataset.value;
				let total = (parseFloat(subtotal) + 8.00).toFixed(2);
				document.getElementById('totalAmount').textContent = "RM "+ total
			}
			else
			{
				let subtotal = document.getElementById("subtotal").dataset.value;
				let total = (parseFloat(subtotal)).toFixed(2);
				document.getElementById('totalAmount').textContent = "RM "+ total
			}
			
            document.getElementById('shippingCost').textContent = cost;
			$("#delivery_method").val(type);
           
        }

        function selectPayment(element, type) {
            document.querySelectorAll('[onclick^="selectPayment"]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            selectedPayment = type;

            // Show/hide card details
            if (type === 'card') {
                document.getElementById('cardDetails').style.display = 'block';
            } else {
                document.getElementById('cardDetails').style.display = 'none';
            }
			
			 $("#payment_method").val(type);
        }

        function updateTotal() {
            const subtotal = 909.96;
            let shipping = 0;

            if (selectedDelivery === 'express') shipping = 25.00;
            if (selectedDelivery === 'overnight') shipping = 50.00;

            const total = subtotal + shipping;
            document.getElementById('totalAmount').textContent = 'RM ' + total.toFixed(2);
        }

        function placeOrder() {
            // Validate form
            const inputs = document.querySelectorAll('.form-control[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = '#ef4444';
                }
            });

            // Validate branch selection
            const branchSelected = document.querySelector('input[name="branch"]:checked');
            if (!branchSelected) {
                valid = false;
                alert('Please select which branch recommended this website');
                return;
            }

            if (!valid) {
                alert('Please fill all required fields');
                return;
            }

            // Process order
            alert('Order placed successfully! Redirecting to confirmation...');
            window.location.href = 'confirmation.html';
        }

        // Toggle Menu Functionality
        const toggleMenu = document.getElementById('toggleMenu');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        if (toggleMenu) {
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
        }
    </script>
</body>

</html>