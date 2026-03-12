let dataTable;

$(document).ready(function () {
	
    dataTable = $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: false,
        responsive: false,
		
    });

});

function editProducts(productID) {
	
	  // Reset file inputs
    document.getElementById("edit_imageNameSingle").value = "";
    document.getElementById("edit_imageNameMultiple").value = "";

    // Clear previews
    document.getElementById("edit_outputPreview1").innerHTML = "";
    document.getElementById("edit_outputPreview").innerHTML = "";
	document.getElementById("view_product_image").innerHTML = "";
	
	document.getElementById("view_product_image_list").innerHTML = `
    <tr>
        <td colspan="2" class="text-center text-muted">
            No images
        </td>
    </tr>
`;
	
	
		$.ajax({
            url: 'ProcessController.php',
            type: 'GET',
			dataType: 'json',
            data: {
                productID: productID,
				action: 'view'
            },
            success: function (response) {
               
				if(response.status == "success")
				{
					document.getElementById('edit_productID').value = productID;
					document.getElementById('edit_product_name').value = response.data.product_name;
					document.getElementById('edit_sku').value = response.data.sku;
					document.getElementById('edit_categoryID').value = response.data.categoryID;
					document.getElementById('edit_materialID').value = response.data.materialID;
					document.getElementById('edit_gemstone').value = response.data.gemstone;
					document.getElementById('edit_price').value = response.data.price;
					document.getElementById('edit_original_price').value = response.data.original_price;
					document.getElementById('edit_stock_quantity').value = response.data.stock_quantity;
					document.getElementById('edit_width').value = response.data.width;
					document.getElementById('edit_styles').value = response.data.styles;
					document.getElementById('edit_colors').value = response.data.colors;
					document.getElementById('edit_description').value = response.data.description;
					document.getElementById('edit_shipping_description').value = response.data.shipping_description;
					document.getElementById('edit_delivery_time_text').value = response.data.delivery_time_text;
					document.getElementById('edit_returns_text').value = response.data.returns_text;
					document.getElementById('edit_care_instructions').value = response.data.care_instructions;
					document.getElementById('edit_packaging').value = response.data.packaging;
					document.getElementById('edit_status').value = response.data.status;
					
					renderProductImages(response.data);
					
				}
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
	

}

function renderProductImages(data) {

   
    if (data.product_image) {
        document.getElementById("view_product_image").innerHTML = `
            <img src="uploads/products/${data.product_image}"
                 class="img-thumbnail"
                 style="max-width:200px">
        `;
    } else {
        document.getElementById("view_product_image").innerHTML =
            `<span class="text-muted">No main image</span>`;
    }

   
    let tbody = document.getElementById("view_product_image_list");
    tbody.innerHTML = "";

    if (data.sub_images) {
        try {
            let images = JSON.parse(data.sub_images);

            images.forEach(img => {
				
				 const safeId = img.replace(/[^a-zA-Z0-9]/g, '');

				
                tbody.innerHTML += `
                    <tr id="img_row_${safeId}">
                        <td>
                            <img src="uploads/products/${img}" class="img-thumbnail" style="width:80px">
                        </td>
                        <td>
                            <button class="btn-sm btn-delete" type="button" onclick="deleteSubImage(this, ${data.productID}, '${img}')">
										<i class="fas fa-trash"></i>
									</button>
                        </td>
                    </tr>
                `;
            });

        } catch (e) {
            console.error("Invalid sub_images JSON", e);
            tbody.innerHTML = `
                <tr>
                    <td colspan="2" class="text-center text-danger">
                        Image data error
                    </td>
                </tr>
            `;
        }
    } else {
        tbody.innerHTML = `
            <tr>
                <td colspan="2" class="text-center text-muted">
                    No sub images
                </td>
            </tr>
        `;
    }
}

function deleteSubImage(btn, productID, imageName) {

    //if (!confirm("Delete this image?")) return;

    $.ajax({
        url: 'ProcessController.php',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'delete_sub_image',
            productID: productID,
            image: imageName
        },
        success: function (res) {

            if (res.status === "success") {

               btn.closest('tr').remove();

            } else {
                alert(res.msg || "Failed to delete image");
            }
        },
        error: function () {
            alert("Server error");
        }
    });
}


$("#editProductForm").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    formData.append("action", "update_product");

    $.ajax({
        url: "edit_product.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.status === "success") {
                alert("Product updated successfully");
                $("#editProductModal").modal("hide");
                location.reload(); // or refresh table only
            } else {
                alert(res.msg || "Update failed");
            }
        },
        error: function (e, jxr) {
            alert("Server error");
        }
    });
});

function deleteProducts(productID) {
    
	
	if (!confirm("Are you sure you want to delete this product?")) return;

    $.ajax({
        url: 'ProcessController.php',
        type: 'POST',
        dataType: 'json',
        data: {
            del_action: 'delete_product',
            productID: productID
        },
        success: function(res) {
            if (res.status === "success") {
                // Remove the product row from the table
                alert("Product deleted successfully");
				location.reload();
            } else {
                alert(res.msg || "Failed to delete product");
            }
        },
        error: function() {
            alert("Server error");
        }
    });
}


function updateBestseller(checkbox, productID) {
    let is_bestseller = checkbox.checked ? 1 : 0;

    $.ajax({
        url: "ProcessController.php",
        type: "POST",
        data: {
            productID: productID,
            is_bestseller: is_bestseller
        },
        success: function (response) {
            console.log("Updated:", response);
        },
        error: function () {
            alert("Failed to update bestseller status");
            // rollback checkbox if error
            checkbox.checked = !checkbox.checked;
        }
    });
}


$('#addCategory').on('click', function () {
        const code     = $('#code').val();
        const category    = $('#category').val();

        // Basic validation
        if (code === '' || category === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                code: code,
                category: category
            },
            success: function (response) {
               
                if(response.status == "success")
				{
					alert(response.msg);
					location.reload();
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
});

function viewCategory(categoryID){
	$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                categoryID: categoryID,
                action: 'view'
            },
            success: function (response) {
				
				$('#categoryID').val(categoryID);
                $('#edit_code').val(response.data.code);
                $('#edit_category').val(response.data.category);
				$('#edit_status').val(response.data.status);
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

$('#editCategory').on('click', function () {

		const categoryID = $('#categoryID').val();
		const edit_code = $('#edit_code').val();
        const edit_category = $('#edit_category').val();
        const edit_status = $('#edit_status').val();

        // Basic validation
        if (edit_code === '' || edit_category === '' || edit_status === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				categoryID: categoryID,
				edit_code: edit_code,
                edit_category: edit_category,
                edit_status: edit_status
            },
            success: function (response) {
                // Close modal
                alert(response.msg);
				location.reload();
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
});

function deleteCategory(categoryID)
{
	
	if (!confirm('Are you sure you want to delete this data?')) {
        return;
    }
	
	$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                categoryID: categoryID,
                action: 'delete'
            },
            success: function (response) {
				
				  alert(response.msg);
				  location.reload();
			
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

$('#addMaterial').on('click', function () {
        const code     = $('#code').val();
        const material    = $('#material').val();

        // Basic validation
        if (code === '' || material === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                code: code,
                material: material
            },
            success: function (response) {
               
                if(response.status == "success")
				{
					alert(response.msg);
					location.reload();
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
});

function viewMaterial(materialID){
	$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                materialID: materialID,
                action: 'view'
            },
            success: function (response) {
				
				$('#materialID').val(materialID);
                $('#edit_code').val(response.data.code);
                $('#edit_material').val(response.data.material);
				$('#edit_status').val(response.data.status);
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

$('#editMaterial').on('click', function () {

		const materialID = $('#materialID').val();
		const edit_code = $('#edit_code').val();
        const edit_material = $('#edit_material').val();
        const edit_status = $('#edit_status').val();

        // Basic validation
        if (edit_code === '' || edit_material === '' || edit_status === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				materialID: materialID,
				edit_code: edit_code,
                edit_material: edit_material,
                edit_status: edit_status
            },
            success: function (response) {
                // Close modal
                alert(response.msg);
				location.reload();
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
});

function deleteMaterial(materialID){
	
		if (!confirm('Are you sure you want to delete this data?')) {
        return;
    }
	
	$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                materialID: materialID,
                action: 'delete'
            },
            success: function (response) {
				
				  alert(response.msg);
				  location.reload();
			
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
	
}



