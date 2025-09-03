
        <?php 
            include __DIR__ . '/../include/header.php';
            require_once __DIR__ . '/../private/view-order-tabs-backend.php';
            $orders = getOrderTabs();
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
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

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

                                                  

<!-- Start Order Cards -->

<div class="row mb-3">
    <div class="col-md-4">
        <div class="position-relative">
            <input type="text" class="form-control ps-3" id="searchInput" placeholder="Search invoice number...">
            <i class="ri-search-line position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
        </div>
    </div>
</div>

<?php
// Add pagination logic
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($orders);
$total_pages = ceil($total_items / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

// Slice the orders array for current page
$displayed_orders = array_slice($orders, $offset, $items_per_page);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Order Management</h4>
            </div>
            <div class="card-body">
                <div class="row" id="ordersList">
                    <?php foreach($displayed_orders as $order): ?>
                    <div class="col-12 mb-3">
                        <div class="card border <?= $order['status'] === 'completed' ? 'border-success bg-success bg-opacity-10' : '' ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title mb-1">Order #<?= htmlspecialchars($order['invoice_number']) ?></h5>
                                        <p class="text-muted mb-1">Company: <?= htmlspecialchars($order['customer_company_name'] ?? 'N/A') ?></p>
                                        <p class="text-muted mb-1">Contact: <?= htmlspecialchars($order['customer_name']) ?>
                                        <?php if($order['customer_phone']): ?>(<?= htmlspecialchars($order['customer_phone']) ?>)<?php endif; ?></p>
                                        <div class="text-info mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong>Total Amount Due:</strong>
                                                <span class="fs-5 fw-bold">RM<?= number_format($order['total_amount'], 2) ?></span>
                                            </div>
                                        </div>
                                        <?php if(isset($order['total_paid'])): ?>
                                        <div class="text-success mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong>Total Paid:</strong>
                                                <span class="fs-5 fw-bold">RM<?= number_format($order['total_paid'], 2) ?></span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($order['has_payment']): ?>
                                        <div class="text-warning">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong>ETA:</strong>
                                                <span class="fs-5">
                                                <?php if ($order['max_lead_time']): ?>
                                                    <span class="fw-bold"><?= $order['max_lead_time'] ?> Days</span>
                                                    <small>(<?= date('d M Y', strtotime($order['estimated_completion_date'])) ?>)</small>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
                                            <button type="button" class="btn btn-primary" 
                                                    onclick="loadOrderList(<?= $order['invoice_id'] ?>)" 
                                                    data-bs-toggle="modal" data-bs-target="#orderListModal">
                                                Order List
                                            </button>
                                            <button type="button" class="btn btn-info" 
                                                    onclick="loadCartonDetails(<?= $order['invoice_id'] ?>)" 
                                                    data-bs-toggle="modal" data-bs-target="#cartonDetailModal">
                                                Carton Detail
                                            </button>
                                            <button type="button" class="btn btn-success" 
                                                    onclick="preparePayment(<?= $order['invoice_id'] ?>, <?= $order['total_amount'] ?>)" 
                                                    data-bs-toggle="modal" data-bs-target="#paymentModal">
                                                + Payment
                                            </button>
                                            <button type="button" 
                                                    class="btn <?= $order['status'] === 'completed' ? 'btn-warning' : 'btn-success' ?>" 
                                                    onclick="toggleOrderStatus(<?= $order['invoice_id'] ?>, '<?= $order['status'] ?>')">
                                                <?= $order['status'] === 'completed' ? 'Mark as Incomplete' : 'Mark as Completed' ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Add pagination -->
                <div class="row mt-3">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a>
                                </li>
                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($current_page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Order Cards -->

<!-- Order List Modal -->
<div class="modal fade" id="orderListModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Price/Unit (RM)</th>
                                <th>QTY</th>
                                <th>Total Price (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Carton Detail Modal -->
<div class="modal fade" id="cartonDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carton Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Carton Dimensions</th>
                                <th>Weight</th>
                                <th>PCS/Carton</th>
                                <th>CBM/Carton</th>
                                <th>CBM/MOQ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label class="form-label">Total Amount Due (RM)</label>
                        <input type="text" class="form-control" id="totalAmount" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount Paid (RM)</label>
                        <input type="number" class="form-control" id="amountPaid" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remaining Amount (RM)</label>
                        <input type="text" class="form-control" id="remainingAmount" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>
                </form>
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

    // Add this near the top of the DOMContentLoaded event handler
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

        if (paidAmount > totalAmount) {
            Swal.fire({
                title: 'Error!',
                text: 'Payment amount cannot exceed total amount',
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
            cancelButtonText: 'No, cancel!',
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('invoice_id', currentInvoiceId);
                formData.append('amount_paid', amountPaidInput.value);
                formData.append('is_first_payment', totalAmount === parseFloat(amountPaidInput.value));
                
                fetch('../private/view-order-tabs-backend.php?action=submit_payment', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Payment Successful!',
                            text: 'The payment has been recorded successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(data.error || 'Failed to process payment');
                    }
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error processing payment: ' + error.message,
                        icon: 'error'
                    });
                });
            }
        });
    });

    // Function to mark order as completed
    window.markAsCompleted = function(orderId) {
        if (confirm('Are you sure you want to mark this order as completed?')) {
            // Here you'll add the AJAX call to update order status
            console.log('Order marked as completed:', orderId);
        }
    };

    // Replace the existing loadOrderList function in your script section with:
    window.loadOrderList = function(invoiceId) {
        console.log('Loading order list for invoice:', invoiceId); // Debug log

        // Show loading state
        const tbody = document.querySelector('#orderListModal tbody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';

        fetch(`../private/view-order-tabs-backend.php?action=get_order_items&invoice_id=${invoiceId}`)
            .then(res => {
                console.log('Response received:', res); // Debug log
                return res.json();
            })
            .then(data => {
                console.log('Data received:', data); // Debug log
                if(data.success && data.items) {
                    tbody.innerHTML = data.items.map((item, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${htmlEscape(item.product_name)}</td>
                            <td class="text-end">${formatCurrency(item.unit_price)}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">${formatCurrency(item.total_price)}</td>
                        </tr>
                    `).join('');

                    // Add total row
                    const total = data.items.reduce((sum, item) => sum + parseFloat(item.total_price), 0);
                    tbody.innerHTML += `
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">${formatCurrency(total)}</td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">No items found</td></tr>';
                    console.error('Failed to load order items:', data.error);
                }
            })
            .catch(error => {
                console.error('Error loading order items:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading items</td></tr>';
            });
    };

    // Update the loadCartonDetails function in your script section
    window.loadCartonDetails = function(invoiceId) {
        const tbody = document.querySelector('#cartonDetailModal tbody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';

        fetch(`../private/view-order-tabs-backend.php?action=get_carton_details&invoice_id=${invoiceId}`)
            .then(res => res.json())
            .then(data => {
                if(data.success && data.details && data.details.length > 0) {
                    tbody.innerHTML = data.details.map(detail => {
                        // Calculate total CBM/Carton including additional cartons
                        const totalCBMCarton = parseFloat(detail.cbm_carton || 0) +
                            parseFloat(detail.add_carton1_total_cbm || 0) +
                            parseFloat(detail.add_carton2_total_cbm || 0) +
                            parseFloat(detail.add_carton3_total_cbm || 0) +
                            parseFloat(detail.add_carton4_total_cbm || 0) +
                            parseFloat(detail.add_carton5_total_cbm || 0) +
                            parseFloat(detail.add_carton6_total_cbm || 0);

                        return `
                            <tr>
                                <td>
                                    ${htmlEscape(detail.product_name)}
                                    <small class="d-block text-muted">${htmlEscape(detail.product_code)}</small>
                                </td>
                                <td class="text-center">
                                    ${detail.carton_width} × ${detail.carton_height} × ${detail.carton_length}
                                </td>
                                <td class="text-end">${formatNumber(detail.carton_weight)} kg</td>
                                <td class="text-center">${detail.pcs_per_carton}</td>
                                <td class="text-end">${formatNumber(totalCBMCarton)}</td>
                                <td class="text-end">${formatNumber(detail.new_total_cbm_moq || 0)}</td>
                            </tr>
                        `;
                    }).join('') + `
                        <tr class="table-light">
                            <td colspan="2" class="text-end fw-bold">Total:</td>
                            <td class="text-end fw-bold">${formatNumber(
                                data.details.reduce((sum, item) => sum + parseFloat(item.carton_weight || 0), 0)
                            )} kg</td>
                            <td class="text-center fw-bold">${
                                data.details.reduce((sum, item) => sum + parseInt(item.pcs_per_carton || 0), 0)
                            }</td>
                            <td class="text-end fw-bold">${formatNumber(
                                data.details.reduce((sum, item) => {
                                    return sum + 
                                        parseFloat(item.cbm_carton || 0) +
                                        parseFloat(item.add_carton1_total_cbm || 0) +
                                        parseFloat(item.add_carton2_total_cbm || 0) +
                                        parseFloat(item.add_carton3_total_cbm || 0) +
                                        parseFloat(item.add_carton4_total_cbm || 0) +
                                        parseFloat(item.add_carton5_total_cbm || 0) +
                                        parseFloat(item.add_carton6_total_cbm || 0);
                                }, 0)
                            )}</td>
                            <td class="text-end fw-bold">${formatNumber(
                                data.details.reduce((sum, item) => sum + parseFloat(item.new_total_cbm_moq || 0), 0)
                            )}</td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">No carton details found</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading details</td></tr>';
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

    function formatCurrency(value) {
        return parseFloat(value || 0).toLocaleString('en-MY', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Add this function to prepare payment modal
    window.preparePayment = function(invoiceId, totalAmount) {
        currentInvoiceId = invoiceId;
        document.getElementById('totalAmount').value = totalAmount.toFixed(2);
        document.getElementById('amountPaid').value = '';
        document.getElementById('remainingAmount').value = '';
    };

    // Add toggleOrderStatus function
    window.toggleOrderStatus = function(invoiceId, currentStatus) {
        const newStatus = currentStatus === 'completed' ? 'pending' : 'completed';
        
        Swal.fire({
            title: 'Are you sure?',
            text: newStatus === 'completed' ? 
                'Do you want to mark this order as completed?' : 
                'Do you want to mark this order as incomplete?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'No, cancel!',
            showCloseButton: true
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
                            text: newStatus === 'completed' ? 
                                'Order has been marked as completed.' : 
                                'Order has been marked as incomplete.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(data.error || 'Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Status update error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error updating status: ' + error.message,
                        icon: 'error'
                    });
                });
            }
        });
    };

    // Replace the existing search JavaScript code
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const orderCards = document.querySelectorAll('#ordersList .col-12');
        
        orderCards.forEach(card => {
            const invoiceNumber = card.querySelector('.card-title').textContent.toLowerCase();
            if (invoiceNumber.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
