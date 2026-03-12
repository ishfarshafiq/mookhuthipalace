<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
include_once('includes/authentication.php');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Mookuthi Palace Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style_product.css" rel="stylesheet" type="text/css" />
	
	<script>
	
function previewImages1(event) {
    var files = event.target.files;
    var output = document.getElementById('outputPreview1');
    output.innerHTML = ''; // Clear previous

    for (var i = 0; i < files.length; i++) {
        var img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.style.width = "150px";
        img.style.height = "100px";
        img.style.margin = "5px";
        output.appendChild(img);
    }
}
	
function previewImages(event) {
    var files = event.target.files;
    var output = document.getElementById('outputPreview');
    output.innerHTML = ''; // Clear previous

    for (var i = 0; i < files.length; i++) {
        var img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.style.width = "150px";
        img.style.height = "100px";
        img.style.margin = "5px";
        output.appendChild(img);
    }
}

function edit_ProductpreviewImages1(event) {
    var files = event.target.files;
    var output = document.getElementById('edit_outputPreview1');
    output.innerHTML = ''; // Clear previous

    for (var i = 0; i < files.length; i++) {
        var img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.style.width = "150px";
        img.style.height = "100px";
        img.style.margin = "5px";
        output.appendChild(img);
    }
}
	
function edit_previewImages(event) {
    var files = event.target.files;
    var output = document.getElementById('edit_outputPreview');
    output.innerHTML = ''; // Clear previous

    for (var i = 0; i < files.length; i++) {
        var img = document.createElement("img");
        img.src = URL.createObjectURL(files[i]);
        img.style.width = "150px";
        img.style.height = "100px";
        img.style.margin = "5px";
        output.appendChild(img);
    }
}
</script>
</head>

<body>
    <?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>


	<?php							
		$filter = "WHERE a.is_delete = '0'";

		// // STATUS FILTER
		if (!empty($_GET['statusFilter'])) {
			$status = mysqli_real_escape_string($conn, $_GET['statusFilter']);
			$filter .= " AND a.status = '$status'";
		}

		// CATEGORY FILTER
		if (!empty($_GET['categoryFilter'])) {
			$category = mysqli_real_escape_string($conn, $_GET['categoryFilter']);
			$filter .= " AND b.category = '$category'";
		}
		
		if (!empty($_GET['priceFilter'])) {
			$price = (int)$_GET['priceFilter'];
			$filter .= " AND a.original_price <= $price";
		}
		
		if (!empty($_GET['search'])) {
			$search = $_GET['search'];
			$filter .= " AND a.product_name like '%$search%' or a.sku like '%$search%' or b.category like '%$search%' or c.material like '%$search%' or a.gemstone like '%$search%'";
		}
	?>
    
<!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h1>Products Inventory</h1>
            </div>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus-circle"></i> Add Product
            </button>
        </div>
		
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filter Products
            </div>
			<form method="GET">
            
			<div class="row g-3">
                <div class="col-md-4">
					<?php
					$result = mysqli_query($conn, "SELECT * FROM category WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY categoryID DESC");
					$selectedCategory = $_GET['categoryFilter'] ?? '';
					?>

					<label class="form-label">Category</label>
					<select id="categoryFilter" name="categoryFilter" class="form-select" onchange="this.form.submit()">
						<option value="">All Categories</option>
						<?php while($row = mysqli_fetch_assoc($result)) { ?>
							<option value="<?= htmlspecialchars($row['category']); ?>" <?= ($selectedCategory == $row['category']) ? 'selected' : ''; ?>>
								<?= htmlspecialchars($row['category']); ?>
							</option>

						<?php } ?>
					</select>
				</div>
                
			
                <div class="col-md-4">
					
                    <label class="form-label">Stock Status</label>
                    <select name="statusFilter" id="statusFilter" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
						<option value="ACTIVE" <?= ($_GET['statusFilter'] ?? '')=='ACTIVE'?'selected':'' ?>>Active</option>
                        <option value="IN STOCK" <?= ($_GET['statusFilter'] ?? '')=='IN STOCK'?'selected':'' ?>>In Stock</option>
                        <option value="LOW STOCK" <?= ($_GET['statusFilter'] ?? '')=='LOW STOCK'?'selected':'' ?>>Low Stock</option>
                        <option value="OUT OF STOCK" <?= ($_GET['statusFilter'] ?? '')=='OUT OF STOCK'?'selected':'' ?>>Out of Stock</option>
						<option value="INACTIVE" <?= ($_GET['statusFilter'] ?? '')=='INACTIVE'?'selected':'' ?>>Inactive</option>
                    </select>
					
				</div>
				
				
                
				<!-- PRICE RANGE -->
				<div class="col-md-4">
					<label class="form-label">
						Price Range:
						<strong class="text-primary">
							RM <span id="priceValue"><?= $_GET['priceFilter'] ?? 50000 ?></span>
						</strong>
					</label>

					<input type="range" class="form-range" name="priceFilter" min="0" max="100000" step="50" value="<?= $_GET['priceFilter'] ?? 10000 ?>" oninput="priceValue.innerText=this.value" onchange="this.form.submit()" >
				</div>
				
				<div class="col-md-4">
					<label class="form-label">Search</label>
					<input type="text" id="search" name="search" class="form-control" placeholder="Search products..">
				</div>
				
	<div class="col-md-4 d-flex align-items-end">
    <a href="<?= strtok($_SERVER["REQUEST_URI"], '?'); ?>" 
       class="btn btn-outline-secondary w-50">
        <i class="bi bi-arrow-counterclockwise me-2"></i>
        Reset Filters
    </a>
</div>

				
            </div>
			
			</form>
      
		
		<?php
		
		$limit = 10; // products per page
		$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
		$offset = ($page - 1) * $limit;
		
		$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM product WHERE is_delete = '0'");
		$countRow = mysqli_fetch_assoc($countResult);
		$totalRecords = $countRow['total'];
		$totalPages = ceil($totalRecords / $limit);
		
					
			$sql = "SELECT a.productID, a.product_name, a.sku, b.category, a.original_price, a.price, a.stock_quantity, c.material, a.is_bestseller, a.status, a.product_image
			FROM product a
			inner join category b on a.categoryID = b.categoryID
			inner join material c on a.materialID = c.materialID
			$filter
			ORDER BY a.productID DESC
			LIMIT $limit OFFSET $offset";
			
			$result = mysqli_query($conn, $sql);
			?>

        <!-- Products Table -->
        <div class="dashboard-card mt-4">
            <div class="card-title">
                <i class="fas fa-list"></i> All Products
            </div>
            <div class="table-responsive">
                <table class="table product-table mb-0">
                    <thead>
                        <tr>
                            <th class="sortable" onclick="sortTable('product')">Product</th>
                            <th class="sortable" onclick="sortTable('sku')">SKU</th>
                            <th class="sortable" onclick="sortTable('category')">Category</th>
							<th class="sortable" onclick="sortTable('price')">Original Price</th>
                            <th class="sortable" onclick="sortTable('price')">Promo Price</th>
                            <th class="sortable" onclick="sortTable('stock')">Stock</th>
                            <th class="sortable" onclick="sortTable('material')">Material</th>
							<th class="sortable" onclick="sortTable('bestselling')">Best Selling</th>
                            <th class="sortable" onclick="sortTable('status')">Status</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                  <tbody>
					<?php if (mysqli_num_rows($result) > 0): ?>
						<?php $i = 0; ?>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
						
						 <tr>
							<td>
							 <div class="product-info">
								<div class="product-image">
									<?php if (!empty($row['product_image'])): ?>
										<img src="<?= "uploads/products/".$row['product_image']; ?>" alt="Product Image">
									<?php else: ?>
										<i class="fas fa-gem"></i>
									<?php endif; ?>
								</div>
								<div class="product-details">
									<div class="product-name"><?= $row['product_name']; ?></div>
									<div class="product-sku"><?= $row['sku']; ?></div>
								</div>
							</div>
							</td>
							<td><?= $row['sku']; ?></td>
							<td><?= $row['category']; ?></td>
							<td><strong>RM <?= $row['original_price']; ?></strong></td>
							<td><strong><?php if($row['price']!=""){ echo "RM ".$row['price'];} ?></strong></td>
							<td><strong><?= $row['stock_quantity']; ?></strong></td>
							<td><?= $row['material']; ?></td>
							<td><center><input type="checkbox" value="1" <?= !empty($row['is_bestseller']) ? 'checked' : '' ?> onchange="updateBestseller(this, <?= $row['productID']; ?>)"></center></td>
							<td>
								<?php
								if($row['status']== "ACTIVE")
								{
									?>
									<span class="badge badge-success">Active</span>
									<?php
								}
								else if($row['status']== "LOW STOCK")
								{
									?>
									<span class="badge badge-warning">Low Stock</span>
									<?php
								}
								else if($row['status']== "OUT OF STOCK")
								{
									?>
									<span class="badge badge-danger">Out of stock</span>
									<?php
								}
								else
								{
									?>
									<span class="badge badge-danger">Inactive</span>
									<?php
								}
								?>
							</td>
							<td>
								<div class="action-buttons">
									<button class="btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="editProducts(<?= $row['productID']; ?>)">
										<i class="fas fa-edit"></i>
									</button>
									<button class="btn-sm btn-delete" type="button" onclick="deleteProducts(<?= $row['productID']; ?>)">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</td>
						</tr>
						
							<?php 
							
							$i++;
							
							endwhile; 
							
							?>
							
							<?php else: ?>
							
							<tr>
								<td colspan="9" class="text-center text-muted">
									No products found
								</td>
							</tr>
							<?php endif; ?>
							
						
					</tbody>
                </table>
            </div>
			
            <div class="pagination-section">
                <div class="pagination-info">
                   Showing <?= min($offset + $limit, $totalRecords); ?> of <?= $totalRecords; ?> products
                </div>
               <div class="paginations">
					<ul class="pagination mb-0">
						<!-- Previous -->
						<li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
							<a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
						</li>

						<!-- Page numbers -->
						<?php for ($p = 1; $p <= $totalPages; $p++): ?>
							<li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
								<a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
							</li>
						<?php endfor; ?>

						<!-- Next -->
						<li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
							<a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
						</li>
					</ul>
				</div>

            </div>
			
			
        </div>
    </main>

	
	
	 <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
		 <form action="add_product.php" id="addProductForm" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Add New Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   
					
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="e.g., PROD001" required>
                            </div>
                        </div>
						
						<h6>Specifications</h6>
						<hr/>
						
                        <div class="row mb-3">
                            
							<div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="categoryID" name="categoryID">
								<option value="">Select Categories</option>
								<?php
								$result = mysqli_query($conn, "SELECT * FROM category WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY categoryID DESC");
								 while($row = mysqli_fetch_assoc($result)) {
								?>
                                  <option value="<?= htmlspecialchars($row['categoryID']); ?>"><?= htmlspecialchars($row['category']); ?></option>
								<?php } ?>
                                </select>
                            </div>
							
							<div class="col-md-6">
                                <label class="form-label">Material</label>
								
								<select class="form-select" id="materialID" name="materialID">
								<option value="">Select Material</option>
								<?php
								$result = mysqli_query($conn, "SELECT * FROM material WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY materialID DESC");
								 while($row = mysqli_fetch_assoc($result)) {
								?>
                                  <option value="<?= htmlspecialchars($row['materialID']); ?>"><?= htmlspecialchars($row['material']); ?></option>
								<?php } ?>
                                </select>
								
                                <!--<input type="text" id="material" name="material" class="form-control" placeholder="e.g., 22K Gold">-->
                            </div>
							
                        </div>
						
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Gemstone</label>
                                <input type="text" id="gemstone" name="gemstone" class="form-control" placeholder="e.g., American Diamonds">
                            </div>
							
							<div class="col-md-6">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" placeholder="0" required>
                            </div>
                        </div>
						
                        <div class="row mb-3">
						
							<div class="col-md-6">
                                <label class="form-label">Original Price (RM)</label>
                                <input type="number" id="original_price" name="original_price" class="form-control" placeholder="0.00" step="0.01" required>
                            </div>
						
                            <div class="col-md-6">
                                <label class="form-label">Promo Price (RM)</label>
                                <input type="number" id="price" name="price" class="form-control" placeholder="0.00" step="0.01">
                            </div>
                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Width (cm)</label>
                                <input type="text" id="width" name="width" class="form-control" placeholder="e.g., 1.3 cm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Available Styles</label> <span style="color:red"><small>e.g..Screw, Pressing, Wired</small></span>
                                <input type="text" id="styles" name="styles" class="form-control" placeholder="e.g., Screw, Pressing, Wired" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Colors</label> <span style="color:red"><small>e.g..White, Blue, Pink</small></span>
                                <input type="text" id="colors" name="colors" class="form-control" placeholder="e.g., White, Blue, Pink" required> 
                            </div>
                        </div>
						
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Packaging</label>
                                <input type="text" id="packaging" name="packaging" class="form-control" placeholder="e.g., Luxury Gift Box with Certificate">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </div>
						
						<h6>Shipping & Returns</h6>
						<hr/>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Shipping</label>
                                <textarea class="form-control" id="shipping_description" name="shipping_description" rows="3" placeholder="Enter Shipping & Returns Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Delivery Time</label>
                                <textarea class="form-control" id="delivery_time_text" name="delivery_time_text" rows="3" placeholder="Enter Delivery Time Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Returns</label>
                                <textarea class="form-control" id="returns_text" name="returns_text" rows="3" placeholder="Enter Returns Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Care Instructions</label>
                                <textarea class="form-control" id="care_instructions" name="care_instructions" rows="3" placeholder="Enter Care Instructions Description"></textarea>
                            </div>
                        </div>
						
						<h6>Product Image</h6>
						<hr/>
						
						 <div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Upload Main Product Image</label>
							   <input type="file" name="imageNameSingle" id="imageNameSingle" class="form-control" onchange="previewImages1(event)" />
							</div>
							
							<div class="col-md-6">
								<label class="form-label">Upload Sub Product Images</label>
							   <input type="file" name="imageNameMultiple[]" id="imageNameMultiple" class="form-control" multiple onchange="previewImages(event)" />
							</div>
							
							
						</div>
						
						<div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">Main Image</label>
								<center>
									<div id="outputPreview1"></div>
								</center>
							</div>
						</div>
						
						
						 <div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">Sub Images</label>
								<center>
									<div id="outputPreview"></div>
								</center>
							</div>
						</div>
						
						
						
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="addProduct" name="addProduct" class="btn btn-primary"> <i class="fas fa-save"></i> Save Product</button>
                </div>
				</form>
            </div>
        </div>
    </div>

	
	
	  <!-- Edit Product Modal -->
	<div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
		 <form id="editProductForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Edit Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   
					    <input type="hidden" id="edit_productID" name="productID">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="edit_product_name" name="edit_product_name" placeholder="Enter product name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" id="edit_sku" name="edit_sku" placeholder="e.g., PROD001" required>
                            </div>
                        </div>
						
						<h6>Specifications</h6>
						<hr/>
						
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
								<select class="form-select" id="edit_categoryID" name="edit_categoryID">
								<option value="">Select Categories</option>
								<?php
								$result = mysqli_query($conn, "SELECT * FROM category WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY categoryID DESC");
								 while($row = mysqli_fetch_assoc($result)) {
								?>
                                  <option value="<?= htmlspecialchars($row['categoryID']); ?>"><?= htmlspecialchars($row['category']); ?></option>
								<?php } ?>
								</select>
                            </div>
							<div class="col-md-6">
                                <label class="form-label">Material</label>
								<select class="form-select" id="edit_materialID" name="edit_materialID">
								<option value="">Select Material</option>
								<?php
								$result = mysqli_query($conn, "SELECT * FROM material WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY materialID DESC");
								 while($row = mysqli_fetch_assoc($result)) {
								?>
                                  <option value="<?= htmlspecialchars($row['materialID']); ?>"><?= htmlspecialchars($row['material']); ?></option>
								<?php } ?>
								</select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Gemstone</label>
                                <input type="text" id="edit_gemstone" name="edit_gemstone" class="form-control" placeholder="e.g., American Diamonds">
                            </div>
							<div class="col-md-6">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" id="edit_stock_quantity" name="edit_stock_quantity" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="row mb-3">
						
							<div class="col-md-6">
                                <label class="form-label">Original Price (RM)</label>
                                <input type="number" id="edit_original_price" name="edit_original_price" class="form-control" placeholder="0.00" step="0.01" required>
                            </div>
						
                            <div class="col-md-6">
                                <label class="form-label">Promo Price (RM)</label>
                                <input type="number" id="edit_price" name="edit_price" class="form-control" placeholder="0.00" step="0.01">
                            </div>
                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Width (cm)</label>
                                <input type="text" id="edit_width" name="edit_width" class="form-control" placeholder="e.g., 1.3 cm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Available Styles</label> <span style="color:red"><small>e.g..Screw, Pressing, Wired</small></span>
                                <input type="text" id="edit_styles" name="edit_styles" class="form-control" placeholder="e.g., Screw, Pressing, Wired" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Colors</label> <span style="color:red"><small>e.g..White, Blue, Pink</small></span>
                                <input type="text" id="edit_colors" name="edit_colors" class="form-control" placeholder="e.g., White, Blue, Pink" required>
                            </div>
                        </div>
						
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="edit_description" rows="3" placeholder="Enter product description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Packaging</label>
                                <input type="text" id="edit_packaging" name="edit_packaging" class="form-control" placeholder="e.g., Luxury Gift Box with Certificate">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select id="edit_status" name="edit_status" class="form-select" required>
                                    <option value="ACTIVE">Active</option>
									<option value="LOW STOCK">Low Stock</option>
									<option value="OUT OF STOCK">Out Of Stock</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </div>
						
						<h6>Shipping & Returns</h6>
						<hr/>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Shipping</label>
                                <textarea class="form-control" id="edit_shipping_description" name="edit_shipping_description" rows="3" placeholder="Enter Shipping & Returns Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Delivery Time</label>
                                <textarea class="form-control" id="edit_delivery_time_text" name="edit_delivery_time_text" rows="3" placeholder="Enter Delivery Time Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Returns</label>
                                <textarea class="form-control" id="edit_returns_text" name="edit_returns_text" rows="3" placeholder="Enter Returns Description"></textarea>
                            </div>
                        </div>
						
						 <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Care Instructions</label>
                                <textarea class="form-control" id="edit_care_instructions" name="edit_care_instructions" rows="3" placeholder="Enter Care Instructions Description"></textarea>
                            </div>
                        </div>
						
						<h6>Product Image</h6>
						<hr/>
						
						<!--Preview-->
						<div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">Main Image</label>
								<center>
									<div id="view_product_image"></div>
								</center>
							</div>
						</div>
						
						
						<table class="table product-table mb-0">
							<thead>
								<tr>
									<th>Sub Images</th>
									<th>Actions</th>
								</tr>
							</thead >
						  <tbody id="view_product_image_list"></tbody>
						</table>
						<!--End Preview-->
						
						
						 <div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Upload Main Product Image</label>
							   <input type="file" name="edit_imageNameSingle" id="edit_imageNameSingle" class="form-control" onchange="edit_ProductpreviewImages1(event)" />
							</div>
							
							<div class="col-md-6">
								<label class="form-label">Upload Sub Product Images</label>
							   <input type="file" name="edit_imageNameMultiple[]" id="edit_imageNameMultiple" class="form-control" multiple onchange="edit_previewImages(event)" />
							</div>
							
							
						</div>
						
						<div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">Main Image</label>
								<center>
									<div id="edit_outputPreview1"></div>
								</center>
							</div>
						</div>
						
						
						 <div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">Sub Images</label>
								<center>
									<div id="edit_outputPreview"></div>
								</center>
							</div>
						</div>
						
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
						<i class="fas fa-save"></i> Update Product
					</button>
                </div>
				</form>
            </div>
        </div>
    </div>



    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/product.js"></script>
	<script>
    $(document).ready(function() {
        // Mobile sidebar toggle functionality
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (mobileToggle && sidebar && sidebarOverlay) {
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

            // Remove any existing event listeners and add new ones
            mobileToggle.removeEventListener('click', toggleSidebar);
            mobileToggle.addEventListener('click', toggleSidebar);
            
            sidebarOverlay.removeEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar when clicking nav links on mobile
            document.querySelectorAll('.nav-link').forEach(link => {
                link.removeEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                if (sidebar && sidebarOverlay) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });
    });

   
    // Auto-submit filter on Enter key
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });
</script>
</body>

</html>