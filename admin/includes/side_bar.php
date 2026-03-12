 <?php
// Get the current page name
$current_page = basename($_SERVER['REQUEST_URI'], ".php");

$sql_new_orders = mysqli_query($conn, "select count(*) as new_orders from checkout where new_orders = '1';");
$row_new_orders = mysqli_fetch_assoc($sql_new_orders);
$new_orders = $row_new_orders['new_orders'];
?>

<style>
.order-badge {
    background: #ff3b3b;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 50px;
    min-width: 22px;
    text-align: center;
}

</style>

<!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
         <div class="sidebar-inner" id="sidebarInner">
        <div class="sidebar-brand">
            <a href="index.php">
                <img src="img/mookuthi-palace-logo.png" width="150">
                <span>Mookhuthi Admin</span>
            </a>
        </div>


        <ul class="sidebar-menu">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= ($current_page == 'index') ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="products.php" class="nav-link <?= ($current_page == 'products') ? 'active' : '' ?>">
                    <i class="fas fa-gem"></i>
                    <span>Products</span>
                </a>
            </li>
			
			 <li class="nav-item">
                <a href="category.php" class="nav-link <?= ($current_page == 'category') ? 'active' : '' ?>">
					<i class="fas fa-dot-circle"></i>
                    <span>Category</span>
                </a>
            </li>
			
			 <li class="nav-item">
                <a href="materials.php" class="nav-link <?= ($current_page == 'materials') ? 'active' : '' ?>">
                    <i class="fas fa-ring"></i>
                    <span>Material</span>
                </a>
            </li>
            <li class="nav-item">
				<a href="orders.php" class="nav-link <?= ($current_page == 'orders') ? 'active' : '' ?>">
					<i class="fas fa-shopping-bag"></i>
					<span>Orders</span>

					<?php if($new_orders > 0): ?>
						<span class="order-badge"><?php echo $new_orders; ?></span>
					<?php endif; ?>
				</a>
			</li>
            <li class="nav-item">
                <a href="customers.php" class="nav-link <?= ($current_page == 'customers') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="testimoni.php" class="nav-link <?= ($current_page == 'testimoni') ? 'active' : '' ?>">
                    <i class="fas fa-quote-left"></i>
                    <span>Testimoni</span>
                </a>
            </li>
			<li class="nav-item">
                <a href="settings.php" class="nav-link <?= ($current_page == 'settings') ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="logout.php" class="nav-link <?= ($current_page == 'logout') ? 'active' : '' ?>">
                    <i class="fas fa-power-off"></i>
                    <span>Logout</span>
                </a>
            </li>


        </ul>
    </div></div>