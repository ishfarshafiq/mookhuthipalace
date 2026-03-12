function deleteTestimoni(testimoniID)
{
	if (!confirm("Are you sure you want to delete this testimoni?")) return;
	
	  $.ajax({
			url: 'ProcessController.php',
			type: 'POST',
			dataType: 'json',
			data: {
				delete_testimoni: 'delete_testimoni',
				testimoniID: testimoniID
			},
			success: function(res) {
				if (res.status === "success") {
					alert("Testimoni deleted successfully");
					location.reload();
				} else {
					alert(res.msg || "Failed to delete testimoni");
				}
			},
			error: function() {
				alert("Server error");
			}
		});
}