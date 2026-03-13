<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_SESSION['userID']))
{
	echo "<script>location.href = 'profile.php';</script>";
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/style_login.css" rel="stylesheet" type="text/css" />
</head>

<body>
   
   	<!-- Navbar -->
	<!-- Navigation Bar -->
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

    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-icon">
                <div class="logo-circle">
                    <i class="fas fa-user"></i>
                </div>
            </div>

            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your account to continue shopping</p>

            <!-- Login Form -->
            <form action="" method="post">
			
			<input type="text" name="fakeuser" style="display:none">
			<input type="password" name="fakepass" style="display:none">
			
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" required>
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="forgetpassword.php" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" id="btnLogin" name="btnLogin" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Social Login -->
            <div class="social-login">
                <button class="btn-social" onclick="facebookLogin()">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
                <button class="btn-social" onclick="googleLoginRegister()">
                    <i class="fab fa-google"></i> Google
                </button>
            </div>

            <!-- Sign Up Link -->
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign Up Now</a>
            </div>
        </div>
    </div>
	
	
	  <?php
		if(isset($_POST['btnLogin'])){
			
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			$result=mysqli_query($conn,"select * from user_account where email = '$email' and password = '$password' and status = 'ACTIVE' limit 1");
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_assoc($result);
				
				$_SESSION['userID'] = $row['userID'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['password'] = $row['password'];
				
				$userID = $row['userID'];
				$email = $row['email'];
				$password = $row['password'];
				$role = $row['role'];
				
				if(isset($_POST['remember']))
				{	
					 setcookie('userID', $userID, time() + (86400 * 30), "/");
					 setcookie('email', $email, time() + (86400 * 30), "/");
					 setcookie('password', $password, time() + (86400 * 30), "/");
					
				}
				
				//admin
				if($role == "admin")
				{
					echo "<script>location.href = 'admin/';</script>";
				}
				
				if($role == "customer")
				{
					
					
						echo "<script>location.href = 'profile.php';</script>";
					
					
				}
				
			}
			
		
		}
	?>
	

    <!-- Footer -->
   <?php include_once('includes/footer.php'); ?>
   
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

        function handleLogin(event) {
            event.preventDefault();
            alert('Login successful! Redirecting to home...');
            window.location.href = 'index.php';
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
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
</body>

</html>