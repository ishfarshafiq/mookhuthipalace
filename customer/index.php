<?php
session_start();
include("dbconnect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Mookhuthi Palace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link href="css/style_profile.css" rel="stylesheet" type="text/css" />
  
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
   
   <?php include_once('includes/side_bar.php'); ?>
   
	<!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">👤 My Profile</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="padding-bottom: 5rem;">
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">👩</div>
                <div class="profile-name">Priya Sharma</div>
                <div class="profile-email"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                        data-cfemail="e09092899981ce938881928d81a0858d81898cce838f8d">[email&#160;protected]</a></div>

                <ul class="sidebar-menu">
                    <li><button class="menu-btn active" data-section="account"><i
                                class="fas fa-user-circle me-2"></i>Account Details</button></li>
                    <li><button class="menu-btn" data-section="purchases"><i class="fas fa-shopping-history me-2"></i>My
                            Purchases</button></li>
                    <li><button class="menu-btn" data-section="billing"><i
                                class="fas fa-map-marker-alt me-2"></i>Addresses</button></li>
                </ul>

                <button class="logout-btn" onclick="handleLogout()">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </div>

            <!-- Content Area -->
            <div class="profile-content">
                <!-- Account Details -->
                <div class="content-section active" id="account">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Account Details
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">Priya Sharma</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                data-cfemail="9fefedf6e6feb1ecf7feedf2fedffaf2fef6f3b1fcf0f2">[email&#160;protected]</a>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">+60 12 345 6789</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Date of Birth</div>
                        <div class="detail-value">15 March 1995</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value">January 2022</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value" style="color: #4ade80;"><i class="fas fa-check-circle me-2"></i>Active
                        </div>
                    </div>

                    <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#editAccountModal"><i
                            class="fas fa-edit me-2"></i>Edit Account Details</button>
                </div>

                <!-- My Purchases -->
                <div class="content-section" id="purchases">
                    <div class="section-title">
                        <i class="fas fa-shopping-history"></i> My Purchases
                    </div>

                    <div class="purchase-item">
                        <div class="purchase-header">
                            <div class="purchase-id">Order #MP-2024-00891</div>
                            <div class="purchase-status status-delivered"><i
                                    class="fas fa-check-circle me-1"></i>Delivered</div>
                        </div>
                        <div class="purchase-details">
                            <div><strong>Date:</strong> January 21, 2026</div>
                            <div><strong>Amount:</strong> RM 659.97</div>
                            <div><strong>Items:</strong> 3 Products</div>
                            <div><strong>Delivery:</strong> January 26, 2026</div>
                        </div>
                        <button class="btn-view-order" data-bs-toggle="modal" data-bs-target="#orderDetailsModal"
                            onclick="viewOrderDetails(1)">
                            <i class="fas fa-eye me-1"></i>View Order
                        </button>
                    </div>

                    <div class="purchase-item">
                        <div class="purchase-header">
                            <div class="purchase-id">Order #MP-2024-00852</div>
                            <div class="purchase-status status-processing"><i
                                    class="fas fa-shipping-fast me-1"></i>Processing</div>
                        </div>
                        <div class="purchase-details">
                            <div><strong>Date:</strong> January 18, 2026</div>
                            <div><strong>Amount:</strong> RM 449.98</div>
                            <div><strong>Items:</strong> 2 Products</div>
                            <div><strong>Est. Delivery:</strong> January 28, 2026</div>
                        </div>
                        <button class="btn-view-order" data-bs-toggle="modal" data-bs-target="#orderDetailsModal"
                            onclick="viewOrderDetails(2)">
                            <i class="fas fa-eye me-1"></i>View Order
                        </button>
                    </div>

                    <div class="purchase-item">
                        <div class="purchase-header">
                            <div class="purchase-id">Order #MP-2024-00810</div>
                            <div class="purchase-status status-delivered"><i
                                    class="fas fa-check-circle me-1"></i>Delivered</div>
                        </div>
                        <div class="purchase-details">
                            <div><strong>Date:</strong> January 5, 2026</div>
                            <div><strong>Amount:</strong> RM 299.99</div>
                            <div><strong>Items:</strong> 1 Product</div>
                            <div><strong>Delivery:</strong> January 10, 2026</div>
                        </div>
                        <button class="btn-view-order" data-bs-toggle="modal" data-bs-target="#orderDetailsModal"
                            onclick="viewOrderDetails(3)">
                            <i class="fas fa-eye me-1"></i>View Order
                        </button>
                    </div>
                </div>

                <!-- Addresses (Combined Billing & Shipping) -->
                <div class="content-section" id="billing">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Manage Addresses
                    </div>

                    <!-- Address Tabs -->
                    <ul class="nav nav-tabs" id="addressTabs" role="tablist"
                        style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.25); margin-bottom: 2rem;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="billing-tab" data-bs-toggle="tab"
                                data-bs-target="#billingTab" type="button" role="tab" aria-controls="billingTab"
                                aria-selected="true"
                                style="color: var(--mp-text); border: none; border-bottom: 2px solid transparent; padding: 0.75rem 1.5rem; transition: all 0.3s ease;">
                                <i class="fas fa-credit-card me-2"></i>Billing Address
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab"
                                data-bs-target="#shippingTab" type="button" role="tab" aria-controls="shippingTab"
                                aria-selected="false"
                                style="color: var(--mp-text); border: none; border-bottom: 2px solid transparent; padding: 0.75rem 1.5rem; transition: all 0.3s ease;">
                                <i class="fas fa-truck me-2"></i>Shipping Address
                            </button>
                        </li>
                    </ul>

                    <style>
                        .nav-link.active {
                            color: var(--mp-gold) !important;
                            border-bottom-color: var(--mp-gold) !important;
                        }

                        .nav-link:hover {
                            color: var(--mp-gold-soft) !important;
                        }
                    </style>

                    <!-- Tab Content -->
                    <div class="tab-content" id="addressTabsContent">
                        <!-- Billing Address Tab -->
                        <div class="tab-pane fade show active" id="billingTab" role="tabpanel"
                            aria-labelledby="billing-tab">
                            <div class="address-grid">
                                <div class="address-card">
                                    <div class="address-title"><i class="fas fa-map-pin"></i> Primary Billing Address
                                    </div>
                                    <div class="address-text">
                                        Priya Sharma<br>
                                        123 Main Street, Apartment 45<br>
                                        Kuala Lumpur, KL 50050<br>
                                        Malaysia<br>
                                        +60 12 345 6789
                                    </div>
                                    <div class="address-actions">
                                        <button class="btn-small" data-bs-toggle="modal"
                                            data-bs-target="#editAddressModal"
                                            onclick="openEditAddress('billing', 1)"><i
                                                class="fas fa-edit me-1"></i>Edit</button>
                                        <button class="btn-small"><i class="fas fa-trash me-1"></i>Delete</button>
                                    </div>
                                </div>

                                <div class="address-card">
                                    <div class="address-title"><i class="fas fa-plus"></i> Add New Address</div>
                                    <div class="address-text">
                                        Click the button below to add a new billing address for your account.
                                    </div>
                                    <div class="address-actions">
                                        <button class="btn-small" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal" onclick="openAddAddress('billing')"><i
                                                class="fas fa-plus me-1"></i>Add Address</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address Tab -->
                        <div class="tab-pane fade" id="shippingTab" role="tabpanel" aria-labelledby="shipping-tab">
                            <div class="address-grid">
                                <div class="address-card">
                                    <div class="address-title"><i class="fas fa-map-pin"></i> Primary Shipping Address
                                    </div>
                                    <div class="address-text">
                                        Priya Sharma<br>
                                        123 Main Street, Apartment 45<br>
                                        Kuala Lumpur, KL 50050<br>
                                        Malaysia<br>
                                        +60 12 345 6789
                                    </div>
                                    <div class="address-actions">
                                        <button class="btn-small" data-bs-toggle="modal"
                                            data-bs-target="#editAddressModal"
                                            onclick="openEditAddress('shipping', 1)"><i
                                                class="fas fa-edit me-1"></i>Edit</button>
                                        <button class="btn-small"><i class="fas fa-trash me-1"></i>Delete</button>
                                    </div>
                                </div>

                                <div class="address-card">
                                    <div class="address-title"><i class="fas fa-map-pin"></i> Work Address</div>
                                    <div class="address-text">
                                        Priya Sharma<br>
                                        Corporate Tower, Floor 10<br>
                                        Bangsar, KL 59100<br>
                                        Malaysia<br>
                                        +60 12 345 6789
                                    </div>
                                    <div class="address-actions">
                                        <button class="btn-small" data-bs-toggle="modal"
                                            data-bs-target="#editAddressModal"
                                            onclick="openEditAddress('shipping', 2)"><i
                                                class="fas fa-edit me-1"></i>Edit</button>
                                        <button class="btn-small"><i class="fas fa-trash me-1"></i>Delete</button>
                                    </div>
                                </div>

                                <div class="address-card">
                                    <div class="address-title"><i class="fas fa-plus"></i> Add New Address</div>
                                    <div class="address-text">
                                        Click the button below to add a new shipping address for your account.
                                    </div>
                                    <div class="address-actions">
                                        <button class="btn-small" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal" onclick="openAddAddress('shipping')"><i
                                                class="fas fa-plus me-1"></i>Add Address</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Details Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAccountLabel"><i class="fas fa-user-circle me-2"></i>Edit Account Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editAccountForm">
                        <div class="mb-3">
                            <label for="fullName" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Full Name</label>
                            <input type="text" class="form-control" id="fullName" value="Priya Sharma"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Email Address</label>
                            <input type="email" class="form-control" id="email" value="priya.sharma@example.com"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" value="+60 12 345 6789"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label" style="color: var(--mp-muted); font-weight: 600;">Date
                                of Birth</label>
                            <input type="date" class="form-control" id="dob" value="1995-03-15"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAccountDetails()"
                        style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <h5 class="modal-title"
                        style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700;"
                        id="editAddressLabel"><i class="fas fa-edit me-2"></i>Edit Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem; color: var(--mp-text);">
                    <form id="editAddressForm">
                        <div class="mb-3">
                            <label for="addressName" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Full Name</label>
                            <input type="text" class="form-control" id="addressName" value="Priya Sharma"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Street Address</label>
                            <input type="text" class="form-control" id="address" value="123 Main Street, Apartment 45"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">City</label>
                                <input type="text" class="form-control" id="city" value="Kuala Lumpur"
                                    style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">State/Province</label>
                                <input type="text" class="form-control" id="state" value="KL"
                                    style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postal" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">Postal Code</label>
                                <input type="text" class="form-control" id="postal" value="50050"
                                    style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label"
                                    style="color: var(--mp-muted); font-weight: 600;">Country</label>
                                <input type="text" class="form-control" id="country" value="Malaysia"
                                    style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="addressPhone" class="form-label"
                                style="color: var(--mp-muted); font-weight: 600;">Phone Number</label>
                            <input type="tel" class="form-control" id="addressPhone" value="+60 12 345 6789"
                                style="background: rgba(212, 175, 55, 0.05); border: 0.5px solid rgba(212, 175, 55, 0.25); color: var(--mp-text); border-radius: 6px; padding: 0.75rem;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAddress()"
                        style="background: linear-gradient(135deg, var(--mp-gold), var(--mp-gold-soft)); border: none; color: var(--mp-black); padding: 0.6rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Save
                        Address</button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: linear-gradient(135deg, rgba(26, 15, 10, 0.95) 0%, rgba(11, 11, 12, 0.98) 100%); border: 0.5px solid rgba(212, 175, 55, 0.25); border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <div>
                        <h5 class="modal-title"
                            style="color: var(--mp-gold); font-family: 'Playfair Display', serif; font-weight: 700; margin-bottom: 0.25rem;"
                            id="orderDetailsLabel">Order Details</h5>
                        <p style="color: var(--mp-muted); font-size: 0.85rem; margin: 0;" id="modalOrderNumber"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0.7);"></button>
                </div>
                <div class="modal-body"
                    style="padding: 1.5rem; color: var(--mp-text); max-height: 70vh; overflow-y: auto;">
                    <!-- Order Status -->
                    <div
                        style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem;">Order Status</h6>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div id="modalStatusIcon"
                                style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            </div>
                            <div>
                                <p style="margin: 0; font-weight: 600; font-size: 1.1rem;" id="modalStatusText"></p>
                                <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.9rem;"
                                    id="modalStatusDate"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div
                        style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem;">Items Ordered</h6>
                        <div id="modalOrderItems" style="display: flex; flex-direction: column; gap: 1rem;"></div>
                    </div>

                    <!-- Order Summary -->
                    <div
                        style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem;">Order Summary</h6>
                        <div style="display: grid; gap: 0.75rem;">
                            <div
                                style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                                <span style="color: var(--mp-muted);">Subtotal</span>
                                <span id="modalSubtotal" style="font-weight: 600;"></span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                                <span style="color: var(--mp-muted);">Shipping</span>
                                <span id="modalShipping" style="font-weight: 600;"></span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                                <span style="color: var(--mp-muted);">Tax</span>
                                <span id="modalTax" style="font-weight: 600;"></span>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; padding-top: 0.75rem; color: var(--mp-gold); font-weight: 700; font-size: 1.1rem;">
                                <span>Total Amount</span>
                                <span id="modalTotalAmount"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div
                        style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 0.5px solid rgba(212, 175, 55, 0.15);">
                        <h6 style="color: var(--mp-gold); font-weight: 700; margin-bottom: 1rem;">Delivery Information
                        </h6>
                        <div>
                            <p style="color: var(--mp-muted); margin: 0 0 0.5rem 0;">Delivery Address</p>
                            <p style="margin: 0; line-height: 1.6;" id="modalDeliveryAddress"></p>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer" style="border-top: 0.5px solid rgba(212, 175, 55, 0.15); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background: rgba(212, 175, 55, 0.15); border: none; color: var(--mp-text); padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Close</button>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>About Us</h5>
                    <p style="color: #bbb; font-size: 0.95rem;">Mookhuthi Palace - Your destination for authentic,
                        luxurious Indian nose pins since 2015.</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="products.php">Shop</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section mb-3 mb-md-0">
                    <h5>Customer Service</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Track Order</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-section">
                    <h5>Connect With Us</h5>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#"><i class="fab fa-facebook-f"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-instagram"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                        <a href="#"><i class="fab fa-youtube"
                                style="color: var(--mp-gold); font-size: 1.2rem; text-decoration: none;"></i></a>
                    </div>
                </div>
            </div>
            <div
                style="border-top: 0.5px solid var(--mp-border); padding-top: 2rem; margin-top: 2rem; text-align: center; color: var(--mp-muted); font-size: 0.9rem;">
                <p>&copy; 2026 Mookhuthi Palace. All rights reserved. | Privacy Policy | Terms & Conditions</p>
            </div>
        </div>
    </footer>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Menu Functionality
        const toggleMenu = document.getElementById('toggleMenu');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        toggleMenu.addEventListener('click', () => {
            toggleMenu.classList.toggle('active');
            sideMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
        });

        menuOverlay.addEventListener('click', () => {
            toggleMenu.classList.remove('active');
            sideMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        const sideMenuLinks = sideMenu.querySelectorAll('a');
        sideMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                toggleMenu.classList.remove('active');
                sideMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });
        });

        // Profile Section Navigation
        const menuButtons = document.querySelectorAll('.menu-btn');
        const contentSections = document.querySelectorAll('.content-section');

        menuButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and sections
                menuButtons.forEach(btn => btn.classList.remove('active'));
                contentSections.forEach(section => section.classList.remove('active'));

                // Add active class to clicked button and corresponding section
                button.classList.add('active');
                const sectionId = button.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
            });
        });

        // ===== ACCOUNT DETAILS FUNCTIONS =====
        function saveAccountDetails() {
            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const dob = document.getElementById('dob').value;

            if (fullName && email && phone) {
                alert('Account details updated successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAccountModal'));
                modal.hide();
            } else {
                alert('Please fill in all required fields');
            }
        }

        // ===== ADDRESS FUNCTIONS =====
        let currentAddressType = '';
        let currentAddressId = '';

        function openEditAddress(type, addressId) {
            currentAddressType = type;
            currentAddressId = addressId;
        }

        function saveAddress() {
            const name = document.getElementById('addressName').value;
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const postal = document.getElementById('postal').value;
            const country = document.getElementById('country').value;
            const phone = document.getElementById('addressPhone').value;

            if (name && address && city && state && postal && country && phone) {
                alert('Address updated successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAddressModal'));
                modal.hide();
            } else {
                alert('Please fill in all required fields');
            }
        }

        function openAddAddress(type) {
            currentAddressType = type;
            document.getElementById('addAddressForm').reset();
        }

        function addNewAddress() {
            const type = document.getElementById('addressType').value;
            const name = document.getElementById('newAddressName').value;
            const address = document.getElementById('newAddress').value;
            const city = document.getElementById('newCity').value;
            const state = document.getElementById('newState').value;
            const postal = document.getElementById('newPostal').value;
            const country = document.getElementById('newCountry').value;
            const phone = document.getElementById('newAddressPhone').value;

            if (name && address && city && state && postal && country && phone) {
                alert('New address added successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
                modal.hide();
            } else {
                alert('Please fill in all required fields');
            }
        }

        // ===== ORDER DETAILS DATA =====
        const orderData = {
            1: {
                orderId: '#MP-2024-00891',
                status: 'Delivered',
                statusIcon: 'fas fa-check-circle',
                statusColor: '#4ade80',
                statusDate: 'Delivered on January 26, 2026',
                items: [
                    { name: 'Gold Plated Kundan Nose Pin', quantity: 1, price: 'RM 199.99' },
                    { name: 'Emerald Stone Nose Pin', quantity: 1, price: 'RM 249.99' },
                    { name: 'Diamond Shape Nose Pin', quantity: 1, price: 'RM 209.99' }
                ],
                subtotal: 'RM 659.97',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 659.97',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
               
            },
            2: {
                orderId: '#MP-2024-00852',
                status: 'Processing',
                statusIcon: 'fas fa-shipping-fast',
                statusColor: '#d4af37',
                statusDate: 'Est. Delivery: January 28, 2026',
                items: [
                    { name: 'Pearl Studded Nose Pin', quantity: 1, price: 'RM 179.99' },
                    { name: 'Ruby Red Nose Pin', quantity: 1, price: 'RM 269.99' }
                ],
                subtotal: 'RM 449.98',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 449.98',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
                timeline: [
                    { date: 'January 18, 2026', event: 'Order Placed', completed: true },
                    { date: 'January 19, 2026', event: 'Order Confirmed', completed: true },
                    { date: 'January 25, 2026', event: 'In Transit', completed: false },
                    { date: 'January 28, 2026', event: 'Expected Delivery', completed: false }
                ]
            },
            3: {
                orderId: '#MP-2024-00810',
                status: 'Delivered',
                statusIcon: 'fas fa-check-circle',
                statusColor: '#4ade80',
                statusDate: 'Delivered on January 10, 2026',
                items: [
                    { name: 'Emerald Kundan Nose Pin', quantity: 1, price: 'RM 299.99' }
                ],
                subtotal: 'RM 299.99',
                shipping: 'RM 0.00',
                tax: 'RM 0.00',
                total: 'RM 299.99',
                address: 'Priya Sharma<br>123 Main Street, Apartment 45<br>Kuala Lumpur, KL 50050<br>Malaysia<br>Phone: +60 12 345 6789',
                timeline: [
                    { date: 'January 5, 2026', event: 'Order Placed', completed: true },
                    { date: 'January 6, 2026', event: 'Order Confirmed', completed: true },
                    { date: 'January 8, 2026', event: 'Shipped', completed: true },
                    { date: 'January 10, 2026', event: 'Delivered', completed: true }
                ]
            }
        };

        // ===== VIEW ORDER DETAILS FUNCTION =====
        function viewOrderDetails(orderId) {
            const order = orderData[orderId];

            // Set order number
            document.getElementById('modalOrderNumber').textContent = `Order ${order.orderId}`;

            // Set status
            const statusIcon = document.getElementById('modalStatusIcon');
            statusIcon.innerHTML = `<i class="${order.statusIcon}" style="font-size: 1.8rem; color: ${order.statusColor};"></i>`;
            statusIcon.style.background = order.statusColor === '#4ade80' ? 'rgba(74, 222, 128, 0.2)' : 'rgba(212, 175, 55, 0.2)';

            document.getElementById('modalStatusText').textContent = order.status;
            document.getElementById('modalStatusDate').textContent = order.statusDate;

            // Set order items
            const itemsContainer = document.getElementById('modalOrderItems');
            itemsContainer.innerHTML = order.items.map(item => `
                <div style="padding: 1rem; background: rgba(212, 175, 55, 0.05); border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; font-weight: 600;">${item.name}</p>
                        <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.85rem;">Quantity: ${item.quantity}</p>
                    </div>
                    <span style="color: var(--mp-gold); font-weight: 600;">${item.price}</span>
                </div>
            `).join('');

            // Set order summary
            document.getElementById('modalSubtotal').textContent = order.subtotal;
            document.getElementById('modalShipping').textContent = order.shipping;
            document.getElementById('modalTax').textContent = order.tax;
            document.getElementById('modalTotalAmount').textContent = order.total;

            // Set delivery address
            document.getElementById('modalDeliveryAddress').innerHTML = order.address;

            // Set order timeline
            const timelineContainer = document.getElementById('modalOrderTimeline');
            timelineContainer.innerHTML = order.timeline.map((item, index) => `
                <div style="margin-bottom: ${index === order.timeline.length - 1 ? '0' : '1.5rem'}; display: flex; gap: 1rem;">
                    <div style="width: 16px; height: 16px; border-radius: 50%; background: ${item.completed ? 'var(--mp-gold)' : 'rgba(212, 175, 55, 0.3)'}; margin-top: 0.25rem; flex-shrink: 0;"></div>
                    <div>
                        <p style="margin: 0; font-weight: 600; color: var(--mp-text);">${item.event}</p>
                        <p style="margin: 0.25rem 0 0 0; color: var(--mp-muted); font-size: 0.9rem;">${item.date}</p>
                    </div>
                </div>
            `).join('');
        }

        // ===== FORM INPUT STYLING =====
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.style.borderColor = 'var(--mp-gold)';
                this.style.boxShadow = '0 0 8px rgba(212, 175, 55, 0.3)';
            });
            input.addEventListener('blur', function () {
                this.style.borderColor = 'rgba(212, 175, 55, 0.25)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>

</html>