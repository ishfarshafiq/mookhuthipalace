$(document).ready(function () {
	const userID = $('#userID').val();
	const customer_code = $('#customer_code').val();
	reloadCart(userID, customer_code);
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

$("#checkoutForm").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    // Convert to object (optional)
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
	
	 // Billing Required Fields
	let requiredBillingFields = [
		"billing_first_name",
		"billing_last_name",
		"billing_email",
		"billing_phone",
		"billing_address",
		"billing_city",
		"billing_state",
		"billing_postcode",
		"billing_country"
	];
	
	
	for (let field of requiredBillingFields) {
		if (!data[field] || data[field] === "") {
			alert("Please fill all billing details.");
			return;
		}
	}
	
	
	if(data.delivery_method == "selfCollect")
	{
		//Select Collecting Point
		let collectBranch = $("input[name='collectBranch']:checked").val();
		if (!collectBranch) {
            alert("Please select collection branch.");
            return;
        }
		
		
		data.collectBranch = collectBranch;
		
	}
	
	if(data.delivery_method === "standard")
	{
		
		let isShippingDifferent = $("#shippingToggle").is(":checked");
		data.isShippingDifferent = isShippingDifferent;
		
		if ($("#shippingToggle").is(":checked")) {
			
			let requiredShippingFields = [
					"shipping_name",
					"shipping_email",
					"shipping_phone",
					"shipping_address_line1",
					"shipping_address_line2",
					"shipping_city",
					"shipping_state",
					"shipping_postcode",
					"shipping_country"
				];
				
			 for (let field of requiredShippingFields) {
				if (!data[field] || data[field] === "") {
					alert("Please fill all shipping details.");
					return;
				}
			}
		
		
		
		}
	
	}
	
	let recommendedBranch = $("input[name='branch']:checked").val();
	
	if(!recommendedBranch)
	{
		alert("Please choose which branch recommended this website?");
		return;
	}
	
	data.recommendedBranch = recommendedBranch;
	
	let paymentMethod = $("#payment_method").val();
	if(paymentMethod == "card")
	{
		
		 // Billing Required Fields
		let requiredCardFields = [
			"card_holder_name",
			"card_number",
			"expiry_date",
			"cvv"
		];
		
		for (let field of requiredCardFields) {
			if (!data[field] || data[field] === "") {
				alert("Please fill all card details.");
				return;
			}
		}
		
	}
	
    $.ajax({
        url: "submit_orders.php",
        type: "POST",
		dataType: 'json',
        data: data,
        success: function (res) {
			if(res.status == "success")
			{
				checkCustomerAccount(res.customer_code, res.order_code, res.billing_addressID);
				//alert("Your order code: "+res.order_code);
			}
			
			if(res.status == "exists")
			{
				alert(res.order_code+" "+res.msg);
			}
        },
		 error: function(e, jxr) {
				alert("Your request is not successful. " + jxr);
			}
    });

    //console.log(data);

});

function checkCustomerAccount(customer_code, order_code, billing_addressID)
{
	$.ajax({
        url: "AccountController.php",
        type: "POST",
		dataType: 'json',
       data: {
            customer_code: customer_code,
            order_code: order_code,
			billing_addressID: billing_addressID
        },
        success: function (res) {
			if(res.status === "success" && res.user_account === "existing") {
               
			  
			   location.href = "process.php?userID="+ res.userID +"&order_code=" + order_code;
			   
            }
			else if(res.status === "success" && res.user_account === "new_account")
			{
				 location.href = "signup.php?userID="+ res.userID +"&order_code=" + order_code;
			}
			else
			{
				alert(res.message);
				location.href = "products.php";
			}
        },
		 error: function(xhr) {
				alert("Your request is not successful. ");
				console.log(xhr.responseText);
			}
    });
}


function toggleShippingAddress() {
			const userID = $('#userID').val();
            const shippingSection = document.getElementById('shippingAddressSection');
            const shippingInputs = document.querySelectorAll('.shipping-input');
            const toggle = document.getElementById('shippingToggle');

            if (toggle.checked) {
                shippingSection.style.display = 'block';
				// Make shipping inputs required
                shippingInputs.forEach(input => {
                    if (input.type !== 'email' && input.type !== 'tel' && input.getAttribute('placeholder') !== 'Apartment, suite, etc.') {
                        input.setAttribute('required', 'required');
                    }
                });
				
			
			// ✅ Get currently active (checked) radio
				$.ajax({
					url: 'AccountController.php',
					type: 'POST',
					dataType: 'json',
					data: {
						userID: userID,
						active_shipping_addressID: "get active shipping addrss"
					},
					success: function (response) {
					   
					 if(response.status == "success")
					 {
						//populate shipping data data
						$('#shipping_name').val(response.data.name);
						$('#shipping_phone').val(response.data.phone);
						$('#shipping_email').val(response.data.email);
						$('#shipping_address_line1').val(response.data.address_line1);
						$('#shipping_address_line2').val(response.data.address_line2);
						$('#shipping_city').val(response.data.city);
						$('#shipping_state').val(response.data.state);
						$('#shipping_postcode').val(response.data.postcode);
						$('#shipping_country').val("MY");
						
					 }
				 
					},
					error: function(e, jxr) {
							alert("Your request is not successful. " + jxr);
					}
				});
				
				
				
				
            } else {
                shippingSection.style.display = 'none';
                // Remove required attribute
                shippingInputs.forEach(input => {
                    input.removeAttribute('required');
                    input.value = '';
                });
            }
        }
		
		
function resetShippingAddress()
{
	$('#shipping_name').val("");
	$('#shipping_phone').val("");
	$('#shipping_email').val("");
	$('#shipping_address_line1').val("");
	$('#shipping_address_line2').val("");
	$('#shipping_city').val("");
	$('#shipping_state').val("");
	$('#shipping_postcode').val("");
}