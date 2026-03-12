<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_GET['productID']))
{
	$productID = $_GET['productID'];
	$sql = "SELECT a.*, b.category, c.material FROM product a 
	inner join category b on a.categoryID = b.categoryID
	inner join material c on a.materialID = c.materialID
	WHERE a.productID = $productID and a.status = 'ACTIVE' and a.is_delete = '0'";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	$product_name = $row['product_name'];
	$sku = $row['sku'];
	$category = $row['category'];
	$material = $row['material'];
	$gemstone = $row['gemstone'];
	
	
	if($row['price']!="")
	{
		$prices = number_format((float)$row['price'], 2, '.', '');	
	}
	else
	{
		$prices = number_format((float)$row['original_price'], 2, '.', '');
	}
	
	$stock_quantity = (int)$row['stock_quantity'];
	$width = $row['width'];
	$styles = json_decode($row['styles'], true);
	$colors = json_decode($row['colors'], true);
	
	
	$description = $row['description'];
	$packaging = $row['packaging'];
	$shipping_description = $row['shipping_description'];
	$delivery_time_text = $row['delivery_time_text'];
	$returns_text = $row['returns_text'];
	$care_instructions = $row['care_instructions'];
	$product_image = "admin/uploads/products/".$row['product_image'];
	// if($row['sub_images'] != "")
	// {
		// $subImages = json_decode($row['sub_images'], true); // convert JSON → array	
	// }
	
	$product_image_main = $row['product_image'];

	$subImages = [];

	if (!empty($row['sub_images'])) {
		$subImages = json_decode($row['sub_images'], true);
	}
	
	array_push($subImages, $product_image_main);
	
	
}

$customer_code=""; 
if(isset($_SESSION['customer_code']))
{ 
	$customer_code = $_SESSION['customer_code']; 
}

$userID = ""; $name=""; $is_profile=0;
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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Product Details | Mookhuthi Palace</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
  <link href="css/style_product_details.css" rel="stylesheet" type="text/css" />
  
<script>
document.addEventListener("DOMContentLoaded", function () {

    function toggleButtons(buttonSelector, activeClass, hiddenInputId, dataAttr) {
        const buttons = document.querySelectorAll(buttonSelector);
        const hiddenInput = document.getElementById(hiddenInputId);

        buttons.forEach(btn => {
            btn.addEventListener("click", function () {

                buttons.forEach(b => {
                    b.classList.remove("mp-btn-gold", activeClass);
                    b.classList.add("mp-btn-outline-gold");
                });

                this.classList.remove("mp-btn-outline-gold");
                this.classList.add("mp-btn-gold", activeClass);

                if (hiddenInput) {
                    hiddenInput.value = this.dataset[dataAttr];
                }
            });
        });
    }

    // style buttons
    toggleButtons(".style-btn", "active-style", "selected_style", "style");

    // color buttons (if you want both handled here)
    toggleButtons(".color-btn", "active-color", "selected_color", "color");

});
</script>
  
</head>

<body>
 	<?php include_once('includes/navbar.php'); ?>
   
	<?php include_once('includes/side_bar.php'); ?>
	
	
  <!-- Menu Overlay -->
  <div class="menu-overlay" id="menuOverlay"></div>

  <main class="py-5">
    <div class="container">
      <div class="row g-4">
        <!-- Gallery -->
        <div class="col-lg-6">
          <div class="gallery-sticky">
            <div class="mp-surface p-3 p-md-4">

              <img id="mainImage" class="img-main mb-3" src="<?php echo $product_image;?>" alt="Sunflower Nose Pin - Front View">

				<div class="row g-2">
				  <?php if (!empty($subImages)): ?>
					<?php foreach ($subImages as $img): ?>
					  <div class="col-3">
						<img class="thumb" src="admin/uploads/products/<?= htmlspecialchars($img) ?>" alt="Product image" onclick="changeMainImage(this.src)">
					  </div>
					<?php endforeach; ?>
				  <?php endif; ?>
				</div>

              <div class="mp-muted small mt-3">
                High-quality product images showcase all angles and details.
              </div>
            </div>
          </div>
        </div>

        <!-- Details -->
        <div class="col-lg-6">
          <div class="details-scrollable">  
		  
			<input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>" />
			<input type="hidden" id="customer_code" name="customer_code" value="<?php echo $customer_code;?>" />
			<input type="hidden" id="productID" name="productID" value="<?php echo $productID;?>" />
		  
            <h1 class="h2 fw-bold mb-2" style="color: var(--mp-gold-soft);"><?php echo $product_name;?></h1>
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge rounded-pill badge-bestseller">SKU:<?php echo $sku;?></span>
            
            </div>

            <div class="mp-surface p-3 p-md-4 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="mp-muted">Price</div>
                <div class="h3 mb-0 fw-bold" style="color:var(--mp-gold-soft)">RM <span id="unitPrice"><?php echo $prices;?></span>
                </div>
              </div>

              <hr class="border-opacity-25 mb-4">

              <!-- Style and Material Row -->
              <div class="row g-4 mb-4">
                
				<?php if (!empty($styles) && is_array($styles)) : ?>
				<div class="col-6">
				  <label class="mp-muted small mb-2 d-block fw-600">Style</label>
				  <div class="d-flex gap-2 flex-wrap style-options">
						<?php foreach ($styles as $index => $style) : ?>
							<button type="button" class="btn btn-sm <?= $index === 0 ? 'mp-btn-gold active-style' : 'mp-btn-outline-gold' ?> rounded-pill style-btn" data-style="<?= htmlspecialchars($style); ?>">
								<?= htmlspecialchars($style); ?>
							</button>
						<?php endforeach; ?>
				  </div>

				  <!-- optional: store selected style -->
				  <input type="hidden" name="selected_style" id="selected_style"
						 value="<?= !empty($styles) ? htmlspecialchars($styles[0]) : '' ?>">
				</div>
				<?php endif; ?>

                <div class="col-6">
                  <label class="mp-muted small mb-2 d-block fw-600">Material</label>
                  <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm mp-btn-gold rounded-pill"><?= !empty($material) ? htmlspecialchars($material) : '' ?></button>
                  </div>
                </div>
              </div>

              <!-- Color and Quantity Row -->
              <div class="row g-4 mb-4">
                
				<?php if (!empty($colors) && is_array($colors)) : ?>
				<div class="col-6">
				  <label class="mp-muted small mb-2 d-block fw-600">Color</label>
				  <div class="d-flex gap-2 flex-wrap color-options">
						<?php foreach ($colors as $index => $color) : ?>
							<button type="button" class="btn btn-sm <?= $index === 0 ? 'mp-btn-gold active-color' : 'mp-btn-outline-gold' ?> rounded-pill color-btn" data-color="<?= htmlspecialchars($color); ?>">
								<?= htmlspecialchars($color); ?>
							</button>
						<?php endforeach; ?>
				  </div>

				  <!-- optional: store selected color -->
				  <input type="hidden" name="selected_color" id="selected_color"
						 value="<?= !empty($colors) ? htmlspecialchars($colors[0]) : '' ?>">
				</div>
				<?php endif; ?>


                <div class="col-6">
                  <label class="mp-muted small mb-2 d-block fw-600">Quantity</label>
                  <div class="input-group">
                    <button class="btn mp-btn-outline-gold rounded-start-pill" type="button" id="minusBtn">−</button>
                    <input class="form-control mp-input text-center" id="qtyInput" name="qtyInput" value="1" inputmode="numeric">
                    <button class="btn mp-btn-outline-gold rounded-end-pill" type="button" id="plusBtn">+</button>
                  </div>
                </div>
              </div>

              <!-- Subtotal -->
              <div class="d-flex justify-content-between mp-muted mb-4">
                <span class="fw-600">Subtotal</span>
                <span class="fw-semibold text-white">RM <span id="subtotal">0</span></span>
              </div>

              <!-- Buttons -->
              <div class="d-grid gap-2">
                <button class="btn mp-btn-gold rounded-pill py-3" type="button" id="addToCartBtn">
                  <i class="fas fa-shopping-bag me-2"></i>Add to Cart
                </button>
                <!--<a class="btn mp-btn-outline-gold rounded-pill py-3" href="checkout.php">
                  Buy Now
                </a>-->
              </div>
            </div>

            <div class="mp-surface p-3 p-md-4">
			<?php
			if($stock_quantity > 0)
			{
				?>
				<div class="stock-status in-stock">
					<span class="stock-indicator"></span> In Stock (<?php echo $stock_quantity;?> units available)
				  </div>
				<?php
			}
			?>
            </div>

            <div class="mp-surface p-3 p-md-4 mt-3">
              <div class="tabs-section">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                      data-bs-target="#description" type="button" aria-selected="true" role="tab">Description</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                      data-bs-target="#specifications" type="button" aria-selected="false" role="tab"
                      tabindex="-1">Specifications</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                      type="button" aria-selected="false" role="tab" tabindex="-1">Shipping &amp; Returns</button>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade active show" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p class="mp-muted" style="margin-top: 1rem;"><?php echo $description;?></p>
                  </div>
				  
                  <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <p class="mp-muted" style="margin-top: 1rem;"><strong
                        style="color: var(--mp-gold-soft);">Material:</strong> <?= !empty($material) ? $material : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Gemstone:</strong> <?= !empty($gemstone) ? $gemstone : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Width:</strong> <?= !empty($width) ? $width."cm" : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Available Styles:</strong> <?= !empty($styles) && is_array($styles) ? implode(', ', $styles) : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Colors:</strong> <?= !empty($colors) && is_array($colors) ? implode(', ', $colors) : ''; ?></p>
                    <p class="mp-muted" style="margin-bottom: 0;"><strong style="color: var(--mp-gold-soft);">Packaging:</strong> <?= !empty($packaging) ? $packaging : ''; ?></p>
                  </div>
				  
                  
				  
				   <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    <p class="mp-muted" style="margin-top: 1rem;"><strong
                        style="color: var(--mp-gold-soft);">Shipping:</strong> <?= !empty($shipping_description) ? $shipping_description : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Delivery Time:</strong> <?= !empty($delivery_time_text) ? $delivery_time_text : ''; ?></p>
                    <p class="mp-muted"><strong style="color: var(--mp-gold-soft);">Returns:</strong> <?= !empty($returns_text) ? $returns_text : ''; ?></p>
                    <p class="mp-muted" style="margin-bottom: 0;"><strong style="color: var(--mp-gold-soft);">Care
                        Instructions:</strong> <?= !empty($care_instructions) ? $care_instructions : ''; ?></p>
                  </div>
				  
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Recommended -->
        <section class="pt-5">
          <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
            <div>
              <div class="mp-section-title">Recommendations</div>
              <h2 class="h4 fw-bold mb-0">Pairs well with</h2>
            </div>
            <a class="btn mp-btn-outline-gold rounded-pill" href="products.php">Back to products</a>
          </div>

          <div class="row g-3 py-4">
             <?php
				$sql = "SELECT a.productID, a.product_name, a.description, a.sku, b.category, a.original_price, a.price, a.stock_quantity, c.material, a.status, a.product_image FROM product a
				inner join category b on a.categoryID = b.categoryID
				inner join material c on a.materialID = c.materialID
				WHERE a.productID != $productID and a.is_bestseller = '1' and a.status = 'ACTIVE' and a.is_delete = '0'";
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
              <div class="product-card">
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
                  <p class="product-desc"><?php echo (strlen($row['description']) > 30) ? substr($row['description'], 0, 30) . '...' : $row['description'];?></p>
                  
					 <?php
					 if($price > 0){
					 ?>
					 <p class="product-price">
						<span class="price-original">RM <?php echo number_format((float)$row['original_price'], 2, '.', '');?></span>
						<span class="price-promo">RM <?php echo number_format((float)$row['price'], 2, '.', '');?></span>
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
                  
				  
				  <button class="btn btn-add-cart w-100" onclick="window.location.href='product-details.php?productID=<?php echo $row['productID']; ?>'">View</button>
                </div>
              </div>
            </div>
			<?php
					   }
			}
			?>
           
          </div>
        </section>

      </div>
	  
	  
	  <div class="toast-container position-fixed top-0 end-0 p-3">
		  <div id="cartToast" class="toast align-items-center text-bg-warning border-0" role="alert">
			<div class="d-flex">
			  <div class="toast-body" id="cartToastMsg">
				Added to cart
			  </div>
			  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
			</div>
		  </div>
		</div>

	  
  </main>

  <!-- Footer -->
	<?php include_once('includes/footer.php'); ?>

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

    // Gallery thumbs - Change main image on click


    const mainImage = document.getElementById('mainImage');
    const thumbs = document.querySelectorAll('.thumb');
    thumbs.forEach((thumb, index) => {
      thumb.addEventListener('click', () => {
        thumbs.forEach(x => x.classList.remove('active'));
        thumb.classList.add('active');
        mainImage.src = thumb.src.replace('w=100&h=100', 'w=600&h=600');
        mainImage.alt = thumb.alt;
      });
      // Set first thumbnail as active on load
      if (index === 0) {
        thumb.classList.add('active');
      }
    });

    // Quantity and subtotal
    const unitPrice = Number(document.getElementById('unitPrice').textContent);
    const qtyInput = document.getElementById('qtyInput');
    const subtotal = document.getElementById('subtotal');

    function clampQty(v) {
      const n = Number(String(v).replace(/[^\d]/g, '')) || 1;
      return Math.min(99, Math.max(1, n));
    }

    function updateSubtotal() {
      const q = clampQty(qtyInput.value);
      qtyInput.value = q;
      subtotal.textContent = (unitPrice * q).toFixed(0);
    }

    document.getElementById('minusBtn').addEventListener('click', () => {
      qtyInput.value = clampQty(qtyInput.value) - 1;
      updateSubtotal();
    });

    document.getElementById('plusBtn').addEventListener('click', () => {
      qtyInput.value = clampQty(qtyInput.value) + 1;
      updateSubtotal();
    });

    qtyInput.addEventListener('input', updateSubtotal);
    updateSubtotal();

    // Add to cart
    // document.getElementById('addToCartBtn').addEventListener('click', () => {
      // const q = clampQty(qtyInput.value);
      // alert(`Added to cart: Royal Pearl Collection (Qty: ${q})`);
    // });
  </script>
</body>

</html>