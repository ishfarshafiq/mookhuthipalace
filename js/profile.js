$(document).ready(function () {
	const userID = $('#userID').val() || "";
	const customer_code = $('#customer_code').val() || "";;
	if(userID != "" || customer_code != "")
	{
		reloadCart(userID,customer_code);	
	}
	else
	{
		$('#cart-badge').text(0);
	}
	
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

$(document).ready(function () {

    $("#togglePassword").click(function () {

        const passwordInput = $("#password");
        const icon = $(this).find("i");

        if (passwordInput.attr("type") === "password") {
            passwordInput.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            passwordInput.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }

    });

});

$("#btnCheckEmail").click(function (e) {

    e.preventDefault();

    $('#error_email').text("");

    const validate_email = $('#email').val().trim();
    var error = 0;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(validate_email)) {
        $('#error_email').text("Please enter a valid email address");
        error++;
    }

    if (error === 0) {

        $.ajax({
            url: 'AccountController.php',
            type: 'POST',
            dataType: 'json',
            data: {
                validate_email: validate_email
            },
            success: function (response) {

                if (response.status === "success") {
                    location.href = 'changepassword.php?userID=' + response.userID;
                } else {
                    alert("Invalid email address!");
                }

            },
            error: function (xhr, status, error) {
                alert("Your request was not successful. " + error);
            }
        });
    }
});

$("#btnChangePassword").click(function (e) {

    e.preventDefault();

    $('#error_email').text("");
	$('#error_password').text("");
	$('#error_confirm_password').text("");

    const changepass_email = $('#email').val().trim();
	const changepass_password = $('#password').val();
    const changepass_confirm_password = $('#confirm_password').val();
	
    var error = 0;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(changepass_email)) {
        $('#error_email').text("Not a valid email address");
        error++;
    }
	
	 if (changepass_password.length < 6) {
			$('#error_password').text("Password must be at least 6 characters");
			error++;
		}
		else
		{
			$('#error_password').text("");
		}

		
		if (changepass_password !== changepass_confirm_password) {
			$('#error_confirm_password').text("Passwords do not match");
			error++;
		}
		else
		{
			$('#error_confirm_password').text("");
		}

    if (error === 0) {

        $.ajax({
            url: 'AccountController.php',
            type: 'POST',
            dataType: 'json',
            data: {
                changepass_email: changepass_email,
				changepass_password: changepass_password
            },
            success: function (response) {

                if (response.status === "success") {
					alert("Password is changed! Please login with new password.")
                    location.href = 'login.php';
                } else {
                    alert("Invalid email address!");
                }

            },
            error: function (xhr, status, error) {
                alert("Your request was not successful. " + error);
            }
        });
    }
});

$("#signupForm").on("submit", function (e) {

    e.preventDefault(); // stop auto submit
	
	$('#error_name').text("");
	$('#error_email').text("");
	$('#error_phone').text("");
	$('#error_password').text("");
	$('#error_confirm_password').text("");
	
	const params = new URLSearchParams(window.location.search);
	const userID = params.get("userID") || "";;
	const orderCode = params.get("order_code") || "";;

    const name = $('#name').val().trim();
    const email = $('#email').val().trim();
    const phone = $('#phone').val().trim();
    const password = $('#password').val();
    const confirm_password = $('#confirm_password').val();
	
	var error = 0;
    
    if (name === "") {
		$('#error_name').text("Name is required");
        error++; 
    }
	else
	{
		$('#error_name').text("");
	}

    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        $('#error_email').text("Please enter a valid email address");
        error++; 
    }
	else
	{
		$('#error_email').text("");
	}

    if (phone === "") {
		$('#error_phone').text("Please enter valid phone number (Example: +60123456789)");
        error++; 
    }
	else
	{
		$('#error_phone').text("");
	}
	
    // const phonePattern = /^\+?60\d{8,9}$/;
    // if (!phonePattern.test(phone)) {
		// $('#error_phone').text("Please enter valid phone number (Example: +60123456789)");
        // error++; 
    // }
	// else
	// {
		// $('#error_phone').text("");
	// }
	
    if (password.length < 6) {
		$('#error_password').text("Password must be at least 6 characters");
        error++;
    }
	else
	{
		$('#error_password').text("");
	}

    
    if (password !== confirm_password) {
		$('#error_confirm_password').text("Passwords do not match");
        error++;
    }
	else
	{
		$('#error_confirm_password').text("");
	}
	
	if(error == 0)
	{
		
		$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                userID: userID,
                orderCode: orderCode,
                name: name,
				email: email,
				phone: phone,
				password: password
            },
            success: function (response) {
               
              if (response.status === "success" && response.signup_orders == 1 && response.invoice_created == 1) {
					location.href = 'request.php?userID=' + userID + '&invoiceID=' + orderCode + '&gt=' + response.gt;
			  }
			  else if(response.status === "success" && response.signup_orders == 0 && response.orders == 0)
			  {
				  alert("Signup successfully, Please login to your account");
				  location.href = 'login.php';
			  }
			  else
			  {
				  alert("Unable to signup!");
				  if(userID!="" && orderCode != "")
				  {
						location.href = 'signup.php?'+ userID +'&order_code'+orderCode;  
				  }
				  else
				  {
					  location.href = 'signup.php';
				  }
				 
			  }
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
		
	}
	
	   

    
    
});

function viewOrders(ordercode){

    $.ajax({
        url: 'ProfileController.php',
        type: 'POST',
        dataType: 'json',
        data: { ordercode: ordercode },

        success: function (response) {

            if(response.status === "success"){

                let itemsHTML = "";
                let subtotal = parseFloat(response.subtotal);
				let delivery_fee = parseFloat(response.delivery_fee);

                // Set order number
                $("#modalOrderNumber").text("Order #" + response.ordercode);

                // Loop items
                response.items.forEach(function(item){

                    itemsHTML += `
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(212,175,55,0.1); padding-bottom:10px;">
                            <div>
                                <strong>${item.product_name}</strong><br>
                                <small>Style: ${item.style} | Color: ${item.color}</small><br>
                                <small>Qty: ${item.quantity}</small>
                            </div>
                            <div>
                                RM ${(item.quantity * item.price).toFixed(2)}
                            </div>
                        </div>
                    `;
                });

                $("#modalOrderItems").html(itemsHTML);

                // Summary
                $("#modalSubtotal").text("RM " + subtotal.toFixed(2));
                $("#modalShipping").text("RM " + delivery_fee.toFixed(2));
                $("#modalTax").text("RM 0.00");
                $("#modalTotalAmount").text("RM " + (subtotal + delivery_fee).toFixed(2));

                // Status
                let delivered = response.is_delivered;

                if(delivered == "Delivered"){
                    $("#modalStatusText").text("Delivered");
                    $("#modalStatusIcon").html('<i class="fas fa-check-circle fa-2x text-success"></i>');
                }else if(delivered == "Shipped"){
					$("#modalStatusText").text("Shipped");
                    $("#modalStatusIcon").html('<i class="fas fa-clock fa-2x text-warning"></i>');
				}else if(delivered == "Cancelled"){
					$("#modalStatusText").text("Cancelled");
                    $("#modalStatusIcon").html('<i class="fas fa-times-circle fa-2x text-danger"></i>');
				}
				else{
                    $("#modalStatusText").text("Processing");
                    $("#modalStatusIcon").html('<i class="fas fa-clock fa-2x text-warning"></i>');
                }

                $("#modalStatusDate").text("Payment Date: " + response.payment_date);

                // Delivery Info
				const deliveryTypes = {
						standard: "Standard",
						foreign: "Singapore",
						selfCollect: "Self Collect"
					};
				$("#modalDeliveryType").text(deliveryTypes[response.delivery_method] || "Unknown");
				
                $("#modalDeliveryAddress").html(response.billing_address.address);

                // Show modal
                $("#orderDetailsModal").modal("show");
            }
        },
        error: function(e) {
            alert("Your request is not successful.");
        }
    });
}


 // ===== ACCOUNT DETAILS FUNCTIONS =====
 function saveAccountDetails() {

	const form = document.getElementById("editAccountForm");
	const formData = new FormData(form);

	const userID = $('#userID').val() || "";
	formData.append("userID", userID);

	if(formData.get("fullName") == "" || formData.get("email") == "" || formData.get("phone") == "")
	{
		alert('Please fill in all required fields');
		return;
	}

	$.ajax({
		url: 'AccountController.php',
		type: 'POST',
		data: formData,
		processData: false,   // VERY IMPORTANT
		contentType: false,   // VERY IMPORTANT
		dataType: 'json',
		success: function (response) {

			if(response.status == "success" && response.security_changed == 1)
			{
				alert("Successfully update! Please login for verification."); 
				location.href = 'logout.php';
			}
			else
			{
				alert("Successfully update!"); 
				location.href = 'profile.php';
			}
		},
		error: function(e) {
			alert("Upload failed.");
		}
	});
}

function deleteProfileImage(userID) {
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				userID: userID,
				deleteProfileImg:'1'
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert(response.msg); 
				location.href = 'profile.php';
			 }
			
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
}

// function saveAccountDetails() {
	// const userID = $('#userID').val() || "";
	// const fullName = document.getElementById('fullName').value;
	// const email = document.getElementById('email').value;
	// const phone = document.getElementById('phone').value;
	// const dob = document.getElementById('dob').value;
	// const password = document.getElementById('password').value;

	// if(fullName == "" || email == "" || phone == "")
	// {
		// alert('Please fill in all required fields');
		// return;
	// }
	
	// $.ajax({
            // url: 'AccountController.php',
            // type: 'POST',
			// dataType: 'json',
            // data: {
				// userID: userID,
                // fullName: fullName,
				// email: email,
				// phone: phone,
				// dob: dob,
				// password: password
            // },
            // success: function (response) {
               
             // if(response.status == "success" && response.security_changed == 1)
			 // {
				// alert("Successfully update! Please login for verification."); 
				// location.href = 'logout.php';
			 // }
			 // else
			 // {
				 // alert("Successfully update!"); 
				// location.href = 'profile.php';
			 // }
		 
				
            // },
            // error: function(e, jxr) {
					// alert("Your request is not successful. " + jxr);
			// }
        // });

// }

  function saveBillingAddress() {
			const email = document.getElementById('email').value;
            const billing_first_name = document.getElementById('billing_first_name').value;
            const billing_last_name = document.getElementById('billing_last_name').value;
            const billing_address = document.getElementById('billing_address').value;
            const billing_city = document.getElementById('billing_city').value;
            const billing_state = document.getElementById('billing_state').value;
            const billing_postcode = document.getElementById('billing_postcode').value;
            const billing_phone = document.getElementById('billing_phone').value;
			
			if(billing_address == "" || billing_city == "" || billing_state == "" || billing_postcode == "")
			{
				alert('Please fill in all required fields');
				return;
			}
			
		$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				email: email,
                billing_first_name: billing_first_name,
				billing_last_name: billing_last_name,
				billing_address: billing_address,
				billing_city: billing_city,
				billing_state: billing_state,
				billing_postcode: billing_postcode,
				billing_phone: billing_phone
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert(response.msg); 
				location.href = 'profile.php';
			 }
		 
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });

}

function openShippingEditAddress(shipping_addressID){
	
	document.getElementById('shipping_addressID').value = shipping_addressID;
	
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				shipping_addressID: shipping_addressID
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				
				document.getElementById('shipping_name').value = response.data.name;
				document.getElementById('shipping_phone').value = response.data.phone;
				document.getElementById('shipping_address1').value = response.data.address_line1;
				document.getElementById('shipping_address2').value = response.data.address_line2;
				document.getElementById('shipping_city').value = response.data.city;
				document.getElementById('shipping_state').value = response.data.state;
				document.getElementById('shipping_postcode').value = response.data.postcode;
				document.getElementById('is_active').checked = response.data.is_active == 1;
			 }
		 
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
}

function saveShippingAddress()
{
	const shipping_addressID = $('#shipping_addressID').val();
	const shipping_name = $('#shipping_name').val();
	const shipping_phone = $('#shipping_phone').val();
	const shipping_address1 = $('#shipping_address1').val();
	const shipping_address2 = $('#shipping_address2').val();
	const shipping_city = $('#shipping_city').val();
	const shipping_state = $('#shipping_state').val();
	const shipping_postcode = $('#shipping_postcode').val();
	var is_active = $("#is_active").is(":checked") ? 1 : 0;
	
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				shipping_addressIDs: shipping_addressID,
				shipping_name: shipping_name,
				shipping_phone: shipping_phone,
				shipping_address1: shipping_address1, 
				shipping_address2: shipping_address2,
				shipping_city: shipping_city,
				shipping_state: shipping_state,
				shipping_postcode: shipping_postcode,
				is_active: is_active
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert("Update successfully!");
				location.href = 'profile.php';
				
			 }
		 
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
	
	
	
	
}

//Add Billing Address
function addBillingAddress() {
	
	const userID = $('#userID').val() || "";
	const addbilling_first_name = $('#addbilling_first_name').val();
	const addbilling_last_name = $('#addbilling_last_name').val();
	const addbilling_address = $('#addbilling_address').val();
	const addbilling_city = $('#addbilling_city').val();
	const addbilling_state = $('#addbilling_state').val();
	const addbilling_postcode = $('#addbilling_postcode').val();
	const addbilling_phone = $('#addbilling_phone').val();
	
	if(addbilling_first_name == "" || addbilling_last_name == "" || addbilling_address == "" || addbilling_city == "" || addbilling_state == "" || addbilling_postcode == "" || addbilling_phone == "")
	{
		alert("All fields are required.");
		return;
	}
	
	
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				userID: userID,
				addbilling_first_name: addbilling_first_name,
				addbilling_last_name: addbilling_last_name,
				addbilling_address: addbilling_address,
				addbilling_city: addbilling_city, 
				addbilling_state: addbilling_state,
				addbilling_postcode: addbilling_postcode,
				addbilling_phone: addbilling_phone
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert("Billing address saved!");
				location.href = 'profile.php';
				
			 }
		 
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
	
}

function addShippingAddress() {
	const userID = $('#userID').val() || "";
	const add_shipping_name = $('#add_shipping_name').val();
	const add_shipping_email = $('#add_shipping_email').val();
	const add_shipping_phone = $('#add_shipping_phone').val();
	const add_shipping_address1 = $('#add_shipping_address1').val();
	const add_shipping_address2 = $('#add_shipping_address2').val();
	const add_shipping_city = $('#add_shipping_city').val();
	const add_shipping_state = $('#add_shipping_state').val();
	const add_shipping_postcode = $('#add_shipping_postcode').val();
	
	if(add_shipping_name == "" || add_shipping_email == "" || add_shipping_phone == "" || add_shipping_address1 == "" || add_shipping_city == "" || add_shipping_state == "" || add_shipping_postcode == "")
	{
		alert("All fields are required.");
		return;
	}
	
	
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				userID: userID,
				add_shipping_name: add_shipping_name,
				add_shipping_email: add_shipping_email,
				add_shipping_phone: add_shipping_phone,
				add_shipping_address1: add_shipping_address1, 
				add_shipping_address2: add_shipping_address2,
				add_shipping_city: add_shipping_city,
				add_shipping_state: add_shipping_state,
				add_shipping_postcode: add_shipping_postcode
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert("Shipping address saved!");
				location.href = 'profile.php';
				
			 }
		 
				
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
	
}

function deleteShippingAddress(del_shipping_addressID) {
	
	$.ajax({
            url: 'AccountController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				del_shipping_addressID: del_shipping_addressID
            },
            success: function (response) {
               
             if(response.status == "success")
			 {
				alert("Shipping address deleted!");
				location.href = 'profile.php';
				
			 }
		 
            },
            error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
			}
        });
	
}


function updateActiveShippingAddress(radio, addressID) {

	const userID = $('#userID').val() || "";

    if (!radio.checked) return;

    // Optional: Add active class UI highlight
    document.querySelectorAll('.address-card').forEach(card => {
        card.classList.remove('active-address');
    });

    radio.closest('.address-card').classList.add('active-address');


    fetch("AccountController.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "activ_shipping_addressID=" + addressID + "&userID=" + userID
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log("Shipping address activated");
        } else {
            alert("Failed to activated shipping address");
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

//Google
function googleLoginRegister() {

    google.accounts.id.initialize({
        client_id: "40236553918-nbo9gpodc0t3aie8rt8amragjgrkmk1k.apps.googleusercontent.com",
        callback: handleGoogleResponse
    });

    google.accounts.id.prompt();
}

function handleGoogleResponse(response) {

    const data = parseJwt(response.credential);

    fetch("google-register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: data.sub,
            name: data.name,
            email: data.email
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {
            window.location.href = "profile.php";
        } else {
            alert("Failed Authentication.");
        }

    });
}

function parseJwt(token) {

    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');

    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

//FB
function facebookRegister() {

    FB.login(function(response) {

        if (response.authResponse) {

            FB.api('/me', { fields: 'id,name,email' }, function(userData) {

                fetch("facebook-register.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(userData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        window.location.href = "index.php";
                    } else {
                        alert("Something went wrong.");
                    }
                });

            });

        } else {
            alert("Facebook signup cancelled.");
			location.reload();
        }

    }, { scope: 'public_profile,email' });
}

function facebookLogin() {

    FB.login(function(response) {

        if (response.authResponse) {

            FB.api('/me', { fields: 'id,name,email' }, function(userData) {

                fetch("facebook-login.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(userData)
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "success") {
                        window.location.href = "profile.php";
                    } 
                    else if (data.status === "not_found") {
                        alert("Account not found. Please register first.");
                    } 
                    else {
                        alert("Login failed.");
                    }

                });

            });

        } else {
            alert("Facebook login cancelled.");
        }

    }, { scope: 'public_profile,email' });
}

