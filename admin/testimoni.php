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
    <title>Testimoni - Mookuthi Palace Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style_customer.css" rel="stylesheet" type="text/css" />
	
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
	</script>
	
</head>

<body>
   <?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title"><i class="fas fa-quote-left"></i> Testimoni</h1>
                <p class="page-subtitle">Manage testimoni and view insights</p>
            </div>
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimoniModal">
                <i class="fas fa-plus"></i> Add Testimoni
            </button>
        </div>
		
		
		<?php
		$result = mysqli_query($conn, "SELECT * FROM testimoni");
		?>
        <!-- Customers Table -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-list"></i> All Testimoni
            </div>
         
			
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="dataTable">
                    <thead>
                        <tr>
							<th>No.</th>
                            <th>Testimoni</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						
						<?php $i = 1; ?>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
						<tr>
								 <td><?= $i; ?></td>
								 <td>
									 <div class="product-image">
											 <img src="<?= "uploads/testimoni/".$row['image']; ?>" width="100" height="100">
									</div>
								 </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <a class="btn-sm btn-view" href="<?= "uploads/testimoni/".$row['image']; ?>" target="_blank"><i class="fas fa-eye"></i></a>
                                    <button class="btn-sm btn-delete" title="Delete" onclick="deleteTestimoni(<?= $row['testimoniID']; ?>)"><i class="fas fa-trash"></i></button>
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
    <div class="modal fade" id="addTestimoniModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
		
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Add New Testimoni
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="add_testimoni.php" id="addTestimoniForm" method="post" autocomplete="off" enctype="multipart/form-data">
				<div class="modal-body">
                   
                       <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
								<input type="file" name="image" id="image" class="form-control" onchange="previewImages1(event)" />
                            </div>
                        </div>
						
						<div class="form-group row">
							<div class="col-sm-10">
							<label class="form-label">View Image</label>
								<center>
									<div id="outputPreview1"></div>
								</center>
							</div>
						</div>
                        
						
						
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="addTestimoni" name="addTestimoni" class="btn btn-primary"> <i class="fas fa-save"></i> Add Testimoni</button>
                </div>
				</form>
				
            </div>
        </div>
    </div>
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/testimoni.js"></script>

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
