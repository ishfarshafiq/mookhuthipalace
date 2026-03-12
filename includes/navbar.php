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
				<li class="cart-icon-wrapper">
					<a href="cart.php" title="Cart">
						<i class="bi bi-handbag fa-2x"></i>
						<span id="cart-badge" class="cart-badge"></span>
					</a>
				</li>
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
