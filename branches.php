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
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Branches - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap"
        rel="stylesheet">
		
	<link href="css/style_branches.css" rel="stylesheet" type="text/css" />
   

</head>

<body>
    
	<?php include_once('includes/navbar.php'); ?>
   
	<?php include_once('includes/side_bar.php'); ?>
	
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

	<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
	<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
    <!-- Hero -->
    <div class="hero">
        <div class="container">
            <h1 class="page-title">Visit Our Branches</h1>
            <p style="color: #ccc; font-size: 1.1rem;">Find a Mookhuthi Palace near you</p>
        </div>
    </div>

    <!-- Branches -->
    <div class="container">
        <div class="branches-grid">
            <!-- Branch 1: Kuala Lumpur (Flagship) -->
            <div class="branch-card">
                <div class="branch-header">
                    <div class="branch-icon">👑</div>
                    <div>
                        <div class="branch-name">Mookhuthi Palace Kuala Lumpur</div>
                        <p style="color: #999; font-size: 0.85rem; margin: 0;">Kuala Lumpur</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="detail-info">
                        <h5>Address</h5>
                        <p>LOT 75-G Medan Bunus, Off Jalan Masjid India, 50100 Kuala Lumpur, WILAYAH PERSEKUTUAN</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                    <div class="detail-info">
                        <h5>Phone</h5>
                        <p>016-865 4786</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="detail-info">
                        <h5>Email</h5>
                        <p>mookhuthipalace75@gmail.com</p>
                    </div>
                </div>

                <div class="branch-hours">
                    <div class="hours-title">Opening Hours</div>
                    <div class="hours-list">
                        <div>Everyday: 10:30 AM - 6:45 PM</div>
                    </div>
                </div>

                <button class="contact-button" onclick="window.location.href='tel:016-8654786'">
                    <i class="fas fa-phone"></i> Call Now
                </button>
                <div class="footer-connect p-4">
                    <h5>Connect With Us</h5>
                <div class="social-links">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/share/1BHDFNVE4d/" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <!-- Instagram -->
                    <a href="https://www.instagram.com/mookuthipalaceklbranch?igsh=MWs5YXZ1eXRydWZsbA=="
                        target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/message/N3EVDBT6UJH3O1" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@mookuthipalace?_r=1&_t=ZS-94ayye9i3mv" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <!-- YouTube (optional if added later) -->
                    <a href="#" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            </div>

            <!-- Branch 2: Taiping -->
            <div class="branch-card">
                <div class="branch-header">
                    <div class="branch-icon">💎</div>
                    <div>
                        <div class="branch-name">Mookhuthi Palace Taiping</div>
                        <p style="color: #999; font-size: 0.85rem; margin: 0;">Perak Branch</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="detail-info">
                        <h5>Address</h5>
                        <p>No. 136, Jalan Pasar, 34000 Taiping, Perak</p><br>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                    <div class="detail-info">
                        <h5>Phone</h5>
                        <p>010-387 2626</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="detail-info">
                        <h5>Email</h5>
                        <p>mookhuthipalace136@gmail.com</p>
                    </div>
                </div>

                <div class="branch-hours">
                    <div class="hours-title">Opening Hours</div>
                    <div class="hours-list">
                        <div>Everyday: 9:30 AM - 5:45 PM</div>
                    </div>
                </div>

                <button class="contact-button" onclick="window.location.href='tel:0103872626'">
                    <i class="fas fa-phone"></i> Call Now
                </button>
                <div class="footer-connect p-4">
                    <h5>Connect With Us</h5>
                <div class="social-links">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/share/18XtWV617J/?mibextid=wwXIfr&wa_status_inline=true"
                        target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <!-- Instagram -->
                    <a href="https://www.instagram.com/mookhuthipalacetaiping?igsh=MW0ya3EwZTloYjl3Ng%3D%3D&utm_source=qr"
                        target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/message/WFLQVJ2AR34IE1" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@mookhuthipalacetaiping?_r=1&_t=ZS-94b0Gpx0IlR" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <!-- YouTube (optional if you add later) -->
                    <a href="#" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            </div>

            <!-- Branch 3: Ipoh -->
            <div class="branch-card">
                <div class="branch-header">
                    <div class="branch-icon">✨</div>
                    <div>
                        <div class="branch-name">Mookhuthi Palace Ipoh</div>
                        <p style="color: #999; font-size: 0.85rem; margin: 0;">Perak Branch</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="detail-info">
                        <h5>Address</h5>
                        <p>271, Jalan Silibin, Taman Alkaff, 30100 Ipoh, Perak</p><br>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                    <div class="detail-info">
                        <h5>Phone</h5>
                        <p>012-335 4786</p>
                    </div>
                </div>

                <div class="branch-detail">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="detail-info">
                        <h5>Email</h5>
                        <p>mookhuthipalace271@gmail.com</p>
                    </div>
                </div>

                <div class="branch-hours">
                    <div class="hours-title">Opening Hours</div>
                    <div class="hours-list">
                        <div>Everyday: 10:00 AM - 6:15 PM</div>
                    </div>
                </div>

                <button class="contact-button" onclick="window.location.href='tel:0123354786'">
                    <i class="fas fa-phone"></i> Call Now
                </button>
                 <div class="footer-connect p-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/share/17yPEBqwaw/" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/mookhuthipalace271?igsh=MXV5ZGt4ejhxbW94cA=="
                            target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://wa.me/60123354786" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>

                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@mookhuthipalaceipoh?_r=1&_t=ZS-94b0A6eyX4u" target="_blank">
                            <i class="fab fa-tiktok"></i>
                        </a>

                        <!-- YouTube -->
                        <a href="#" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

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