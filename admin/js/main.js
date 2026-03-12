$('#saveAdmin').on('click', function () {
        const name = $('#name').val();
        const email = $('#email').val();
        const phone = $('#phone').val();
		const address = $('#address').val();

        // // Basic validation
        if (name === '' || email === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
                name: name,
                email: email,
                phone: phone,
				address: address,
				save_admin:"save_admin"
            },
            success: function (response) {
               
                alert(response.msg);
				location.reload();
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
});

function validateCurrentPassword()
{
	const current_password = $('#current_password').val();
	 // // Basic validation
        if (current_password === '') {
            alert('Current password is required!');
            return;
        }
		
		$.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				current_password:current_password
            },
            success: function (response) {
               
                if(response.status == "fail")
				{
					alert('Invalid password!');
				}
				else
				{
					$("#new_password").prop("disabled", false);
					$("#confirm_new_password").prop("disabled", false);
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
	
}

$('#savePassword').on('click', function () {
       
        
        const new_password = $('#new_password').val();
		const confirm_new_password = $('#confirm_new_password').val();

        // // Basic validation
        if (new_password === '' || confirm_new_password === '') {
            alert('All fields are required');
            return;
        }

 if (new_password == confirm_new_password) {

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				new_password: new_password
            },
            success: function (response) {
               
                alert(response.msg);
				location.href='logout.php';
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		
 }
		
});

function loadEnvironment()
{
	const environment = $('#environment').val();
	 $.ajax({
            url: 'ProcessController.php',
            type: 'GET',
			dataType: 'json',
            data: {
				environment: environment
            },
            success: function (response) {
               
                if(response.status == "success")
				{
					$('#secret_key').val(response.data.secret_key);
					$('#category_code').val(response.data.category_code);
					$('#status').val(response.data.status);
				}
				else
				{
					$('#secret_key').val("");
					$('#category_code').val("");
					$('#category_code').val("INACTIVE");
				}
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
}

$('#saveAPI').on('click', function () {
       
        
        const secret_key = $('#secret_key').val();
		const category_code = $('#category_code').val();
		const status = $('#status').val();
		const environment = $('#environment').val();

        // // Basic validation
        if (secret_key === '' || category_code === '') {
            alert('All fields are required');
            return;
        }

        $.ajax({
            url: 'ProcessController.php',
            type: 'POST',
			dataType: 'json',
            data: {
				secret_key: secret_key,
				category_code: category_code,
				status: status,
				environment: environment
            },
            success: function (response) {
               
                alert(response.msg);
				
            },
             error: function(e, jxr) {
					alert("Your request is not successful. " + jxr);
				}
        });
		

		
});


