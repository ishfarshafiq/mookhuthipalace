function loadOrderDetailByOrderCode(ordercode)
{
		$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                ordercode: ordercode,
                action: 'view'
            },
            success: function (response) {
				
				document.getElementById('viewOrderId').textContent = response.data.ordercode;
				document.getElementById('viewCustomer').textContent = response.data.name;
				document.getElementById('viewDate').textContent = response.data.order_date;
				document.getElementById('viewAmount').textContent = "RM "+response.data.subtotal;
				document.getElementById('viewDeliveryType').textContent = response.data.delivery_method;
				
				const statusBadge = document.getElementById('viewOrderStatus');
				statusBadge.textContent =  response.data.is_delivered;
				statusBadge.className = 'badge ' + getStatusBadgeClass(response.data.is_delivered);
				
				const paymentBadge = document.getElementById('viewPaymentStatus');
				paymentBadge.textContent = response.data.payment_status;
				paymentBadge.className = 'badge ' + getPaymentBadgeClass(response.data.payment_status);
				
				
				  let productHtml = '';

					response.data.products.forEach(function(p) {
						productHtml += `
							<div>
								${p.product_name} 
								(${p.style} - ${p.color}) 
								x ${p.quantity}
							</div>
						`;
					});

					$("#viewProduct").html(productHtml);
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

function loadUpdateFormByOrderCode(ordercode)
{
		$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                ordercode: ordercode,
                action: 'editview'
            },
            success: function (response) {
				
				document.getElementById('updateOrderId').textContent = response.data.ordercode;
				//document.getElementById('orderStatusSelect').value = response.data.is_delivered;
				var deliverStatus = response.data.is_delivered;
				var select = document.getElementById('orderStatusSelect');
				select.value = deliverStatus;
				// Disable if Delivered
				if (deliverStatus === "Delivered") {
					select.disabled = true;
				} else {
					select.disabled = false;
				}
				
				
				//document.getElementById('paymentStatusSelect').value = response.data.payment_status;
				var paymentStatus = response.data.payment_status;

				var select = document.getElementById('paymentStatusSelect');
				select.value = paymentStatus;

				// Disable if Paid
				if (paymentStatus === "Paid") {
					select.disabled = true;
				} else {
					select.disabled = false;
				}
								
				
				document.getElementById('updateNotes').value = response.data.notes;
				
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

 function saveUpdates() {
	 
            const orderId = document.getElementById('updateOrderId').textContent;
            const orderStatus = document.getElementById('orderStatusSelect').value;
            const paymentStatus = document.getElementById('paymentStatusSelect').value;
            const notes = document.getElementById('updateNotes').value;
			
			
				$.ajax({
					url: 'ProcessController.php',
					type: 'POST',
					dataType: 'json',
					data: {
						orderId: orderId,
						orderStatus: orderStatus,
						paymentStatus: paymentStatus,
						notes: notes
					},
					success: function (response) {
						alert(`Order ${orderId} updated!\n\nOrder Status: ${orderStatus}\nPayment Status: ${paymentStatus}${notes ? '\nNotes: ' + notes : ''}`);
						window.location.reload();
					},
					error: function(e, jxr) {
							alert("Your request is not successful. " + jxr);
					}
				});


          
        }