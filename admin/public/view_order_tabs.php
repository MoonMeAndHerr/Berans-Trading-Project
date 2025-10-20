
        <?php 
            include __DIR__ . '/../include/header.php';
            require_once __DIR__ . '/../private/view-order-tabs-backend.php';
            $orders = getOrderTabs();
        ?>
        <!-- Minimal CSS for Order Tabs -->
        <link href="assets/css/order-tabs-minimal.css" rel="stylesheet" type="text/css" />


        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Order Management</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Orders</a></li>
                                        <li class="breadcrumb-item active">Management</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Pagination logic
                    $items_per_page = 8;
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $total_items = count($orders);
                    $total_pages = ceil($total_items / $items_per_page);
                    $offset = ($current_page - 1) * $items_per_page;
                    $displayed_orders = array_slice($orders, $offset, $items_per_page);
                    ?>

                    <!-- Order Container -->
                    <div class="order-container">
                        <!-- Header Section -->
                        <div class="order-header-section">
                            <div class="search-compact">
                                <input type="text" id="searchInput" placeholder="Search orders by number, customer, or status...">
                                <i class="ri-search-line search-icon"></i>
                                <button type="button" class="search-clear" id="clearSearch" style="display: none;">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                            <div class="filter-compact">
                                <select id="statusFilter" class="filter-select">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Order List -->
                        <div class="order-list" id="ordersList">
                            <?php foreach($displayed_orders as $order): ?>
                            <div class="order-item <?= $order['status'] === 'completed' ? 'completed' : '' ?>" data-order-id="<?= $order['invoice_id'] ?>">
                                <div class="order-row">
                                    <div class="order-status-indicator">
                                        <div class="order-status-dot <?= $order['status'] === 'completed' ? 'completed' : 'pending' ?>"></div>
                                    </div>
                                    
                                    <div class="order-info-main">
                                        <div class="order-number">
                                            Order #<?= htmlspecialchars($order['invoice_number']) ?>
                                            <span class="order-badge"><?= ucfirst($order['status']) ?></span>
                                        </div>
                                        <div class="order-customer">
                                            <i class="ri-building-line" style="font-size: 0.75rem;"></i>
                                            <?= htmlspecialchars($order['customer_company_name'] ?? $order['customer_name']) ?>
                                            <?php if(!empty($order['customer_phone'])): ?>
                                            <span class="customer-phone"><?= htmlspecialchars($order['customer_phone']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="order-amounts">
                                        <div class="amount-row">
                                            <span class="amount-label">Total:</span>
                                            <span class="amount-total">RM <?= number_format($order['original_total'], 2) ?></span>
                                        </div>
                                        <?php if(isset($order['total_paid']) && $order['total_paid'] > 0): ?>
                                        <div class="amount-row">
                                            <span class="amount-label">Paid:</span>
                                            <span class="amount-paid">RM <?= number_format($order['total_paid'], 2) ?></span>
                                        </div>
                                        <div class="amount-row">
                                            <span class="amount-label">Balance:</span>
                                            <span class="amount-remaining">RM <?= number_format($order['original_total'] - $order['total_paid'], 2) ?></span>
                                        </div>
                                        <?php else: ?>
                                        <div class="amount-row">
                                            <span class="amount-label">Status:</span>
                                            <span class="amount-remaining">Unpaid</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="order-actions-compact">
                                        <?php if ($order['has_payment'] && $order['max_lead_time']): ?>
                                        <div class="eta-indicator">
                                            <i class="ri-time-line"></i>
                                            ETA: <?= $order['max_lead_time'] ?>d
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="action-buttons">
                                            <button class="btn-compact btn-compact-primary" onclick="toggleOrderDetails(<?= $order['invoice_id'] ?>)">
                                                <i class="ri-eye-line"></i>
                                                Details
                                            </button>
                                            
                                            <button class="btn-compact btn-compact-secondary" onclick="loadOrderList(<?= $order['invoice_id'] ?>)" data-bs-toggle="modal" data-bs-target="#orderListModal">
                                                <i class="ri-list-check"></i>
                                                Items
                                            </button>
                                            
                                            <button class="btn-compact btn-success" onclick="preparePayment(<?= $order['invoice_id'] ?>, <?= $order['original_total'] ?>)" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                                <i class="ri-money-dollar-circle-line"></i>
                                                Payment
                                            </button>
                                            
                                            <button class="btn-compact btn-info" onclick="debugInvoice(<?= $order['invoice_id'] ?>)" title="Debug Invoice">
                                                <i class="ri-bug-line"></i>
                                                Debug
                                            </button>
                                            
                                            <button class="btn-compact <?= $order['status'] === 'completed' ? 'btn-warning' : 'btn-success' ?>" onclick="toggleOrderStatus(<?= $order['invoice_id'] ?>, '<?= $order['status'] ?>')">
                                                <i class="<?= $order['status'] === 'completed' ? 'ri-arrow-go-back-line' : 'ri-check-line' ?>"></i>
                                                <?= $order['status'] === 'completed' ? 'Reopen' : 'Complete' ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Expandable Details -->
                                <div class="order-details">
                                    <div class="details-grid">
                                        <div class="detail-item">
                                            <div class="detail-label">Customer Contact</div>
                                            <div class="detail-value">
                                                <?= htmlspecialchars($order['customer_name']) ?>
                                                <?php if($order['customer_phone']): ?>
                                                <br><small style="color: var(--order-text-muted);"><?= htmlspecialchars($order['customer_phone']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($order['has_payment'] && $order['max_lead_time']): ?>
                                        <div class="detail-item">
                                            <div class="detail-label">Delivery Timeline</div>
                                            <div class="detail-value">
                                                <?= date('d M Y', strtotime($order['estimated_completion_date'])) ?>
                                                <div class="detail-breakdown">
                                                    <span class="breakdown-item">Production: <?= $order['max_production_lead_time'] ?? 0 ?>d</span>
                                                    <span class="breakdown-item">Delivery: <?= $order['delivery_days'] ?? 0 ?>d</span>
                                                    <?php if ($order['shipping_method_name']): ?>
                                                    <span class="breakdown-item"><?= htmlspecialchars($order['shipping_method_name']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="detail-item">
                                            <div class="detail-label">Quick Actions</div>
                                            <div class="detail-value">
                                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                    <button class="btn-compact btn-outline" onclick='confirmEditOrder(<?= $order['invoice_id'] ?>, {
                                                        customerPayment: <?= isset($order['total_paid']) && $order['total_paid'] > 0 ? $order['total_paid'] : 0 ?>,
                                                        supplierPayment: <?= isset($order['supplier_payments_total']) && $order['supplier_payments_total'] > 0 ? $order['supplier_payments_total'] : 0 ?>,
                                                        shippingPayment: <?= isset($order['shipping_payments_total']) && $order['shipping_payments_total'] > 0 ? $order['shipping_payments_total'] : 0 ?>,
                                                        commissionPayment: <?= isset($order['commission_paid_amount']) && $order['commission_paid_amount'] > 0 ? $order['commission_paid_amount'] : 0 ?>,
                                                        conversionRate: <?= isset($order['conversion_rate']) && $order['conversion_rate'] > 0 ? $order['conversion_rate'] : 0.032 ?>
                                                    })'>
                                                        <i class="ri-edit-line"></i> Edit Order
                                                    </button>
                                                    <button class="btn-compact btn-outline" onclick="loadCartonDetails(<?= $order['invoice_id'] ?>)" data-bs-toggle="modal" data-bs-target="#cartonDetailModal">
                                                        <i class="ri-archive-line"></i> Carton Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-minimal">
                            <?php if($current_page > 1): ?>
                            <a href="?page=<?= $current_page - 1 ?>" class="page-link">
                                <i class="ri-arrow-left-line"></i> Previous
                            </a>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <div class="page-item <?= ($current_page == $i) ? 'active' : '' ?>">
                                <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                            </div>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                            <a href="?page=<?= $current_page + 1 ?>" class="page-link">
                                Next <i class="ri-arrow-right-line"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                <!-- Modals -->
                <!-- Order List Modal -->
                <div class="modal fade" id="orderListModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="ri-list-check"></i>Order Items
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table-minimal">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product</th>
                                            <th class="text-end">Price/Unit</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carton Detail Modal -->
                <div class="modal fade" id="cartonDetailModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="ri-archive-line"></i>Carton Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table-minimal">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Dimensions</th>
                                            <th class="text-end">Weight</th>
                                            <th class="text-center">PCS/Carton</th>
                                            <th class="text-end">CBM/Carton</th>
                                            <th class="text-end">Carton Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light border-0 py-4">
                                <div>
                                    <h5 class="modal-title fw-bold text-dark mb-1">
                                        <i class="ri-money-dollar-circle-line me-2"></i>Customer Payment
                                    </h5>
                                    <p class="text-muted small mb-0">Record customer payment for this invoice</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <!-- Left Column: Payment Summary & Form -->
                                    <div class="col-lg-6">
                                        <!-- Payment Summary -->
                                        <div class="bg-light rounded-3 p-4 mb-4">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="ri-calculator-line me-2"></i>Payment Summary
                                            </h6>
                                            
                                            <!-- Hidden field to store invoice ID -->
                                            <input type="hidden" id="hiddenInvoiceId" value="">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-medium text-dark small mb-1">Total Invoice Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-muted">RM</span>
                                                    <input type="text" class="form-control bg-light border-start-0 ps-0 fw-bold" id="totalAmount" readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-medium text-dark small mb-1">Already Paid</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0 text-success">RM</span>
                                                    <input type="text" class="form-control bg-light border-start-0 ps-0 text-success fw-bold" id="alreadyPaid" readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="form-label fw-medium text-dark small mb-1">Outstanding Balance</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-warning bg-opacity-25 border-warning text-warning">RM</span>
                                                    <input type="text" class="form-control border-warning bg-warning bg-opacity-10 fw-bold text-warning" id="outstandingBalance" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Payment Form -->
                                        <div class="border rounded-3 p-4">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="ri-bank-card-line me-2"></i>Make Payment
                                            </h6>
                                            <form id="paymentForm">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-dark">Amount to Pay Now</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">RM</span>
                                                        <input type="number" class="form-control border-start-0 ps-0" id="amountPaid" 
                                                               step="0.01" placeholder="0.00" required>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="ri-information-line me-1"></i>Enter the amount customer is paying now
                                                    </small>
                                                    <small class="text-info d-block mt-1">
                                                        <i class="ri-subtract-line me-1"></i><strong>Tip:</strong> Use negative value (e.g., -2000) to reverse/deduct a payment
                                                    </small>
                                                </div>
                                                
                                                <div class="border rounded p-2 mb-3" style="background-color: transparent;">
                                                    <div class="d-flex align-items-start">
                                                        <i class="ri-information-line text-muted me-2 mt-1"></i>
                                                        <div class="flex-grow-1">
                                                            <small class="fw-medium d-block mb-1">Remaining Balance After Payment</small>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text bg-transparent border-0 text-muted ps-0">RM</span>
                                                                <input type="text" class="form-control bg-transparent border-0 fw-bold" id="remainingAmount" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-dark w-100 py-2">
                                                    <i class="ri-secure-payment-line me-2"></i>Process Payment
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Column: Payment History -->
                                    <div class="col-lg-6">
                                        <div class="h-100">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="ri-history-line me-2"></i>Payment History
                                            </h6>
                                            <div class="bg-light rounded-3 p-3 payment-history-scroll" style="max-height: 500px; overflow-y: auto;">
                                                <div id="paymentHistorySection">
                                                    <div class="text-center text-muted py-5">
                                                        <div class="spinner-border spinner-border-sm" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <div class="mt-2 small">Loading payment history...</div>
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

                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->
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
    <!-- SweetAlert2 JS -->
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment calculation
            const paymentForm = document.getElementById('paymentForm');
            const totalAmountInput = document.getElementById('totalAmount');
            const amountPaidInput = document.getElementById('amountPaid');
            const remainingAmountInput = document.getElementById('remainingAmount');
            let currentInvoiceId = null;

            // Update remaining amount when paid amount changes
            amountPaidInput.addEventListener('input', function() {
                const outstandingBalance = parseFloat(document.getElementById('outstandingBalance').value) || 0;
                const paidAmount = parseFloat(this.value) || 0;
                const remainingAmount = outstandingBalance - paidAmount;
                remainingAmountInput.value = remainingAmount.toFixed(2);
                
                const remainingContainer = remainingAmountInput.closest('.border');
                
                // Check if it's a negative payment (deduction)
                if (paidAmount < 0) {
                    // Deduction - show in orange/warning color
                    remainingAmountInput.classList.add('text-warning', 'fw-bold');
                    remainingAmountInput.classList.remove('text-danger');
                    if (remainingContainer) {
                        remainingContainer.classList.add('border-warning');
                        remainingContainer.classList.remove('border-danger');
                        remainingContainer.style.backgroundColor = 'rgba(245, 158, 11, 0.05)';
                    }
                }
                // Check if overpayment (positive amount causing negative balance)
                else if (remainingAmount < -0.01) {
                    // Overpayment - show in red/danger color
                    remainingAmountInput.classList.add('text-danger', 'fw-bold');
                    remainingAmountInput.classList.remove('text-warning');
                    if (remainingContainer) {
                        remainingContainer.classList.add('border-danger');
                        remainingContainer.classList.remove('border-warning');
                        remainingContainer.style.backgroundColor = 'rgba(220, 53, 69, 0.05)';
                    }
                }
                else {
                    // Normal payment - remove special styling
                    remainingAmountInput.classList.remove('text-danger', 'text-warning', 'fw-bold');
                    if (remainingContainer) {
                        remainingContainer.classList.remove('border-danger', 'border-warning');
                        remainingContainer.style.backgroundColor = 'transparent';
                    }
                }
            });

            // Handle payment form submission
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const paidAmount = parseFloat(amountPaidInput.value);
                const totalAmount = parseFloat(totalAmountInput.value);
                const alreadyPaid = parseFloat(document.getElementById('alreadyPaid').value) || 0;
                const outstandingBalance = parseFloat(document.getElementById('outstandingBalance').value) || 0;
                const remainingAmount = outstandingBalance - paidAmount;

                console.log('Form submitted - Amount:', paidAmount, 'Total:', totalAmount, 'Already Paid:', alreadyPaid);

                if (!paidAmount || isNaN(paidAmount) || paidAmount === 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please enter a valid payment amount (cannot be zero)',
                        icon: 'error'
                    });
                    return;
                }

                // Check for negative payment (deduction/reversal)
                if (paidAmount < 0) {
                    const deductionAmount = Math.abs(paidAmount);
                    
                    // Validate that deduction doesn't exceed already paid amount
                    if (deductionAmount > alreadyPaid) {
                        Swal.fire({
                            title: 'Invalid Deduction!',
                            html: `
                                <div class="text-start">
                                    <p class="mb-3"><i class="ri-error-warning-line text-danger me-2"></i>Cannot deduct more than what has been paid.</p>
                                    <hr>
                                    <p class="mb-2"><strong>Already Paid:</strong> RM ${alreadyPaid.toFixed(2)}</p>
                                    <p class="mb-2"><strong>Deduction Attempted:</strong> <span class="text-danger">RM ${deductionAmount.toFixed(2)}</span></p>
                                    <p class="mb-3"><strong>Maximum Deduction Allowed:</strong> <span class="text-success">RM ${alreadyPaid.toFixed(2)}</span></p>
                                    <hr>
                                    <p class="text-muted small mb-0"><i class="ri-information-line me-1"></i>You can only reverse payments that have been made.</p>
                                </div>
                            `,
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Payment Deduction/Reversal',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><i class="ri-error-warning-line text-warning me-2"></i>You are about to <strong>deduct/reverse</strong> a payment:</p>
                                <hr>
                                <p class="mb-2"><strong>Already Paid:</strong> RM ${alreadyPaid.toFixed(2)}</p>
                                <p class="mb-2"><strong>Deduction Amount:</strong> <span class="text-danger">RM ${deductionAmount.toFixed(2)}</span></p>
                                <p class="mb-2"><strong>New Paid Amount:</strong> <span class="text-success">RM ${(alreadyPaid - deductionAmount).toFixed(2)}</span></p>
                                <p class="mb-3"><strong>New Outstanding:</strong> <span class="text-warning">RM ${remainingAmount.toFixed(2)}</span></p>
                                <hr>
                                <p class="text-muted small mb-0"><i class="ri-information-line me-1"></i>This will increase the outstanding balance by RM ${deductionAmount.toFixed(2)}</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Deduct Payment',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#f59e0b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processPayment();
                        }
                    });
                    return;
                }

                // Check for overpayment (positive amount exceeding balance)
                if (remainingAmount < -0.01) {
                    const overpayment = Math.abs(remainingAmount);
                    Swal.fire({
                        title: 'Overpayment Detected!',
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>Outstanding Balance:</strong> RM ${outstandingBalance.toFixed(2)}</p>
                                <p class="mb-2"><strong>Amount Entered:</strong> RM ${paidAmount.toFixed(2)}</p>
                                <p class="mb-3"><strong class="text-danger">Overpayment:</strong> <span class="text-danger">RM ${overpayment.toFixed(2)}</span></p>
                                <p class="text-muted small">This will result in a negative balance of RM ${remainingAmount.toFixed(2)}</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Proceed Anyway',
                        cancelButtonText: 'Cancel & Fix',
                        confirmButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processPayment();
                        }
                    });
                    return;
                }

                // Show normal confirmation dialog
                Swal.fire({
                    title: 'Confirm Payment',
                    text: `Are you sure customer has made payment of RM ${paidAmount.toFixed(2)}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processPayment();
                    }
                });
            });

            // Extract payment processing logic into a function
            function processPayment() {
                        // Add validation for currentInvoiceId with fallback to hidden field
                        let invoiceIdToUse = currentInvoiceId;
                        
                        // If currentInvoiceId is null, try to get it from the hidden field
                        if (!invoiceIdToUse) {
                            invoiceIdToUse = document.getElementById('hiddenInvoiceId').value;
                            console.log('currentInvoiceId was null, using hiddenInvoiceId:', invoiceIdToUse);
                        }
                        
                        if (!invoiceIdToUse) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Invoice ID is missing. Please close this modal and try clicking the Payment button again.',
                                icon: 'error'
                            });
                            return;
                        }
                        
                        console.log('Submitting payment for invoice:', invoiceIdToUse, 'amount:', amountPaidInput.value);
                        
                        // Show loading indicator
                        Swal.fire({
                            title: 'Processing Payment...',
                            html: 'Please wait while we record your payment.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        const formData = new FormData();
                        formData.append('invoice_id', invoiceIdToUse);
                        formData.append('amount_paid', amountPaidInput.value);
                        formData.append('is_first_payment', totalAmount === parseFloat(amountPaidInput.value));
                        
                        // Debug: log FormData contents
                        for (let [key, value] of formData.entries()) {
                            console.log('FormData:', key, '=', value);
                        }
                        
                        fetch('../private/view-order-tabs-backend.php?action=submit_payment', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => {
                            // Log the raw response for debugging
                            console.log('Raw response status:', res.status);
                            console.log('Raw response headers:', res.headers);
                            
                            // Clone response to read as text first for debugging
                            return res.clone().text().then(text => {
                                console.log('Raw response text:', text);
                                
                                // Check if the response is valid JSON
                                try {
                                    const data = JSON.parse(text);
                                    return data;
                                } catch (jsonError) {
                                    console.error('JSON Parse Error:', jsonError);
                                    console.error('Response text that failed to parse:', text);
                                    throw new Error('Server returned invalid JSON response. Check console for details.');
                                }
                            });
                        })
                        .then(data => {
                            console.log('Parsed response data:', data);
                            
                            if(data.success) {
                                Swal.fire({
                                    title: 'Payment Successful!',
                                    text: data.message || 'The payment has been recorded successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    timer: 2000
                                }).then(() => {
                                    // Reload the payment history in the modal
                                    const invoiceId = currentInvoiceId || document.getElementById('hiddenInvoiceId').value;
                                    if (invoiceId) {
                                        loadPaymentHistory(invoiceId);
                                        // Also update the preparePayment to refresh the summary
                                        const totalAmount = parseFloat(document.getElementById('totalAmount').value);
                                        if (totalAmount) {
                                            preparePayment(invoiceId, totalAmount);
                                        }
                                    }
                                    // Reload the page to update the main list
                                    setTimeout(() => window.location.reload(), 500);
                                });
                            } else {
                                throw new Error(data.error || 'Failed to process payment');
                            }
                        })
                        .catch(error => {
                            console.error('Payment submission error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error processing payment: ' + error.message,
                                icon: 'error',
                                footer: 'Check the browser console for more details.'
                            });
                        });
            }

            // Enhanced search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const clearSearchBtn = document.getElementById('clearSearch');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    // Show/hide clear button
                    if (clearSearchBtn) {
                        clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
                    }
                    
                    filterOrders();
                });
                
                // Clear search functionality
                if (clearSearchBtn) {
                    clearSearchBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        clearSearchBtn.style.display = 'none';
                        filterOrders();
                        searchInput.focus();
                    });
                }
            }
            
            if (statusFilter) {
                statusFilter.addEventListener('change', filterOrders);
            }
            
            function filterOrders() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : '';
                const orderItems = document.querySelectorAll('.order-item');
                let visibleCount = 0;
                
                orderItems.forEach(item => {
                    const orderNumber = item.querySelector('.order-number')?.textContent.toLowerCase() || '';
                    const customerName = item.querySelector('.order-customer')?.textContent.toLowerCase() || '';
                    const statusBadge = item.querySelector('.order-badge')?.textContent.toLowerCase() || '';
                    
                    const matchesSearch = !searchTerm || 
                        orderNumber.includes(searchTerm) || 
                        customerName.includes(searchTerm) ||
                        statusBadge.includes(searchTerm);
                    
                    const matchesStatus = !selectedStatus || statusBadge.includes(selectedStatus);
                    
                    const shouldShow = matchesSearch && matchesStatus;
                    item.style.display = shouldShow ? '' : 'none';
                    
                    if (shouldShow) visibleCount++;
                });
                
                // Update any results summary if present
                console.log(`Showing ${visibleCount} of ${orderItems.length} orders`);
            }
        });

        // Toggle order details
        function toggleOrderDetails(orderId) {
            const orderItem = document.querySelector(`[data-order-id="${orderId}"]`);
            if (orderItem) {
                orderItem.classList.toggle('expanded');
                
                // Update button text and icon
                const detailsBtn = orderItem.querySelector('.btn-compact-primary');
                if (detailsBtn) {
                    const isExpanded = orderItem.classList.contains('expanded');
                    const icon = detailsBtn.querySelector('i');
                    const text = detailsBtn.querySelector('span') || detailsBtn.childNodes[detailsBtn.childNodes.length - 1];
                    
                    if (isExpanded) {
                        icon.className = 'ri-eye-off-line';
                        if (text.textContent) text.textContent = ' Hide';
                    } else {
                        icon.className = 'ri-eye-line';
                        if (text.textContent) text.textContent = ' Details';
                    }
                }
            }
        }

        // Load order list with minimal loading
        window.loadOrderList = function(invoiceId) {
            const tbody = document.querySelector('#orderListModal tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="loading-minimal">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <span>Loading...</span>
                    </td>
                </tr>
            `;

            fetch(`../private/view-order-tabs-backend.php?action=get_order_items&invoice_id=${invoiceId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.items) {
                        tbody.innerHTML = data.items.map((item, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${htmlEscape(item.product_name)}</td>
                                <td class="text-end">RM ${formatNumber(item.unit_price)}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td class="text-end">RM ${formatNumber(item.total_price)}</td>
                            </tr>
                        `).join('');

                        const total = data.items.reduce((sum, item) => sum + parseFloat(item.total_price), 0);
                        tbody.innerHTML += `
                            <tr style="background: var(--order-bg-tertiary); font-weight: 600;">
                                <td colspan="4" class="text-end">Total</td>
                                <td class="text-end">RM ${formatNumber(total)}</td>
                            </tr>
                        `;
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="color: var(--order-text-muted); padding: 2rem;">No items found</td></tr>';
                    }
                })
                .catch(error => {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="color: var(--order-danger); padding: 2rem;">Error loading items</td></tr>';
                });
        };

        // Load carton details
        window.loadCartonDetails = function(invoiceId) {
            const tbody = document.querySelector('#cartonDetailModal tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="loading-minimal">
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <div class="loading-dot"></div>
                        <span>Loading...</span>
                    </td>
                </tr>
            `;

            fetch(`../private/view-order-tabs-backend.php?action=get_carton_details&invoice_id=${invoiceId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.details && data.details.length > 0) {
                        const rowsData = data.details.map(detail => {
                            const totalCBMCarton = parseFloat(detail.cbm_carton || 0) +
                                parseFloat(detail.add_carton1_total_cbm || 0) +
                                parseFloat(detail.add_carton2_total_cbm || 0) +
                                parseFloat(detail.add_carton3_total_cbm || 0) +
                                parseFloat(detail.add_carton4_total_cbm || 0) +
                                parseFloat(detail.add_carton5_total_cbm || 0) +
                                parseFloat(detail.add_carton6_total_cbm || 0);
                            
                            // Calculate Carton Quantity: QTY / PCS/CARTON, minimum 1
                            const orderedQty = parseFloat(detail.quantity || 1);
                            const pcsPerCarton = parseFloat(detail.pcs_per_carton || 1);
                            let cartonQuantity = pcsPerCarton > 0 ? orderedQty / pcsPerCarton : 0;
                            cartonQuantity = cartonQuantity < 1 ? 1 : Math.ceil(cartonQuantity);

                            return {
                                html: `
                                <tr>
                                    <td>
                                        <strong>${htmlEscape(detail.product_name)}</strong>
                                        <br><small style="color: var(--order-text-muted);">${htmlEscape(detail.product_code)}</small>
                                    </td>
                                    <td class="text-center">
                                        <small style="background: var(--order-bg-tertiary); padding: 0.125rem 0.375rem; border-radius: var(--order-radius); font-family: monospace;">
                                            ${detail.carton_width} × ${detail.carton_height} × ${detail.carton_length}
                                        </small>
                                    </td>
                                    <td class="text-end">${formatNumber3Decimal(detail.carton_weight)} kg</td>
                                    <td class="text-center">
                                        <span style="background: var(--order-accent-light); color: var(--order-accent); padding: 0.125rem 0.375rem; border-radius: var(--order-radius); font-weight: 500;">
                                            ${detail.pcs_per_carton}
                                        </span>
                                    </td>
                                    <td class="text-end">${formatNumber3Decimal(totalCBMCarton)}</td>
                                    <td class="text-end">${formatNumber3Decimal(cartonQuantity)}</td>
                                </tr>
                                `,
                                totalCBM: totalCBMCarton * cartonQuantity
                            };
                        });

                        // Calculate total CBM
                        const totalCBM = rowsData.reduce((sum, row) => sum + row.totalCBM, 0);
                        
                        // Generate HTML with total row
                        const rowsHTML = rowsData.map(row => row.html).join('');
                        const totalRowHTML = `
                            <tr style="background: var(--order-bg-tertiary); font-weight: 600; border-top: 2px solid var(--order-border);">
                                <td colspan="5" style="text-align: right; padding: 1rem;"><strong>Total CBM:</strong></td>
                                <td class="text-end" style="padding: 1rem;"><strong>${formatNumber3Decimal(totalCBM)}</strong></td>
                            </tr>
                        `;
                        
                        tbody.innerHTML = rowsHTML + totalRowHTML;
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="color: var(--order-text-muted); padding: 2rem;">No carton details found</td></tr>';
                    }
                })
                .catch(error => {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="color: var(--order-danger); padding: 2rem;">Error loading details</td></tr>';
                });
        };

        // Prepare payment modal
        window.preparePayment = function(invoiceId, totalAmount) {
            console.log('preparePayment called with:', { invoiceId, totalAmount });
            
            // Validate inputs
            if (!invoiceId) {
                console.error('preparePayment: invoiceId is missing!');
                Swal.fire({
                    title: 'Error',
                    text: 'Invoice ID is missing. Cannot prepare payment.',
                    icon: 'error'
                });
                return;
            }
            
            if (!totalAmount || totalAmount <= 0) {
                console.error('preparePayment: totalAmount is invalid!', totalAmount);
                Swal.fire({
                    title: 'Error',
                    text: 'Total amount is invalid. Cannot prepare payment.',
                    icon: 'error'
                });
                return;
            }
            
            currentInvoiceId = invoiceId;
            
            // Get the order item to find total_paid
            const orderItem = document.querySelector(`[data-order-id="${invoiceId}"]`);
            let totalPaid = 0;
            
            if (orderItem) {
                // Try to find the "Paid:" amount from the order amounts section
                const amountRows = orderItem.querySelectorAll('.amount-row');
                amountRows.forEach(row => {
                    const label = row.querySelector('.amount-label');
                    if (label && label.textContent.includes('Paid:')) {
                        const paidText = row.querySelector('.amount-paid').textContent;
                        // Extract number from "RM 7,300.00" format
                        const match = paidText.match(/[\d,]+\.?\d*/);
                        if (match) {
                            totalPaid = parseFloat(match[0].replace(/,/g, ''));
                        }
                    }
                });
            }
            
            console.log('Total paid found:', totalPaid);
            
            const outstandingBalance = totalAmount - totalPaid;
            
            document.getElementById('totalAmount').value = totalAmount.toFixed(2);
            document.getElementById('alreadyPaid').value = totalPaid.toFixed(2);
            document.getElementById('outstandingBalance').value = outstandingBalance.toFixed(2);
            document.getElementById('amountPaid').value = ''; // Keep blank for user to enter
            document.getElementById('remainingAmount').value = outstandingBalance.toFixed(2); // Will update as user types
            document.getElementById('hiddenInvoiceId').value = invoiceId;
            
            console.log('Payment modal initialized:', {
                invoiceId,
                totalAmount: totalAmount.toFixed(2),
                totalPaid: totalPaid.toFixed(2),
                outstandingBalance: outstandingBalance.toFixed(2)
            });
            
            // Update modal title with invoice details
            const modalTitle = document.querySelector('#paymentModal .modal-title');
            const modalSubtitle = document.querySelector('#paymentModal .modal-header p');
            if (modalTitle) {
                modalTitle.innerHTML = `<i class="ri-money-dollar-circle-line me-2"></i>Customer Payment - Invoice #${invoiceId}`;
            }
            if (modalSubtitle) {
                modalSubtitle.innerHTML = `Outstanding Balance: <strong class="text-warning">RM ${outstandingBalance.toFixed(2)}</strong>`;
            }
            
            // Load payment history
            loadPaymentHistory(invoiceId);
        };
        
        /**
         * Load payment history for an invoice
         */
        function loadPaymentHistory(invoiceId) {
            const section = document.getElementById('paymentHistorySection');
            
            // Show loading spinner
            section.innerHTML = `
                <div class="text-center text-muted py-3">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Loading payment history...
                </div>
            `;
            
            fetch(`../private/view-order-tabs-backend.php?action=get_payment_history&invoice_id=${invoiceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.payments && data.payments.length > 0) {
                        displayPaymentHistory(data.payments);
                    } else {
                        section.innerHTML = '<p class="text-muted mb-0"><i class="ri-information-line"></i> No payment history found</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading payment history:', error);
                    section.innerHTML = '<p class="text-danger mb-0"><i class="ri-error-warning-line"></i> Failed to load payment history</p>';
                });
        }
        
        /**
         * Display payment history records
         */
        function displayPaymentHistory(payments) {
            const section = document.getElementById('paymentHistorySection');
            
            console.log('Payment history data received:', payments); // Debug log
            
            let html = '<div class="table-responsive">';
            html += '<table class="table table-sm mb-0">';
            html += '<thead>';
            html += '<tr class="border-0">';
            html += '<th class="border-0 bg-transparent fw-medium text-muted small">DATE & TIME</th>';
            html += '<th class="border-0 bg-transparent fw-medium text-muted small text-end">AMOUNT</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            
            let totalPaid = 0;
            payments.forEach(payment => {
                const amount = parseFloat(payment.amount_paid);
                console.log('Processing payment:', payment.payment_id, 'Amount:', amount, 'Type:', typeof payment.amount_paid); // Debug log
                totalPaid += amount;
                
                // Check if it's a deduction (negative payment)
                const isDeduction = amount < 0;
                const displayAmount = Math.abs(amount);
                
                html += '<tr>';
                html += `<td class="border-0 py-2">`;
                html += `<div class="d-flex align-items-center">`;
                if (isDeduction) {
                    html += `<i class="ri-indeterminate-circle-line text-danger me-2"></i>`;
                } else {
                    html += `<i class="ri-checkbox-circle-line text-success me-2"></i>`;
                }
                html += `<small class="text-dark">${payment.formatted_date}</small>`;
                html += `</div>`;
                html += `</td>`;
                html += `<td class="border-0 py-2 text-end">`;
                if (isDeduction) {
                    html += `<small class="text-danger fw-medium">- RM ${displayAmount.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</small>`;
                    html += `<br><small class="text-muted" style="font-size: 0.7rem;">(Deduction)</small>`;
                } else {
                    html += `<small class="text-success fw-medium">+ RM ${displayAmount.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</small>`;
                }
                html += `</td>`;
                html += '</tr>';
            });
            
            // Add total row with border on top
            const totalTextClass = totalPaid >= 0 ? 'text-success' : 'text-danger';
            html += '<tr class="border-top">';
            html += '<td class="pt-3 pb-2 fw-bold text-dark border-0">Total Paid</td>';
            html += `<td class="pt-3 pb-2 text-end fw-bold ${totalTextClass} border-0">RM ${totalPaid.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`;
            html += '</tr>';
            
            html += '</tbody>';
            html += '</table>';
            html += '</div>';
            
            section.innerHTML = html;
        }

        // Toggle order status
        window.toggleOrderStatus = function(invoiceId, currentStatus) {
            const newStatus = currentStatus === 'completed' ? 'pending' : 'completed';
            
            Swal.fire({
                title: 'Are you sure?',
                text: newStatus === 'completed' ? 
                    'Mark this order as completed?' : 
                    'Mark this order as incomplete?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    formData.append('status', newStatus);
                    
                    fetch('../private/view-order-tabs-backend.php?action=toggle_status', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                title: 'Status Updated!',
                                text: `Order has been marked as ${newStatus}.`,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.reload());
                        } else {
                            throw new Error(data.error || 'Failed to update status');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error updating status: ' + error.message,
                            icon: 'error'
                        });
                    });
                }
            });
        };

        // Debug invoice function
        window.debugInvoice = function(invoiceId) {
            console.log('Debug invoice called for ID:', invoiceId);
            
            fetch('../private/view-order-tabs-backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=debug_invoice&invoice_id=${invoiceId}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Debug invoice response:', data);
                
                if (data.success) {
                    Swal.fire({
                        title: 'Invoice Debug Info',
                        html: `
                            <div style="text-align: left;">
                                <strong>Invoice ID:</strong> ${data.invoice_id}<br>
                                <strong>Status:</strong> ${data.status}<br>
                                <strong>Total Amount:</strong> RM ${data.total_amount}<br>
                                <strong>Customer:</strong> ${data.customer_name}<br>
                                <strong>Date:</strong> ${data.date}<br>
                                <strong>Items Count:</strong> ${data.items_count}
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Debug Error',
                        text: data.message || 'Failed to get invoice debug info',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Debug invoice error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to debug invoice: ' + error.message,
                    icon: 'error'
                });
            });
        };

        // Helper functions
        function htmlEscape(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function formatNumber(value) {
            return parseFloat(value || 0).toLocaleString('en-MY', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatNumber3Decimal(value) {
            return parseFloat(value || 0).toLocaleString('en-MY', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
        }

        // Confirm Edit Order with Payment Warning
        function confirmEditOrder(invoiceId, payments) {
            const hasCustomerPayment = payments.customerPayment > 0;
            const hasSupplierPayment = payments.supplierPayment > 0;
            const hasShippingPayment = payments.shippingPayment > 0;
            const hasCommissionPayment = payments.commissionPayment > 0;
            const hasAnyPayment = hasCustomerPayment || hasSupplierPayment || hasShippingPayment || hasCommissionPayment;
            
            // Convert supplier payment from YEN to RM for display
            const conversionRate = payments.conversionRate || 0.032;
            const supplierPaymentRM = payments.supplierPayment / conversionRate;
            
            if (hasAnyPayment) {
                // Build payment details list
                let paymentDetails = [];
                
                if (hasCustomerPayment) {
                    paymentDetails.push(`
                        <li style="margin-bottom: 8px;">
                            <strong class="text-success">💰 Customer Payments:</strong> 
                            <span class="text-success">RM ${payments.customerPayment.toLocaleString('en-MY', {minimumFractionDigits: 2})}</span>
                            <br><small class="text-muted" style="margin-left: 20px;">Recorded in Order Management</small>
                        </li>
                    `);
                }
                
                if (hasSupplierPayment) {
                    paymentDetails.push(`
                        <li style="margin-bottom: 8px;">
                            <strong class="text-warning">🏭 Supplier Payments:</strong> 
                            <span class="text-warning">RM ${supplierPaymentRM.toLocaleString('en-MY', {minimumFractionDigits: 2})}</span>
                            <br><small class="text-muted" style="margin-left: 20px;">Recorded in Profit & Loss</small>
                        </li>
                    `);
                }
                
                if (hasShippingPayment) {
                    paymentDetails.push(`
                        <li style="margin-bottom: 8px;">
                            <strong class="text-info">🚢 Shipping Payments:</strong> 
                            <span class="text-info">RM ${payments.shippingPayment.toLocaleString('en-MY', {minimumFractionDigits: 2})}</span>
                            <br><small class="text-muted" style="margin-left: 20px;">Recorded in Profit & Loss</small>
                        </li>
                    `);
                }
                
                if (hasCommissionPayment) {
                    paymentDetails.push(`
                        <li style="margin-bottom: 8px;">
                            <strong style="color: #8b5cf6;">👤 Commission Payments:</strong> 
                            <span style="color: #7c3aed;">RM ${payments.commissionPayment.toLocaleString('en-MY', {minimumFractionDigits: 2})}</span>
                            <br><small class="text-muted" style="margin-left: 20px;">Recorded in Profit & Loss</small>
                        </li>
                    `);
                }
                
                // Show warning with specific payment details
                Swal.fire({
                    title: '⚠️ Payment Warning',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p style="margin-bottom: 15px;">
                                <strong class="text-danger">This order has existing payments recorded!</strong>
                            </p>
                            
                            <div style="background: var(--vz-input-bg, rgba(243, 244, 246, 0.5)); padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid var(--vz-border-color, rgba(209, 213, 219, 0.3));">
                                <p style="margin: 0 0 10px 0; font-weight: 600;">
                                    <i class="ri-money-dollar-circle-line"></i> Payment Summary:
                                </p>
                                <ul style="margin: 0; padding-left: 20px; list-style: none;">
                                    ${paymentDetails.join('')}
                                </ul>
                            </div>
                            
                            <p style="margin-bottom: 10px;" class="text-warning">
                                <i class="ri-alert-line"></i> <strong>Editing this order will affect:</strong>
                            </p>
                            <ul style="margin: 10px 0; padding-left: 20px;" class="text-muted">
                                <li>Cost calculations (supplier & shipping)</li>
                                <li>Profit/loss margins</li>
                                <li>Payment balances & reconciliation</li>
                                ${hasCommissionPayment ? '<li>Staff commission amounts</li>' : ''}
                            </ul>
                            
                            <div style="background: rgba(254, 243, 199, 0.2); border-left: 3px solid #f59e0b; padding: 10px; margin-top: 15px; border-radius: 4px;">
                                <p style="margin: 0; font-size: 0.9em;" class="text-warning">
                                    <i class="ri-information-line"></i> 
                                    <strong>Important:</strong> After saving changes, you'll be prompted to adjust payment records automatically to maintain accurate financial records.
                                </p>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    width: '600px',
                    showCancelButton: true,
                    confirmButtonText: '<i class="ri-edit-line"></i> Yes, Continue Editing',
                    cancelButtonText: '<i class="ri-close-line"></i> Cancel',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6c757d',
                    customClass: {
                        popup: 'payment-warning-popup',
                        confirmButton: 'btn-confirm-edit',
                        cancelButton: 'btn-cancel-edit'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Navigate to edit page
                        window.location.href = `forms-update-order.php?invoice_id=${invoiceId}`;
                    }
                });
            } else {
                // No payments - directly navigate to edit page
                window.location.href = `forms-update-order.php?invoice_id=${invoiceId}`;
            }
        }
    </script>
