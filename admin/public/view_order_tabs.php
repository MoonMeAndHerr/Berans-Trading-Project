
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
                                            <span class="amount-total">RM <?= number_format($order['total_amount'], 2) ?></span>
                                        </div>
                                        <?php if(isset($order['total_paid']) && $order['total_paid'] > 0): ?>
                                        <div class="amount-row">
                                            <span class="amount-label">Paid:</span>
                                            <span class="amount-paid">RM <?= number_format($order['total_paid'], 2) ?></span>
                                        </div>
                                        <div class="amount-row">
                                            <span class="amount-label">Balance:</span>
                                            <span class="amount-remaining">RM <?= number_format($order['total_amount'] - $order['total_paid'], 2) ?></span>
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
                                            
                                            <button class="btn-compact btn-success" onclick="preparePayment(<?= $order['invoice_id'] ?>, <?= $order['total_amount'] ?>)" data-bs-toggle="modal" data-bs-target="#paymentModal">
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
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="ri-money-dollar-circle-line"></i>Add Payment
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="paymentForm" class="form-minimal">
                                    <!-- Hidden field to store invoice ID -->
                                    <input type="hidden" id="hiddenInvoiceId" value="">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="ri-money-cny-circle-line"></i>Total Amount Due (RM)
                                        </label>
                                        <input type="text" class="form-control" id="totalAmount" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="ri-bank-card-line"></i>Amount Paid (RM)
                                        </label>
                                        <input type="number" class="form-control" id="amountPaid" step="0.01" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="ri-calculator-line"></i>Remaining Amount (RM)
                                        </label>
                                        <input type="text" class="form-control" id="remainingAmount" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-compact btn-outline" data-bs-dismiss="modal">
                                    <i class="ri-close-line"></i> Cancel
                                </button>
                                <button type="submit" form="paymentForm" class="btn-compact btn-success">
                                    <i class="ri-check-line"></i> Submit Payment
                                </button>
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
                const totalAmount = parseFloat(totalAmountInput.value) || 0;
                const paidAmount = parseFloat(this.value) || 0;
                const remainingAmount = totalAmount - paidAmount;
                remainingAmountInput.value = remainingAmount.toFixed(2);
            });

            // Handle payment form submission
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const paidAmount = parseFloat(amountPaidInput.value);
                const totalAmount = parseFloat(totalAmountInput.value);

                if (paidAmount <= 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please enter a valid payment amount',
                        icon: 'error'
                    });
                    return;
                }

                // Show confirmation dialog
                Swal.fire({
                    title: 'Confirm Payment',
                    text: `Are you sure customer has made payment of RM${paidAmount.toFixed(2)}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                    confirmButtonText: 'OK'
                                }).then(() => window.location.reload());
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
                });
            });

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
                            
                            // Calculate Carton Quantity: PCS/CARTON / QTY
                            const orderedQty = parseFloat(detail.quantity || 1);
                            const pcsPerCarton = parseFloat(detail.pcs_per_carton || 0);
                            const cartonQuantity = orderedQty > 0 ? pcsPerCarton / orderedQty : 0;

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
                                    <td class="text-end">${formatNumber(detail.carton_weight)} kg</td>
                                    <td class="text-center">
                                        <span style="background: var(--order-accent-light); color: var(--order-accent); padding: 0.125rem 0.375rem; border-radius: var(--order-radius); font-weight: 500;">
                                            ${detail.pcs_per_carton}
                                        </span>
                                    </td>
                                    <td class="text-end">${formatNumber(totalCBMCarton)}</td>
                                    <td class="text-end">${formatNumber(cartonQuantity)}</td>
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
                                <td class="text-end" style="padding: 1rem;"><strong>${formatNumber(totalCBM)}</strong></td>
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
            document.getElementById('totalAmount').value = totalAmount.toFixed(2);
            document.getElementById('amountPaid').value = '';
            document.getElementById('remainingAmount').value = '';
            document.getElementById('hiddenInvoiceId').value = invoiceId; // Set hidden field as backup
            console.log('currentInvoiceId set to:', currentInvoiceId);
            console.log('hiddenInvoiceId set to:', invoiceId);
            
            // Add a visible indicator in the modal title to show which invoice
            const modalTitle = document.querySelector('#paymentModal .modal-title');
            if (modalTitle) {
                modalTitle.textContent = `Payment for Invoice #${invoiceId}`;
            }
        };

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
    </script>
