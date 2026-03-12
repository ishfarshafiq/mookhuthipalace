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
			$filter .= " AND a.price <= $price";
		}
		
		if (!empty($_GET['search'])) {
			$search = $_GET['search'];
			$filter .= " AND a.product_name like '%$search%' or a.sku like '%$search%' or b.category like '%$search%' or b.material like '%$search%' or a.gemstone like '%$search%'";
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
		 <form id="editProductForm" enctype="multipart/form-data">-->
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
				<!--</form>-->
            </div>
        </div>
    </div>
	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/product.js"></script>
   
   <script>
        // Sample Product Data - HTML inline static data
        const products = [
            { id: 1, name: 'Traditional Gold Nath', sku: 'TRDN001', category: 'Gold Pins', price: 899, stock: 24, branch: 'Kuala Lumpur', material: '22K Gold', gemstone: 'American Diamonds', width: '1.3 cm', styles: 'Screw, Pressing, Wired', colors: 'White, Blue, Pink', status: 'Active', description: 'Elegant traditional design with classic appeal', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: 'img/m2.jpg', shipping: 'Free shipping on orders over RM 500. Delivery within 3-5 business days.', returns: '30-day money-back guarantee. Free returns within 14 days.' },
            { id: 2, name: 'Diamond Studded Nose Pin', sku: 'DIA001', category: 'Diamond Collection', price: 1549, stock: 8, branch: 'Ipoh', material: '18K Gold', gemstone: 'Swarovski Crystals', width: '1.2 cm', styles: 'Pressing, Wired', colors: 'White, Yellow', status: 'Low Stock', description: 'Premium diamond studded design for special occasions', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: 'img/m1.jpg', shipping: 'Standard shipping RM 25. Express delivery available.', returns: '14-day returns on unworn items.' },
            { id: 3, name: 'Royal Kundan Nath', sku: 'KUND001', category: 'Kundan Work', price: 1299, stock: 15, branch: 'Taiping', material: '22K Gold', gemstone: 'Semi-precious Stones', width: '1.5 cm', styles: 'Screw, Pressing', colors: 'Red, Green, Blue', status: 'Active', description: 'Traditional Kundan work with intricate details', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: 'img/m2.jpg', shipping: 'Free shipping on all jewelry orders.', returns: '30-day full refund policy.' },
            { id: 4, name: 'Pearl Drop Nose Pin', sku: 'PERL001', category: 'Pearl Collection', price: 649, stock: 0, branch: 'Kuala Lumpur', material: '916 Gold', gemstone: 'Freshwater Pearls', width: '1.0 cm', styles: 'Screw, Wired', colors: 'White, Black', status: 'Out of Stock', description: 'Elegant pearl drop design for daily wear', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: 'img/m1.jpg', shipping: 'Expedited shipping available.', returns: 'Exchange within 60 days.' },
            { id: 5, name: 'Contemporary Design Nath', sku: 'CONT001', category: 'Modern Designer', price: 899, stock: 32, branch: 'Taiping', material: '22K Gold', gemstone: 'Cubic Zirconia', width: '1.1 cm', styles: 'Pressing, Wired', colors: 'White, Rose Gold', status: 'Active', description: 'Modern minimalist design with contemporary appeal', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: null, shipping: 'Free shipping on orders over RM 500.', returns: '30-day returns policy applies.' },
            { id: 6, name: 'Bridal Nose Pin Set', sku: 'BRID001', category: 'Bridal Collection', price: 3299, stock: 5, branch: 'Ipoh', material: '24K Gold', gemstone: 'Mixed Gemstones', width: '1.4 cm', styles: 'Screw, Pressing, Wired', colors: 'Gold, White, Rose', status: 'Low Stock', description: 'Premium bridal collection for special occasions', packaging: 'Luxury Gift Box with Certificate of Authenticity', image: null, shipping: 'Free express shipping on bridal collections.', returns: '60-day money-back guarantee for bridal items.' }
        ];

        let currentSort = { column: 'product', direction: 'asc' };
        let filteredProducts = [...products];
        let currentPage = 1;
        const itemsPerPage = 10;

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Loaded - Initializing');
            renderTable();
            
            // Direct event listener binding
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    console.log('Search input changed:', this.value);
                    filterAndRender();
                });
            }

            // Category filter
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    console.log('Category changed:', this.value);
                    filterAndRender();
                });
            }

            // Status filter
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    console.log('Status changed:', this.value);
                    filterAndRender();
                });
            }

            // Price filter
            const priceFilter = document.getElementById('priceFilter');
            if (priceFilter) {
                priceFilter.addEventListener('input', function() {
                    console.log('Price changed:', this.value);
                    filterAndRender();
                });
            }

            // Mobile toggle
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

                mobileToggle.addEventListener('click', toggleSidebar);
                sidebarOverlay.addEventListener('click', closeSidebar);

                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 768) {
                            closeSidebar();
                        }
                    });
                });
            }
        });

        function setupEventListeners() {
            // This function is now handled inline in DOMContentLoaded
        }

        function filterAndRender() {
            console.log('=== FILTER AND RENDER CALLED ===');
            
            const searchInput = document.getElementById('searchInput');
            const searchText = searchInput ? searchInput.value.toLowerCase() : '';
            console.log('Search input element:', searchInput);
            console.log('Search text value:', searchText);
            console.log('Products array length:', products.length);
            
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            const priceFilter = document.getElementById('priceFilter');

            filteredProducts = products.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(searchText) ||
                    product.sku.toLowerCase().includes(searchText);
                const matchesCategory = !categoryFilter || categoryFilter.value === '' || product.category === categoryFilter.value;
                const matchesStatus = !statusFilter || statusFilter.value === '' || product.status === statusFilter.value;

                let matchesPrice = true;
                if (priceFilter && priceFilter.value) {
                    const [min, max] = priceFilter.value.split('-').map(v => parseInt(v.trim()));
                    matchesPrice = (!min || product.price >= min) && (!max || product.price <= max);
                }

                if (searchText && !matchesSearch) {
                    console.log('Product filtered out (no search match):', product.name);
                }

                return matchesSearch && matchesCategory && matchesStatus && matchesPrice;
            });

            console.log('Filtered products count:', filteredProducts.length);
            console.log('Filtered products:', filteredProducts);

            currentPage = 1;
            renderTable();
        }

        function sortTable(column) {
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = column;
                currentSort.direction = 'asc';
            }

            // Update visual indicators
            document.querySelectorAll('.sortable').forEach(th => {
                th.classList.remove('asc', 'desc');
            });
            event.target.classList.add(currentSort.direction);

            const sorted = [...filteredProducts].sort((a, b) => {
                let aVal, bVal;

                if (column === 'product') {
                    aVal = a.name.toLowerCase();
                    bVal = b.name.toLowerCase();
                } else if (column === 'sku') {
                    aVal = a.sku;
                    bVal = b.sku;
                } else if (column === 'category') {
                    aVal = a.category;
                    bVal = b.category;
                } else if (column === 'price') {
                    aVal = a.price;
                    bVal = b.price;
                } else if (column === 'stock') {
                    aVal = a.stock;
                    bVal = b.stock;
                } else if (column === 'branch') {
                    aVal = a.branch;
                    bVal = b.branch;
                } else if (column === 'material') {
                    aVal = a.material;
                    bVal = b.material;
                } else if (column === 'status') {
                    aVal = a.status;
                    bVal = b.status;
                }

                if (typeof aVal === 'string') {
                    return currentSort.direction === 'asc' ?
                        aVal.localeCompare(bVal) :
                        bVal.localeCompare(aVal);
                } else {
                    return currentSort.direction === 'asc' ?
                        aVal - bVal :
                        bVal - aVal;
                }
            });

            filteredProducts = sorted;
            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('productTableBody');
            const recordCount = document.getElementById('recordCount');
            recordCount.textContent = filteredProducts.length;

            // Calculate pagination
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageProducts = filteredProducts.slice(startIndex, endIndex);

            if (pageProducts.length === 0) {
                tbody.innerHTML = `
                    <tr class="empty-state">
                        <td colspan="10">
                            <div>
                                <i class="fas fa-inbox"></i>
                                <p>No products found</p>
                            </div>
                        </td>
                    </tr>
                `;
                renderPagination(totalPages);
                return;
            }

            tbody.innerHTML = pageProducts.map(product => `
                <tr>
                    <td>
                        <button class="btn-expand" onclick="toggleDetails(this, ${product.id})">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </td>
                    <td>
                        <div class="product-info">
                            <div class="product-image">
                                ${product.image ? `<img src="${product.image}">` : '<i class="fas fa-gem"></i>'}
                            </div>
                            <div class="product-details">
                                <div class="product-name">${product.name}</div>
                                <div class="product-sku">${product.sku}</div>
                            </div>
                        </div>
                    </td>
                    <td>${product.sku}</td>
                    <td>${product.category}</td>
                    <td><strong>RM ${product.price.toLocaleString()}</strong></td>
                    <td><strong>${product.stock}</strong></td>
                    <td>${product.material}</td>
                    <td>
                        ${getStatusBadge(product.status)}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                onclick="editProduct(${product.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-sm btn-delete" onclick="deleteProduct(${product.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="details-row" id="details-${product.id}">
                    <td colspan="10">
                        <div class="details-content">
                            <!-- Tabs -->
                            <div class="details-tabs">
                                <button class="details-tab-btn active" onclick="switchTab(event, 'spec-${product.id}')">Specifications</button>
                                <button class="details-tab-btn" onclick="switchTab(event, 'shipping-${product.id}')">Shipping & Returns</button>
                            </div>

                            <!-- Specifications Tab -->
                            <div id="spec-${product.id}" class="details-tab-content active">
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Gemstone</div>
                                        <div class="detail-value">${product.gemstone}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Width</div>
                                        <div class="detail-value">${product.width}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Available Styles</div>
                                        <div class="detail-value">${product.styles}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Colors</div>
                                        <div class="detail-value">${product.colors}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Description</div>
                                        <div class="detail-value">${product.description}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Packaging</div>
                                        <div class="detail-value">${product.packaging}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping & Returns Tab -->
                            <div id="shipping-${product.id}" class="details-tab-content">
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Shipping Information</div>
                                        <div class="detail-value">${product.shipping}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Returns & Exchange Policy</div>
                                        <div class="detail-value">${product.returns}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');

            renderPagination(totalPages);
        }

        function getStatusBadge(status) {
            const statusMap = {
                'Active': 'badge-success',
                'Low Stock': 'badge-warning',
                'Out of Stock': 'badge-danger'
            };
            return `<span class="badge ${statusMap[status] || 'badge-secondary'}">${status}</span>`;
        }

        function toggleDetails(btn, productId) {
            const detailsRow = document.getElementById(`details-${productId}`);
            btn.classList.toggle('expanded');
            detailsRow.classList.toggle('show');
        }

        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) {
                document.getElementById('editProductName').value = product.name;
                document.getElementById('editSKU').value = product.sku;
                document.getElementById('editCategory').value = product.category;
                document.getElementById('editMaterial').value = product.material;
                document.getElementById('editGemstone').value = product.gemstone;
                document.getElementById('editPrice').value = product.price;
                document.getElementById('editStock').value = product.stock;
                document.getElementById('editWidth').value = product.width;
                document.getElementById('editStyles').value = product.styles;
                document.getElementById('editColors').value = product.colors;
                document.getElementById('editDescription').value = product.description;
                document.getElementById('editPackaging').value = product.packaging;
                document.getElementById('editStatus').value = product.status;
            }
        }

        function saveProduct() {
            alert('Product saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
            modal.hide();
        }

        function updateProduct() {
            alert('Product updated successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
            modal.hide();
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                const index = products.findIndex(p => p.id === id);
                if (index > -1) {
                    products.splice(index, 1);
                    currentPage = 1;
                    filterAndRender();
                    alert('Product deleted successfully!');
                }
            }
        }

        function renderPagination(totalPages) {
            const paginationContainer = document.getElementById('paginationContainer');
            if (!paginationContainer) return;

            let html = '';
            
            // Previous button
            if (currentPage > 1) {
                html += `<button class="pagination-btn" onclick="goToPage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></button>`;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<span class="pagination-dots">...</span>`;
                }
            }

            // Next button
            if (currentPage < totalPages) {
                html += `<button class="pagination-btn" onclick="goToPage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></button>`;
            }

            paginationContainer.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            renderTable();
            window.scrollTo(0, 0);
        }

        function switchTab(event, tabId) {
            event.preventDefault();
            const detailsRow = event.target.closest('tr');
            
            // Hide all tabs in this details row
            detailsRow.querySelectorAll('.details-tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active from all buttons in this details row
            detailsRow.querySelectorAll('.details-tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab and mark button as active
            document.getElementById(tabId).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>

</html>