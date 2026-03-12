$(document).ready(function () {
	const userID = $('#userID').val();
	const customer_code = $('#customer_code').val();
	reloadCart(userID,customer_code);
});

function reloadCart(userID,customer_code){
	
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

