<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
include_once('includes/authentication.php');

// Pagination settings
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$count_sql = "SELECT COUNT(DISTINCT a.userID) as total
              FROM payment_transaction a
              INNER JOIN user_account b ON a.userID = b.userID
              WHERE a.payment_status = 'Paid'";

if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $count_sql .= " AND (b.name LIKE '%$search_safe%' 
                    OR b.email LIKE '%$search_safe%' 
                    OR b.phone LIKE '%$search_safe%')";
}

$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

?>


<?php
if (isset($_GET['export']) && $_GET['export'] == "1") {

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="customer_orders_export.csv"');

    $output = fopen("php://output", "w");

    // CSV Column Headers
    fputcsv($output, [
        'Customer',
        'Email',
        'Phone',
        'Total Orders',
        'Total Spent',
        'Last Order'
    ]);

   

    // $exportSql = "SELECT ua.userID, ua.name, ua.profile, ua.email, ua.phone,
											// COUNT(a.ordercode) AS total_orders,
											// SUM(
												// (b.quantity * b.price)
											// ) 
											// + SUM(
												// CASE 
													// WHEN LOWER(c.delivery_method) = 'standard' THEN 8
													// WHEN LOWER(c.delivery_method) = 'foreign' THEN 18
													// ELSE 0
												// END
											// ) AS total_spent,
											// MAX(a.payment_date) AS last_order
										// FROM payment_transaction a
										// INNER JOIN orders b ON a.ordercode = b.order_code
										// INNER JOIN checkout c ON b.order_code = c.order_code
										// INNER JOIN user_account ua ON a.userID = ua.userID
										// WHERE a.payment_status IN ('Paid')
										// GROUP BY ua.userID
										// ORDER BY ua.name";
	
	$exportSql = "SELECT userID, name, profile, email, phone, SUM(total_spent) AS total_spent_by_customer, MAX(payment_date) AS last_order, COUNT(ordercode) AS total_orders
									FROM (
										SELECT 
											a.ordercode, 
											ua.userID,
											ua.name,
											ua.profile,
											ua.email,
											ua.phone,
											a.payment_date,
											SUM(b.quantity * b.price) + 
											(CASE 
												WHEN LOWER(c.delivery_method) = 'standard' THEN 8
												WHEN LOWER(c.delivery_method) = 'foreign' THEN 18
												ELSE 0
											END) AS total_spent
										FROM payment_transaction a
										INNER JOIN orders b ON a.ordercode = b.order_code
										INNER JOIN checkout c ON b.order_code = c.order_code
										INNER JOIN user_account ua ON a.userID = ua.userID
										WHERE a.payment_status = 'Paid'
										GROUP BY 
													a.ordercode,
													ua.name,
													ua.profile,
													ua.email,
													ua.phone,
													a.payment_date,
													c.delivery_method
												) x
												GROUP BY 
													name,
													profile,
													email,
													phone
												ORDER BY total_spent_by_customer DESC
												LIMIT $limit OFFSET $offset
										";
	
	$exportResult = mysqli_query($conn, $exportSql);

    while ($row = mysqli_fetch_assoc($exportResult)) {

        fputcsv($output, [
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['total_orders'],
            number_format($row['total_spent_by_customer'], 2),
			$row['last_order']
        ]);
    }

    fclose($output);
    exit();
}

$gradients = [
    "linear-gradient(45deg, #e74c3c, #f39c12)",
    "linear-gradient(45deg, #3498db, #9b59b6)",
    "linear-gradient(45deg, #1abc9c, #16a085)",
    "linear-gradient(45deg, #f1c40f, #e67e22)",
    "linear-gradient(45deg, #2ecc71, #27ae60)",
    "linear-gradient(45deg, #8e44ad, #2980b9)",
    "linear-gradient(45deg, #ff6b6b, #f06595)",
    "linear-gradient(45deg, #00c6ff, #0072ff)",

    "linear-gradient(45deg, #ff9a9e, #fad0c4)",
    "linear-gradient(45deg, #a18cd1, #fbc2eb)",
    "linear-gradient(45deg, #fad0c4, #ffd1ff)",
    "linear-gradient(45deg, #ffecd2, #fcb69f)",
    "linear-gradient(45deg, #a1c4fd, #c2e9fb)",
    "linear-gradient(45deg, #d4fc79, #96e6a1)",
    "linear-gradient(45deg, #84fab0, #8fd3f4)",
    "linear-gradient(45deg, #fccb90, #d57eeb)",

    "linear-gradient(45deg, #667eea, #764ba2)",
    "linear-gradient(45deg, #ff758c, #ff7eb3)",
    "linear-gradient(45deg, #43cea2, #185a9d)",
    "linear-gradient(45deg, #ff9966, #ff5e62)"
];

$rowIndex = 0;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Mookuthi Palace Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style_customer.css" rel="stylesheet" type="text/css" />
	
	<style>
	.customer-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}
	</style>
	
</head>

<body>
   <?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title"><i class="fas fa-users"></i> Purchased Customers</h1>
                <p class="page-subtitle">Manage customer relationships and view insights</p>
            </div>
           <form method="GET">
				<button type="submit" name="export" value="1" class="btn btn-primary">
					<i class="fas fa-download"></i> Export List
				</button>
			</form>
        </div>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value"><?php echo $ttl_customer;?></div>
                    <div class="stat-card-label">Total Customers</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-card-value"><?php echo $new_this_month;?></div>
                    <div class="stat-card-label">New This Month</div>
                </div>
            </div>
            
        </div>

       

        <!-- Customers Table -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-list"></i> All Purchased Customers
            </div>
			
            <!-- Data Table Header -->
            <form method="get">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--light-gold);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="color: var(--text-dark); font-weight: 600;">Show</label>
                    <select onchange="window.location='?limit='+this.value;" style="border: 2px solid var(--light-gold); border-radius: 6px; padding: 0.4rem 0.8rem; cursor: pointer;">
						<option value="10" <?= $limit==10?'selected':'' ?> >10</option>
                        <option value="25" <?= $limit==25?'selected':'' ?> >25</option>
                        <option value="50" <?= $limit==50?'selected':'' ?> >50</option>
                        <option value="100" <?= $limit==100?'selected':'' ?> >100</option>
                    </select>
                    <label style="color: var(--text-dark); font-weight: 600;">entries</label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="color: var(--text-dark); font-weight: 600;">Search:</label>
                    <input type="text" placeholder="Search customers..." id="search" name="search" value="<?= htmlspecialchars($search) ?>" onkeypress="if(event.key==='Enter'){doSearch(this.value)}" style="border: 2px solid var(--light-gold); border-radius: 6px; padding: 0.5rem 1rem; width: 100px;">
                </div>
            </div>
			</form>
			
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="min-width: 100%; width: 100%;">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Last Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
					
						<?php
								
								$sql="SELECT userID, name, profile, email, phone, SUM(total_spent) AS total_spent_by_customer, MAX(payment_date) AS last_order, COUNT(ordercode) AS total_orders
									FROM (
										SELECT 
											a.ordercode, 
											ua.userID,
											ua.name,
											ua.profile,
											ua.email,
											ua.phone,
											a.payment_date,
											SUM(b.quantity * b.price) + 
											(CASE 
												WHEN LOWER(c.delivery_method) = 'standard' THEN 8
												WHEN LOWER(c.delivery_method) = 'foreign' THEN 18
												ELSE 0
											END) AS total_spent
										FROM payment_transaction a
										INNER JOIN orders b ON a.ordercode = b.order_code
										INNER JOIN checkout c ON b.order_code = c.order_code
										INNER JOIN user_account ua ON a.userID = ua.userID
										WHERE a.payment_status = 'Paid'";

								if (!empty($search)) {
									$search_safe = mysqli_real_escape_string($conn, $search);
									 $sql .= " AND (
													ua.name LIKE '%$search_safe%' 
													OR ua.email LIKE '%$search_safe%' 
													OR ua.phone LIKE '%$search_safe%'
												  )";
								}

								$sql .= " GROUP BY 
													a.ordercode,
													ua.userID,
													ua.name,
													ua.profile,
													ua.email,
													ua.phone,
													a.payment_date,
													c.delivery_method
												) x
												GROUP BY 
													userID,	
													name,
													profile,
													email,
													phone
												ORDER BY total_spent_by_customer DESC
												LIMIT $limit OFFSET $offset";
										  
								$result = mysqli_query($conn, $sql);
							while($row = mysqli_fetch_assoc($result)) {
								
								$currentGradient = $gradients[$rowIndex % count($gradients)];
								$rowIndex++;
								
						?>
					
                        <tr>
                           <td>
								<div style="display: flex; align-items: center; gap: 0.8rem;">
									
									<?php if(!empty($row['profile'])) { ?>
										
										<img src="../uploads/<?php echo $row['profile']; ?>" class="customer-img">
										<div class="customer-name"> <?php echo $row['name']; ?> </div>
											 
									<?php } else { ?>
									
									 <?php
										$nameParts = explode(" ", trim($row['name']));
										$initials = "";

										foreach($nameParts as $part) {
											if(!empty($part)) {
												$initials .= strtoupper($part[0]);
											}
										}

										$initials = substr($initials, 0, 2); // limit to 2 letters
									?>

									<div class="customer-avatar" style="background: <?php echo $currentGradient; ?>;"><?php echo $initials;?></div>
									<div class="customer-name"> <?php echo $row['name']; ?> </div>
									<?php } ?>
								</div>
							</td>
                            <td>
                                <span class="customer-email"><?php echo $row['email'];?></span>
                            </td>
                            <td><?php echo $row['phone'];?></td>
                            <td><strong><?php echo $row['total_orders'];?></strong></td>
                            <td><strong>RM <?php echo number_format((float)$row['total_spent_by_customer'], 2, '.', '');?></strong></td>
                            <td><?php echo date("M d, Y", strtotime($row['last_order']));?></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn-sm btn-view" href="order_list.php?userID=<?php echo $row['userID'];?>"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php
							}
						?>
					
					</tbody>
                </table>
            </div>
            <!-- Data Table Footer -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2"
                style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--light-gold);">
                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    <?php
					$start = ($total_records > 0) ? $offset + 1 : 0;
					$end = min($offset + $limit, $total_records);
					?>
					Showing <?= $start ?> to <?= $end ?> of <?= $total_records ?> entries
                </div>
				
               <div class="d-flex align-items-center gap-2">
				<?php if ($page > 1): ?>
					<a href="?page=<?= $page-1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"
					   class="btn btn-sm"
					   style="border: 2px solid var(--light-gold); border-radius: 6px; padding: 0.5rem 0.8rem;">
					   Previous
					</a>
				<?php endif; ?>

				<?php for ($i = 1; $i <= $total_pages; $i++): ?>
					<a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"
					   class="btn btn-sm <?= $i==$page ? '' : '' ?>"
					   style="<?= $i==$page ? 
					   'background: var(--primary-gold); color: var(--primary-blue);' :
					   'border: 2px solid var(--light-gold);' ?>
					   border-radius: 6px; padding: 0.5rem 0.8rem;">
					   <?= $i ?>
					</a>
				<?php endfor; ?>

				<?php if ($page < $total_pages): ?>
					<a href="?page=<?= $page+1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"
					   class="btn btn-sm"
					   style="border: 2px solid var(--light-gold); border-radius: 6px; padding: 0.5rem 0.8rem;">
					   Next
					</a>
				<?php endif; ?>

				</div>
            </div>
        </div> <!-- End dashboard-card -->
    
	</div> <!-- End main-content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        const mobileToggle = document.getElementById("mobileToggle");
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        if (mobileToggle && sidebar && sidebarOverlay) {
            function toggleSidebar() {
                sidebar.classList.toggle("show");
                sidebarOverlay.classList.toggle("show");
                document.body.style.overflow = sidebar.classList.contains("show") ? "hidden" : "";
            }

            function closeSidebar() {
                sidebar.classList.remove("show");
                sidebarOverlay.classList.remove("show");
                document.body.style.overflow = "";
            }

            mobileToggle.addEventListener("click", toggleSidebar);
            sidebarOverlay.addEventListener("click", closeSidebar);

            document.querySelectorAll(".nav-link").forEach(link => {
                link.addEventListener("click", () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        }
        // Add data-label attributes for mobile stacked table view
        document.addEventListener("DOMContentLoaded", () => {
            const table = document.querySelector(".table-responsive table");
            if (!table) return;
            const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.textContent.trim());
            table.querySelectorAll("tbody tr").forEach(tr => {
                Array.from(tr.children).forEach((cell, i) => {
                    if (cell.tagName === "TD" && !cell.getAttribute("data-label")) {
                        cell.setAttribute("data-label", headers[i] || "");
                    }
                });
            });
        });

    </script>
</body>

</html>
