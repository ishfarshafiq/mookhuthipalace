<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link href="css/style_checkout.css" rel="stylesheet" type="text/css" />
</head>

<body>
   	<?php include_once('includes/navbar.php'); ?>
   
	<?php include_once('includes/side_bar.php'); ?>
	
    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Secure Checkout</h1>
        </div>
    </div>



    <!-- Checkout Form -->
    <div class="container checkout-container">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">

                <!-- Delivery Method -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck"></i> Delivery Method
                    </h3>

                    <div class="option-group">
                        <div class="option-card active" onclick="selectDelivery(this, 'selfCollect')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-store"></i> Self Collect</h4>
                                <p>Pick up at our store (11:00 AM - 6:00 PM)</p>
                            </div>
                            <div class="option-price">FREE</div>
                        </div>

                        <div class="option-card" onclick="selectDelivery(this, 'standard')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-truck"></i> Standard Delivery</h4>
                                <p>Delivery in 3-5 business days</p>
                            </div>
                            <div class="option-price">FREE</div>
                        </div>

                        <div class="option-card" onclick="selectDelivery(this, 'express')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-bolt"></i> Express Delivery</h4>
                                <p>Delivery in 1-2 business days</p>
                            </div>
                            <div class="option-price">RM 25.00</div>
                        </div>

                        <div class="option-card" onclick="selectDelivery(this, 'overnight')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-moon"></i> Overnight Delivery</h4>
                                <p>Delivery next business day</p>
                            </div>
                            <div class="option-price">RM 50.00</div>
                        </div>
                    </div>
                </div>

                <!-- Self Collect Branch Selection -->
                <div class="form-section" id="selfCollectSection" style="display: block;">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Select Collection Point
                    </h3>

                    <div class="option-group" style="flex-direction: column; gap: 1.5rem;">
                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="kuala-lumpur" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Kuala Lumpur</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">LOT 75-G Medan Bunus, Off Jalan Masjid India, 50100 Kuala Lumpur, WILAYAH PERSEKUTUAN</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="taiping" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Taiping</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">No. 136, Jalan Pasar, 34000 Taiping, Perak</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.06); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; cursor: pointer; transition: all 0.3s ease;" class="collection-option">
                            <input type="radio" name="collectBranch" value="ipoh" style="margin-top: 6px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--mp-gold); margin-bottom: 0.5rem; font-size: 1.1rem;">Mookhuthi Palace Ipoh</div>
                                <div style="font-size: 0.95rem; color: var(--mp-muted); line-height: 1.6;">271, Jalan Silibin, Taman Alkaff, 30100 Ipoh, Perak</div>
                                <div style="font-size: 0.85rem; color: var(--mp-muted); margin-top: 0.75rem;"><i class="fas fa-clock"></i> Operating Hours: 11:00 AM - 6:00 PM Daily</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Billing Address
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" placeholder="your@email.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" placeholder="+60 1234567890" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Street Address *</label>
                        <input type="text" class="form-control" placeholder="House,Condo, Apartment, suite, unit, etc."
                            required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">City/Town *</label>
                            <input type="text" class="form-control" placeholder="Enter city" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">State *</label>
                            <select class="form-control" required>
                                <option value="">-- Select State --</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Pulau Pinang">Pulau Pinang</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Kuala Lumpur">Kuala Lumpur (FT)</option>
                                <option value="Putrajaya">Putrajaya (FT)</option>
                                <option value="Labuan">Labuan (FT)</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" placeholder="Enter postal code" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country *</label>
                            <select class="form-control" required>
                                <option value="">Select Country</option>
                                <option value="MY">Malaysia</option>

                            </select>
                        </div>
                    </div>

                    <!-- Branch Recommendation Section -->
                    <div class="form-group"
                        style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--mp-border);">
                        <label class="form-label">Which Branch Recommended This Website? *</label>
                        <p style="color: var(--mp-muted); font-size: 0.9rem; margin-bottom: 1rem;">Please select the
                            branch that referred you to us</p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <label
                                style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="IPOH"
                                    style="margin-right: 0.75rem; cursor: pointer;" required>
                                <span>IPOH</span>
                            </label>
                            <label
                                style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="TAIPING"
                                    style="margin-right: 0.75rem; cursor: pointer;" required>
                                <span>TAIPING</span>
                            </label>
                            <label
                                style="display: flex; align-items: center; padding: 1rem; background: #2a2a2a; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; color: var(--mp-text);">
                                <input type="radio" name="branch" value="KUALA_LUMPUR"
                                    style="margin-right: 0.75rem; cursor: pointer;" required>
                                <span>KUALA LUMPUR</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Toggle -->
                <div
                    style="display: flex; align-items: center; gap: 1rem; margin: 2rem 0 0 0; padding-bottom: 2rem; border-bottom: 1px solid var(--mp-border);">
                    <label for="shippingToggle" style="margin: 0; font-weight: 500; color: var(--mp-text);">
                        Shipping Address Different from Billing
                    </label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="shippingToggle" onchange="toggleShippingAddress()">
                        <label for="shippingToggle" class="toggle-label"></label>
                    </div>
                </div>

                <!-- Shipping Address Section (Hidden by default) -->
                <div id="shippingAddressSection" class="form-section" style="display: none; margin-top: 2rem;">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Shipping Address
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control shipping-input" placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control shipping-input" placeholder="Enter email address">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control shipping-input" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address Line 1 *</label>
                            <input type="text" class="form-control shipping-input" placeholder="Street address">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" class="form-control shipping-input" placeholder="Apartment, suite, etc.">
                        </div>
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control shipping-input" placeholder="Enter city">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">State *</label>
                            <select class="form-control shipping-input">
                                <option value="">-- Select State --</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Pulau Pinang">Pulau Pinang</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Kuala Lumpur">Kuala Lumpur (FT)</option>
                                <option value="Putrajaya">Putrajaya (FT)</option>
                                <option value="Labuan">Labuan (FT)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postal Code *</label>
                            <input type="text" class="form-control shipping-input" placeholder="Enter postal code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country *</label>
                            <select class="form-control shipping-input">
                                <option value="">Select Country</option>
                                <option value="MY">Malaysia</option>
                            </select>
                        </div>
                    </div>
                </div>
              

                <!-- Payment Method -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i> Payment Method
                    </h3>

                    <div class="option-group">
                        <div class="option-card active" onclick="selectPayment(this, 'card')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-credit-card"></i> Credit/Debit Card</h4>
                                <p>Visa, MasterCard, Amex</p>
                            </div>
                        </div>
                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" style="margin-top: 2rem;">
                            <div class="form-group">
                                <label class="form-label">Card Holder Name *</label>
                                <input type="text" class="form-control" placeholder="Enter cardholder name" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Card Number *</label>
                                <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19"
                                    required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date *</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" class="form-control" placeholder="123" maxlength="3" required>
                                </div>
                            </div>
                        </div>
                        <div class="option-card" onclick="selectPayment(this, 'bank')">
                            <div class="option-radio"></div>
                            <div class="option-content" style="flex: 1;">
                                <h4><i class="fas fa-university"></i> Bank Transfer</h4>
                                <p>Direct bank transfer</p>
                            </div>
                        </div>


                    </div>


                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; margin-bottom: 3rem;">
                    <a href="cart.php" class="btn-back" style="flex: 1;">← Back to Cart</a>
                    <button class="btn-place-order" style="flex: 1;" onclick="placeOrder()">
                        <i class="fas fa-lock"></i> Place Order
                    </button>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <!-- Items -->
                    <div class="order-item">
                        <div class="item-image"><img src="img/m1.jpg" class="img-fluid"></div>
                        <div class="order-item-details">
                            <div class="order-item-name">Royal Pearl</div>
                            <div class="order-item-meta">Qty: 1</div>
                        </div>
                        <div class="order-item-price">RM 189.99</div>
                    </div>

                    <div class="order-item">
                        <div class="item-image"><img src="img/m2.jpg" class="img-fluid"></div>
                        <div class="order-item-details">
                            <div class="order-item-name">Maharaja Gold</div>
                            <div class="order-item-meta">Qty: 2</div>
                        </div>
                        <div class="order-item-price">RM 499.98</div>
                    </div>

                    <div class="order-item">
                        <div class="item-image"><img src="img/m1.jpg" class="img-fluid"></div>
                        <div class="order-item-details">
                            <div class="order-item-name">Diamond Dazzle</div>
                            <div class="order-item-meta">Qty: 1</div>
                        </div>
                        <div class="order-item-price">RM 219.99</div>
                    </div>

                    <!-- Totals -->
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>RM 909.96</span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span id="shippingCost">FREE</span>
                    </div>

                    <div class="summary-item">
                        <span>Tax</span>
                        <span>RM 0.00</span>
                    </div>

                    <div class="summary-item total">
                        <span class="label">Total</span>
                        <span class="value" id="totalAmount">RM 909.96</span>
                    </div>

                    <!-- Security Info -->
                    <div
                        style="padding: 1rem; background: rgba(212, 175, 55, 0.1); border-radius: 8px; text-align: center;">
                        <p style="font-size: 0.85rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-lock"></i> 100% Secure Checkout
                        </p>
                        <p style="font-size: 0.8rem; color: #999;">
                            Your payment information is encrypted and secure
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
	<?php include_once('includes/footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedDelivery = 'standard';
        let selectedPayment = 'card';

        // Toggle Shipping Address Section
        function toggleShippingAddress() {
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
            } else {
                shippingSection.style.display = 'none';
                // Remove required attribute
                shippingInputs.forEach(input => {
                    input.removeAttribute('required');
                    input.value = '';
                });
            }
        }

        function selectDelivery(element, type) {
            document.querySelectorAll('[onclick^="selectDelivery"]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            selectedDelivery = type;

            // Show/hide Self Collect section
            const selfCollectSection = document.getElementById('selfCollectSection');
            if (type === 'selfCollect') {
                selfCollectSection.style.display = 'block';
            } else {
                selfCollectSection.style.display = 'none';
            }

            let cost = 'FREE';
            if (type === 'express') cost = 'RM 25.00';
            if (type === 'overnight') cost = 'RM 50.00';

            document.getElementById('shippingCost').textContent = cost;
            updateTotal();
        }

        function selectPayment(element, type) {
            document.querySelectorAll('[onclick^="selectPayment"]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            selectedPayment = type;

            // Show/hide card details
            if (type === 'card') {
                document.getElementById('cardDetails').style.display = 'block';
            } else {
                document.getElementById('cardDetails').style.display = 'none';
            }
        }

        function updateTotal() {
            const subtotal = 909.96;
            let shipping = 0;

            if (selectedDelivery === 'express') shipping = 25.00;
            if (selectedDelivery === 'overnight') shipping = 50.00;

            const total = subtotal + shipping;
            document.getElementById('totalAmount').textContent = 'RM ' + total.toFixed(2);
        }

        function placeOrder() {
            // Validate form
            const inputs = document.querySelectorAll('.form-control[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = '#ef4444';
                }
            });

            // Validate branch selection
            const branchSelected = document.querySelector('input[name="branch"]:checked');
            if (!branchSelected) {
                valid = false;
                alert('Please select which branch recommended this website');
                return;
            }

            if (!valid) {
                alert('Please fill all required fields');
                return;
            }

            // Process order
            alert('Order placed successfully! Redirecting to confirmation...');
            window.location.href = 'confirmation.php';
        }

        // Toggle Menu Functionality
        const toggleMenu = document.getElementById('toggleMenu');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        if (toggleMenu) {
            // Toggle menu open/close
            toggleMenu.addEventListener('click', () => {
                toggleMenu.classList.toggle('active');
                sideMenu.classList.toggle('active');
                menuOverlay.classList.toggle('active');
            });

            // Close menu when clicking overlay
            menuOverlay.addEventListener('click', () => {
                toggleMenu.classList.remove('active');
                sideMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });

            // Close menu when clicking a link
            const sideMenuLinks = sideMenu.querySelectorAll('a');
            sideMenuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    toggleMenu.classList.remove('active');
                    sideMenu.classList.remove('active');
                    menuOverlay.classList.remove('active');
                });
            });
        }
    </script>
</body>

</html>