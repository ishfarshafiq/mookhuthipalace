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
    <title>Category - Mookuthi Palace Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style_customer.css" rel="stylesheet" type="text/css" />
</head>

<body>
   <?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title"><i class="fas fa-users"></i> Category</h1>
                <p class="page-subtitle">Manage category and view insights</p>
            </div>
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
		
		
		<?php
		$result = mysqli_query($conn, "SELECT * FROM category where is_delete = '0' ORDER BY categoryID DESC");
		?>
        <!-- Customers Table -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-list"></i> All Category
            </div>
         
			
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="dataTable">
                    <thead>
                        <tr>
							<th>No.</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						
						<?php $i = 1; ?>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
						<tr>
								 <td><?= $i; ?></td>
								 <td><?= $row['code']; ?></td>
                            
								<td><?= $row['category']; ?></td>
                            
								<td>
								<?php
									if($row['status']== "ACTIVE")
									{
										?>
										  <span class="badge bg-success">Active</span>
										<?php
									}else{
									?>
										<span class="badge bg-danger">Inactive</span>
									<?php
									}
									?>
									
								</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#editCategoryModal" onclick="viewCategory(<?= $row['categoryID']; ?>)"><i class="fas fa-eye"></i></button>
                                    <button class="btn-sm btn-delete" title="Delete" onclick="deleteCategory(<?= $row['categoryID']; ?>)"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
						
						<?php 
							
							$i++;
							
							endwhile; 
							
							?>
				   </tbody>
                </table>
            </div>
			
            
			
			
        </div> <!-- End dashboard-card -->
    </div> <!-- End main-content -->
	
	
	<!-- Add Product Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
		
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                   
                       <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Code</label>
                                <input type="text" id="code" name="code" class="form-control" placeholder="e.g., 0001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" id="category" name="category" class="form-control" placeholder="e.g., Single Stones">
                            </div>
                        </div>
                        
						
						
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="addCategory" name="addCategory" class="btn btn-primary"> <i class="fas fa-save"></i> Save Category</button>
                </div>
				
            </div>
        </div>
    </div>
	
	<!-- Edit Product Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
		
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
				
				<input type="hidden" id="categoryID" name="categoryID" class="form-control" required>
                   
                       <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Code</label>
                                <input type="text" id="edit_code" name="edit_code" class="form-control" placeholder="e.g., 0001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" id="edit_category" name="edit_category" class="form-control" placeholder="e.g., Single Stones" required>
                            </div>
                        </div>
						
						<div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                 <select id="edit_status" name="edit_status" class="form-select">
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
						
						
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="editCategory" name="editCategory" class="btn btn-primary"> <i class="fas fa-save"></i> Save Category</button>
                </div>
				
            </div>
        </div>
    </div>

	

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/product.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
	
	
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
