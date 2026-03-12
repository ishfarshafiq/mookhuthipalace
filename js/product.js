$(document).ready(function () {
	const userID = $('#userID').val();
	const customer_code = $('#customer_code').val();
	reloadCart(userID, customer_code);
});

document.getElementById('addToCartBtn').addEventListener('click', () => {
	 const userID = $('#userID').val();
	 const customer_code = $('#customer_code').val();
     const productID = $('#productID').val();
	 const style = $('#selected_style').val() ?? '';
	 const color = $('#selected_color').val() ?? '';
	 const qty = $('#qtyInput').val();
	 
	$.ajax({
            url: 'CartController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				userID: userID,
				customer_code: customer_code,
                productID: productID,
				style: style,
				color: color,
				qty: qty
            },
            success: function (response) {
				
				if(response.status == "success")
				{
					$('#customer_code').text(response.customer_code);
					
					//reloadcart
					reloadCart(userID, response.customer_code);
					
					$('#cartToastMsg').text(response.msg);
					const toast = new bootstrap.Toast(document.getElementById('cartToast'));
					toast.show();
				}
				else
				{
					alert("Unable to add into cart.")
				}
               
                 
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
    
});

function reloadCart(userID, customer_code){
	
	$.ajax({
            url: 'CartController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				number_of_products:'number_of_products',
				userID: userID,
				customer_code: customer_code
            },
            success: function (response) {
				
				if(response.status == "success")
				{
					$('#cart-badge').text(response.counts_products_incart);
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
	
}