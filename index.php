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
    <title>Mookhuthi Palace - Luxury Indian Nose Pins</title>
    <meta name="description"
        content="Shop authentic Indian nose pins (mookuthi) in Malaysia. Premium gold-tone & imitation designs, bridal and daily wear. Fast delivery across Malaysia from Mookuthi Palace." />
    <meta name="keywords"
        content="mookuthi palace, mookuthi malaysia, nose pin malaysia, indian nose pin, mookuthi, mukuthi, nath, nathni, nose ring, bridal nose pin, south indian jewellery, temple jewellery, indian jewellery malaysia, artificial jewellery malaysia" />

    <meta property="og:url" content="https://mookhuthipalace.com/" />
    <meta property="og:title" content="Mookuthi Palace | Indian Nose Pins in Malaysia" />
    <meta property="og:description"
        content="Authentic Indian nose pins (mookuthi) in Malaysia—bridal & daily wear designs with fast delivery nationwide." />
    <meta property="og:image" content="https://mookhuthipalace.com/mookuthi-palace-logo.png" />

    <meta name="robots" content="index, follow" />

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link href="css/style_index.css" rel="stylesheet" type="text/css" />
	<!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
   
   .swiper {
      width: 100%;
      height: 400px;
    }

    .swiper-slide {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: bold;
      color: #fff;
      position: relative;
      overflow: hidden;
    }

    .swiper-slide img {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }

    .swiper-slide::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.2);
      z-index: 2;
    }
.swiper-button-next, .swiper-button-prev {
	
	color: var(--swiper-navigation-color,#f0008f);
}
.swiper-pagination-bullet-active {
	opacity: var(--swiper-pagination-bullet-opacity, 1);
	background: var(--swiper-pagination-color,var(--swiper-navigation-color,#f0008f));
}
    .slide1 { background: #e74c3c; }
    .slide2 { background: #3498db; }
    .slide3 { background: #2ecc71; }
    .slide4 { background: #9b59b6; }
  </style>


</head>

<body>
    
   <?php include_once('includes/navbar.php'); ?>
   
   <?php include_once('includes/side_bar.php'); ?>

    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Hero Slider Section -->
    <!-- Swiper -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide slide1"> <img src="banner/slider1.jpg" alt="nosepin"></div>
            <div class="swiper-slide slide2"> <img src="banner/slider2.jpg" alt="Slide 1"></div>
            <div class="swiper-slide slide3"><img src="banner/slider3.jpg" alt="Slide 1"></div>
            <div class="swiper-slide slide4"><img src="banner/slider4.jpg" alt="Slide 1"></div>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>


    <!-- Featured Products -->
    <section class="featured-section" id="featured">
		<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
		<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
        <div class="container">
            <div>
                <div class="mp-section-title">Featured</div>
                <h2 class="h3 fw-bold mb-0">Bestsellers you’ll love</h2>
                <div class="mp-muted">Tap a product to open details.</div>
            </div>

            <div class="row g-4 py-4">
                
		  <?php
			$sql = "SELECT a.productID, a.product_name, a.description, a.sku, b.category, a.original_price, a.price, a.stock_quantity, c.material, a.status, a.product_image FROM product a
			inner join category b on a.categoryID = b.categoryID
			inner join material c on a.materialID = c.materialID
			WHERE a.status = 'ACTIVE' and a.is_bestseller = '1' and a.is_delete = '0'";
			$result = mysqli_query($conn,$sql);
			
			if(mysqli_num_rows($result) > 0)
			{
						while($row = mysqli_fetch_assoc($result)) {
							$original_price = $row['original_price'];
							$price = $row['price'];
						?>
				<!-- Product 1 -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="product-card">
                        <?php
							if($row['product_image'] != ""){
							?>
								<div><img src="admin/uploads/products/<?php echo $row['product_image'];?>" class="img-fluid"></div>
							<!--<div class="product-image-wrapper">
								<img src="admin/uploads/products/<?php //echo $row['product_image'];?>" class="product-image">
							</div>-->
							<?php
							}
							?>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo $row['product_name'];?></h3>
                            <p class="product-desc"><?php echo (strlen($row['description']) > 30) ? substr($row['description'], 0, 30) . '...' : $row['description'];?></p>
                            
							 <?php
							 if($price > 0)
							 {
							 ?>
							 <p class="product-price">
								<span class="price-original">RM <?php echo number_format((float)$row['original_price'], 2, '.', '');?></span>
								<span class="price-promo">RM <?php echo number_format((float)$row['price'], 2, '.', '');?></span>
							</p>
							 <?php
							 }
							 else
							 {
								?>
								<p class="product-price">RM <?php echo number_format((float)$row['original_price'], 2, '.', '');?></p>
								<?php												
							 }
							 ?>
							
							<button class="btn btn-add-cart w-100" onclick="window.location.href='product-details.php?productID=<?php echo $row['productID']; ?>'">View</button>
                        </div>
                    </div>
                </div>
				
				<?php
							   }
					}
			?>

               
				<div class="text-center"> <a href="products.php" class="btn btn-gold">🎁 View Our Collection</a></div>
            </div>
        </div>
    </section>
    <!-- Promotions Banner -->
    <div class="container mt-1 pt-1">
        <div class="promo-banner">
            <div class="promo-content">
                <p class="promo-label">🎁 Limited Time Offer</p>
                <h2 class="promo-title">Special Launch Discount</h2>
                <p class="promo-desc">Enjoy 25% off on all collections this month. Free delivery on orders above RM200
                </p>
                <a href="products.php" class="btn btn-gold">Shop Now</a>
            </div>
        </div>
    </div>
    <!-- Why Choose Us -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title" style="margin-bottom: 4rem;">Why Choose Us</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3 class="feature-title">Premium Quality</h3>
                        <p class="feature-desc">Authentic gold and gemstone jewelry crafted by skilled artisans</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="feature-title">Fast Delivery</h3>
                        <p class="feature-desc">Quick and secure shipping across Malaysia with tracking</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Secure Payment</h3>
                        <p class="feature-desc">100% secure transactions with multiple payment options</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-undo"></i>
                        </div>
                        <h3 class="feature-title">Easy Returns</h3>
                        <p class="feature-desc">30-day return policy with no questions asked</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container ">
            <div class="footer-connect">
                <h5>Connect With Us</h5>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© <span>Copyright</span>
                    <script> document.write(new Date().getFullYear())  </script>
                    <span class="copyright">Mookhuthi Palace (M) Sdn. Bhd. </span>202501029797 (1631209-P) | <span>All
                        rights reserved. | Privacy Policy |
                        Terms & Conditions</span>
                </p>
                <div class="credits"> Designed by <a href="https://webprodesign.my/"><span class="white">
                            WebPro Design</span></a> </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            spaceBetween: 30,

            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },

            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
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