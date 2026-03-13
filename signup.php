<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/style_signup.css" rel="stylesheet" type="text/css" />
	<style>
	.social-signup {
    display: flex;
    justify-content: center;
}
	</style>
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
					<li><a href="login.php" title="login" class="gapicon"><i
								class="bi bi-person-bounding-box fa-2x"></i></a></li>
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

    <div class="signup-container">
        <div class="signup-card">
            <!-- Logo -->
            <div class="logo-icon">
                <div class="logo-circle">
                    <i class="fas fa-crown"></i>
                </div>
            </div>

            <h1 class="signup-title">Join Us</h1>
            <p class="signup-subtitle">Create your account to enjoy exclusive offers</p>

            <!-- Sign Up Form -->
            <form id="signupForm" action="" method="post">
				<div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
					<span style="color:red" id="error_name"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="abc@gmail.com">
					<span style="color:red" id="error_email"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+60 12345678">
					<span style="color:red" id="error_phone"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
					<span style="color:red" id="error_password"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
					<span style="color:red" id="error_confirm_password"></span>
                </div>

                <!-- Terms & Conditions -->
                <div class="terms">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <span class="terms-text">
                        I agree to the <a href="#" class="terms-link">Terms & Conditions</a> and <a href="#"
                            class="terms-link">Privacy Policy</a>
                    </span>
                </div>

                <button type="submit" id="btnSignUp" name="btnSignUp" class="btn-signup">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Social Sign Up -->
            <div class="social-signup">
                <!--<button type="button" class="btn-social" onclick="facebookRegister()">
					<i class="fab fa-facebook-f"></i> Facebook
				</button>
                <button type="button" class="btn-social" onclick="googleLoginRegister()">
                    <i class="fab fa-google"></i> Google
                </button>-->
				  <button type="button" class="btn-social" onclick="googleLoginRegister()">
					<i class="fab fa-google"></i> Google
				</button>
            </div>

            <!-- Login Link -->
            <div class="login-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
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
	
	<script src="https://accounts.google.com/gsi/client" async defer></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/profile.js"></script>
	
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

        function handleSignUp(event) {
            event.preventDefault();

            // Get form values for password confirmation
            const form = event.target;
            const password = form.querySelector('input[type="password"]:nth-of-type(1)').value;
            const confirmPassword = form.querySelector('input[type="password"]:nth-of-type(2)').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            alert('Account created successfully! Redirecting to login...');
            window.location.href = 'login.php';
        }
    </script>
	
	<div id="fb-root"></div>

	<script>
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '1464757282025386',
			cookie     : true,
			xfbml      : false,
			version    : 'v25.0'
		});
	};

	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	
</body>

</html>