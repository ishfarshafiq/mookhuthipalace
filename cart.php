<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

$customer_code=""; 
if(isset($_SESSION['customer_code']))
{ 
	$customer_code = $_SESSION['customer_code']; 
}

$userID = ""; $name=""; $is_profile=0;
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
		$phone = $row['phone'];
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
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap"
        rel="stylesheet">
	<link href="css/style_cart.css" rel="stylesheet" type="text/css" />
    
</head>

<body>
    
	<?php include_once('includes/navbar.php'); ?>
   
	<?php include_once('includes/side_bar.php'); ?>
	
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Shopping Cart</h1>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="container cart-container">
	<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
	<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div id="cartItems" class="cart-items">
                   
                    <?php
					$oder_sum_subtotal=0;$is_item=0;
					$sql = "SELECT a.cartID, b.product_image, b.product_name, a.color, a.style, b.original_price, b.price, a.quantity FROM cart a 
								inner join product b on a.productID = b.productID
								where (a.customer_code = '$customer_code' or a.userID = '$userID')  and b.status in ('ACTIVE','LOW STOCK');";
					$result = mysqli_query($conn,$sql);
					
					if(mysqli_num_rows($result) > 0)
					{
						$is_item = 1;
						while($row = mysqli_fetch_assoc($result)) {
							
							if($row['price']!="")
							{
								$prices = $row['price'];
							}
							else
							{
								$prices = $row['original_price'];
							}
							
							$oder_sum_subtotal +=  number_format((float)$row['quantity'] * $prices, 2, '.', '');
							
							$subtotal =  number_format((float)$row['quantity'] * $prices, 2, '.', '');
					?>
				   <div class="cart-item" data-cartid="<?php echo $row['cartID']; ?>">
				   
							<?php
								if($row['product_image'] != ""){
							?>
							<div class="item-image">
								<img src="admin/uploads/products/<?php echo $row['product_image'];?>" width="120">
							</div>
						 <?php }?> 
                        <div class="item-details">
                            <div class="item-name"><?php echo $row['product_name'];?></div>
							<?php if($row['color'] != ""){?>                           
							<div class="item-meta">Color: <?php echo $row['color'];?></div>
						   <?php }?> 
						   <?php if($row['style'] != ""){?>                           
							<div class="item-meta">Style: <?php echo $row['style'];?></div>
						   <?php }?> 
                            <div class="item-price">RM <?php echo number_format((float)$prices, 2, '.', '');?></div>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(this, -1, <?php echo $row['cartID']; ?>)">−</button>
                                <input type="number" class="quantity-input" value="<?php echo $row['quantity'];?>" min="1" max="10">
                                <button class="quantity-btn" onclick="updateQuantity(this, 1, <?php echo $row['cartID']; ?>)">+</button>
                            </div>
                            <div class="item-subtotal">
                                <div class="subtotal-label">Subtotal</div>
                                <div class="subtotal-amount">RM <?php echo $subtotal;?></div>
                            </div>
                            <button class="btn-remove" onclick="removeItem(this)">Remove</button>
                        </div>
                    </div>
					<?php
							   }
					}
					
					if($is_item == 0){ $proceed_checkout="#"; }else{ $proceed_checkout="checkout.php"; }
					?>
                   
				
				</div>

                <!-- Empty Cart Message (Hidden) -->
                <div id="emptyCart" class="empty-cart" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p class="empty-text">Your cart is empty</p>
                    <p style="color: #999; margin-bottom: 2rem;">Start shopping for our exquisite collection of nose
                        pins</p>
                    <a href="products.php" class="btn btn-continue-shopping">Continue Shopping</a>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value" id="subtotal">RM <?php echo number_format((float)$oder_sum_subtotal, 2, '.', '');?></span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value" id="shipping">Free</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Tax (0%)</span>
                        <span class="summary-value" id="tax">RM 0.00</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Discount</span>
                        <span class="summary-value" id="discount" style="color: #4ade80;">-RM 0.00</span>
                    </div>

                    <div class="summary-row total">
                        <span class="label">Total</span>
                        <span class="value" id="total">RM <?php echo number_format((float)$oder_sum_subtotal, 2, '.', '');?></span>
                    </div>

                    <!-- Coupon Section -->
                    <!-- <div class="coupon-section">
                        <input type="text" class="coupon-input" placeholder="Enter Coupon Code" id="couponCode">
                        <button class="btn-apply-coupon" onclick="applyCoupon()">Apply Coupon</button>
                    </div> -->

                    <!-- Checkout Button -->
                    <a href="<?php echo $proceed_checkout;?>" class="btn btn-checkout" style="text-decoration: none;" <?php if($is_item == 0){ echo "disabled"; } ?> >
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </a>
                    <a href="products.php" class="btn-continue-shop">Continue Shopping</a>

                    <!-- Shipping Info -->
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(212, 175, 55, 0.2);">
                        <p style="font-size: 0.9rem; color: #999; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle"></i> Free shipping on orders above RM 200
                        </p>
                        <p style="font-size: 0.9rem; color: #999;">
                            <i class="fas fa-check-circle"></i> 100% Secure Checkout
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/cart.js"></script>
	
	<script>
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