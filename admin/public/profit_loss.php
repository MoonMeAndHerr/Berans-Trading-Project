<?php

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';
include __DIR__ . '/../include/header.php';
?>



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                                                <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Basic Elements</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Basic Elements</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Clean Security Access -->
                        <div id="securitySection">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="clean-card p-4 text-center">
                                        <div class="mb-4">
                                            <i class="ri-lock-line" style="font-size: 48px; color: #6b7280;"></i>
                                        </div>
                                        <h3 class="mb-2" style="color: #111827;">Security Verification</h3>
                                        <p class="text-muted mb-4">Enter your access key to view profit & loss data</p>
                                        
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="password" class="form-control-clean" id="accessKey" 
                                                       placeholder="Access key" onkeypress="if(event.key === 'Enter') verifyAccess()">
                                                <button type="button" class="btn btn-clean-outline" id="toggleKeyVisibility">
                                                    <i class="ri-eye-line" id="keyIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-clean px-4" onclick="verifyAccess()">
                                            <i class="ri-unlock-line me-2"></i>Verify Access
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div id="mainContent" style="display: none;">
                            


                            <!-- Filter Controls -->
                            <div class="card mb-4">
                                <div class="card-body py-3">
                                    <div class="row g-3 align-items-end">
                                        <!-- Clean Page Header -->
                                        <div class="d-flex justify-content-between align-items-center ">
                                            <div>
                                                <h1 class="page-title">Profit & Loss Management</h1>
                                                <p class="page-subtitle">Track order profitability and manage payments</p>
                                            </div>
                                            <button class="btn btn-clean" onclick="loadProfitLossData()">
                                                <i class="ri-refresh-line me-2"></i>Refresh Data
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium text-muted">Filter by Month</label>
                                            <input type="month" id="monthFilter" class="form-control" placeholder="Select month">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium text-muted">Search Orders</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="text" id="searchInput" class="form-control border-start-0" 
                                                       placeholder="Search by order number, customer name, or date...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium text-muted">Date Range</label>
                                            <div class="input-group">
                                                <input type="date" id="dateFromFilter" class="form-control" placeholder="From">
                                                <span class="input-group-text bg-light px-2">to</span>
                                                <input type="date" id="dateToFilter" class="form-control" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-clean btn-sm" onclick="applyFilters()" title="Apply Filters">
                                                    <i class="ri-filter-line"></i> Filter
                                                </button>
                                                <button class="btn btn-clean-outline btn-sm" onclick="clearFilters()" title="Clear Filters">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clean Orders Table -->
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="profitLossTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 9%">Order #</th>
                                                <th style="width: 16%">Customer</th>
                                                <th style="width: 11%">Order Date</th>
                                                <th style="width: 13%">Profit/Loss</th>
                                                <th style="width: 13%">Total Paid</th>
                                                <th style="width: 11%">Remaining</th>
                                                <th style="width: 12%">Staff Commission</th>
                                                <th style="width: 9%">Status</th>
                                                <th style="width: 6%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="profitLossTableBody">
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="ri-loader-4-line"></i> Loading data...
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="card-footer bg-light border-top-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div id="paginationInfo" class="text-muted">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                                <!-- Will be populated by JavaScript -->
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->

                <!-- Profit Details Modal -->
                <div class="modal fade" id="profitDetailsModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Profit & Loss Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="profitDetailsContent">
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading details...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Management Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light border-0 py-4">
                                <div>
                                    <h5 class="modal-title fw-bold text-dark mb-1">Payment Management</h5>
                                    <p class="text-muted small mb-0">Manage supplier and shipping payments</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <!-- Clean Tab Navigation -->
                                <div class="border-bottom px-4 pt-3">
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active border-0 px-4 py-3 fw-medium" data-bs-toggle="tab" href="#supplierPaymentTab" role="tab">
                                                <i class="ri-factory-line me-2 fs-5"></i>
                                                Supplier Payments
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link border-0 px-4 py-3 fw-medium" data-bs-toggle="tab" href="#shippingPaymentTab" role="tab">
                                                <i class="ri-ship-line me-2 fs-5"></i>
                                                Shipping Payments
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Supplier Payment Tab -->
                                    <div class="tab-pane active" id="supplierPaymentTab" role="tabpanel">
                                        <div class="p-4">
                                            <div class="row g-4">
                                                <!-- Payment Summary -->
                                                <div class="col-md-5">
                                                    <div class="bg-light rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-calculator-line me-2"></i>Payment Summary
                                                        </h6>
                                                        <div id="supplierSummary" class="payment-summary">
                                                            <!-- Will be populated by JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Payment Form -->
                                                <div class="col-md-7">
                                                    <div class="border rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-money-dollar-circle-line me-2"></i>Make Payment
                                                        </h6>
                                                        <form id="supplierPaymentForm">
                                                            <input type="hidden" id="supplierInvoiceId" name="invoice_id">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Amount</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light border-end-0">¥</span>
                                                                    <input type="number" class="form-control border-start-0 ps-0" id="supplierPaymentAmount" 
                                                                           name="amount" step="0.01" min="0" placeholder="0.00" required>
                                                                </div>
                                                                <small class="text-muted">Amount in Japanese Yen</small>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Note <span class="text-muted">(optional)</span></label>
                                                                <textarea class="form-control" id="supplierPaymentDescription" 
                                                                          name="description" rows="2" 
                                                                          placeholder="Add payment description or notes..."></textarea>
                                                            </div>
                                                            
                                                            <div class="alert alert-light border mb-3">
                                                                <div class="d-flex align-items-start">
                                                                    <i class="ri-information-line text-muted me-2 mt-1"></i>
                                                                    <div>
                                                                        <small class="fw-medium text-dark">Payment Impact</small>
                                                                        <div id="supplierPaymentImpact" class="small text-muted">Enter amount to see impact</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-dark w-100 py-2">
                                                                <i class="ri-secure-payment-line me-2"></i>Process Payment
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Payment History -->
                                            <div class="mt-4">
                                                <h6 class="fw-bold text-dark mb-3">
                                                    <i class="ri-history-line me-2"></i>Payment History
                                                </h6>
                                                <div class="bg-light rounded-3 p-3">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr class="border-0">
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DATE</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">AMOUNT</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DESCRIPTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="supplierPaymentHistory" class="border-0">
                                                                <!-- Will be populated by JavaScript -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Payment Tab -->
                                    <div class="tab-pane" id="shippingPaymentTab" role="tabpanel">
                                        <div class="p-4">
                                            <div class="row g-4">
                                                <!-- Payment Summary -->
                                                <div class="col-md-5">
                                                    <div class="bg-light rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-calculator-line me-2"></i>Payment Summary
                                                        </h6>
                                                        <div id="shippingSummary" class="payment-summary">
                                                            <!-- Will be populated by JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Payment Form -->
                                                <div class="col-md-7">
                                                    <div class="border rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-money-dollar-circle-line me-2"></i>Make Payment
                                                        </h6>
                                                        <form id="shippingPaymentForm">
                                                            <input type="hidden" id="shippingInvoiceId" name="invoice_id">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Amount</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light border-end-0">¥</span>
                                                                    <input type="number" class="form-control border-start-0 ps-0" id="shippingPaymentAmount" 
                                                                           name="amount" step="0.01" min="0" placeholder="0.00" required>
                                                                </div>
                                                                <small class="text-muted">Amount in Japanese Yen</small>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Note <span class="text-muted">(optional)</span></label>
                                                                <textarea class="form-control" id="shippingPaymentDescription" 
                                                                          name="description" rows="2" 
                                                                          placeholder="Add payment description or notes..."></textarea>
                                                            </div>
                                                            
                                                            <div class="alert alert-light border mb-3">
                                                                <div class="d-flex align-items-start">
                                                                    <i class="ri-information-line text-muted me-2 mt-1"></i>
                                                                    <div>
                                                                        <small class="fw-medium text-dark">Payment Impact</small>
                                                                        <div id="shippingPaymentImpact" class="small text-muted">Enter amount to see impact</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-dark w-100 py-2">
                                                                <i class="ri-secure-payment-line me-2"></i>Process Payment
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Payment History -->
                                            <div class="mt-4">
                                                <h6 class="fw-bold text-dark mb-3">
                                                    <i class="ri-history-line me-2"></i>Payment History
                                                </h6>
                                                <div class="bg-light rounded-3 p-3">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr class="border-0">
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DATE</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">AMOUNT</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DESCRIPTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="shippingPaymentHistory" class="border-0">
                                                                <!-- Will be populated by JavaScript -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Payment Modal -->
                <div class="modal fade" id="addPaymentModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addPaymentForm">
                                    <input type="hidden" id="paymentInvoiceId" name="invoice_id">
                                    <input type="hidden" id="paymentType" name="payment_type">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Payment Type</label>
                                        <input type="text" class="form-control" id="paymentTypeDisplay" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Amount (¥)</label>
                                        <input type="number" class="form-control" id="paymentAmount" 
                                               name="amount" step="0.01" min="0" required>
                                        <small class="text-muted">Enter amount in Japanese Yen</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Description (Optional)</label>
                                        <textarea class="form-control" id="paymentDescription" 
                                                  name="description" rows="3" 
                                                  placeholder="Payment description or notes..."></textarea>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-money-dollar-circle-line"></i> Add Payment
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mark Complete Confirmation Modal -->
                <div class="modal fade" id="markCompleteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Mark Order as Complete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Confirmation Required:</strong> This will mark the order as completed and show final profit/loss summary.
                                </div>
                                
                                <div id="completionSummary">
                                    <!-- Summary will be loaded here -->
                                </div>
                                
                                <input type="hidden" id="completeInvoiceId">
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success" id="confirmComplete">
                                        <i class="ri-check-line"></i> Confirm Complete
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- END layout-wrapper -->
    

    <?php include __DIR__ . '/../include/themesetting.php';?>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery for easier AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

    <!-- Profit Loss Management JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Starting initialization');
            
            // DOM Elements
            const accessKeyInput = document.getElementById('accessKey');
            const verifyAccessKeyBtn = document.getElementById('verifyAccessKey');
            const toggleKeyVisibilityBtn = document.getElementById('toggleKeyVisibility');
            const keyIcon = document.getElementById('keyIcon');
            const securitySection = document.getElementById('securitySection');
            const mainContentSection = document.getElementById('mainContentSection');
            const lockAccessBtn = document.getElementById('lockAccess');

            console.log('Elements found:', {
                accessKeyInput: !!accessKeyInput,
                verifyAccessKeyBtn: !!verifyAccessKeyBtn,
                toggleKeyVisibilityBtn: !!toggleKeyVisibilityBtn,
                keyIcon: !!keyIcon
            });

            // Toggle password visibility
            if (toggleKeyVisibilityBtn && accessKeyInput && keyIcon) {
                console.log('Setting up toggle button listener');
                toggleKeyVisibilityBtn.addEventListener('click', function() {
                    console.log('Toggle button clicked');
                    console.log('Current input type:', accessKeyInput.type);
                    
                    if (accessKeyInput.type === 'password') {
                        accessKeyInput.type = 'text';
                        keyIcon.className = 'ri-eye-off-line';
                        console.log('Changed to text - showing password');
                    } else {
                        accessKeyInput.type = 'password';
                        keyIcon.className = 'ri-eye-line';
                        console.log('Changed to password - hiding text');
                    }
                });
            } else {
                console.log('Toggle elements not found!', {
                    toggleBtn: !!toggleKeyVisibilityBtn,
                    input: !!accessKeyInput,
                    icon: !!keyIcon
                });
            }

            // Verify access key
            if (verifyAccessKeyBtn) {
                console.log('Setting up verify button listener');
                verifyAccessKeyBtn.addEventListener('click', function() {
                    console.log('Verify button clicked');
                    const enteredKey = accessKeyInput.value.trim();
                    console.log('Entered key:', enteredKey);
                    console.log('Expected key:', ACCESS_KEY);
                    
                    if (enteredKey === ACCESS_KEY) {
                        console.log('Key matched - granting access');
                        accessGranted = true; // Use global variable
                        securitySection.style.display = 'none';
                        mainContentSection.style.display = 'block';
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Access Granted!',
                            text: 'You can now access the profit & loss management system.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Load profit/loss data
                        loadProfitLossData();
                    } else {
                        console.log('Key did not match - access denied');
                        Swal.fire({
                            icon: 'error',
                            title: 'Access Denied',
                            text: 'Invalid access key. Please contact your administrator.',
                            confirmButtonText: 'Try Again'
                        });
                        accessKeyInput.value = '';
                        accessKeyInput.focus();
                    }
                });
            } else {
                console.log('Verify button not found!');
            }

            // Lock access
            if (lockAccessBtn) {
                lockAccessBtn.addEventListener('click', function() {
                    accessGranted = false; // Use global variable
                    securitySection.style.display = 'block';
                    mainContentSection.style.display = 'none';
                    accessKeyInput.value = '';
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Access Locked',
                        text: 'Security verification is required to access the system again.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }

            // Allow Enter key to verify access key
            if (accessKeyInput) {
                accessKeyInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        verifyAccessKeyBtn.click();
                    }
                });
            }

            // Function to load profit/loss data
            function loadProfitLossData() {
                fetch('../private/profit_loss_backend.php?action=get_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProfitLossOrders(data.orders);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load profit & loss data: ' + (data.error || 'Unknown error')
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to connect to the server.'
                    });
                });
            }

            // Function to display orders in profit/loss format
            function displayProfitLossOrders(orders) {
                const profitLossList = document.getElementById('profitLossList');
                
                if (!orders || orders.length === 0) {
                    profitLossList.innerHTML = '<div class="col-12 text-center py-5"><p>No orders found.</p></div>';
                    return;
                }

                let ordersHtml = '';
                orders.forEach(order => {
                    const profit = parseFloat(order.total_profit || 0);
                    const profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                    const profitIcon = profit >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                    
                    ordersHtml += `
                        <div class="col-12 mb-3">
                            <div class="card border-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <h5 class="card-title mb-1">${order.invoice_number || 'N/A'}</h5>
                                            <p class="text-muted mb-0">${order.customer_name || 'Unknown Customer'}</p>
                                            <small class="text-muted">${order.customer_company_name || ''}</small>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Total Revenue:</strong></p>
                                            <span class="badge bg-primary">RM ${formatCurrency(order.total_amount || 0)}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Total Cost:</strong></p>
                                            <span class="badge bg-warning">¥${formatCurrency(order.total_cost_yen || 0)}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Profit/Loss:</strong></p>
                                            <span class="badge bg-${profit >= 0 ? 'success' : 'danger'}">
                                                <i class="${profitIcon}"></i> RM ${formatCurrency(Math.abs(profit))}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-info" onclick="viewProfitDetails('${order.invoice_id}')">
                                                    <i class="ri-eye-line"></i> View Details
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" onclick="managePayments('${order.invoice_id}')">
                                                    <i class="ri-money-dollar-circle-line"></i> Payments
                                                </button>
                                                ${order.status !== 'completed' ? 
                                                    `<button class="btn btn-sm btn-success" onclick="markAsComplete('${order.invoice_id}')">
                                                        <i class="ri-check-line"></i> Complete
                                                    </button>` : 
                                                    `<span class="badge bg-success">Completed</span>`
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                profitLossList.innerHTML = ordersHtml;
            }

            // Helper function to format currency
            function formatCurrency(value) {
                return parseFloat(value || 0).toLocaleString('en-MY', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Helper function to format numbers
            function formatNumber(value) {
                return parseFloat(value || 0).toLocaleString('en-MY', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const orderCards = document.querySelectorAll('#profitLossList .col-12');
                
                orderCards.forEach(card => {
                    const invoiceNumber = card.querySelector('.card-title').textContent.toLowerCase();
                    const customerName = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (invoiceNumber.includes(searchTerm) || customerName.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }); // End of DOMContentLoaded

        // Global functions (outside DOMContentLoaded to be accessible by onclick handlers)
        // Security Configuration
        const ACCESS_KEY = "PROFIT2024";
        let accessGranted = false;

        // Verify Access Function
        function verifyAccess() {
            const key = document.getElementById('accessKey').value;
            
            if (key === ACCESS_KEY) {
                accessGranted = true;
                document.getElementById('securitySection').style.display = 'none';
                document.getElementById('mainContent').style.display = 'block';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Access Granted',
                    text: 'You can now access the profit & loss management system.',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Load data
                loadProfitLossData();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'Invalid access key. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
                document.getElementById('accessKey').value = '';
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility() {
            const accessKeyInput = document.getElementById('accessKey');
            const keyIcon = document.getElementById('keyIcon');
            
            console.log('togglePasswordVisibility called');
            console.log('Current type:', accessKeyInput ? accessKeyInput.type : 'input not found');
            
            if (accessKeyInput && keyIcon) {
                if (accessKeyInput.type === 'password') {
                    accessKeyInput.type = 'text';
                    keyIcon.className = 'ri-eye-off-line';
                    console.log('Password revealed');
                } else {
                    accessKeyInput.type = 'password';
                    keyIcon.className = 'ri-eye-line';
                    console.log('Password hidden');
                }
            }
        }

        // Initialize event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set up payment form listeners
            const supplierForm = document.getElementById('supplierPaymentForm');
            const shippingForm = document.getElementById('shippingPaymentForm');
            
            if (supplierForm) {
                supplierForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const invoiceId = document.getElementById('supplierInvoiceId').value;
                    const amount = document.getElementById('supplierPaymentAmount').value;
                    const description = document.getElementById('supplierPaymentDescription').value;
                    
                    if (!amount || amount <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount'
                        });
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'add_supplier_payment');
                    formData.append('invoice_id', invoiceId);
                    formData.append('amount', amount);
                    formData.append('description', description);
                    
                    submitPayment(formData, 'supplier');
                });
            }
            
            if (shippingForm) {
                shippingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const invoiceId = document.getElementById('shippingInvoiceId').value;
                    const amount = document.getElementById('shippingPaymentAmount').value;
                    const description = document.getElementById('shippingPaymentDescription').value;
                    
                    if (!amount || amount <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount'
                        });
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'add_shipping_payment');
                    formData.append('invoice_id', invoiceId);
                    formData.append('amount', amount);
                    formData.append('description', description);
                    
                    submitPayment(formData, 'shipping');
                });
            }

            // Set up complete confirmation listener
            const confirmCompleteBtn = document.getElementById('confirmComplete');
            if (confirmCompleteBtn) {
                confirmCompleteBtn.addEventListener('click', function() {
                    const invoiceId = document.getElementById('completeInvoiceId').value;
                    
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=mark_complete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Completed!',
                                text: 'The order has been marked as completed.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Close modal and refresh data
                            bootstrap.Modal.getInstance(document.getElementById('markCompleteModal')).hide();
                            loadProfitLossData(currentPage); // Refresh main list
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Failed to mark order as complete'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                });
            }
        });

        function submitPayment(formData, type) {
            const submitButton = document.querySelector(`#${type}PaymentForm button[type="submit"]`);
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="ri-loader-4-line"></i> Processing Payment...';
            submitButton.disabled = true;
            
            // Validate form data
            const amount = formData.get('amount');
            const invoiceId = formData.get('invoice_id');
            
            if (!amount || amount <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please enter a valid payment amount greater than 0',
                    confirmButtonColor: '#dc3545'
                });
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }
            
            if (!invoiceId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Invoice ID is required for payment processing',
                    confirmButtonColor: '#dc3545'
                });
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }
            
            // Add action to form data
            formData.append('action', type === 'supplier' ? 'add_supplier_payment' : 'add_shipping_payment');
            
            console.log('Submitting payment:', {
                type: type,
                action: formData.get('action'),
                invoice_id: formData.get('invoice_id'),
                amount: formData.get('amount'),
                description: formData.get('description')
            });
            
            fetch('../private/profit_loss_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed response:', data);
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Processed',
                            text: `${type.charAt(0).toUpperCase() + type.slice(1)} payment has been recorded successfully`,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#28a745'
                        });
                        
                        // Reset form
                        document.getElementById(`${type}PaymentForm`).reset();
                        document.getElementById(`${type}PaymentImpact`).innerHTML = 'Enter amount to see impact';
                        
                        // Reload payment data
                        const invoiceId = formData.get('invoice_id');
                        loadPaymentTabsData(invoiceId);
                        
                        // Refresh the main table
                        loadProfitLossData();
                    } else {
                        console.error('Payment failed:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: data.message || data.error || 'Failed to process payment',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    console.error('Response was:', text);
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Server returned an invalid response. Please check the console for details.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to server. Please check your connection and try again.',
                    confirmButtonColor: '#dc3545'
                });
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        // Helper functions
        function formatCurrency(amount) {
            // Handle null, undefined, empty string, or NaN values
            if (amount === null || amount === undefined || amount === '' || isNaN(amount)) {
                return '0';
            }
            
            const numValue = parseFloat(amount);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0';
            }
            
            return new Intl.NumberFormat('ja-JP').format(numValue);
        }

        function formatNumber(value) {
            // Handle null, undefined, empty string, or NaN values
            if (value === null || value === undefined || value === '' || isNaN(value)) {
                return '0';
            }
            
            const numValue = parseFloat(value);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0';
            }
            
            return new Intl.NumberFormat().format(numValue);
        }

        let currentPage = 1;
        let currentFilters = {};
        
        function loadProfitLossData(page = 1) {
            currentPage = page;
            const tableBody = document.getElementById('profitLossTableBody');
            
            // Show loading
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="ri-loader-4-line"></i> Loading data...
                        </div>
                    </td>
                </tr>
            `;
            
            // Build query string with filters
            let queryParams = new URLSearchParams();
            queryParams.append('action', 'get_orders');
            queryParams.append('page', page);
            
            // Add filters to query
            Object.keys(currentFilters).forEach(key => {
                if (currentFilters[key]) {
                    queryParams.append(key, currentFilters[key]);
                }
            });
            
            fetch(`../private/profit_loss_backend.php?${queryParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.orders) {
                    displayCleanOrdersTable(data.orders);
                    displayPagination(data.pagination);
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">No orders found</div>
                            </td>
                        </tr>
                    `;
                    clearPagination();
                }
            })
            .catch(error => {
                console.error('Error loading profit/loss data:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-danger">Error loading data</div>
                        </td>
                    </tr>
                `;
                clearPagination();
            });
        }

        function applyFilters() {
            const monthFilter = document.getElementById('monthFilter').value;
            const searchFilter = document.getElementById('searchInput').value;
            const dateFromFilter = document.getElementById('dateFromFilter').value;
            const dateToFilter = document.getElementById('dateToFilter').value;
            
            // Update current filters
            currentFilters = {
                month: monthFilter,
                search: searchFilter,
                date_from: dateFromFilter,
                date_to: dateToFilter
            };
            
            // Reset to first page when applying filters
            currentPage = 1;
            loadProfitLossData(1);
        }

        function clearFilters() {
            // Clear all filter inputs
            document.getElementById('monthFilter').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            
            // Clear current filters
            currentFilters = {};
            
            // Reset to first page
            currentPage = 1;
            loadProfitLossData(1);
        }

        function displayPagination(pagination) {
            if (!pagination) return;
            
            const infoDiv = document.getElementById('paginationInfo');
            const controlsUl = document.getElementById('paginationControls');
            
            // Update info
            const start = ((pagination.current_page - 1) * pagination.limit) + 1;
            const end = Math.min(pagination.current_page * pagination.limit, pagination.total_records);
            infoDiv.innerHTML = `Showing ${start}-${end} of ${pagination.total_records} orders`;
            
            // Update controls
            let paginationHtml = '';
            
            // Previous button
            if (pagination.current_page > 1) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadProfitLossData(${pagination.current_page - 1}); return false;">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-left-line"></i></span>
                    </li>
                `;
            }
            
            // Page numbers
            for (let i = 1; i <= pagination.total_pages; i++) {
                if (i === pagination.current_page) {
                    paginationHtml += `
                        <li class="page-item active">
                            <span class="page-link">${i}</span>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadProfitLossData(${i}); return false;">${i}</a>
                        </li>
                    `;
                }
            }
            
            // Next button
            if (pagination.current_page < pagination.total_pages) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadProfitLossData(${pagination.current_page + 1}); return false;">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-right-line"></i></span>
                    </li>
                `;
            }
            
            controlsUl.innerHTML = paginationHtml;
        }
        
        function clearPagination() {
            document.getElementById('paginationInfo').innerHTML = '';
            document.getElementById('paginationControls').innerHTML = '';
        }

        function displayCleanOrdersTable(orders) {
            const tableBody = document.getElementById('profitLossTableBody');
            
            if (!orders || orders.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">No orders found</div>
                        </td>
                    </tr>
                `;
                return;
            }

            const rows = orders.map(order => {
                // Use the backend calculated values - keep separate like modal
                const supplierCostYen = parseFloat(order.total_supplier_cost_yen || 0);
                const shippingCostRm = parseFloat(order.total_shipping_cost_rm || 0);
                const supplierPaidYen = parseFloat(order.supplier_payments_total || 0);
                const shippingPaidRm = parseFloat(order.shipping_payments_total || 0);
                
                // Calculate remaining amounts separately
                const supplierRemainingYen = Math.max(0, supplierCostYen - supplierPaidYen);
                const shippingRemainingRm = Math.max(0, shippingCostRm - shippingPaidRm);
                
                // Calculate payment status based on both currencies
                let status = 'unpaid';
                let statusText = 'Unpaid';
                const supplierFullyPaid = supplierRemainingYen <= 0 && supplierCostYen > 0;
                const shippingFullyPaid = shippingRemainingRm <= 0 && shippingCostRm > 0;
                const hasSupplierPayment = supplierPaidYen > 0;
                const hasShippingPayment = shippingPaidRm > 0;
                
                if (supplierFullyPaid && shippingFullyPaid) {
                    status = 'paid';
                    statusText = 'Fully Paid';
                } else if (hasSupplierPayment || hasShippingPayment) {
                    status = 'partial';
                    statusText = 'Partially Paid';
                }
                
                return `
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #111827;">${order.order_number || '#' + order.invoice_id}</div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #374151;">${order.customer_name || 'Unknown'}</div>
                            <div style="font-size: 12px; color: #6b7280;">${order.customer_company_name || ''}</div>
                        </td>
                        <td>
                            <div style="color: #374151;">${formatDate(order.order_date)}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: ${order.actual_profit_loss >= 0 ? '#059669' : '#dc2626'};">
                                RM${formatNumber(order.actual_profit_loss)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                ${order.actual_profit_loss >= 0 ? 'Profit' : 'Loss'}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #059669;">
                                ¥${formatNumber(supplierPaidYen)} + RM${formatNumber(shippingPaidRm)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                Supplier + Shipping payments
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: ${(supplierRemainingYen <= 0 && shippingRemainingRm <= 0) ? '#059669' : '#dc2626'};">
                                ¥${formatNumber(supplierRemainingYen)} + RM${formatNumber(shippingRemainingRm)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                Remaining amounts
                            </div>
                        </td>
                        <td>
                            ${order.staff_name ? `
                                <div style="font-weight: 500; color: #374151;">${order.staff_name}</div>
                                <div style="font-size: 12px; color: #059669;">
                                    ${order.commission_percentage}% = RM${formatNumber((order.actual_profit_loss * (order.commission_percentage / 100)))}
                                </div>
                            ` : `
                                <div style="color: #6b7280; font-style: italic;">No commission</div>
                            `}
                        </td>
                        <td>
                            <span class="status-badge status-${status}">${statusText}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-clean btn-clean-sm" onclick="openPaymentModal(${order.invoice_id})" title="Make Payment">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </button>
                                <button class="btn btn-clean-outline btn-clean-sm" onclick="viewProfitDetails(${order.invoice_id})" title="View Details">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button class="btn btn-clean-outline btn-clean-sm" onclick="markAsComplete(${order.invoice_id})" title="Mark Complete">
                                    <i class="ri-check-line"></i>
                                </button>
                                <button class="btn btn-danger btn-clean-sm" onclick="deleteOrder(${order.invoice_id})" title="Delete Order">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = rows;
        }

        // Utility Functions
        function formatNumber(value) {
            // Handle null, undefined, empty string, or NaN values
            if (value === null || value === undefined || value === '' || isNaN(value)) {
                return '0.00';
            }
            
            const numValue = parseFloat(value);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0.00';
            }
            
            // For very small values, use more decimal places to avoid showing 0.00
            if (Math.abs(numValue) > 0 && Math.abs(numValue) < 0.01) {
                return new Intl.NumberFormat('en-MY', {
                    minimumFractionDigits: 4,
                    maximumFractionDigits: 4
                }).format(numValue);
            }
            
            return new Intl.NumberFormat('en-MY', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(numValue);
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString('en-MY');
        }

        // Add openPaymentModal function placeholder
        function openPaymentModal(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }
            
            // Open the payment modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
            
            // Load order data for the payment modal
            loadPaymentTabsData(invoiceId);
        }

        // View Profit Details Function
        function viewProfitDetails(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('profitDetailsModal'));
            modal.show();

            // Load profit details
            fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Raw profit details data:', data); // Debug log
                
                // ENHANCED DEBUGGING - Show actual values received from backend
                if (data.success && data.items && data.items.length > 0) {
                    const item = data.items[0];
                    
                    // Show comprehensive debug info
                    const debugInfo = `DEBUGGING - BACKEND RESPONSE VALUES:
                    
Unit Shipping Cost RM: "${item.unit_shipping_cost_rm}" (type: ${typeof item.unit_shipping_cost_rm})
Total Shipping Cost RM: "${item.total_shipping_cost_rm}" (type: ${typeof item.total_shipping_cost_rm})
Total Cost RM: "${item.total_cost_rm}" (type: ${typeof item.total_cost_rm})
Product ID: "${item.product_id}"

After parseFloat conversion:
- parseFloat(unit_shipping_cost_rm): ${parseFloat(item.unit_shipping_cost_rm || 0)}
- parseFloat(total_cost_rm): ${parseFloat(item.total_cost_rm || 0)}

After formatNumber:
- formatNumber(unit_shipping_cost): ${formatNumber(parseFloat(item.unit_shipping_cost_rm || 0))}
- formatNumber(total_cost): ${formatNumber(parseFloat(item.total_cost_rm || 0))}

This shows the RAW values from backend vs final display values.`;
                    
                    alert(debugInfo);
                }
                
                if (data.success) {
                    // Log individual items for debugging
                    if (data.items && data.items.length > 0) {
                        console.log('First item details:', data.items[0]);
                        console.log('Unit shipping cost from first item:', data.items[0].unit_shipping_cost_rm);
                        console.log('Total cost from first item:', data.items[0].total_cost_rm);
                    }
                    displayProfitDetails(data);
                } else {
                    document.getElementById('profitDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Error: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('profitDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
            });
        }

        // Mark as Complete Function
        function markAsComplete(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            Swal.fire({
                title: 'Mark Order as Complete?',
                text: `Are you sure you want to mark order #${invoiceId} as completed?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, mark as complete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=mark_complete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Completed!',
                                text: 'The order has been marked as completed.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Refresh the table
                            loadProfitLossData(currentPage);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Failed to mark order as complete'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                }
            });
        }

        // Delete Order Function
        function deleteOrder(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Order?',
                text: `Are you sure you want to permanently delete order #${invoiceId}? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=delete_order', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Deleted',
                                text: 'The order has been permanently deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Refresh the table
                            loadProfitLossData(currentPage);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: data.error || 'Failed to delete order'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                }
            });
        }

        // Display Profit Details Function
        function displayProfitDetails(data) {
            const order = data.order;
            const items = data.items || [];
            const summary = data.summary;
            
            let itemsHtml = '';
            items.forEach(item => {
                const itemProfit = parseFloat(item.item_profit || 0);
                const profitClass = itemProfit >= 0 ? 'text-success' : 'text-danger';
                
                // Safely extract and format values
                const quantity = parseFloat(item.quantity || 0);
                const unitPrice = parseFloat(item.unit_price || 0);
                const itemRevenue = parseFloat(item.item_revenue || 0);
                const unitSupplierCost = parseFloat(item.unit_supplier_cost_yen || 0);
                const unitShippingCost = parseFloat(item.unit_shipping_cost_rm || 0);
                const totalCostRm = parseFloat(item.total_cost_rm || 0);
                
                itemsHtml += `
                    <tr>
                        <td>${item.product_name || 'N/A'}</td>
                        <td>${formatNumber(quantity)}</td>
                        <td>RM ${formatNumber(unitPrice)}</td>
                        <td>RM ${formatNumber(itemRevenue)}</td>
                        <td>¥${formatNumber(unitSupplierCost)}</td>
                        <td>RM ${formatNumber(unitShippingCost)}</td>
                        <td>RM ${formatNumber(totalCostRm)}</td>
                        <td class="${profitClass}">RM ${formatNumber(Math.abs(itemProfit))}</td>
                    </tr>
                `;
            });

            const totalProfitClass = (summary?.total_profit || 0) >= 0 ? 'text-success' : 'text-danger';
            
            // Safely extract summary values
            const totalRevenue = parseFloat(summary?.total_revenue || 0);
            const totalSupplierCost = parseFloat(summary?.total_supplier_cost_yen || 0);
            const totalShippingCost = parseFloat(summary?.total_shipping_cost_rm || 0);
            const totalProfit = parseFloat(summary?.total_profit || 0);
            
            const html = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Order Information</h6>
                        <p><strong>Invoice:</strong> #${order.invoice_id}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Unknown'}</p>
                        <p><strong>Company:</strong> ${order.customer_company_name || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="badge bg-${order.status === 'completed' ? 'success' : 'warning'}">${order.status || 'Active'}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Financial Summary</h6>
                        <p><strong>Total Revenue:</strong> RM ${formatNumber(totalRevenue)}</p>
                        <p><strong>Supplier Cost:</strong> ¥${formatNumber(totalSupplierCost)}</p>
                        <p><strong>Shipping Cost:</strong> RM ${formatNumber(totalShippingCost)}</p>
                        <p><strong>Total Profit:</strong> <span class="${totalProfitClass}">RM ${formatNumber(Math.abs(totalProfit))}</span></p>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3">Item-Level Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Revenue</th>
                                <th>Supplier Cost</th>
                                <th>Shipping Cost</th>
                                <th>Total Cost</th>
                                <th>Profit/Loss</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml || '<tr><td colspan="8" class="text-center">No items found</td></tr>'}
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('profitDetailsContent').innerHTML = html;
        }

        function displayProfitLossOrders(orders) {
            const profitLossList = document.getElementById('profitLossList');
            
            if (!orders || orders.length === 0) {
                profitLossList.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>No Orders Found</h5>
                            <p>There are currently no orders to display profit & loss information for.</p>
                        </div>
                    </div>
                `;
                return;
            }

            let ordersHtml = '';
            orders.forEach(order => {
                const profit = parseFloat(order.profit || 0);
                const profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                const profitIcon = profit >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                
                ordersHtml += `
                    <div class="col-md-6 col-lg-4 order-card" data-invoice="${order.invoice_number.toLowerCase()}" data-customer="${order.customer_name.toLowerCase()}">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-1">${order.invoice_number}</h6>
                                        <p class="text-muted mb-0">${order.customer_name}</p>
                                        ${order.customer_company_name ? `<small class="text-muted">${order.customer_company_name}</small>` : ''}
                                    </div>
                                    <span class="badge bg-${order.status === 'completed' ? 'success' : 'warning'}">${order.status}</span>
                                </div>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Revenue:</small><br>
                                        <span class="badge bg-primary">RM ${formatCurrency(order.total_amount || 0)}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Cost:</small><br>
                                        <span class="badge bg-warning">¥${formatCurrency(order.total_cost_yen || 0)}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Profit:</small><br>
                                        <span class="badge bg-${profit >= 0 ? 'success' : 'danger'}">
                                            <i class="${profitIcon}"></i> RM ${formatCurrency(Math.abs(profit))}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-info" onclick="viewProfitDetails('${order.invoice_id}')">
                                        <i class="ri-eye-line"></i> View Details
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="managePayments('${order.invoice_id}')">
                                        <i class="ri-money-dollar-circle-line"></i> Payments
                                    </button>
                                    ${order.status !== 'completed' ? 
                                        `<button class="btn btn-sm btn-success" onclick="markAsComplete('${order.invoice_id}')">
                                            <i class="ri-check-line"></i> Complete
                                        </button>` : 
                                        `<span class="badge bg-success">Completed</span>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            profitLossList.innerHTML = ordersHtml;
        }

        function loadPaymentTabsData(invoiceId) {
            // Set invoice IDs for both forms
            document.getElementById('supplierInvoiceId').value = invoiceId;
            document.getElementById('shippingInvoiceId').value = invoiceId;

            // Load order details and payment history
            Promise.all([
                fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`),
                fetch(`../private/profit_loss_backend.php?action=get_payment_history&invoice_id=${invoiceId}`)
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([orderData, paymentData]) => {
                if (orderData.success && paymentData.success) {
                    displayPaymentTabs(invoiceId, orderData, paymentData);
                    setupPaymentCalculations(orderData);
                } else {
                    document.getElementById('supplierSummary').innerHTML = 
                        '<div class="alert alert-danger">Error loading data</div>';
                    document.getElementById('shippingSummary').innerHTML = 
                        '<div class="alert alert-danger">Error loading data</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('supplierSummary').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
                document.getElementById('shippingSummary').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
            });
        }

        function displayPaymentTabs(invoiceId, orderData, paymentData) {
            const summary = orderData.summary;
            const payments = paymentData.payments || [];
            
            // Calculate due amounts - keep currencies separate like main table
            const supplierDue = summary.total_supplier_cost_yen;
            const shippingDue = summary.total_shipping_cost_rm; // Fixed: use RM for shipping
            const supplierPaid = summary.supplier_payments_made;
            const shippingPaid = summary.shipping_payments_made;
            const supplierBalance = supplierDue - supplierPaid;
            const shippingBalance = shippingDue - shippingPaid;

            // Supplier Summary
            const supplierSummaryHtml = `
                <div class="mb-3">
                    <p class="mb-1"><strong>Total Due:</strong></p>
                    <h5 class="text-primary">¥${formatCurrency(supplierDue)}</h5>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Amount Paid:</strong></p>
                    <h6 class="text-success">¥${formatCurrency(supplierPaid)}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Balance:</strong></p>
                    <h6 class="${supplierBalance > 0 ? 'text-danger' : supplierBalance < 0 ? 'text-warning' : 'text-success'}">
                        ¥${formatCurrency(Math.abs(supplierBalance))}
                        ${supplierBalance > 0 ? '(Outstanding)' : supplierBalance < 0 ? '(Overpaid)' : '(Paid)'}
                    </h6>
                </div>
                <div class="alert alert-light">
                    <small><strong>Note:</strong> Overpayment will reduce profit margin</small>
                </div>
            `;

            // Shipping Summary - use RM currency to match main table
            const shippingSummaryHtml = `
                <div class="mb-3">
                    <p class="mb-1"><strong>Total Due:</strong></p>
                    <h5 class="text-info">RM ${formatNumber(shippingDue)}</h5>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Amount Paid:</strong></p>
                    <h6 class="text-success">RM ${formatNumber(shippingPaid)}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Balance:</strong></p>
                    <h6 class="${shippingBalance > 0 ? 'text-danger' : shippingBalance < 0 ? 'text-warning' : 'text-success'}">
                        RM ${formatNumber(Math.abs(shippingBalance))}
                        ${shippingBalance > 0 ? '(Outstanding)' : shippingBalance < 0 ? '(Overpaid)' : '(Paid)'}
                    </h6>
                </div>
                <div class="alert alert-light">
                    <small><strong>Note:</strong> Underpayment will increase profit margin</small>
                </div>
            `;

            document.getElementById('supplierSummary').innerHTML = supplierSummaryHtml;
            document.getElementById('shippingSummary').innerHTML = shippingSummaryHtml;

            // Payment History
            const supplierPayments = payments.filter(p => p.type === 'supplier');
            const shippingPayments = payments.filter(p => p.type === 'shipping');

            let supplierHistoryHtml = '';
            supplierPayments.forEach(payment => {
                supplierHistoryHtml += `
                    <tr>
                        <td>${payment.date}</td>
                        <td>¥${formatCurrency(payment.amount)}</td>
                        <td>${payment.description || 'N/A'}</td>
                    </tr>
                `;
            });

            let shippingHistoryHtml = '';
            shippingPayments.forEach(payment => {
                shippingHistoryHtml += `
                    <tr>
                        <td>${payment.date}</td>
                        <td>¥${formatCurrency(payment.amount)}</td>
                        <td>${payment.description || 'N/A'}</td>
                    </tr>
                `;
            });

            document.getElementById('supplierPaymentHistory').innerHTML = 
                supplierHistoryHtml || '<tr><td colspan="3" class="text-center">No payments recorded</td></tr>';
            document.getElementById('shippingPaymentHistory').innerHTML = 
                shippingHistoryHtml || '<tr><td colspan="3" class="text-center">No payments recorded</td></tr>';
        }

        function setupPaymentCalculations(orderData) {
            const summary = orderData.summary;
            
            // Supplier payment calculation
            const supplierAmountInput = document.getElementById('supplierPaymentAmount');
            const supplierImpactDiv = document.getElementById('supplierPaymentImpact');
            
            if (supplierAmountInput && supplierImpactDiv) {
                supplierAmountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value) || 0;
                    const due = summary.total_supplier_cost_yen;
                    const paid = summary.supplier_payments_made;
                    const newTotal = paid + amount;
                    const difference = newTotal - due;
                    
                    let impactHtml = '';
                    if (amount === 0) {
                        impactHtml = 'Enter amount to see impact';
                    } else if (difference > 0) {
                        impactHtml = `<span class="text-warning">⚠️ Overpayment by ¥${formatCurrency(difference)}<br>This will reduce profit margin</span>`;
                    } else if (difference < 0) {
                        impactHtml = `<span class="text-info">ℹ️ Partial payment: ¥${formatCurrency(Math.abs(difference))} remaining<br>Remaining balance will maintain profit</span>`;
                    } else {
                        impactHtml = `<span class="text-success">✅ Full payment: Exact amount due</span>`;
                    }
                    
                    supplierImpactDiv.innerHTML = impactHtml;
                });
            }

            // Shipping payment calculation - use RM currency to match main table
            const shippingAmountInput = document.getElementById('shippingPaymentAmount');
            const shippingImpactDiv = document.getElementById('shippingPaymentImpact');
            
            if (shippingAmountInput && shippingImpactDiv) {
                shippingAmountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value) || 0;
                    const due = summary.total_shipping_cost_rm; // Fixed: use RM field
                    const paid = summary.shipping_payments_made;
                    const newTotal = paid + amount;
                    const difference = newTotal - due;
                    
                    let impactHtml = '';
                    if (amount === 0) {
                        impactHtml = 'Enter amount to see impact';
                    } else if (difference > 0) {
                        impactHtml = `<span class="text-warning">⚠️ Overpayment by RM ${formatNumber(difference)}<br>This will reduce profit margin</span>`;
                    } else if (difference < 0) {
                        impactHtml = `<span class="text-info">ℹ️ Partial payment: RM ${formatNumber(Math.abs(difference))} remaining<br>Remaining balance will maintain profit</span>`;
                    } else {
                        impactHtml = `<span class="text-success">✅ Full payment: Exact amount due</span>`;
                    }
                    
                    shippingImpactDiv.innerHTML = impactHtml;
                });
            }
        }

        // Global functions for button actions
        function viewProfitDetails(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('profitDetailsModal'));
            modal.show();

            // Load profit details
            fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayProfitDetails(data);
                } else {
                    document.getElementById('profitDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Error: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('profitDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
            });
        }

        function managePayments(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();

            // Load payment data for both tabs
            loadPaymentTabsData(invoiceId);
        }

        function markAsComplete(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            document.getElementById('completeInvoiceId').value = invoiceId;
            
            // Load order summary for confirmation
            fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const summary = data.summary;
                    const totalProfitClass = summary.total_profit >= 0 ? 'text-success' : 'text-danger';
                    
                    const summaryHtml = `
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Final Profit & Loss Summary</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Total Revenue:</strong> RM ${formatCurrency(summary.total_revenue)}</p>
                                        <p><strong>Total Cost:</strong> ¥${formatCurrency(summary.total_cost_yen)}</p>
                                        <p><strong>Final Profit:</strong> <span class="${totalProfitClass}">RM ${formatCurrency(Math.abs(summary.total_profit))}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Supplier Payments:</strong> ¥${formatCurrency(summary.supplier_payments_made)}</p>
                                        <p><strong>Shipping Payments:</strong> ¥${formatCurrency(summary.shipping_payments_made)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('completionSummary').innerHTML = summaryHtml;
                    
                    const modal = new bootstrap.Modal(document.getElementById('markCompleteModal'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load order summary'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to connect to server'
                });
            });
        }

        function formatCurrency(amount) {
            // Handle null, undefined, empty string, or NaN values
            if (amount === null || amount === undefined || amount === '' || isNaN(amount)) {
                return '0';
            }
            
            const numValue = parseFloat(amount);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0';
            }
            
            return new Intl.NumberFormat('ja-JP').format(numValue);
        }

        // Filter Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        applyFilters();
                    }, 500); // Debounce search for 500ms
                });
                
                // Enter key support for search
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        applyFilters();
                    }
                });
            }
            
            // Month filter auto-apply
            const monthFilter = document.getElementById('monthFilter');
            if (monthFilter) {
                monthFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            // Date range filters auto-apply
            const dateFromFilter = document.getElementById('dateFromFilter');
            const dateToFilter = document.getElementById('dateToFilter');
            
            if (dateFromFilter) {
                dateFromFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            if (dateToFilter) {
                dateToFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
        });
    </script>

<!-- Profit Details Modal -->
<div class="modal fade" id="profitDetailsModal" tabindex="-1" aria-labelledby="profitDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="profitDetailsModalLabel">
                    <i class="fas fa-chart-line me-2"></i>Profit/Loss Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="profitDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-clean-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>