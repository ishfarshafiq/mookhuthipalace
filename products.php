<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

$customer_code=""; 
if(isset($_SESSION['customer_code']))
{ 
	$customer_code = $_SESSION['customer_code']; 
}

$userID = "";$name=""; $is_profile=0;
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
		$phone = $row['phone'];
		
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
    <title>Shop - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link href="css/style_products.css" rel="stylesheet" type="text/css" />
	<style>
        .active > .page-link {
	z-index: 3;
	color: var(--bs-pagination-active-color);
	background-color: #dc0283;
	border-color: #dc0283;
}

.page-link {
	position: relative;
	display: block;
	padding: var(--bs-pagination-padding-y) var(--bs-pagination-padding-x);
	font-size: var(--bs-pagination-font-size);
	color: #dc0283;
	text-decoration: none;
	background-color: #160e0a;
	border: var(--bs-pagination-border-width) solid #dc0283;
	transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
    </style>
</head>

<body>

    <?php include_once('includes/navbar.php'); ?>
   
   <?php include_once('includes/side_bar.php'); ?>
   
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Our Exclusive Collection</h1>
        </div>
    </div>
	
	<?php
	$limit = 12; // products per page

	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

	if ($page < 1) {
		$page = 1;
	}

	$start = ($page - 1) * $limit;
	
	$count_sql = "SELECT COUNT(*) as total 
              FROM product a
              WHERE a.status = 'ACTIVE' 
              AND a.is_delete = '0'";

	$count_result = mysqli_query($conn, $count_sql);
	$count_row = mysqli_fetch_assoc($count_result);
	$total_products = $count_row['total'];

	$total_pages = ceil($total_products / $limit);
	?>

    <!-- Main Content -->
    <div class="container" style="padding-left: 15px; padding-right: 15px; padding-bottom: 5rem;">
        <!-- Search Box -->
        <div class="search-filter-box">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search products...">
            </div>
        </div>

        <div class="row">
			<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
			<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h3 class="filter-title">
                        <i class="fas fa-filter"></i> Filters
                    </h3>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <h6>Price Range</h6>
                        <div class="price-range">
                            <input type="number" id="minPrice" placeholder="Min" value="1">
                            <span>-</span>
                            <input type="number" id="maxPrice" placeholder="Max" value="10000">
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="filter-group">
                        <h6>Category</h6>
						
						 <?php
						$result = mysqli_query($conn, "SELECT category FROM category WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY category");
						while($row = mysqli_fetch_assoc($result)) {
						?> 
						
						<div class="form-check">
                            <input class="form-check-input" type="checkbox" id="<?= htmlspecialchars($row['category']); ?>" name="category" value="<?= htmlspecialchars($row['category']); ?>">
                            <label class="form-check-label" for="<?= htmlspecialchars($row['category']); ?>"><?= htmlspecialchars($row['category']); ?></label>
                        </div>
						
						<?php } ?>

                    </div>


                    <!-- Material -->
                    <div class="filter-group">
                        <h6>Material</h6>
						<?php
						$result = mysqli_query($conn, "SELECT material FROM material WHERE status = 'ACTIVE' and is_delete = '0' ORDER BY material");
						while($row = mysqli_fetch_assoc($result)) {
						?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="<?= htmlspecialchars($row['material']); ?>" name="material" value="<?= htmlspecialchars($row['material']); ?>">
                            <label class="form-check-label" for="<?= htmlspecialchars($row['material']); ?>"><?= htmlspecialchars($row['material']); ?></label>
                        </div>
						<?php } ?>
                    </div>

                    <button class="btn-reset" onclick="resetFilters()">Reset Filters</button>
                </div>
            </div>

            <!-- Products Section -->
            <div class="col-lg-9">
                <!-- Products Header -->
                <div class="products-header">
                    <span class="result-count">Showing <span id="resultCount">12</span> products</span>
                    <select id="sortSelect" class="sort-select">
                        <option value="">Sort By</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>
                </div>

                <!-- Products Grid -->
                <div class="row g-4" id="productsContainer">
				
				   <?php
					// $sql = "SELECT a.productID, a.product_name, a.description, a.sku, b.category, a.original_price, a.price, a.stock_quantity, c.material, a.status, a.product_image FROM product a
					// inner join category b on a.categoryID = b.categoryID
					// inner join material c on a.materialID = c.materialID
					// WHERE a.status = 'ACTIVE' and a.is_delete = '0'";
					
					$sql = "SELECT a.productID, a.product_name, a.description, a.sku, 
							b.category, a.original_price, a.price, 
							a.stock_quantity, c.material, a.status, a.product_image 
							FROM product a
							INNER JOIN category b ON a.categoryID = b.categoryID
							INNER JOIN material c ON a.materialID = c.materialID
							WHERE a.status = 'ACTIVE' 
							AND a.is_delete = '0'
							LIMIT $start, $limit";
					$result = mysqli_query($conn,$sql);
					
					if(mysqli_num_rows($result) > 0)
					{
								while($row = mysqli_fetch_assoc($result)) {
									$original_price = $row['original_price'];
									$price = $row['price'];
									if($price != "")
									{
										$prices = $row['price'];
									}
									else
									{
										$prices = $row['original_price'];
									}
									
								?>
				
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="product-card" data-price="<?php echo number_format((float)$prices, 2, '.', '');?>" data-category="<?php echo $row['category']; ?>" data-material="<?php echo $row['material']; ?>">
										<?php
										if($row['product_image'] != ""){
										?>
											<div><img src="admin/uploads/products/<?php echo $row['product_image'];?>" class="img-fluid"></div>
										<!--<div class="product-image-wrapper">
											<img src="admin/uploads/products/<?php //echo $row['product_image'];?>" class="product-image">
										</div>-->
										<?php
										}
										?>
										<div class="product-info">
											<h3 class="product-name"><?php echo $row['product_name'];?></h3>
											<p class="product-desc">
											<?php 
												echo (strlen($row['description']) > 30) ? substr($row['description'], 0, 30) . '...' : $row['description'];
											?>
											</p>
											 
											 <?php
											 if($price > 0){
											 ?>
											 <p class="product-price">
												<span class="price-original">RM<?php echo number_format((float)$row['original_price'], 2, '.', '');?></span>
												<span class="price-promo">RM<?php echo number_format((float)$row['price'], 2, '.', '');?></span>
											</p>
											 <?php
											 }
											 else
											 {
												?>
												<p class="product-price">RM <?php echo number_format((float)$row['original_price'], 2, '.', '');?></p>
												<?php												
											 }
											 ?>
											 
											
											
											<div class="d-flex gap-2">
												<a href="product-details.php?productID=<?php echo $row['productID'];?>" class="btn btn-view-details flex-grow-1">View</a>
											</div>
										</div>
									</div>
								</div>
				
				<?php
							   }
					}
							?>
                    
				</div>
				
				
				<div class="d-flex justify-content-center mt-4">
					<nav>
						<ul class="pagination">

							<!-- Previous Button -->
							<?php if ($page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
								</li>
							<?php endif; ?>

							<!-- Page Numbers -->
							<?php for ($i = 1; $i <= $total_pages; $i++): ?>
								<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
									<a class="page-link" href="?page=<?php echo $i; ?>">
										<?php echo $i; ?>
									</a>
								</li>
							<?php endfor; ?>

							<!-- Next Button -->
							<?php if ($page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
								</li>
							<?php endif; ?>

						</ul>
					</nav>
				</div>
				
				
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container ">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	  <script type="text/javascript" src="js/product.js"></script>
	
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

        // Product Filter Functionality
        const products = document.querySelectorAll('.product-card');

        function filterProducts() {
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || 999999;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            // Get selected categories
            const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked'))
					.map(cb => cb.value.toLowerCase());

            // Get selected materials
            const selectedMaterials = Array.from(document.querySelectorAll('input[name="material"]:checked'))
					.map(cb => cb.value.toLowerCase());

            let visibleCount = 0;
            products.forEach(product => {
                const price = parseFloat(product.dataset.price);
                const name = product.querySelector('.product-name').textContent.toLowerCase();
                const desc = (product.querySelector('.product-desc')?.textContent || '').toLowerCase();
                const category = (product.dataset.category || '').toLowerCase();
                const material = (product.dataset.material || '').toLowerCase();

                // Check price range
                const priceMatch = (price >= minPrice && price <= maxPrice);

                // Check search term (name/description/category/material)
                const searchMatch =
                    searchTerm === '' ||
                    name.includes(searchTerm) ||
                    desc.includes(searchTerm) ||
                    category.includes(searchTerm) ||
                    material.includes(searchTerm);

                // Check category filter
                const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(category);

                // Check material filter
                const materialMatch = selectedMaterials.length === 0 || selectedMaterials.includes(material);

                const show = priceMatch && searchMatch && categoryMatch && materialMatch;

                product.parentElement.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            document.getElementById('resultCount').textContent = visibleCount;
        }

        function sortProducts() {
            const sortValue = document.getElementById('sortSelect').value;
            const container = document.getElementById('productsContainer');
            const productsArray = Array.from(document.querySelectorAll('.product-card')).filter(p =>
                p.parentElement.style.display !== 'none'
            );

            switch (sortValue) {
                case 'price-low':
                    productsArray.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
                    break;
                case 'price-high':
                    productsArray.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
                    break;
            }

            productsArray.forEach(p => {
                container.appendChild(p.parentElement);
            });
        }

        function resetFilters() {
            document.getElementById('minPrice').value = '50';
            document.getElementById('maxPrice').value = '10000';
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.form-check-input').forEach(cb => cb.checked = false);
            document.getElementById('sortSelect').value = '';
            filterProducts();
        }

        function addToCart(productName, price) {
            const p = Number(price);
            alert(`Added ${productName} (RM ${p.toFixed(2)}) to cart!`);
        }

        document.getElementById('searchInput').addEventListener('keyup', filterProducts);
        document.getElementById('minPrice').addEventListener('change', filterProducts);
        document.getElementById('maxPrice').addEventListener('change', filterProducts);
        document.querySelectorAll('input[name="category"]').forEach(cb => cb.addEventListener('change', filterProducts));
        document.querySelectorAll('input[name="material"]').forEach(cb => cb.addEventListener('change', filterProducts));
        document.getElementById('sortSelect').addEventListener('change', sortProducts);

        // Initialize result count on first load
        filterProducts();
    </script>
</body>

</html>