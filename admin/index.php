<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
include_once('includes/authentication.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mookuthi Palace - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    
	<?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>
	
    <?php
	$filter = "WHERE MONTH(a.payment_date) = MONTH(CURRENT_DATE()) AND YEAR(a.payment_date) = YEAR(CURRENT_DATE())";
	?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title"><i class="fas fa-chart-line"></i> Dashboard</h1>
                    <p class="page-subtitle">Welcome back! Here's your business overview.</p>
                </div>
               <!-- <button class="btn btn-primary">
                    <i class="fas fa-download"></i> Export Report
                </button>-->
            </div>

            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-card-icon"
                            style="background: rgba(39, 174, 96, 0.1); color: var(--success-green);">
                           <i class="fas fa-sack-dollar"></i>



                        </div>
                        <div class="stat-card-label">Total Revenue</div>
                        <div class="stat-card-value">RM <?php echo $total_sales; ?></div>
                        <!--<div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </div>-->
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-card-label">Total Orders</div>
                        <div class="stat-card-value"><?php echo $total_orders;?></div>
                        <!--<div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i> 8% increase
                        </div>-->
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-card-icon"
                            style="background: rgba(231, 76, 60, 0.1); color: var(--danger-red);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-card-label">New Customers</div>
                        <div class="stat-card-value"><?php echo $total_cust;?></div>
                        <!--<div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i> 23% growth
                        </div>-->
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-card-icon"
                            style="background: rgba(212, 175, 55, 0.1); color: var(--primary-gold);">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-card-label">Products in Stock</div>
                        <div class="stat-card-value"><?php echo $total_product;?></div>
                       <!--<div class="stat-card-change negative">
                            <i class="fas fa-arrow-down"></i> 3 items low
                        </div>-->
                    </div>
                </div>
            </div>



            <!-- Recent Orders -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <div class="card-title">
                            <i class="fas fa-list"></i> Recent Orders
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
								
			<?php
			// $sql="SELECT a.ordercode, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, SUM(b.quantity * b.price) AS subtotal
					// FROM payment_transaction a
					// INNER JOIN orders b ON a.ordercode = b.order_code
					// INNER JOIN checkout c ON b.order_code = c.order_code
					// INNER JOIN user_account ua ON a.userID = ua.userID
					// $filter
					// GROUP BY a.ordercode
					// ORDER BY b.ordersID DESC";
					$result = mysqli_query($conn, "select * from payment_transaction WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) order by payment_transactionID desc");
					while($row = mysqli_fetch_assoc($result)) {
					
			?>
					<tr>
						<td>#<?php echo $row['ordercode']; ?></td>
						<td><?php echo $row['name']; ?></td>
						<td>RM <?php echo number_format((float)$row['billamount'], 2, '.', ''); ?></td>
						<td><?php echo date("M d, Y  h:i A", strtotime($row['payment_date'])); ?></td>
						<td>
						<?php if($row['payment_status'] == "Paid"){ ?>
							<span class="badge badge-success">Paid</span>
						<?php } else if($row['payment_status'] == "Pending"){ ?>
							<span class="badge badge-warning">Pending</span>
						<?php } else { ?>
							<span class="badge badge-danger">Fail</span>
						<?php } ?>
						</td>
					</tr>
			
			<?php
					   }
			?>
								
                                  <!--  <tr>
                                        <td>#NNMY001</td>
                                        <td>Ahmad Hassan</td>
                                        <td>RM 1,549</td>
                                        <td>Jan 15, 2026</td>
                                        <td><span class="badge badge-success">Delivered</span></td>
                                    </tr>
                                    <tr>
                                        <td>#NNMY002</td>
                                        <td>Siti Nurhaliza</td>
                                        <td>RM 3,299</td>
                                        <td>Jan 14, 2026</td>
                                        <td><span class="badge badge-warning">Processing</span></td>
                                    </tr>
                                    <tr>
                                        <td>#NNMY003</td>
                                        <td>Rajesh Kumar</td>
                                        <td>RM 899</td>
                                        <td>Jan 13, 2026</td>
                                        <td><span class="badge badge-success">Delivered</span></td>
                                    </tr>
                                    <tr>
                                        <td>#NNMY004</td>
                                        <td>Lim Wei Chen</td>
                                        <td>RM 649</td>
                                        <td>Jan 12, 2026</td>
                                        <td><span class="badge badge-danger">Pending</span></td>
                                    </tr>-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-4">
                    <div class="dashboard-card">
                        <div class="card-title">
                            <i class="fas fa-lightning-bolt"></i> Quick Actions
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                            <a href="products.php" class="btn btn-primary w-100"
                                style="background: rgba(0, 102, 179, 0.1); color: var(--primary-blue); border: 2px solid var(--light-gold); padding: 0.8rem;">
                                <i class="fas fa-plus"></i> Add New Product
                            </a>
                            <a href="orders.php" class="btn btn-primary w-100"
                                style="background: rgba(0, 102, 179, 0.1); color: var(--primary-blue); border: 2px solid var(--light-gold); padding: 0.8rem;">
                                <i class="fas fa-cube"></i> Manage Orders
                            </a>
                            <a href="customers.php" class="btn btn-primary w-100"
                                style="background: rgba(0, 102, 179, 0.1); color: var(--primary-blue); border: 2px solid var(--light-gold); padding: 0.8rem;">
                                <i class="fas fa-user-plus"></i> View Customers
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        mobileToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking a menu link on mobile
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    </script>
</body>

</html>