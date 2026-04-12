<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
include_once('includes/authentication.php');
?>

<?php
$filter = "WHERE 1=1";

	if (!empty($_GET['userID'])) {
		$userID = mysqli_real_escape_string($conn, $_GET['userID']);
		$filter .= " AND ua.userID = '$userID'";
	}


	if (!empty($_GET['orderStatusFilter'])) {
		$orderStatus = mysqli_real_escape_string($conn, $_GET['orderStatusFilter']);
		$filter .= " AND a.is_delivered = '$orderStatus'";
	}

	if (!empty($_GET['paymentStatusFilter'])) {
		$paymentStatus = mysqli_real_escape_string($conn, $_GET['paymentStatusFilter']);
		$filter .= " AND a.payment_status = '$paymentStatus'";
	}

	if (!empty($_GET['orderDate'])) {
		$orderDate = $_GET['orderDate'];
		$filter .= " AND DATE(a.payment_date) = '$orderDate'";
	}


?>

<?php
if (isset($_GET['export']) && $_GET['export'] == "1") {
	
	//echo "<script>alert($filter)</script>";

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="cust_orders_export.csv"');

    $output = fopen("php://output", "w");

    // CSV Column Headers
    fputcsv($output, [
        'Order Code',
        'Customer',
        'Order Date',
        'Status',
        'Payment Status',
        'Amount',
        'Delivery Method'
    ]);

    // $exportSql = "SELECT a.ordercode, ua.name, c.delivery_method, 
                         // a.payment_status, a.payment_date, 
                         // a.is_delivered, 
                         // SUM(b.quantity * b.price) AS subtotal
                  // FROM payment_transaction a
                  // INNER JOIN orders b ON a.ordercode = b.order_code
                  // INNER JOIN checkout c ON b.order_code = c.order_code
                  // INNER JOIN user_account ua ON a.userID = ua.userID
                  // $filter
                  // GROUP BY a.ordercode
                  // ORDER BY b.ordersID DESC";
				  
	$exportSql = "SELECT 
								a.ordercode, 
								ua.name, 
								c.delivery_method, 
								a.payment_status, 
								c.collecting_point, 
								a.payment_date, 
								a.is_delivered, 
								SUM(b.quantity * b.price) AS subtotal
							FROM payment_transaction a
							INNER JOIN orders b ON a.ordercode = b.order_code
							INNER JOIN checkout c ON b.order_code = c.order_code
							INNER JOIN user_account ua ON a.userID = ua.userID
							$filter
							GROUP BY 
								a.ordercode,
								ua.name,
								c.delivery_method,
								a.payment_status,
								c.collecting_point,
								a.payment_date,
								a.is_delivered
							ORDER BY MAX(b.ordersID) DESC;";
				  
    $exportResult = mysqli_query($conn, $exportSql);

    while ($row = mysqli_fetch_assoc($exportResult)) {

        fputcsv($output, [
            $row['ordercode'],
            $row['name'],
            $row['payment_date'],
            $row['is_delivered'],
            $row['payment_status'],
            number_format($row['subtotal'], 2),
            ($row['delivery_method'] == "selfCollect") ? "Self Collect" : "Delivery"
        ]);
    }

    fclose($output);
    exit();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Mookuthi Palace Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style_order.css" rel="stylesheet" type="text/css" />
</head>

<body>
  	<?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>
	
	

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title"><i class="fas fa-shopping-bag"></i> Orders</h1>
                <p class="page-subtitle">Track and manage all customer orders</p>
            </div>
            <!--<button class="btn btn-primary">
                <i class="fas fa-download"></i> Export Orders
            </button>-->
			
			<form method="GET">
			
				<input type="hidden" name="userID" value="<?php echo $userID; ?>">
			
				<button type="submit" name="export" value="1" class="btn btn-primary">
					<i class="fas fa-download"></i> Export Orders
				</button>
			</form>
			
        </div>

        <!-- Filters -->
		<form method="GET" action="order_list.php">
		
        <div class="filters-section">
		
		  <input type="hidden" name="userID" value="<?php echo $userID; ?>">
		
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Order Status</label>
                    <select class="form-select" id="orderStatusFilter" name="orderStatusFilter" onchange="this.form.submit()">
                        <option value="" >All Orders</option>
                        <option value="Pending" <?= ($_GET['orderStatusFilter'] ?? '')=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Processing" <?= ($_GET['orderStatusFilter'] ?? '')=='Processing'?'selected':'' ?>>Processing</option>
                        <option value="Shipped" <?= ($_GET['orderStatusFilter'] ?? '')=='Shipped'?'selected':'' ?>>Shipped</option>
                        <option value="Delivered" <?= ($_GET['orderStatusFilter'] ?? '')=='Delivered'?'selected':'' ?>>Delivered</option>
                        <option value="Cancelled" <?= ($_GET['orderStatusFilter'] ?? '')=='Cancelled'?'selected':'' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Payment Status</label>
                    <select class="form-select" id="paymentStatusFilter" name="paymentStatusFilter" onchange="this.form.submit()">
                        <option value="">All Payments</option>
                        <option value="Paid" <?= ($_GET['paymentStatusFilter'] ?? '')=='Paid'?'selected':'' ?>>Paid</option>
                        <option value="Pending" <?= ($_GET['paymentStatusFilter'] ?? '')=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Failed" <?= ($_GET['paymentStatusFilter'] ?? '')=='Failed'?'selected':'' ?>>Failed</option>
                    </select>
                </div>
               
                <div class="col-md-3">
                    <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Order Date</label>
                    <input type="date" id="orderDate" name="orderDate" class="form-control" value="<?= $_GET['orderDate'] ?? '' ?>" onchange="this.form.submit()">
                </div>
            </div>
        </div>
		
		</form>

        <!-- Orders List -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-list"></i> Orders
            </div>
			
			<?php
			
			$statusClasses = [
				"Processing" => "warning",
				"Shipped"    => "info",
				"Cancelled"  => "danger",
				"Delivered"  => "success"
			];
			
			// $sql="SELECT a.ordercode, ua.name, c.delivery_method, c.payment_method, a.payment_status, c.collecting_point, a.payment_date, a.is_delivered, SUM(b.quantity * b.price) AS subtotal
					// FROM payment_transaction a
					// INNER JOIN orders b ON a.ordercode = b.order_code
					// INNER JOIN checkout c ON b.order_code = c.order_code
					// INNER JOIN user_account ua ON a.userID = ua.userID
					// $filter
					// GROUP BY a.ordercode
					// ORDER BY b.ordersID DESC";
					
					$sql="SELECT 
								a.ordercode, 
								ua.name, 
								c.delivery_method, 
								c.payment_method, 
								a.payment_status, 
								c.collecting_point, 
								a.payment_date, 
								a.is_delivered, 
								SUM(b.quantity * b.price) AS subtotal
							FROM payment_transaction a
							INNER JOIN orders b ON a.ordercode = b.order_code
							INNER JOIN checkout c ON b.order_code = c.order_code
							INNER JOIN user_account ua ON a.userID = ua.userID
							$filter
							GROUP BY 
								a.ordercode,
								ua.name,
								c.delivery_method,
								c.payment_method,
								a.payment_status,
								c.collecting_point,
								a.payment_date,
								a.is_delivered
							ORDER BY MAX(b.ordersID) DESC;";
					
					$result = mysqli_query($conn, $sql);
					while($row = mysqli_fetch_assoc($result)) {
						
						$delivery_method_desc = [
								"selfCollect" => "Self Collect",
								"standard" => "Standard",
								"foreign" => "Singapore"
							];
						
						
						$status = $row['is_delivered'];
						$class = $statusClasses[$status] ?? "secondary";
						
						$delivery_fee = 0;
						
						$fees = [
							"standard" => 8,
							"foreign" => 18
						];
						
						$delivery_fee = $fees[$row['delivery_method']] ?? 0;
						
						// if($row['delivery_method'] == "standard"){
							// $delivery_fee = 8;
						// }

						
						$productSql = "SELECT d.product_name, b.style, b.color, b.quantity FROM orders b
										INNER JOIN product d ON b.productID = d.productID
										WHERE b.order_code = '".$row['ordercode']."'";

						$productResult = mysqli_query($conn, $productSql);
						
			?>
			<div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id">#<?php echo $row['ordercode']; ?></div>
                        <div class="order-date"><?php echo date("M d, Y \a\t h:i A", strtotime($row['payment_date'])); ?></div>
                    </div>
                    <div>
                        <?php echo "<span class='badge bg-$class'>$status</span>"; ?>
                        <?php echo ($row['payment_status'] == "Paid") ? "<span class='badge badge-success ms-2'>Paid</span>" : (($row['payment_status'] == "Pending") ? "<span class='badge badge-info ms-2'>Pending</span>" : "<span class='badge badge-warning ms-2'>Fail</span>");?>
                    </div>
                </div>
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label">Customer</div>
                        <div class="detail-value"><?php echo $row['name']; ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Amount</div>
                        <div class="detail-value">RM <?php echo number_format((float)$row['subtotal'] + $delivery_fee, 2, '.', ''); ?></div>
                    </div>
                    <div class="detail-item">
						<div class="detail-label">Products</div>
						<div class="detail-value">
							<?php 
							while($product = mysqli_fetch_assoc($productResult)) {
								echo "
									<div>
										{$product['product_name']} 
										({$product['style']} - {$product['color']}) 
										x {$product['quantity']}
									</div>
								";
							}
							?>
						</div>
					</div>
                    <div class="detail-item">
                        <div class="detail-label">Delivery Method</div>
                        <div class="detail-value"><?php echo $delivery_method_desc[$row['delivery_method']];?></div>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="loadOrderDetailByOrderCode('<?php echo $row['ordercode']; ?>')"><i class="fas fa-eye"></i> View</button>
                    <button class="btn-sm btn-update" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="loadUpdateFormByOrderCode('<?php echo $row['ordercode']; ?>')"><i class="fas fa-edit"></i> Update</button>
                </div>
            </div>
			
			<?php
					   }
			?>

		</div>
    
	
	</div>
	
	

   <!-- View Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-detail-item">
                        <div class="detail-label">Order ID</div>
                        <div class="detail-value" id="viewOrderId">-</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Customer Name</div>
                                <div class="detail-value" id="viewCustomer">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Order Date</div>
                                <div class="detail-value" id="viewDate">-</div>
                            </div>
                        </div>
                    </div>
					<div class="modal-detail-item">
                        <div class="detail-label">Contact Number</div>
                        <div class="detail-value" id="viewPhone">-</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Amount</div>
                                <div class="detail-value" id="viewAmount">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Delivery Type</div>
                                <div class="detail-value" id="viewDeliveryType">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-detail-item">
                        <div class="detail-label">Product</div>
                        <div class="detail-value" id="viewProduct">-</div>
                    </div>
					<div class="modal-detail-item">
                        <div class="detail-label">Recommended Branch</div>
                        <div class="detail-value" id="viewRecommendedBranch">-</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Delivery Status</div>
                                <span class="badge" id="viewOrderStatus">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-detail-item">
                                <div class="detail-label">Payment Status</div>
                                <span class="badge" id="viewPaymentStatus">-</span>
                            </div>
                        </div>
                    </div>
					
					  <!-- Address Section -->
                    <hr style="border-color: var(--light-gold); margin: 1rem 0;">
                    <div style="font-weight: 700; color: var(--primary-blue); font-size: 0.95rem; margin-bottom: 0.8rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-gold);"></i> Delivery Addresses</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="modal-detail-item" style="background: rgba(212,175,55,0.07); border-radius: 10px; padding: 0.8rem 1rem;">
                                <div class="detail-label"><i class="fas fa-shipping-fast" style="color: var(--primary-gold);"></i> Shipping Address</div>
                                <div class="detail-value" id="viewShippingAddress" style="font-weight: 600; white-space: pre-line; line-height: 1.6;">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-detail-item" style="background: rgba(66,34,14,0.05); border-radius: 10px; padding: 0.8rem 1rem;">
                                <div class="detail-label"><i class="fas fa-file-invoice" style="color: var(--primary-gold);"></i> Billing Address</div>
                                <div class="detail-value" id="viewBillingAddress" style="font-weight: 600; white-space: pre-line; line-height: 1.6;">-</div>
                            </div>
                        </div>
                    </div>
					
					 <div class="modal-detail-item">
						<div class="detail-label">Pick Up Location</div>
						<div class="detail-value" id="viewPickUpDesc">-</div>
					</div>
					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-detail-item" style="margin-bottom: 2rem;">
                        <div class="detail-label">Order ID</div>
                        <div class="detail-value" id="updateOrderId">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Order Status</label>
                        <select class="form-select" id="orderStatusSelect" name="orderStatusSelect">
                            <option value="">Select Status</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Payment Status</label>
                        <select class="form-select" id="paymentStatusSelect" name="paymentStatusSelect">
                            <option value="">Select Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--primary-blue); font-weight: 600;">Notes (Optional)</label>
                        <textarea class="form-control" id="updateNotes" name="updateNotes" rows="3" placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUpdates()"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/orders.js"></script>
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

        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });

        // View Details Modal Functions
        function loadOrderDetails(orderId, customer, amount, product, branch, date, orderStatus, paymentStatus) {
            document.getElementById('viewOrderId').textContent = orderId;
            document.getElementById('viewCustomer').textContent = customer;
            document.getElementById('viewAmount').textContent = amount;
            document.getElementById('viewProduct').textContent = product;
            document.getElementById('viewBranch').textContent = branch;
            document.getElementById('viewDate').textContent = date;
            
            const statusBadge = document.getElementById('viewOrderStatus');
            statusBadge.textContent = orderStatus;
            statusBadge.className = 'badge ' + getStatusBadgeClass(orderStatus);
            
            const paymentBadge = document.getElementById('viewPaymentStatus');
            paymentBadge.textContent = paymentStatus;
            paymentBadge.className = 'badge ' + getPaymentBadgeClass(paymentStatus);
        }

        // Update Status Modal Functions
        function loadUpdateForm(orderId, currentOrderStatus, currentPaymentStatus) {
            document.getElementById('updateOrderId').textContent = orderId;
            document.getElementById('orderStatusSelect').value = currentOrderStatus;
            document.getElementById('paymentStatusSelect').value = currentPaymentStatus === 'Payment Pending' ? 'Pending' : currentPaymentStatus;
            document.getElementById('updateNotes').value = '';
        }

        function saveUpdate() {
            const orderId = document.getElementById('updateOrderId').textContent;
            const orderStatus = document.getElementById('orderStatusSelect').value;
            const paymentStatus = document.getElementById('paymentStatusSelect').value;
            const notes = document.getElementById('updateNotes').value;

            if (!orderStatus || !paymentStatus) {
                alert('Please select both order status and payment status');
                return;
            }

            alert(`Order ${orderId} updated!\n\nOrder Status: ${orderStatus}\nPayment Status: ${paymentStatus}${notes ? '\nNotes: ' + notes : ''}`);
            
            const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
            updateModal.hide();
        }

        function getStatusBadgeClass(status) {
            switch(status) {
                case 'Delivered':
                    return 'badge-success';
                case 'Processing':
                case 'Shipped':
                    return 'badge-warning';
                case 'Pending':
                    return 'badge-danger';
                default:
                    return 'badge-info';
            }
        }

        function getPaymentBadgeClass(status) {
            switch(status) {
                case 'Paid':
                    return 'badge-success';
                case 'Pending':
                case 'Payment Pending':
                    return 'badge-warning';
                case 'Failed':
                    return 'badge-danger';
                default:
                    return 'badge-info';
            }
        }
    </script>
</body>

</html>