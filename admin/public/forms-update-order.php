<?php

    
    include __DIR__ . '/../private/forms-update-order-backend.php';
    include __DIR__ . '/../include/header.php';

    // Redirect if no invoice_id provided
    if (!$invoice_id || !$existingOrder) {
        $_SESSION['error'] = "Invalid order or order not found";
        header('Location: view_order_tabs.php');
        exit();
    }

?>

        <!-- Minimal CSS for Forms -->
        <link href="assets/css/forms-new-order-minimal.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />

        <!-- Custom CSS for Editable Inputs -->
        <style>
            .editable-price, .editable-quantity {
                border: 1px solid #d1d5db;
                padding: 0.375rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                transition: all 0.2s;
            }
            
            .editable-price:focus, .editable-quantity:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .editable-price:hover, .editable-quantity:hover {
                border-color: #9ca3af;
            }
            
            /* Remove spinner arrows for number inputs */
            .editable-price::-webkit-outer-spin-button,
            .editable-price::-webkit-inner-spin-button,
            .editable-quantity::-webkit-outer-spin-button,
            .editable-quantity::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            
            .editable-price[type=number],
            .editable-quantity[type=number] {
                -moz-appearance: textfield;
            }
            
            .row-total {
                font-weight: 600;
                text-align: right;
                padding-right: 1rem !important;
            }
        </style>

        <!-- Scripts -->
        <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>


            <!-- Main Content -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Update Order</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="view_order_tabs.php">Orders</a></li>
                                            <li class="breadcrumb-item active">Update Order</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <form method="POST" action="" id="invoiceForm">
                            <input type="hidden" name="products" id="productsJson">
                            <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoice_id) ?>">
                            <div class="col-lg-12">
                                <div class="form-container-minimal">
                                    <!-- Form Header -->
                                    <div class="form-header-minimal">
                                        <h4>Update Order #<?= htmlspecialchars($existingOrder['invoice_number']) ?></h4>
                                        <div class="subtitle">Modify products, customer information, and update invoice</div>
                                    </div>

                                    <!-- Edit Mode Info Box -->
                                    <div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 12px 16px; margin-bottom: 20px; border-radius: 4px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="ri-information-line" style="color: #4caf50; font-size: 20px;"></i>
                                            <div>
                                                <strong style="color: #2e7d32;">Edit Mode</strong>
                                                <p style="margin: 4px 0 0 0; font-size: 13px; color: #555;">
                                                    You are editing an existing order. Current customer: <strong><?= htmlspecialchars($existingOrder['customer_name']) ?></strong>
                                                    <?php if (!empty($existingOrder['commission_staff_id'])): ?>
                                                        | Commission Staff: <strong>Assigned</strong> (<?= htmlspecialchars($existingOrder['commission_percentage']) ?>%)
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($existingOrder['has_any_payments'])): ?>
                                    <!-- Payment Warning Box -->
                                    <div style="background: #fff3cd; border-left: 4px solid #f59e0b; padding: 12px 16px; margin-bottom: 20px; border-radius: 4px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="ri-alert-line" style="color: #f59e0b; font-size: 20px;"></i>
                                            <div>
                                                <strong style="color: #f59e0b;">⚠️ Payments Detected</strong>
                                                <p style="margin: 4px 0 0 0; font-size: 13px; color: #555;">
                                                    This order has existing payments. Changing prices/quantities will affect profit/loss calculations.
                                                    After submitting, you'll be prompted to adjust payments automatically.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Form Body -->
                                    <div class="form-body-minimal">
                                        <!-- Alert Messages -->
                                        <?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    <?php if (isset($_SESSION['error'])): ?>
                                                        Swal.fire({
                                                            title: 'Error!',
                                                            text: <?= json_encode($_SESSION['error']) ?>,
                                                            icon: 'error'
                                                        });
                                                        <?php unset($_SESSION['error']); ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isset($_SESSION['success'])): ?>
                                                        Swal.fire({
                                                            title: 'Success!',
                                                            text: <?= json_encode($_SESSION['success']) ?>,
                                                            icon: 'success'
                                                        });
                                                        <?php unset($_SESSION['success']); ?>
                                                    <?php endif; ?>
                                                });
                                            </script>
                                        <?php endif; ?>

                                        <!-- Debug Info (hidden, for developer reference) -->
                                        <script>
                                            console.log('Order Data Loaded:', {
                                                invoice_id: '<?= $invoice_id ?>',
                                                invoice_number: '<?= $existingOrder['invoice_number'] ?? 'N/A' ?>',
                                                customer_id: '<?= $existingOrder['customer_id'] ?? 'N/A' ?>',
                                                customer_name: '<?= $existingOrder['customer_name'] ?? 'N/A' ?>',
                                                commission_staff_id: '<?= $existingOrder['commission_staff_id'] ?? 'N/A' ?>',
                                                commission_percentage: '<?= $existingOrder['commission_percentage'] ?? '0' ?>',
                                                discount_type: '<?= $existingOrder['discount_type'] ?? 'none' ?>',
                                                discount_value: '<?= $existingOrder['discount_value'] ?? '0' ?>',
                                                discount_amount: '<?= $existingOrder['discount_amount'] ?? '0' ?>',
                                                subtotal: '<?= $existingOrder['subtotal'] ?? '0' ?>',
                                                grand_total: '<?= $existingOrder['grand_total'] ?? '0' ?>',
                                                items_count: <?= count($existingItems) ?>
                                            });
                                        </script>

                                        <!-- Product Pricing Section -->
                                        <div class="section-header-minimal">
                                            <h5>
                                                <span class="section-icon">
                                                    <i class="ri-shopping-bag-line"></i>
                                                </span>
                                                Product Selection
                                            </h5>
                                        </div>

                                        <div class="form-group-minimal">
                                            <!-- Product Dropdowns -->
                                            <div class="form-row-minimal cols-4">
                                                <div class="form-field-minimal">
                                                    <label for="section">Section</label>
                                                    <select class="form-select" id="section" name="section" required>
                                                        <option disabled selected>Choose Section...</option>
                                                        <?php foreach ($sections as $s): ?>
                                                            <option value="<?= $s['section_id'] ?>"><?= $s['section_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="category">Category</label>
                                                    <select class="form-select" id="category" name="category" required>
                                                        <option disabled selected>Choose Category...</option>
                                                        <?php foreach ($categories as $c): ?>
                                                            <option value="<?= $c['category_id'] ?>" data-section="<?= $c['section_id'] ?>">
                                                                <?= $c['category_name'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="subcategory">Subcategory</label>
                                                    <select class="form-select" id="subcategory" name="subcategory" required>
                                                        <option disabled selected>Choose Subcategory...</option>
                                                        <?php foreach ($subcategories as $sc): ?>
                                                            <option value="<?= $sc['subcategory_id'] ?>" data-category="<?= $sc['category_id'] ?>">
                                                                <?= $sc['subcategory_name'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="product">Product</label>
                                                    <select class="form-select" id="product" name="product_id" required>
                                                        <option disabled selected>Choose Product...</option>
                                                        <?php foreach ($products as $p): ?>
                                                            <option value="<?= $p['product_id'] ?>"
                                                                data-section="<?= $p['section_id'] ?>"
                                                                data-category="<?= $p['category_id'] ?>"
                                                                data-subcategory="<?= $p['subcategory_id'] ?>"
                                                                data-moq="<?= $p['new_moq_quantity'] ?>"
                                                                data-price="<?= $p['new_selling_price'] ?>"
                                                                data-price-id="<?= $p['price_id'] ?>" 
                                                            ><?= $p['display_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Quantity Section -->
                                            <div class="form-row-minimal cols-3">
                                                <div class="form-field-minimal">
                                                    <label for="moq">MOQ (Minimum Order Quantity)</label>
                                                    <input type="number" id="moq" name="moq" class="form-control" readonly>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" placeholder="Enter quantity">
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label>&nbsp;</label>
                                                    <div class="action-button-container">
                                                        <button type="button" id="addProduct" class="btn-minimal btn-primary">
                                                            <i class="ri-add-line"></i>
                                                            Add Product
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Customer & Staff Section -->
                                        <div class="section-header-minimal">
                                            <h5>
                                                <span class="section-icon">
                                                    <i class="ri-user-line"></i>
                                                </span>
                                                Customer & Staff Information
                                            </h5>
                                        </div>

                                        <div class="form-group-minimal">
                                            <div class="form-row-minimal cols-3">
                                                <div class="form-field-minimal">
                                                    <label for="customer">Customer</label>
                                                    <select class="form-select" id="customer" name="customer_id" required>
                                                        <option value="" disabled>Select Customer...</option>
                                                        <?php foreach ($customers as $c): ?>
                                                            <option value="<?= $c['customer_id'] ?>" <?= ($c['customer_id'] == $existingOrder['customer_id']) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($c['customer_name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="selected_staff">Commission Staff</label>
                                                    <select class="form-select" id="selected_staff" name="selected_staff">
                                                        <option value="">Select Staff...</option>
                                                        <?php foreach ($staff as $s): ?>
                                                            <option value="<?= $s['staff_id'] ?>" <?= ($s['staff_id'] == $existingOrder['commission_staff_id']) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($s['staff_name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="staff_commission_percentage">Commission Percentage</label>
                                                    <div class="input-group-minimal">
                                                        <input type="number" class="form-control" id="staff_commission_percentage" name="staff_commission_percentage" 
                                                               min="0" max="100" step="0.1" placeholder="0.0" value="<?= htmlspecialchars($existingOrder['commission_percentage'] ?? '0') ?>">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order List Section -->
                                        <div class="section-header-minimal">
                                            <h5>
                                                <span class="section-icon">
                                                    <i class="ri-list-check"></i>
                                                </span>
                                                Order Summary
                                            </h5>
                                        </div>

                                        <div class="form-group-minimal">
                                            <div class="table-responsive">
                                                <table class="table-minimal" id="productList">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Product Name</th>
                                                            <th>Price/Unit (RM)</th>
                                                            <th>Quantity</th>
                                                            <th>Total Price (RM)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (count($existingItems) > 0): ?>
                                                            <?php foreach ($existingItems as $index => $item): ?>
                                                            <tr>
                                                                <td><?= $index + 1 ?></td>
                                                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                                                <td>
                                                                    <input type="number" 
                                                                           class="form-control editable-price" 
                                                                           value="<?= number_format($item['unit_price'], 2, '.', '') ?>" 
                                                                           step="0.01" 
                                                                           min="0.01" 
                                                                           style="width: 100px; text-align: right;"
                                                                           oninput="updateRowTotal(this)"
                                                                           data-original-price="<?= $item['unit_price'] ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="number" 
                                                                           class="form-control editable-quantity" 
                                                                           value="<?= $item['quantity'] ?>" 
                                                                           min="1" 
                                                                           style="width: 80px; text-align: center;"
                                                                           oninput="updateRowTotal(this)">
                                                                </td>
                                                                <td class="row-total"><?= number_format($item['total_price'], 2) ?></td>
                                                                <td>
                                                                    <div class="table-actions">
                                                                        <button type="button" class="btn-minimal btn-danger" onclick="confirmRemoveProduct(this)">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                            Remove
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <input type="hidden" name="products[]" class="product-data" value='<?= json_encode([
                                                                    'product_id' => $item['product_id'],
                                                                    'price_id' => $item['price_id'],
                                                                    'product_name' => $item['product_name'],
                                                                    'quantity' => $item['quantity'],
                                                                    'unit_price' => $item['unit_price'],
                                                                    'total_price' => $item['total_price']
                                                                ]) ?>'>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr class="empty-state-minimal">
                                                                <td colspan="6">
                                                                    <div class="empty-icon">
                                                                        <i class="ri-shopping-cart-line"></i>
                                                                    </div>
                                                                    <div class="empty-text">No products added yet</div>
                                                                    <div class="empty-subtext">Add products above to see them here</div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Order Summary Section -->
                                            <div class="order-summary-section" id="orderSummary" style="display: <?= count($existingItems) > 0 ? 'block' : 'none' ?>;">
                                                <div class="summary-card">
                                                    <div class="summary-header">
                                                        <h6><i class="ri-calculator-line"></i> Order Summary</h6>
                                                    </div>
                                                    
                                                    <!-- Discount Section -->
                                                    <div class="discount-section">
                                                        <div class="form-row-minimal cols-3">
                                                            <div class="form-field-minimal">
                                                                <label for="discountType">Discount Type</label>
                                                                <select class="form-select" id="discountType" name="discount_type">
                                                                    <option value="none" <?= ($existingOrder['discount_type'] ?? 'none') == 'none' ? 'selected' : '' ?>>No Discount</option>
                                                                    <option value="percentage" <?= ($existingOrder['discount_type'] ?? '') == 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                                                                    <option value="amount" <?= ($existingOrder['discount_type'] ?? '') == 'amount' ? 'selected' : '' ?>>Amount (RM)</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-field-minimal">
                                                                <label for="discountValue">Discount Value</label>
                                                                <input type="number" id="discountValue" name="discount_value" class="form-control" 
                                                                       min="0" step="0.01" placeholder="0.00" 
                                                                       value="<?= htmlspecialchars($existingOrder['discount_value'] ?? '0') ?>"
                                                                       <?= ($existingOrder['discount_type'] ?? 'none') == 'none' ? 'disabled' : '' ?>>
                                                            </div>
                                                            <div class="form-field-minimal">
                                                                <label for="discountAmount">Discount Amount (RM)</label>
                                                                <input type="number" id="discountAmount" name="discount_amount" class="form-control" 
                                                                       readonly placeholder="0.00" value="<?= number_format($existingOrder['discount_amount'] ?? 0, 2) ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Totals -->
                                                    <div class="totals-section">
                                                        <div class="total-row">
                                                            <span class="total-label">Subtotal:</span>
                                                            <span class="total-value" id="subtotalAmount">RM <?= number_format($existingOrder['subtotal'] ?? 0, 2) ?></span>
                                                        </div>
                                                        <div class="total-row">
                                                            <span class="total-label">Discount:</span>
                                                            <span class="total-value" id="displayDiscountAmount">RM <?= number_format($existingOrder['discount_amount'] ?? 0, 2) ?></span>
                                                        </div>
                                                        <div class="total-row total-final">
                                                            <span class="total-label">Grand Total:</span>
                                                            <span class="total-value" id="grandTotalAmount">RM <?= number_format($existingOrder['grand_total'] ?? 0, 2) ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Section -->
                                    <div class="submit-section-minimal">
                                        <button type="submit" class="submit-button-minimal" onclick="return prepareSubmission()">
                                            <i class="ri-refresh-line"></i>
                                            Update Invoice
                                        </button>
                                        <a href="view_order_tabs.php" class="btn-minimal btn-secondary" style="margin-left: 10px;">
                                            <i class="ri-arrow-left-line"></i>
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php include __DIR__ . '/../include/footer.php'; ?>
            </div>
        </div>

        <?php include __DIR__ . '/../include/themesetting.php'; ?>

        <!-- Scripts -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="assets/libs/prismjs/prism.js"></script>
        <script src="assets/js/app.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Custom Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sectionSelect = document.getElementById('section');
                const categorySelect = document.getElementById('category');
                const subcategorySelect = document.getElementById('subcategory');
                const productSelect = document.getElementById('product');

                if (!sectionSelect || !categorySelect || !subcategorySelect || !productSelect) return;

                // Store all data for client-side filtering (like forms-price-add-new.php)
                const allCategories = Array.from(categorySelect.options)
                    .filter(opt => opt.value)
                    .map(opt => ({ value: opt.value, label: opt.text, section: opt.dataset.section }));

                const allSubcategories = Array.from(subcategorySelect.options)
                    .filter(opt => opt.value)
                    .map(opt => ({ value: opt.value, label: opt.text, category: opt.dataset.category }));

                const allProducts = Array.from(productSelect.options)
                    .filter(opt => opt.value)
                    .map(opt => ({
                        value: opt.value,
                        label: opt.text,
                        section: opt.dataset.section,
                        category: opt.dataset.category,
                        subcategory: opt.dataset.subcategory,
                        moq: opt.dataset.moq,
                        price: opt.dataset.price,
                        priceId: opt.dataset.priceId
                    }));

                const baseCfg = { searchEnabled: true, shouldSort: false, itemSelectText: '', placeholder: true };
                const chSection = new Choices(sectionSelect, baseCfg);
                const chCategory = new Choices(categorySelect, baseCfg);
                const chSubcat = new Choices(subcategorySelect, baseCfg);
                const chProduct = new Choices(productSelect, baseCfg);
                const chCustomer = new Choices('#customer', baseCfg);
                const chStaff = new Choices('#selected_staff', baseCfg);

                // DOM Elements
                const moqField = document.getElementById('moq');
                const quantityInput = document.getElementById('quantity');
                const addProductBtn = document.getElementById('addProduct');
                const productList = document.getElementById('productList').getElementsByTagName('tbody')[0];
                
                // Set product count based on existing items
                const existingRows = productList.querySelectorAll('tr:not(.empty-state-minimal)');
                window.productCount = existingRows.length + 1; // Make it global

                // Order Summary Elements
                const orderSummary = document.getElementById('orderSummary');
                const discountTypeSelect = document.getElementById('discountType');
                const discountValueInput = document.getElementById('discountValue');
                const discountAmountInput = document.getElementById('discountAmount');
                const subtotalElement = document.getElementById('subtotalAmount');
                const displayDiscountElement = document.getElementById('displayDiscountAmount');
                const grandTotalElement = document.getElementById('grandTotalAmount');

                // Initialize discount functionality
                window.currentSubtotal = <?= $existingOrder['subtotal'] ?? 0 ?>; // Initialize with existing subtotal

                // Calculation Functions - Make them global so they can be called from anywhere
                window.updateOrderSummary = function() {
                    const rows = productList.querySelectorAll('tbody tr:not(.empty-state-minimal)');
                    
                    if (rows.length === 0) {
                        orderSummary.style.display = 'none';
                        window.currentSubtotal = 0;
                        return;
                    }
                    
                    // Show order summary
                    orderSummary.style.display = 'block';
                    
                    // Calculate subtotal by finding all .row-total cells
                    window.currentSubtotal = 0;
                    rows.forEach(row => {
                        const totalPriceCell = row.querySelector('.row-total');
                        if (totalPriceCell) {
                            // Remove commas before parsing (e.g., "220,000.00" -> "220000.00")
                            const cleanText = totalPriceCell.textContent.replace(/,/g, '');
                            const amount = parseFloat(cleanText) || 0;
                            window.currentSubtotal += amount;
                            console.log('Row total text:', totalPriceCell.textContent, 'Parsed:', amount, 'Running subtotal:', window.currentSubtotal);
                        }
                    });
                    
                    console.log('Final Subtotal:', window.currentSubtotal);
                    
                    // Update display with comma formatting
                    subtotalElement.textContent = `RM ${window.currentSubtotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                    
                    // Recalculate discount and grand total
                    calculateDiscount();
                }

                window.calculateDiscount = function() {
                    const discountType = discountTypeSelect.value;
                    const discountValue = parseFloat(discountValueInput.value) || 0;
                    let discountAmount = 0;
                    
                    console.log('calculateDiscount called - Type:', discountType, 'Value:', discountValue, 'Subtotal:', window.currentSubtotal);
                    
                    if (discountType === 'percentage' && discountValue > 0) {
                        discountAmount = (window.currentSubtotal * discountValue) / 100;
                        // Prevent discount from exceeding subtotal
                        discountAmount = Math.min(discountAmount, window.currentSubtotal);
                    } else if (discountType === 'amount' && discountValue > 0) {
                        discountAmount = Math.min(discountValue, window.currentSubtotal);
                    }
                    
                    const grandTotal = window.currentSubtotal - discountAmount;
                    
                    console.log('Calculated - Discount:', discountAmount, 'Grand Total:', grandTotal);
                    
                    // Update displays with comma formatting
                    discountAmountInput.value = discountAmount.toFixed(2);
                    displayDiscountElement.textContent = `RM ${discountAmount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                    grandTotalElement.textContent = `RM ${grandTotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                }

                // Discount Event Handlers
                discountTypeSelect.addEventListener('change', function() {
                    const discountType = this.value;
                    
                    if (discountType === 'none') {
                        discountValueInput.disabled = true;
                        discountValueInput.value = '';
                    } else {
                        discountValueInput.disabled = false;
                        if (discountType === 'percentage') {
                            discountValueInput.max = '100';
                            discountValueInput.placeholder = 'Enter percentage (0-100)';
                        } else if (discountType === 'amount') {
                            discountValueInput.max = window.currentSubtotal.toString();
                            discountValueInput.placeholder = 'Enter amount in RM';
                        }
                    }
                    
                    calculateDiscount();
                });

                discountValueInput.addEventListener('input', function() {
                    const discountType = discountTypeSelect.value;
                    let value = parseFloat(this.value) || 0;
                    
                    // Validation
                    if (discountType === 'percentage') {
                        if (value > 100) {
                            this.value = 100;
                            value = 100;
                        }
                    } else if (discountType === 'amount') {
                        if (value > window.currentSubtotal) {
                            this.value = window.currentSubtotal.toFixed(2);
                            value = window.currentSubtotal;
                        }
                    }
                    
                    calculateDiscount();
                });

                function clearProductAndMOQ() {
                    chProduct.removeActiveItems();
                    productSelect.value = '';
                    moqField.value = '';
                }

                function rebuildCategoryChoices() {
                    const section = sectionSelect.value;
                    chCategory.clearChoices();
                    const filtered = allCategories.filter(c => !section || c.section === section);
                    chCategory.setChoices([{ value: '', label: 'Choose Category...', disabled: true }], 'value', 'label', false);
                    chCategory.setChoices(filtered.map(c => ({ value: c.value, label: c.label })), 'value', 'label', true);
                    chSubcat.clearChoices();
                    chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true }], 'value', 'label', false);
                    clearProductAndMOQ();
                }

                function rebuildSubcategoryChoices() {
                    const category = categorySelect.value;
                    chSubcat.clearChoices();
                    const filtered = allSubcategories.filter(sc => !category || sc.category === category);
                    chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true }], 'value', 'label', false);
                    chSubcat.setChoices(filtered.map(sc => ({ value: sc.value, label: sc.label })), 'value', 'label', true);
                    clearProductAndMOQ();
                }

                function rebuildProductChoices() {
                    const section = sectionSelect.value;
                    const category = categorySelect.value;
                    const subcategory = subcategorySelect.value;

                    const filtered = allProducts.filter(p =>
                        (!section || p.section === section) &&
                        (!category || p.category === category) &&
                        (!subcategory || p.subcategory === subcategory)
                    );

                    chProduct.clearChoices();
                    chProduct.setChoices([{ value: '', label: 'Choose Product...', disabled: true }], 'value', 'label', false);
                    chProduct.setChoices(filtered.map(p => ({ value: p.value, label: p.label })), 'value', 'label', true);
                    clearProductAndMOQ();
                }

                // Event listeners for cascade filtering
                sectionSelect.addEventListener('change', function () {
                    rebuildCategoryChoices();
                    rebuildProductChoices();
                });
                categorySelect.addEventListener('change', function () {
                    rebuildSubcategoryChoices();
                    rebuildProductChoices();
                });
                subcategorySelect.addEventListener('change', rebuildProductChoices);

                // Product selection - update MOQ
                productSelect.addEventListener('change', function () {
                    const selectedValue = chProduct.getValue(true);
                    const productData = allProducts.find(p => p.value === selectedValue);
                    if (productData) {
                        moqField.value = productData.moq || '';
                    } else {
                        moqField.value = '';
                    }
                });

                // Event: Add Product Button Click
                addProductBtn.addEventListener('click', () => {
                    const selectedProductId = chProduct.getValue(true);
                    const productData = allProducts.find(p => p.value === selectedProductId);
                    const quantity = parseInt(quantityInput.value);
                    const moq = parseInt(moqField.value);
                    
                    // Validation for Add Product
                    if (!selectedProductId || !productData) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please select a product',
                            icon: 'error'
                        });
                        return;
                    }



                    // Check if price exists
                    if (!productData.price || productData.price === 'null' || productData.price === '0') {
                        Swal.fire({
                            title: 'Error!',
                            text: 'This product has no selling price set. Please set the selling price in Product Pricing first.',
                            icon: 'error'
                        });
                        return;
                    }

                    // Check if MOQ exists
                    if (!moq || moq === 0) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'MOQ is not set. Please set the MOQ in Product Pricing first.',
                            icon: 'error'
                        });
                        return;
                    }

                    if (!quantity || quantity < 1) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Quantity must be greater than 0',
                            icon: 'error'
                        });
                        return;
                    }

                    // Get values
                    const unitPrice = parseFloat(productData.price);
                    if (isNaN(unitPrice)) {
                        console.error('Invalid unit price:', productData.price);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error: Invalid product price format. Please check the selling price in Product Pricing.',
                            icon: 'error'
                        });
                        return;
                    }

                    // Remove empty state if it exists
                    const emptyState = productList.querySelector('.empty-state-minimal');
                    if (emptyState) {
                        emptyState.remove();
                    }

                    // Create new row
                    const row = productList.insertRow();
                    row.innerHTML = `
                        <td>${window.productCount}</td>
                        <td>${productData.label}</td>
                        <td>
                            <input type="number" 
                                   class="form-control editable-price" 
                                   value="${unitPrice.toFixed(2)}" 
                                   step="0.01" 
                                   min="0.01" 
                                   style="width: 100px; text-align: right;"
                                   oninput="updateRowTotal(this)"
                                   data-original-price="${unitPrice}">
                        </td>
                        <td>
                            <input type="number" 
                                   class="form-control editable-quantity" 
                                   value="${quantity}" 
                                   min="1" 
                                   style="width: 80px; text-align: center;"
                                   oninput="updateRowTotal(this)">
                        </td>
                        <td class="row-total">${(unitPrice * quantity).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td>
                            <div class="table-actions">
                                <button type="button" class="btn-minimal btn-danger" onclick="confirmRemoveProduct(this)">
                                    <i class="ri-delete-bin-line"></i>
                                    Remove
                                </button>
                            </div>
                        </td>
                        <input type="hidden" name="products[]" class="product-data" value='${JSON.stringify({
                            product_id: selectedProductId,
                            price_id: productData.priceId,
                            product_name: productData.label,
                            quantity: quantity,
                            unit_price: unitPrice,
                            total_price: unitPrice * quantity
                        })}'>
                    `;

                    window.productCount++;

                    // Update order summary
                    updateOrderSummary();

                    // Reset form - using Choices.js methods
                    chProduct.setChoiceByValue('');
                    quantityInput.value = '';
                    moqField.value = '';
                });

                // Initialize cascade - clear dependent dropdowns initially (only for product selection, not customer/staff)
                chCategory.clearChoices();
                chCategory.setChoices([{ value: '', label: 'Choose Category...', disabled: true, selected: true }]);
                chSubcat.clearChoices();
                chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true, selected: true }]);
                chProduct.clearChoices();
                chProduct.setChoices([{ value: '', label: 'Choose Product...', disabled: true, selected: true }]);
                
                // Pre-fill customer and staff dropdowns with existing values
                <?php if (!empty($existingOrder['customer_id'])): ?>
                chCustomer.setChoiceByValue('<?= $existingOrder['customer_id'] ?>');
                <?php endif; ?>
                
                <?php if (!empty($existingOrder['commission_staff_id'])): ?>
                chStaff.setChoiceByValue('<?= $existingOrder['commission_staff_id'] ?>');
                <?php endif; ?>
                
                // Initialize totals from existing order data on page load
                setTimeout(function() {
                    // Calculate and display current totals based on items in the table
                    updateOrderSummary();
                    
                    // Ensure discount fields are properly enabled/disabled based on type
                    const currentDiscountType = discountTypeSelect.value;
                    if (currentDiscountType !== 'none') {
                        discountValueInput.disabled = false;
                    }
                }, 100);
            });

            // Function to update row total when price or quantity changes
            function updateRowTotal(input) {
                const row = input.closest('tr');
                const priceInput = row.querySelector('.editable-price');
                const quantityInput = row.querySelector('.editable-quantity');
                const totalCell = row.querySelector('.row-total');
                const hiddenInput = row.querySelector('.product-data');
                
                // Get current values
                const unitPrice = parseFloat(priceInput.value) || 0;
                const quantity = parseInt(quantityInput.value) || 1;
                
                // Validate minimum values
                if (unitPrice < 0.01) {
                    priceInput.value = '0.01';
                    Swal.fire({
                        title: 'Invalid Price',
                        text: 'Unit price must be at least RM 0.01',
                        icon: 'warning',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }
                
                if (quantity < 1) {
                    quantityInput.value = '1';
                    Swal.fire({
                        title: 'Invalid Quantity',
                        text: 'Quantity must be at least 1',
                        icon: 'warning',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }
                
                // Calculate new total
                const totalPrice = unitPrice * quantity;
                
                console.log('updateRowTotal - Price:', unitPrice, 'Qty:', quantity, 'Total:', totalPrice);
                
                // Update display with comma formatting
                totalCell.textContent = totalPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // Update hidden input with new values
                const productData = JSON.parse(hiddenInput.value);
                productData.unit_price = unitPrice;
                productData.quantity = quantity;
                productData.total_price = totalPrice;
                hiddenInput.value = JSON.stringify(productData);
                
                // Update order summary
                window.updateOrderSummary();
            }

            function confirmRemoveProduct(button) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to remove this product from the order?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'No, cancel!',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const row = button.closest('tr');
                        const tbody = row.parentElement;
                        row.remove();
                        
                        // If no more products, show empty state
                        if (tbody.children.length === 0) {
                            tbody.innerHTML = `
                                <tr class="empty-state-minimal">
                                    <td colspan="6">
                                        <div class="empty-icon">
                                            <i class="ri-shopping-cart-line"></i>
                                        </div>
                                        <div class="empty-text">No products added yet</div>
                                        <div class="empty-subtext">Add products above to see them here</div>
                                    </td>
                                </tr>
                            `;
                        }
                        
                        // Update order summary
                        window.updateOrderSummary();
                    }
                });
            }

            function prepareSubmission() {
                const products = [];
                const rows = document.querySelectorAll('#productList tbody tr:not(.empty-state-minimal)');
                
                if (rows.length === 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please add at least one product to the invoice',
                        icon: 'error'
                    });
                    return false;
                }

                const customerId = document.getElementById('customer').value;
                
                if (!customerId) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select a customer',
                        icon: 'error'
                    });
                    return false;
                }

                // Calculate current costs BEFORE showing confirmation
                calculateCostsAndShowConfirmation(rows, customerId);
                
                return false; // Always return false to prevent default form submission
            }

            // Function to calculate costs and show detailed confirmation
            async function calculateCostsAndShowConfirmation(rows, customerId) {
                const hasPayments = <?= !empty($existingOrder['has_any_payments']) ? 'true' : 'false' ?>;
                
                if (!hasPayments) {
                    // No payments - show simple confirmation
                    showSimpleConfirmation(rows, customerId);
                    return;
                }

                // Show loading while calculating
                Swal.fire({
                    title: 'Calculating Changes...',
                    text: 'Please wait while we analyze the cost impact.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    // Prepare products data
                    const products = [];
                    rows.forEach(row => {
                        const hiddenInput = row.querySelector('.product-data');
                        const priceInput = row.querySelector('.editable-price');
                        const quantityInput = row.querySelector('.editable-quantity');
                        
                        if (hiddenInput && priceInput && quantityInput) {
                            const product = JSON.parse(hiddenInput.value);
                            product.unit_price = parseFloat(priceInput.value) || 0;
                            product.quantity = parseInt(quantityInput.value) || 1;
                            product.total_price = product.unit_price * product.quantity;
                            products.push(product);
                        }
                    });

                    // Prepare form data for cost calculation
                    const formData = new FormData();
                    formData.append('action', 'calculate_cost_changes');
                    formData.append('invoice_id', '<?= $invoice_id ?>');
                    products.forEach((product, index) => {
                        formData.append(`products[${index}]`, JSON.stringify(product));
                    });
                    
                    // Add discount and total information for accurate commission calculation
                    const discountAmount = document.getElementById('discountAmount').value;
                    const subtotal = window.currentSubtotal || 0;
                    const grandTotal = subtotal - (parseFloat(discountAmount) || 0);
                    formData.append('total', grandTotal.toString()); // Add grand_total for commission calculation

                    // Fetch cost comparison
                    const response = await fetch('../private/forms-update-order-backend.php', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    console.log('Cost calculation response:', data);
                    
                    if (data.success && data.adjustment) {
                        console.log('Adjustment data:', data.adjustment);
                        console.log('Has payments:', data.adjustment.has_payments);
                        console.log('Adjustment needed:', data.adjustment.adjustment_needed);
                        
                        // Show detailed confirmation if payments exist and costs changed
                        if (data.adjustment.has_payments && data.adjustment.adjustment_needed) {
                            console.log('Showing detailed confirmation');
                            showDetailedConfirmation(rows, customerId, data.adjustment);
                        } else {
                            console.log('Showing simple confirmation - no significant changes or no payments');
                            showSimpleConfirmation(rows, customerId);
                        }
                    } else {
                        console.log('No adjustment data or unsuccessful response');
                        // No significant changes - show simple confirmation
                        showSimpleConfirmation(rows, customerId);
                    }
                } catch (error) {
                    console.error('Error calculating costs:', error);
                    // Fallback to simple confirmation
                    showSimpleConfirmation(rows, customerId);
                }
            }

            // Simple confirmation (no payments or calculation error)
            function showSimpleConfirmation(rows, customerId) {
                Swal.fire({
                    title: 'Update Invoice?',
                    text: 'Are you sure you want to update this invoice?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel!',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitOrderUpdate(rows, customerId);
                    }
                });
            }

            // Detailed confirmation with cost breakdown
            function showDetailedConfirmation(rows, customerId, adj) {
                const formatRM = (num) => 'RM ' + parseFloat(num).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Check for overpayments
                const hasOverpayment = adj.new_balances.supplier_rm < -0.01 || 
                                      adj.new_balances.shipping_rm < -0.01 || 
                                      adj.new_balances.commission_rm < -0.01;
                
                let breakdownHtml = `
                    <div style="text-align: left; margin: 20px 0;">
                        <h5 style="color: #fbbf24; margin-bottom: 15px;">⚠️ Cost Changes Detected</h5>`;
                
                // Add overpayment warning if detected
                if (hasOverpayment) {
                    breakdownHtml += `
                        <div style="background: rgba(220, 38, 38, 0.15); border: 2px solid #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="color: #fca5a5; margin-bottom: 10px;">⚠️ OVERPAYMENT DETECTED</h6>
                            <p style="margin: 0; color: #fecaca; font-weight: 500;">
                                The new costs are LOWER than what you've already paid. 
                                Automatic adjustment cannot reverse payments. 
                                <strong style="color: #fee2e2;">Manual action will be required</strong> to handle refunds or credits.
                            </p>
                        </div>`;
                }
                
                breakdownHtml += `
                        <div style="background: rgba(251, 191, 36, 0.15); border: 1px solid #fbbf24; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px; color: #fcd34d;">Supplier Costs:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Current Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.supplier_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.supplier_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #f59e0b;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.supplier_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.supplier_rm >= 0 ? '+' : ''}${formatRM(adj.differences.supplier_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; border: 1px solid rgba(251, 191, 36, 0.3);">
                                <div style="color: #fcd34d;">💰 Payments Made: ${formatRM(adj.payments_made.supplier_rm)}</div>
                                <div style="color: ${adj.new_balances.supplier_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.supplier_rm)}</strong>
                                    ${adj.new_balances.supplier_rm > 0.01 ? ' (Underpaid)' : adj.new_balances.supplier_rm < -0.01 ? ' (⚠️ OVERPAID - Refund Needed)' : ' (Balanced)'}
                                </div>
                            </div>
                        </div>
                        
                        <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid #60a5fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px; color: #93c5fd;">Shipping Costs:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Current Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.shipping_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.shipping_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #3b82f6;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.shipping_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.shipping_rm >= 0 ? '+' : ''}${formatRM(adj.differences.shipping_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; border: 1px solid rgba(96, 165, 250, 0.3);">
                                <div style="color: #93c5fd;">💰 Payments Made: ${formatRM(adj.payments_made.shipping_rm)}</div>
                                <div style="color: ${adj.new_balances.shipping_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.shipping_rm)}</strong>
                                    ${adj.new_balances.shipping_rm > 0.01 ? ' (Underpaid)' : adj.new_balances.shipping_rm < -0.01 ? ' (⚠️ OVERPAID - Refund Needed)' : ' (Balanced)'}
                                </div>
                            </div>
                        </div>`;
                
                // Add commission section if commission changes detected
                if (adj.old_costs.commission_rm > 0 || adj.new_costs.commission_rm > 0) {
                    breakdownHtml += `
                        <div style="background: rgba(139, 92, 246, 0.15); border: 1px solid #a78bfa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px; color: #c4b5fd;">Staff Commission:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Current Commission:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.commission_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Commission:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.commission_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #6366f1;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.commission_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.commission_rm >= 0 ? '+' : ''}${formatRM(adj.differences.commission_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; border: 1px solid rgba(167, 139, 250, 0.3);">
                                <div style="color: #c4b5fd;">💰 Commission Paid: ${formatRM(adj.payments_made.commission_rm || 0)}</div>
                                <div style="color: ${adj.new_balances.commission_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.commission_rm)}</strong>
                                    ${adj.new_balances.commission_rm > 0.01 ? ' (Unpaid)' : adj.new_balances.commission_rm < -0.01 ? ' (⚠️ OVERPAID - Manual Adjustment Needed)' : ' (Fully Paid)'}
                                </div>
                            </div>
                        </div>`;
                }
                
                breakdownHtml += `
                        <div style="background: rgba(75, 85, 99, 0.3); border: 1px solid #9ca3af; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center;">
                            <h5 style="margin: 0; color: ${adj.new_balances.total_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 600;">
                                Total Balance: ${formatRM(adj.new_balances.total_rm)}
                            </h5>
                        </div>
                        
                        <p style="margin-top: 15px; font-size: 14px; color: #6b7280; text-align: center;">
                            ${hasOverpayment ? 
                                '⚠️ <strong>Manual action required</strong> to handle overpayments. Proceed with caution.' : 
                                'You\'ll be able to adjust payments automatically after updating.'}
                        </p>
                    </div>
                `;

                Swal.fire({
                    title: 'Review Changes Before Update',
                    html: breakdownHtml,
                    icon: hasOverpayment ? 'error' : 'warning',
                    width: '700px',
                    showCancelButton: true,
                    confirmButtonText: '✅ Proceed with Update',
                    cancelButtonText: '❌ Cancel',
                    confirmButtonColor: hasOverpayment ? '#dc2626' : '#3b82f6',
                    cancelButtonColor: '#6b7280',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitOrderUpdate(rows, customerId);
                    }
                });
            }

            // Function to submit the order update
            function submitOrderUpdate(rows, customerId) {
                // Show loading
                Swal.fire({
                    title: 'Updating Invoice...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const products = [];

                // Prepare products data - read from editable inputs
                rows.forEach(row => {
                    const hiddenInput = row.querySelector('.product-data');
                    const priceInput = row.querySelector('.editable-price');
                    const quantityInput = row.querySelector('.editable-quantity');
                    
                    if (hiddenInput && priceInput && quantityInput) {
                        const product = JSON.parse(hiddenInput.value);
                        
                        // Update with current editable values
                        product.product_name = row.cells[1].textContent.trim();
                        product.unit_price = parseFloat(priceInput.value) || 0;
                        product.quantity = parseInt(quantityInput.value) || 1;
                        product.total_price = product.unit_price * product.quantity;
                        
                        products.push(product);
                    }
                });

                // Prepare form data
                const formData = new FormData();
                
                // Send products as individual array elements like the original form method
                products.forEach((product, index) => {
                    formData.append(`products[${index}]`, JSON.stringify(product));
                });
                
                formData.append('customer_id', customerId);
                formData.append('invoice_id', '<?= $invoice_id ?>');
                
                const selectedStaff = document.getElementById('selected_staff').value;
                const commissionPercentage = document.getElementById('staff_commission_percentage').value;
                
                if (selectedStaff) {
                    formData.append('selected_staff', selectedStaff);
                }
                if (commissionPercentage) {
                    formData.append('staff_commission_percentage', commissionPercentage);
                }

                // Add discount information
                const discountType = document.getElementById('discountType').value;
                const discountValue = document.getElementById('discountValue').value;
                const discountAmount = document.getElementById('discountAmount').value;
                const subtotal = window.currentSubtotal || 0;
                const grandTotal = subtotal - (parseFloat(discountAmount) || 0);

                formData.append('discount_type', discountType);
                formData.append('discount_value', discountValue || '0');
                formData.append('discount_amount', discountAmount || '0');
                formData.append('subtotal', subtotal.toString());
                formData.append('grand_total', grandTotal.toString());

                // Submit via AJAX with timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                
                fetch('', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    signal: controller.signal
                })
                .then(response => {
                    clearTimeout(timeoutId); // Clear timeout on successful response
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, get text and throw error
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('Server returned an error. Please check the console for details.');
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Check if payment adjustment is needed
                        if (data.adjustment && data.adjustment.adjustment_needed) {
                            showPaymentAdjustmentPrompt(data);
                        } else {
                            // Success - no adjustment needed
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'Invoice updated successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Redirect to order list page
                                window.location.href = 'view_order_tabs.php';
                            });
                        }
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error || 'Failed to update invoice.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    clearTimeout(timeoutId);
                    console.error('Error:', error);
                    
                    let errorMessage = 'An error occurred while updating the invoice.';
                    if (error.name === 'AbortError') {
                        errorMessage = 'Request timeout. The server is taking too long to respond.';
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }

            // Function to show payment adjustment prompt after successful update
            function showPaymentAdjustmentPrompt(data) {
                const adj = data.adjustment;
                const invoiceId = data.invoice_id;
                
                // Format numbers
                const formatRM = (num) => 'RM ' + parseFloat(num).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                // Check for overpayments
                const hasOverpayment = adj.new_balances.supplier_rm < -0.01 || 
                                      adj.new_balances.shipping_rm < -0.01 || 
                                      adj.new_balances.commission_rm < -0.01;
                
                // Build detailed breakdown HTML
                let breakdownHtml = `
                    <div style="text-align: left; margin: 20px 0;">
                        <h5 style="color: #fbbf24; margin-bottom: 15px;">⚠️ Cost Changes Detected</h5>`;
                
                // Add overpayment warning if detected
                if (hasOverpayment) {
                    breakdownHtml += `
                        <div style="background: rgba(220, 38, 38, 0.15); border: 2px solid #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="color: #fca5a5; margin-bottom: 10px;">⚠️ OVERPAYMENT DETECTED</h6>
                            <p style="margin: 0; color: #fecaca; font-weight: 500;">
                                The new costs are LOWER than what you've already paid. 
                                Automatic adjustment cannot reverse payments. 
                                <strong style="color: #fee2e2;">You must manually handle refunds or credits in the Profit/Loss page.</strong>
                            </p>
                        </div>`;
                }
                
                breakdownHtml += `
                        <div style="background: rgba(251, 191, 36, 0.15); border: 1px solid #fbbf24; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 style="margin-bottom: 10px; color: #fcd34d;">Supplier Costs:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Previous Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.supplier_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.supplier_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #f59e0b;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.supplier_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.supplier_rm >= 0 ? '+' : ''}${formatRM(adj.differences.supplier_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; margin-top: 10px; border: 1px solid rgba(251, 191, 36, 0.3);">
                                <div style="color: #fcd34d;">💰 Payments Made: ${formatRM(adj.payments_made.supplier_rm)}</div>
                                <div style="color: ${adj.new_balances.supplier_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.supplier_rm)}</strong>
                                    ${adj.new_balances.supplier_rm > 0.01 ? ' (Underpaid)' : adj.new_balances.supplier_rm < -0.01 ? ' (⚠️ OVERPAID - Refund Needed)' : ' (Fully Paid)'}
                                </div>
                            </div>
                        </div>
                        
                        <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid #60a5fa; padding: 15px; border-radius: 8px;">
                            <h6 style="margin-bottom: 10px; color: #93c5fd;">Shipping Costs:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Previous Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.shipping_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Cost:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.shipping_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #3b82f6;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.shipping_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.shipping_rm >= 0 ? '+' : ''}${formatRM(adj.differences.shipping_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; margin-top: 10px; border: 1px solid rgba(96, 165, 250, 0.3);">
                                <div style="color: #93c5fd;">💰 Payments Made: ${formatRM(adj.payments_made.shipping_rm)}</div>
                                <div style="color: ${adj.new_balances.shipping_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.shipping_rm)}</strong>
                                    ${adj.new_balances.shipping_rm > 0.01 ? ' (Underpaid)' : adj.new_balances.shipping_rm < -0.01 ? ' (⚠️ OVERPAID - Refund Needed)' : ' (Fully Paid)'}
                                </div>
                            </div>
                        </div>`;
                
                // Add commission section if commission changes detected
                if (adj.old_costs.commission_rm > 0 || adj.new_costs.commission_rm > 0) {
                    breakdownHtml += `
                        <div style="background: rgba(139, 92, 246, 0.15); border: 1px solid #a78bfa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                            <h6 style="margin-bottom: 10px; color: #c4b5fd;">Staff Commission:</h6>
                            <table style="width: 100%; margin-bottom: 10px;">
                                <tr>
                                    <td>Previous Commission:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.old_costs.commission_rm)}</strong></td>
                                </tr>
                                <tr>
                                    <td>New Commission:</td>
                                    <td style="text-align: right;"><strong>${formatRM(adj.new_costs.commission_rm)}</strong></td>
                                </tr>
                                <tr style="border-top: 2px solid #6366f1;">
                                    <td><strong>Difference:</strong></td>
                                    <td style="text-align: right; color: ${adj.differences.commission_rm >= 0 ? '#dc2626' : '#16a34a'};">
                                        <strong>${adj.differences.commission_rm >= 0 ? '+' : ''}${formatRM(adj.differences.commission_rm)}</strong>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(0, 0, 0, 0.2); padding: 10px; border-radius: 4px; margin-top: 10px; border: 1px solid rgba(167, 139, 250, 0.3);">
                                <div style="color: #c4b5fd;">💰 Commission Paid: ${formatRM(adj.payments_made.commission_rm || 0)}</div>
                                <div style="color: ${adj.new_balances.commission_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 500;">
                                    📊 New Balance: <strong>${formatRM(adj.new_balances.commission_rm)}</strong>
                                    ${adj.new_balances.commission_rm > 0.01 ? ' (Unpaid)' : adj.new_balances.commission_rm < -0.01 ? ' (⚠️ OVERPAID - Manual Adjustment Needed)' : ' (Fully Paid)'}
                                </div>
                            </div>
                        </div>`;
                }
                
                breakdownHtml += `
                        <div style="background: rgba(75, 85, 99, 0.3); border: 1px solid #9ca3af; padding: 15px; border-radius: 8px; margin-top: 15px; text-align: center;">
                            <h5 style="margin: 0; color: ${adj.new_balances.total_rm >= 0 ? '#fca5a5' : '#86efac'}; font-weight: 600;">
                                Total Balance: ${formatRM(adj.new_balances.total_rm)}
                            </h5>
                        </div>
                    </div>
                `;
                
                Swal.fire({
                    title: '⚖️ Payment Adjustment Required',
                    html: breakdownHtml + `
                        <p style="margin-top: 15px; font-size: 14px; color: #6b7280;">
                            ${hasOverpayment ? 
                                '⚠️ <strong>Overpayments cannot be automatically reversed.</strong> You can view and manually adjust in Profit/Loss.' : 
                                'Would you like to automatically create adjustment payments to match the new costs?'}
                        </p>
                    `,
                    icon: hasOverpayment ? 'error' : 'warning',
                    width: '700px',
                    showDenyButton: !hasOverpayment,
                    showCancelButton: true,
                    confirmButtonText: hasOverpayment ? '👁️ View in Profit/Loss' : '✅ Yes, Adjust Payments',
                    denyButtonText: '❌ No, I\'ll Do It Manually',
                    cancelButtonText: hasOverpayment ? 'Close' : '👁️ View in Profit/Loss',
                    confirmButtonColor: hasOverpayment ? '#8b5cf6' : '#3b82f6',
                    denyButtonColor: '#6b7280',
                    cancelButtonColor: hasOverpayment ? '#6b7280' : '#8b5cf6'
                }).then((result) => {
                    if (result.isConfirmed && !hasOverpayment) {
                        // Create automatic adjustments
                        createAutomaticAdjustments(invoiceId, adj);
                    } else if (result.isConfirmed && hasOverpayment) {
                        // Go to profit/loss page for manual handling
                        window.location.href = 'profit_loss.php';
                    } else if (result.isDenied) {
                        // User will adjust manually
                        Swal.fire({
                            title: 'No Adjustment Made',
                            html: `
                                <p>Invoice updated successfully!</p>
                                <p style="color: #f59e0b; margin-top: 10px;">
                                    ⚠️ Please remember to manually adjust payments in the Profit/Loss page.
                                </p>
                            `,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'view_order_tabs.php';
                        });
                    } else {
                        // Go to profit/loss page
                        window.location.href = 'profit_loss.php';
                    }
                });
            }

            // Function to create automatic payment adjustments
            function createAutomaticAdjustments(invoiceId, adj) {
                console.log('=== CREATE AUTOMATIC ADJUSTMENTS ===');
                console.log('Adjustment data received:', JSON.stringify(adj, null, 2));
                console.log('Commission difference:', adj.differences?.commission_rm);
                
                Swal.fire({
                    title: 'Creating Adjustments...',
                    text: 'Please wait while we process the payment adjustments.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const adjustments = [];
                
                // Add supplier adjustment if needed
                if (Math.abs(adj.differences.supplier_rm) > 0.01) {
                    console.log('Adding supplier adjustment:', adj.differences.supplier_rm);
                    adjustments.push({
                        type: 'supplier',
                        amount: adj.differences.supplier_rm,
                        endpoint: 'add_payment_adjustment'
                    });
                } else {
                    console.log('Supplier adjustment NOT added:', adj.differences.supplier_rm);
                }
                
                // Add shipping adjustment if needed
                if (Math.abs(adj.differences.shipping_rm) > 0.01) {
                    console.log('Adding shipping adjustment:', adj.differences.shipping_rm);
                    adjustments.push({
                        type: 'shipping',
                        amount: adj.differences.shipping_rm,
                        endpoint: 'add_payment_adjustment'
                    });
                } else {
                    console.log('Shipping adjustment NOT added:', adj.differences.shipping_rm);
                }
                
                // Add commission adjustment if needed (uses different endpoint)
                if (Math.abs(adj.differences.commission_rm) > 0.01) {
                    console.log('Adding commission adjustment:', adj.differences.commission_rm);
                    adjustments.push({
                        type: 'commission',
                        amount: adj.differences.commission_rm,
                        endpoint: 'pay_staff_commission'
                    });
                } else {
                    console.log('Commission adjustment NOT added:', adj.differences.commission_rm);
                }

                console.log('Total adjustments to process:', adjustments.length);
                console.log('Adjustments array:', JSON.stringify(adjustments, null, 2));

                // Process adjustments sequentially
                let promise = Promise.resolve();
                const results = [];
                
                adjustments.forEach(adjustment => {
                    promise = promise.then(() => {
                        console.log('Processing adjustment:', adjustment);
                        const formData = new FormData();
                        formData.append('invoice_id', invoiceId);
                        
                        if (adjustment.endpoint === 'add_payment_adjustment') {
                            // Supplier or Shipping adjustment
                            formData.append('adjustment_type', adjustment.type);
                            formData.append('amount', adjustment.amount);
                            formData.append('description', 'Automatic adjustment after order update');
                            
                            console.log('Calling add_payment_adjustment for:', adjustment.type, 'Amount:', adjustment.amount);
                            console.log('FormData contents:', {
                                invoice_id: formData.get('invoice_id'),
                                adjustment_type: formData.get('adjustment_type'),
                                amount: formData.get('amount'),
                                description: formData.get('description')
                            });
                            
                            return fetch('../private/profit_loss_backend.php?action=add_payment_adjustment', {
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
                                console.log('Raw response text:', text);
                                try {
                                    const data = JSON.parse(text);
                                    console.log('Payment adjustment response for', adjustment.type + ':', data);
                                    results.push({ type: adjustment.type, success: data.success, data: data, error: data.error });
                                    if (!data.success) {
                                        console.error('Adjustment failed for', adjustment.type + ':', data.error);
                                    }
                                    return data;
                                } catch (e) {
                                    console.error('JSON parse error for', adjustment.type + ':', e, 'Text:', text);
                                    results.push({ type: adjustment.type, success: false, error: 'Invalid JSON response: ' + text });
                                    return { success: false, error: 'Invalid JSON response' };
                                }
                            })
                            .catch(error => {
                                console.error('Error in payment adjustment for', adjustment.type + ':', error);
                                results.push({ type: adjustment.type, success: false, error: error.message });
                                return { success: false, error: error.message }; // Return instead of throw to continue chain
                            });
                        } else if (adjustment.endpoint === 'pay_staff_commission') {
                            // Commission adjustment - only if amount is positive (additional commission due)
                            if (adjustment.amount > 0.01) {
                                formData.append('amount', adjustment.amount);
                                formData.append('notes', 'Automatic commission adjustment after order update');
                                
                                console.log('Calling pay_staff_commission with amount:', adjustment.amount);
                                
                                return fetch('../private/profit_loss_backend.php?action=pay_staff_commission', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => {
                                    console.log('Commission response status:', response.status);
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Commission payment response:', data);
                                    results.push({ type: 'commission', success: data.success, data: data, error: data.error });
                                    if (!data.success) {
                                        console.error('Commission adjustment failed:', data.error);
                                    }
                                    return data;
                                })
                                .catch(error => {
                                    console.error('Error in commission payment:', error);
                                    results.push({ type: 'commission', success: false, error: error.message });
                                    return { success: false, error: error.message }; // Return instead of throw to continue chain
                                });
                            } else {
                                // For negative or zero commission adjustments, skip
                                console.log('Commission adjustment skipped - amount not positive:', adjustment.amount);
                                results.push({ type: 'commission', success: true, skipped: true });
                                return Promise.resolve({ success: true, skipped: true });
                            }
                        }
                    });
                });

                promise.then(() => {
                    console.log('All adjustments processed. Results:', results);
                    
                    // Check if all adjustments succeeded
                    const failed = results.filter(r => !r.success && !r.skipped);
                    const succeeded = results.filter(r => r.success);
                    
                    if (failed.length > 0) {
                        console.error('Some adjustments failed:', failed);
                        Swal.fire({
                            title: 'Partial Success',
                            html: `
                                <p>Invoice updated successfully, but some payment adjustments failed:</p>
                                <ul class="text-start mt-3">
                                    ${succeeded.map(r => `<li class="text-success">✓ ${r.type} adjustment successful</li>`).join('')}
                                    ${failed.map(r => `<li class="text-danger">✗ ${r.type} adjustment failed: ${r.error || 'Unknown error'}</li>`).join('')}
                                </ul>
                                <p class="mt-3">Please adjust payments manually in the Profit & Loss page.</p>
                            `,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'view_order_tabs.php';
                        });
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            html: `
                                <p>Invoice updated and payment adjustments created successfully!</p>
                                <p style="color: #16a34a; margin-top: 10px;">
                                    ✅ All payment balances have been adjusted automatically.
                                </p>
                                <ul class="text-start mt-3">
                                    ${succeeded.map(r => `<li class="text-success">${r.type}: ${r.skipped ? 'No adjustment needed' : 'Adjusted successfully'}</li>`).join('')}
                                </ul>
                            `,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'view_order_tabs.php';
                        });
                    }
                }).catch(error => {
                    console.error('Adjustment error:', error);
                    console.log('Partial results before error:', results);
                    
                    const succeeded = results.filter(r => r.success);
                    
                    Swal.fire({
                        title: 'Partial Success',
                        html: `
                            <p>Invoice updated but there was an error creating automatic adjustments:</p>
                            ${succeeded.length > 0 ? `
                                <p class="mt-2"><strong>Successful adjustments:</strong></p>
                                <ul class="text-start">
                                    ${succeeded.map(r => `<li class="text-success">✓ ${r.type}</li>`).join('')}
                                </ul>
                            ` : ''}
                            <p class="text-danger mt-2">${error.message}</p>
                            <p style="color: #f59e0b; margin-top: 10px;">
                                ⚠️ Please adjust remaining payments manually in the Profit/Loss page.
                            </p>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'view_order_tabs.php';
                    });
                });
            }
        </script>
    </body>
</html>