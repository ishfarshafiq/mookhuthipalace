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
    <title>Mookuthi Palace - Settings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style_settings.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include_once('includes/side_bar.php'); ?>
	
	<?php include_once('includes/topbar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>Settings</h1>
            <p>Manage your account, security, and preferences</p>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs settings-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true">
                    <i class="fas fa-user"></i> Account
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                    <i class="fas fa-shield"></i> Security
                </button>
            </li>
           <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                    <i class="fas fa-bell"></i> Notifications
                </button>
            </li>-->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-api-tab" data-bs-toggle="tab" data-bs-target="#payment-api" type="button" role="tab" aria-controls="payment-api" aria-selected="false" onclick="loadEnvironment()">
                    <i class="fas fa-wallet"></i> Payment & API
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Account Tab -->
            <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                <div class="settings-group">
                    <label class="settings-label">Full Name</label>
                    <p class="settings-description">Your full name as it appears in your profile</p>
                    <input type="text" class="form-control" placeholder="Enter your full name" id="name" name="name" value="<?php echo $name;?>">
                </div>

                <div class="settings-group">
                    <label class="settings-label">Email Address</label>
                    <p class="settings-description">Your primary email for account notifications</p>
                    <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email" value="<?php echo $email;?>">
                </div>

                <div class="settings-group">
                    <label class="settings-label">Phone Number</label>
                    <p class="settings-description">Your contact phone number</p>
                    <input type="tel" class="form-control" placeholder="Enter phone number" id="phone" name="phone" value="<?php echo $phone;?>">
                </div>

                <div class="settings-group">
                    <label class="settings-label">Address</label>
                    <p class="settings-description">Your business address</p>
                    <textarea class="form-control" rows="3" id="address" name="address" placeholder="Enter your address"><?php echo $address;?></textarea>
                </div>

                <div class="button-group">
                    <button class="btn-primary" id="saveAdmin" name="saveAdmin" type="button"> <i class="fas fa-save"></i> Save Changes </button>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                <div class="settings-group">
                    <label class="settings-label">Change Password</label>
                    <p class="settings-description">Update your password regularly for security</p>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current password" onchange="validateCurrentPassword()" autocomplete="off" style="margin-bottom: 1rem;">
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password" style="margin-bottom: 1rem;" disabled>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm new password" disabled>
                </div>


                <div class="button-group">
                    <button class="btn-primary" id="savePassword" name="savePassword"> <i class="fas fa-save"></i> Change Password </button>
                </div>
            </div>

            <!-- Notifications Tab -->
            <!--<div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                <div class="alert-warning">
                    <i class="fas fa-info-circle"></i> Email notification preferences are sent to: <strong><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="0766636a6e69476a68686c72736f6e2964686a">[email&#160;protected]</a></strong>
                </div>

                <div class="settings-group">
                    <label class="settings-label">Email Notifications</label>
                    <p class="settings-description">Choose which emails you want to receive</p>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="orderNotif" checked>
                        <label class="form-check-label" for="orderNotif">
                            Order notifications and updates
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="paymentNotif" checked>
                        <label class="form-check-label" for="paymentNotif">
                            Payment and settlement alerts
                        </label>
                    </div>

                    
                </div>

                

                <div class="settings-group">
                    <label class="settings-label">Notification Message</label>
                    <p class="settings-description">Customize your notification greeting</p>
                    <textarea class="form-control" rows="3" placeholder="Thank you for your business. We appreciate your orders!" maxlength="1000">Thank you for your business. We appreciate your orders!</textarea>
                    <p class="settings-description" style="margin-top: 0.5rem;">Characters remaining: <span id="charCount">1000</span></p>
                </div>

                <div class="button-group">
                    <button class="btn-primary">
                        <i class="fas fa-save"></i> Save Notification Settings
                    </button>
                </div>
            </div>-->

            <!-- Payment Gateway & API Tab (Merged) -->
            <div class="tab-pane fade" id="payment-api" role="tabpanel" aria-labelledby="payment-api-tab">
                
				<!--<div class="alert-success">
                    <i class="fas fa-check-circle"></i> Toyyibpay gateway is active and ready
                </div>-->

                <!-- Payment Gateway Section -->
                <h5 style="color: var(--primary-blue); font-weight: 700; margin-top: 2rem; margin-bottom: 1.5rem;">
                    <i class="fas fa-wallet"></i> Payment Gateway Settings
                </h5>
				
				<div class="settings-group">
					<label class="settings-label">Environment</label>
					<select id="environment" name="environment" class="form-select" onchange="loadEnvironment()" disabled>
						<option value="">-- Select Environment --</option>
						<option value="TESTING">Sandbox (Testing)</option>
						<option value="LIVE" selected>Production (Live)</option>
					</select>
				</div>

                <div class="settings-group">
                    <label class="settings-label">Secret Key</label>
                    <p class="settings-description">Your unique secret key for Toyyibpay integration</p>
                    <div class="api-key-input-wrapper">
                        <input type="text" class="form-control" id="secret_key" name="secret_key" placeholder="Enter your Toyyibpay secret key">
                        <button class="toggle-secret-key" style="cursor: pointer; background: none; border: none; padding: 0.5rem; color: var(--primary-gold);">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="settings-group">
                    <label class="settings-label">Category Code</label>
                    <p class="settings-description">Your merchant category code</p>
                    <input type="text" class="form-control" id="category_code" name="category_code" placeholder="e.g., j0tzqhka">
                </div>
				
				 <div class="settings-group">
                    <label class="settings-label">Status</label>
					<select class="form-control" id="status" name="status">
						<option value="">Choose Status</option>
						<option value="ACTIVE">Active</option>
						<option value="INACTIVE">Inactive</option>
					</select>
                </div>
				
				 

                <div class="button-group">
                    <button class="btn-primary" id="saveAPI" name="saveAPI"> <i class="fas fa-save"></i> Save API Settings </button>
                    <!--<button class="btn-secondary" id="testConnection" name="testConnection"><i class="fas fa-plug"></i> Test Connection </button>-->
                </div>
            </div>
        </div>
    </div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
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

        // Toggle password visibility
        document.querySelectorAll('.toggle-secret-key').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });

        // Save button feedback
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Saved!';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 1500);
                }, 1000);
            });
        });

        // Copy button functionality
        document.querySelectorAll('.btn-secondary').forEach(btn => {
            btn.addEventListener('click', function (e) {
                if (this.innerHTML.includes('copy') || this.innerHTML.includes('Copy')) {
                    e.preventDefault();
                    const input = this.previousElementSibling;
                    input.select();
                    document.execCommand('copy');
                    
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                }
            });
        });
    </script>
</body>

</html>
