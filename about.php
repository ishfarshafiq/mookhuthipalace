<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");


$customer_code=""; 
if(isset($_SESSION['customer_code']))
{ 
	$customer_code = $_SESSION['customer_code']; 
}

$userID="";$name=""; $is_profile=0;
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

$testimonialPath = "admin/uploads/testimoni/";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
	<link href="css/style_about.css" rel="stylesheet" type="text/css" />
</head>

<body>
    
	<?php include_once('includes/navbar.php'); ?>
   
    <?php include_once('includes/side_bar.php'); ?>
	
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1 class="page-title">About Mookhuthi Palace</h1>
            <p style="color: var(--mp-muted); margin-top: 0.5rem;">Crafting elegance since 2015</p>
        </div>
    </div>

    <!-- Main Content -->
    <!-- Content -->
    <div class="container">
	
	<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
	<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
	
        <!-- Our Story -->
        <div class="content-section">
            <h2 class="section-title">Our Story</h2>
            <div class="section-content">
                <div class="section-title"><img src="img/mookuthi-palace-logo.png" width="250" ></div>
                <p>
                    Mookhuthi Palace was established in 2015 with a vision to bring authentic, luxurious Indian nose
                    pins to the world.
                    What started as a small artisan workshop has grown into a renowned brand trusted by customers across
                    Malaysia and beyond.
                </p>
                <p style="margin-top: 1rem;">
                    We are deeply committed to preserving traditional Indian jewelry-making techniques while
                    incorporating contemporary designs.
                    Each piece is handcrafted by skilled artisans who have inherited their craft through generations,
                    ensuring unparalleled quality and authenticity.
                </p>
                <p style="margin-top: 1rem;">
                    Our name, "Mookhuthi Palace," reflects our dedication to creating a sanctuary of elegance and
                    luxury. A mookhuthi is not just a piece of jewelry;
                    it's a symbol of cultural heritage, beauty, and grace.
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">9+</div>
                <div class="stat-label">Years of Excellence</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">15K+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Unique Designs</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Skilled Artisans</div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div style="display: grid; gap: 2rem; margin: 3rem 0;">
            <div class="section-content">
                <h3 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 1rem; font-size: 1.3rem;">
                    <i class="fas fa-bullseye"></i> Our Mission
                </h3>
                <p>
                    To create exquisite, authentic Indian nose pins that celebrate cultural heritage while delivering
                    exceptional quality, craftsmanship, and customer satisfaction to every client.
                </p>
            </div>
            <div class="section-content">
                <h3 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 1rem; font-size: 1.3rem;">
                    <i class="fas fa-eye"></i> Our Vision
                </h3>
                <p>
                    To become the world's most trusted destination for luxury Indian nose pins, recognized for our
                    commitment to tradition, innovation, and exceptional customer experience.
                </p>
            </div>
        </div>

        <!-- Awards & Achievements -->
        <div class="content-section" style="padding: 2rem;">
            <h2 class="section-title">Awards & Recognition</h2>
            <div class="awards-grid">
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-trophy"></i></div>
                    <h4 class="award-title">Best Jewelry Brand 2023</h4>
                    <p class="award-description">Malaysia Fashion Excellence Awards</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-star"></i></div>
                    <h4 class="award-title">Customer Choice Award</h4>
                    <p class="award-description">5-Star Rating Across All Platforms</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-medal"></i></div>
                    <h4 class="award-title">Craftsmanship Excellence</h4>
                    <p class="award-description">Global Artisan Heritage Award 2022</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-certified"></i></div>
                    <h4 class="award-title">Quality Assurance</h4>
                    <p class="award-description">ISO 9001 Certified</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-heart"></i></div>
                    <h4 class="award-title">Ethical Excellence</h4>
                    <p class="award-description">Fair Trade Certified</p>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-globe"></i></div>
                    <h4 class="award-title">International Recognition</h4>
                    <p class="award-description">Featured in Global Luxury Magazine</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="content-section" style="padding: 2rem;">
            <h2 class="section-title">Why Choose Mookhuthi Palace</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-crown"></i> Premium Quality
                    </h4>
                    <p>Authentic gold, silver, and platinum with certified gemstones</p>
                </div>
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-hands"></i> Handcrafted
                    </h4>
                    <p>Each piece is individually crafted by master artisans</p>
                </div>
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-shield-alt"></i> Secure
                    </h4>
                    <p>100% secure checkout and buyer protection</p>
                </div>
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-shipping-fast"></i> Fast Delivery
                    </h4>
                    <p>Free shipping on orders above RM 200</p>
                </div>
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-undo"></i> Easy Returns
                    </h4>
                    <p>30-day return policy with no questions asked</p>
                </div>
                <div>
                    <h4 style="color: var(--gold-primary); font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-headset"></i> Support
                    </h4>
                    <p>24/7 customer service in multiple languages</p>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
       
		<?php
		 $result = mysqli_query($conn, "SELECT * FROM testimoni limit 8");
		 if(mysqli_num_rows($result) > 0){
		?>
	   <div class="content-section" style="padding: 2rem;">
            <h2 class="section-title">Customer Testimonials</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
               <?php 
				  
				   while ($row = mysqli_fetch_assoc($result)) { 
			   ?>
               <div class="testimonial-card">
                    <div class="testimonial-header">
                        <img src="<?php echo $testimonialPath.$row['image'];?>" alt="Customer 3" class="img-fluid" >                        
                    </div>                    
                </div>
				<?php
			   }
				?>

            </div>
        </div>
		<?php
		 }
		?>
		
		
    </div>

    <!-- Footer -->
	<?php include_once('includes/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script> 


    <script>
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
    </script>

</body>

</html>