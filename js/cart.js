$(document).ready(function () {
	const userID = $('#userID').val();
	const customer_code = $('#customer_code').val();
	reloadCart(userID, customer_code);
	
	if (document.querySelectorAll('.cart-item').length === 0) {
		document.getElementById('emptyCart').style.display = 'block';
	}
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


function updateQuantity(btn, change, cartID) {
		const cartItem = btn.closest('.cart-item');
		const input = cartItem.querySelector('.quantity-input');

		let newQty = parseInt(input.value) + change;

		if (newQty >= 1 && newQty <= 10) {
			updateQty(cartID, newQty);
			input.value = newQty;
			
			updateSubtotals();
		}
	}


function updateQty(cartID, newQty){
	
	$.ajax({
            url: 'CartController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				cartID: cartID,
				newQty: newQty
            },
            success: function (response) {
				
				if(response.status == "success")
				{
					
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
	
}

function removeItem(btn) {
	
    const cartItem = btn.closest('.cart-item');
    const cartID = cartItem.dataset.cartid;
	const userID = $('#userID').val();
	const customer_code = $('#customer_code').val();
	
   
	
	$.ajax({
            url: 'CartController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				delete_product_cart: "delete_product_cart",
                cartID: cartID
            },
            success: function (response) {
				
				if(response.status == "success")
				{
					cartItem.remove();
					updateSubtotals();
					
					location.reload();	
				} 
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });

   
}



function updateSubtotals() {
	const items = document.querySelectorAll('.cart-item');
	let total = 0;

	items.forEach((item, index) => {
		const priceText = item.querySelector('.item-price').textContent.replace('RM ', '');
		const price = parseFloat(priceText);
		const qty = parseInt(item.querySelector('.quantity-input').value);
		const subtotal = price * qty;

		item.querySelector('.subtotal-amount').textContent = 'RM ' + subtotal.toFixed(2);
		total += subtotal;
	});

	document.getElementById('subtotal').textContent = 'RM ' + total.toFixed(2);
	document.getElementById('total').textContent = 'RM ' + total.toFixed(2);
}

     
