<?php

    
    include __DIR__ . '/../private/forms-new-order-backend.php';
    include __DIR__ . '/../include/header.php';

?>

        <!-- Minimal CSS for Forms -->
        <link href="assets/css/forms-new-order-minimal.css" rel="stylesheet" type="text/css" />

        <!-- Scripts -->
        <script src="assets/js/layout.js"></script>
        <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>


            <!-- Main Content -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">New Order</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Orders</a></li>
                                            <li class="breadcrumb-item active">Create New</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <form method="POST" action="" id="invoiceForm">
                            <input type="hidden" name="products" id="productsJson">
                            <div class="col-lg-12">
                                <div class="form-container-minimal">
                                    <!-- Form Header -->
                                    <div class="form-header-minimal">
                                        <h4>Create New Order</h4>
                                        <div class="subtitle">Add products, select customer, and generate invoice</div>
                                    </div>

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
                                                        <option value="" disabled selected>Select Customer...</option>
                                                        <?php foreach ($customers as $c): ?>
                                                            <option value="<?= $c['customer_id'] ?>"><?= $c['customer_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="selected_staff">Commission Staff</label>
                                                    <select class="form-select" id="selected_staff" name="selected_staff">
                                                        <option value="" disabled selected>Select Staff...</option>
                                                        <?php foreach ($staff as $s): ?>
                                                            <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['staff_name']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-field-minimal">
                                                    <label for="staff_commission_percentage">Commission Percentage</label>
                                                    <div class="input-group-minimal">
                                                        <input type="number" class="form-control" id="staff_commission_percentage" name="staff_commission_percentage" 
                                                               min="0" max="100" step="0.1" placeholder="0.0">
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
                                                        <tr class="empty-state-minimal">
                                                            <td colspan="6">
                                                                <div class="empty-icon">
                                                                    <i class="ri-shopping-cart-line"></i>
                                                                </div>
                                                                <div class="empty-text">No products added yet</div>
                                                                <div class="empty-subtext">Add products above to see them here</div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Section -->
                                    <div class="submit-section-minimal">
                                        <button type="submit" class="submit-button-minimal" onclick="return prepareSubmission()">
                                            <i class="ri-file-add-line"></i>
                                            Create Invoice
                                        </button>
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
                window.productCount = 1; // Make it global so it can be reset

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

                    // Debug logging
                    console.log('Selected product data:', {
                        price: productData.price,
                        moq: productData.moq,
                        priceId: productData.priceId
                    });

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
                        <td>${unitPrice.toFixed(2)}</td>
                        <td>${quantity}</td>
                        <td>${(unitPrice * quantity).toFixed(2)}</td>
                        <td>
                            <div class="table-actions">
                                <button type="button" class="btn-minimal btn-danger" onclick="confirmRemoveProduct(this)">
                                    <i class="ri-delete-bin-line"></i>
                                    Remove
                                </button>
                            </div>
                        </td>
                        <input type="hidden" name="products[]" value='${JSON.stringify({
                            product_id: selectedProductId,
                            price_id: productData.priceId,
                            product_name: productData.label,
                            quantity: quantity,
                            unit_price: unitPrice,
                            total_price: unitPrice * quantity
                        })}'>
                    `;

                    window.productCount++;

                    // Reset form - using Choices.js methods
                    chProduct.setChoiceByValue('');
                    quantityInput.value = '';
                    moqField.value = '';
                });

                // Initialize cascade - clear dependent dropdowns initially
                chCategory.clearChoices();
                chCategory.setChoices([{ value: '', label: 'Choose Category...', disabled: true, selected: true }]);
                chSubcat.clearChoices();
                chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true, selected: true }]);
                chProduct.clearChoices();
                chProduct.setChoices([{ value: '', label: 'Choose Product...', disabled: true, selected: true }]);
            });

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
                    }
                });
            }

            function prepareSubmission() {
                const products = [];
                const rows = document.querySelectorAll('#productList tbody tr');
                
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

                // Show confirmation before submitting
                Swal.fire({
                    title: 'Create Invoice?',
                    text: 'Are you sure you want to create this invoice?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, create it!',
                    cancelButtonText: 'No, cancel!',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Creating Invoice...',
                            text: 'Please wait while we process your request.',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Prepare products data
                        rows.forEach(row => {
                            const hiddenInput = row.querySelector('input[type="hidden"]');
                            if (hiddenInput) {
                                const product = JSON.parse(hiddenInput.value);
                                product.product_name = row.cells[1].textContent;
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
                        
                        const selectedStaff = document.getElementById('selected_staff').value;
                        const commissionPercentage = document.getElementById('staff_commission_percentage').value;
                        
                        if (selectedStaff) {
                            formData.append('selected_staff', selectedStaff);
                        }
                        if (commissionPercentage) {
                            formData.append('staff_commission_percentage', commissionPercentage);
                        }

                        // Submit via AJAX
                        fetch('', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
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
                                // Success
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message || 'Invoice created successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Reset form after success
                                    document.getElementById('invoiceForm').reset();
                                    
                                    // Reset table to empty state
                                    document.querySelector('#productList tbody').innerHTML = `
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
                                    
                                    // Reset Choices.js selectors
                                    const choicesElements = document.querySelectorAll('.choices');
                                    choicesElements.forEach(element => {
                                        const choices = element.choices;
                                        if (choices) {
                                            choices.setChoiceByValue('');
                                        }
                                    });
                                    
                                    // Reset product count
                                    window.productCount = 1;
                                });
                            } else {
                                throw new Error(data.error || 'Unknown error occurred');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'There was an error creating the invoice. Please try again.',
                                icon: 'error'
                            });
                        });
                    }
                });
                
                return false; // Always return false to prevent default form submission
            }
        </script>
    </body>
</html>