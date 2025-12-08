<?php
include __DIR__ . '/../private/forms-price-add-new-backend.php';
include __DIR__ . '/../include/header.php';

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
                        <?php if ($successMessage || $errorMessage): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($successMessage): ?>
            Swal.fire({
                title: 'Success!',
                text: <?= json_encode($successMessage) ?>,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            Swal.fire({
                title: 'Error!',
                text: <?= json_encode($errorMessage) ?>,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>
<?php endif; ?>

                        <form method="POST">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1"> </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="live-preview">

                                            <!-- Product Pricing -->
                                            <div class="row g-3 mt-2">
                                                <h5 class="text-center fw-bold fs-2 mt-0 mb-3">Product Pricing</h5>

                                                <!-- Dropdowns (unchanged) -->
                            <div class="col-sm-3">
                                <select class="form-select" id="section">
                                    <option disabled selected>Choose Section...</option>
                                    <?php foreach ($sections as $s): ?>
                                        <option value="<?= $s['section_id'] ?>"><?= $s['section_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-select" id="category">
                                    <option disabled selected>Choose Category...</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['category_id'] ?>" data-section="<?= $c['section_id'] ?>">
                                            <?= $c['category_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-select" id="subcategory">
                                    <option disabled selected>Choose Subcategory...</option>
                                    <?php foreach ($subcategories as $sc): ?>
                                        <option value="<?= $sc['subcategory_id'] ?>" data-category="<?= $sc['category_id'] ?>">
                                            <?= $sc['subcategory_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-select" id="product" name="product_id" required>
                                    <option disabled selected>Choose Product...</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['product_id'] ?>"
                                            data-section="<?= $p['section_id'] ?>"
                                            data-category="<?= $p['category_id'] ?>"
                                            data-subcategory="<?= $p['subcategory_id'] ?>"
                                            data-supplier-id="<?= $p['supplier_id'] ?>"
                                            data-supplier-name="<?= htmlspecialchars($p['supplier_name']) ?>"
                                        ><?= $p['display_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                                            </div>

                                            <!-- Inputs arranged 3 per row -->
                                            <div class="row g-3 mt-3">
                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="text" id="supplier_name" class="form-control" placeholder="Supplier" readonly>
                                                        <label>Supplier</label>
                                                    </div>
                                                    <input type="hidden" id="supplier_id" name="supplier_id">
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_price_yen" placeholder="Price (YEN)" required>
                                                        <label>Price (YEN)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" class="form-control" name="new_moq_quantity" placeholder="MOQ (Quantity)" required>
                                                        <label>MOQ (Quantity)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_shipping_moq_yen" placeholder="Shipping / MOQ (YEN)" required>
                                                        <label>Shipping / MOQ (YEN)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_additional_price_moq_yen" placeholder="Additional Price / MOQ (YEN)" required>
                                                        <label>Additional Price / MOQ (YEN)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" 
                                                               id="conversion_rate" 
                                                               value="<?= number_format($currentConversionRate, 3) ?>" 
                                                               readonly>
                                                        <label>Conversion Rate (Auto-fetched)</label>
                                                    </div>
                                                    <input type="hidden" name="new_conversion_rate" value="<?= $currentConversionRate ?>">
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_unit_price_yen" placeholder="Unit Price (YEN)" readonly>
                                                        <label>Unit Price (YEN)</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Divider -->
                                            <div class="row mt-5">
                                                <div class="col-12 text-center">
                                                    <hr style="width: 95%; height:1px; border:none; background-color:#000; margin:0.5rem auto;">
                                                </div>
                                            </div>

                                            <!-- Freight -->
                                            <div class="row g-3 mt-4 align-items-end">
                                                <h5 class="text-center fw-bold fs-2 mt-2 mb-3">Freight</h5>

                                            <div class="col-lg-4">
                                                <div class="form-floating">
                                                    <select class="form-select" name="new_freight_method" id="freight_method" required>
                                                        <option disabled selected>Choose Freight Method...</option>
                                                        <?php foreach($shipping_methods as $ship): ?>
                                                            <option value="<?= $ship['shipping_code'] ?>" data-freight-rate="<?= $ship['freight_rate'] ?>">
                                                                <?= $ship['shipping_code'] ?> - <?= htmlspecialchars($ship['shipping_name']) ?> (RM <?= number_format($ship['freight_rate'], 2) ?><?= strpos($ship['shipping_code'], 'air') !== false || strpos($ship['shipping_code'], 'AIR') !== false ? '/KG' : '/CBM' ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <label>Freight Method</label>
                                                </div>
                                            </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_total_cbm_moq" placeholder="Total CBM / MOQ (m³)" readonly>
                                                        <label>Total CBM / MOQ (m³)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_total_weight_moq" placeholder="Total Weight / MOQ (kg)" readonly>
                                                        <label>Total Weight / MOQ (kg)</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Divider -->
                                            <div class="row mt-5">
                                                <div class="col-12 text-center">
                                                    <hr style="width: 95%; height:1px; border:none; background-color:#000; margin:0.5rem auto;">
                                                </div>
                                            </div>

                                            <!-- Final Pricing -->
                                            
                                            <div class="row g-3 mt-4">
                                            <h5 class="text-center fw-bold fs-2 mt-2 mb-3">Final Pricing</h5>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" 
                                                               name="selling_price_unit" id="selling_price_unit" 
                                                               placeholder="Selling Price (RM)">
                                                        <label>Selling Price (RM)</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="display_cbm_rate" id="display_cbm_rate" placeholder="Choosen CBM Rate" readonly>
                                                        <label>Choosen CBM Rate</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="display_total_cbm" id="display_total_cbm" placeholder="Total CBM" readonly>
                                                        <label>Total CBM</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_unit_price_rm" placeholder="Unit Price (RM)" readonly>
                                                        <label>Unit Price (RM)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_unit_freight_cost_rm" placeholder="Unit Freight Cost (RM)" readonly>
                                                        <label>Unit Freight Cost (RM)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.001" class="form-control" name="new_unit_profit_rm" placeholder="Unit Profit (RM)" readonly>
                                                        <label>Unit Profit (RM)</label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-12 mt-5 text-center">
                                                <button type="submit" class="btn btn-primary">Save Pricing</button>
                                                <a href="table-product-list.php" class="btn btn-secondary" onclick="localStorage.setItem('productTableFromEdit', 'true');">Cancel</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>




                                                  



                        

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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="assets/css/select2-dark-theme.css">

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
<script>
// --- Cascading Dropdowns with Select2 & Product Filtering ---
document.addEventListener('DOMContentLoaded', function () {
    const sectionSelect = document.getElementById('section');
    const categorySelect = document.getElementById('category');
    const subcategorySelect = document.getElementById('subcategory');
    const productSelect = document.getElementById('product');
    const supplierNameInput = document.getElementById('supplier_name');
    const supplierIdInput = document.getElementById('supplier_id');

    if (!sectionSelect || !categorySelect || !subcategorySelect || !productSelect) return;

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
            supplierId: opt.dataset.supplierId || '',
            supplierName: opt.dataset.supplierName || ''
        }));

    // Initialize Select2 with custom styling and search placeholders
    function initSelect2() {
        $('#section').select2({
            placeholder: 'Choose Section...',
            allowClear: false,
            width: '100%',
            language: {
                searching: function() {
                    return 'Searching sections...';
                },
                inputTooShort: function() {
                    return 'Type to search sections';
                }
            }
        });
        
        $('#category').select2({
            placeholder: 'Choose Category...',
            allowClear: false,
            width: '100%',
            language: {
                searching: function() {
                    return 'Searching categories...';
                },
                inputTooShort: function() {
                    return 'Type to search categories';
                }
            }
        });
        
        $('#subcategory').select2({
            placeholder: 'Choose Subcategory...',
            allowClear: false,
            width: '100%',
            language: {
                searching: function() {
                    return 'Searching subcategories...';
                },
                inputTooShort: function() {
                    return 'Type to search subcategories';
                }
            }
        });
        
        $('#product').select2({
            placeholder: 'Choose Product...',
            allowClear: false,
            width: '100%',
            language: {
                searching: function() {
                    return 'Searching products...';
                },
                inputTooShort: function() {
                    return 'Type to search products';
                }
            }
        });
    }

    // Call initialization
    initSelect2();

    // Add custom placeholder text to search fields when dropdowns open
    $('#section').on('select2:open', function() {
        setTimeout(function() {
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.placeholder = 'Search sections...';
            }
        }, 1);
    });

    $('#category').on('select2:open', function() {
        setTimeout(function() {
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.placeholder = 'Search categories...';
            }
        }, 1);
    });

    $('#subcategory').on('select2:open', function() {
        setTimeout(function() {
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.placeholder = 'Search subcategories...';
            }
        }, 1);
    });

    $('#product').on('select2:open', function() {
        setTimeout(function() {
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.placeholder = 'Search products...';
            }
        }, 1);
    });

    function clearProductAndSupplier() {
        $('#product').val('').trigger('change');
        supplierNameInput.value = '';
        supplierIdInput.value = '';
    }

    function rebuildCategoryChoices() {
        const section = sectionSelect.value;
        $('#category').empty().append('<option value="">Choose Category...</option>');
        const filtered = allCategories.filter(c => !section || c.section === section);
        filtered.forEach(c => {
            $('#category').append(`<option value="${c.value}">${c.label}</option>`);
        });
        $('#category').trigger('change');
        
        $('#subcategory').empty().append('<option value="">Choose Subcategory...</option>');
        $('#subcategory').trigger('change');
        clearProductAndSupplier();
    }

    function rebuildSubcategoryChoices() {
        const category = categorySelect.value;
        $('#subcategory').empty().append('<option value="">Choose Subcategory...</option>');
        const filtered = allSubcategories.filter(sc => !category || sc.category === category);
        filtered.forEach(sc => {
            $('#subcategory').append(`<option value="${sc.value}">${sc.label}</option>`);
        });
        $('#subcategory').trigger('change');
        clearProductAndSupplier();
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

        $('#product').empty().append('<option value="">Choose Product...</option>');
        filtered.forEach(p => {
            $('#product').append(`<option value="${p.value}">${p.label}</option>`);
        });
        $('#product').trigger('change');
        clearProductAndSupplier();
    }

    $('#section').on('change', function () {
        rebuildCategoryChoices();
        rebuildProductChoices();
    });
    
    $('#category').on('change', function () {
        rebuildSubcategoryChoices();
        rebuildProductChoices();
    });
    
    $('#subcategory').on('change', rebuildProductChoices);

    $('#product').on('change', function () {
        const selectedValue = $(this).val();
        const meta = allProducts.find(p => p.value === selectedValue);
        if (meta) {
            supplierNameInput.value = meta.supplierName || '';
            supplierIdInput.value = meta.supplierId || '';
        } else {
            supplierNameInput.value = '';
            supplierIdInput.value = '';
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const productSelect = document.getElementById('product');
    const moqInput = document.querySelector('input[name="new_moq_quantity"]');
    const priceYenInput = document.querySelector('input[name="new_price_yen"]');
    const additionalFeeInput = document.querySelector('input[name="new_additional_price_moq_yen"]');
    const unitPriceYenInput = document.querySelector('input[name="new_unit_price_yen"]');
    const conversionRateInput = document.getElementById('conversion_rate');
    const totalCbmField = document.querySelector('input[name="new_total_cbm_moq"]');
    const totalWeightField = document.querySelector('input[name="new_total_weight_moq"]');
    const freightSelect = document.getElementById('freight_method');
    const displayCbmRateField = document.getElementById('display_cbm_rate');
    const displayTotalCBMField = document.getElementById('display_total_cbm');
    const unitPriceRMField = document.querySelector('input[name="new_unit_price_rm"]');
    const unitFreightRMField = document.querySelector('input[name="new_unit_freight_cost_rm"]');
    const unitProfitRMField = document.querySelector('input[name="new_unit_profit_rm"]');
    const sellingPriceField = document.getElementById('selling_price_unit');

    // Set conversion rate from database
    const conversionRate = <?= $currentConversionRate ?>;
    conversionRateInput.value = conversionRate.toFixed(3);

    const productCartonData = {};
    <?php foreach($products as $p): ?>
    productCartonData[<?= $p['product_id'] ?>] = {
        pcs_per_carton: <?= $p['pcs_per_carton'] ?? 0 ?>,
        cbm_carton: <?= $p['cbm_carton'] ?? 0 ?>,
        carton_weight: <?= $p['carton_weight'] ?? 0 ?>,
        add_cartons: [
            <?php for($i=1;$i<=6;$i++): ?>
            {
                pcs: <?= $p["add_carton{$i}_pcs"] ?? 0 ?>,
                cbm: <?= $p["add_carton{$i}_total_cbm"] ?? 0 ?>,
                weight: <?= $p["add_carton{$i}_weight"] ?? 0 ?>
            }<?= $i<6 ? ',' : '' ?>
            <?php endfor; ?>
        ]
    };
    <?php endforeach; ?>

    let selectedCartonData = null;
    
    function calculateUnitPriceYen() {
        const priceYen = parseFloat(priceYenInput.value) || 0;
        const moq = parseInt(moqInput.value) || 0;
        const shippingMoq = parseFloat(document.querySelector('input[name="new_shipping_moq_yen"]').value) || 0;
        const additionalFee = parseFloat(additionalFeeInput.value) || 0;
        
        if (moq > 0) {
            const amount1 = priceYen * moq;
            const amount2 = amount1 + shippingMoq + additionalFee;
            const unitPrice = amount2 / moq;
            unitPriceYenInput.value = unitPrice.toFixed(3);
        } else {
            unitPriceYenInput.value = '';
        }
    }

    function calculateTotals() {
        console.log('calculateTotals called');
        console.log('selectedCartonData:', selectedCartonData);
        
        if (!selectedCartonData) {
            console.log('No carton data selected');
            return;
        }
        
        const moq = parseInt(moqInput.value) || 0;
        if (moq <= 0) {
            console.log('MOQ is 0 or negative:', moq);
            return;
        }

        console.log('MOQ:', moq);
        calculateUnitPriceYen();

        // Calculate total CBM and weight
        let totalCartons = Math.ceil(moq / Math.max(1, selectedCartonData.pcs_per_carton));
        let totalCBM = totalCartons * selectedCartonData.cbm_carton;
        let totalWeight = totalCartons * selectedCartonData.carton_weight;

        selectedCartonData.add_cartons.forEach(ac => {
            if (ac.pcs > 0) {
                let extraCartons = Math.ceil(moq / ac.pcs);
                totalCBM += extraCartons * ac.cbm;
                totalWeight += extraCartons * ac.weight;
            }
        });

        console.log('Total CBM:', totalCBM, 'Total Weight:', totalWeight);

        totalCbmField.value = totalCBM.toFixed(3);
        totalWeightField.value = totalWeight.toFixed(3);
        displayTotalCBMField.value = totalCBM.toFixed(3);

        // Get freight rate from selected option
        const freightOption = freightSelect.selectedOptions[0];
        if (!freightOption) {
            console.log('No freight option selected');
            return;
        }
        
        const freightRate = parseFloat(freightOption.dataset.freightRate) || 0;
        console.log('Freight rate:', freightRate);
        displayCbmRateField.value = freightRate.toFixed(3);

        const unitPriceYen = parseFloat(unitPriceYenInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceField.value) || 0;

        // Determine if air freight based on shipping code
        const shippingCode = freightOption.value.toLowerCase();
        const isAirFreight = shippingCode.includes('air');

        console.log('Shipping code:', shippingCode, 'Is air freight:', isAirFreight);

        // Calculate freight cost based on method
        const totalFreightCost = isAirFreight 
            ? freightRate * totalWeight 
            : freightRate * totalCBM;

        const totalSupplierPrice = unitPriceYen * moq / conversionRate;
        const totalPrice = totalFreightCost + totalSupplierPrice;
        const unitPriceRM = totalPrice / moq;
        const unitFreightRM = totalFreightCost / moq;
        const unitProfitRM = sellingPrice - unitPriceRM;

        console.log('Final calculations:', {
            totalFreightCost,
            totalSupplierPrice,
            totalPrice,
            unitPriceRM,
            unitFreightRM,
            unitProfitRM
        });

        unitPriceRMField.value = unitPriceRM.toFixed(3);
        unitFreightRMField.value = unitFreightRM.toFixed(3);
        unitProfitRMField.value = unitProfitRM.toFixed(3);
    }

    // Make calculateTotals globally accessible for pre-population
    window.calculateTotals = calculateTotals;
    window.selectedCartonData = selectedCartonData;

    // Use Select2 event listener instead of regular change event
    $('#product').on('change', function() {
        const pid = $(this).val();
        selectedCartonData = productCartonData[pid] || null;
        window.selectedCartonData = selectedCartonData;

        // Clear previous calculations
        totalCbmField.value = '';
        totalWeightField.value = '';
        unitPriceRMField.value = '';
        unitFreightRMField.value = '';
        unitProfitRMField.value = '';
        displayCbmRateField.value = '';
        displayTotalCBMField.value = '';
        unitPriceYenInput.value = '';

        if ((parseInt(moqInput.value) || 0) > 0) {
            calculateTotals();
        }
    });

    // Event listeners - removed conversion rate listener since it's readonly
    moqInput.addEventListener('input', calculateTotals);
    priceYenInput.addEventListener('input', calculateTotals);
    additionalFeeInput.addEventListener('input', calculateTotals);
    document.querySelector('input[name="new_shipping_moq_yen"]').addEventListener('input', calculateTotals);
    // Use jQuery for freight select to ensure compatibility
    $(freightSelect).on('change', calculateTotals);
    sellingPriceField.addEventListener('input', calculateTotals);

    // Format decimal inputs to 3 decimal places when user finishes editing
    const decimalInputs = [
        priceYenInput,
        additionalFeeInput,
        document.querySelector('input[name="new_shipping_moq_yen"]'),
        sellingPriceField
    ];
    
    decimalInputs.forEach(input => {
        if (input) {
            input.addEventListener('blur', function() {
                if (this.value && !isNaN(this.value)) {
                    this.value = parseFloat(this.value).toFixed(3);
                }
            });
        }
    });
});
</script>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value) {
            isValid = false;
            Swal.fire({
                title: 'Error!',
                text: `Please fill in ${field.placeholder}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
    
    if (!isValid) return;

    // Show confirmation dialog
    Swal.fire({
        title: 'Save Pricing',
        text: 'Are you sure you want to save this pricing information?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, save it!',
        cancelButtonText: 'No, cancel!',
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});

// Replace any existing alerts in your calculation scripts with SweetAlert2
function showError(message) {
    Swal.fire({
        title: 'Error!',
        text: message,
        icon: 'error',
        confirmButtonText: 'OK'
    });
}

// Example usage in your calculation script:
if (moq <= 0) {
    showError('MOQ must be greater than 0');
    return;
}
</script>

<script>
// Pre-populate form data if product_id is passed via URL
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($prePopulateData): ?>
    
    // Pre-populate data from backend
    const prePopData = <?= json_encode($prePopulateData) ?>;
    
    console.log('Pre-populating data:', prePopData);
    
    // Wait for Select2 to initialize
    setTimeout(function() {
        console.log('Starting pre-population with data:', prePopData);
        
        console.log('Setting section:', prePopData.section_id);
        if (prePopData.section_id) {
            $('#section').val(prePopData.section_id).trigger('change');
        }
        
        setTimeout(function() {
            console.log('Setting category:', prePopData.category_id);
            if (prePopData.category_id) {
                $('#category').val(prePopData.category_id).trigger('change');
            }
            
            setTimeout(function() {
                console.log('Setting subcategory:', prePopData.subcategory_id);
                if (prePopData.subcategory_id) {
                    $('#subcategory').val(prePopData.subcategory_id).trigger('change');
                }
                
                setTimeout(function() {
                    console.log('Setting product:', prePopData.product_id);
                    if (prePopData.product_id) {
                        $('#product').val(prePopData.product_id).trigger('change');
                    }
                    
                    // Populate all the pricing fields
                    setTimeout(function() {
                        console.log('Populating pricing fields...');
                        populatePricingFields(prePopData);
                    }, 500);
                }, 500);
            }, 500);
        }, 500);
    }, 1000); // Reduced delay since Select2 is simpler than Choices.js
    
    function populatePricingFields(data) {
        console.log('Starting field population with data:', data);
        
        // Supplier info
        const supplierNameInput = document.getElementById('supplier_name');
        const supplierIdInput = document.getElementById('supplier_id');
        if (supplierNameInput && data.supplier_name) {
            supplierNameInput.value = data.supplier_name;
            console.log('Set supplier name:', data.supplier_name);
        }
        if (supplierIdInput && data.supplier_id) {
            supplierIdInput.value = data.supplier_id;
            console.log('Set supplier ID:', data.supplier_id);
        }
        
        // Price fields
        const priceYenInput = document.querySelector('input[name="new_price_yen"]');
        if (priceYenInput && data.new_price_yen) {
            priceYenInput.value = data.new_price_yen;
        }
        
        const moqInput = document.querySelector('input[name="new_moq_quantity"]');
        if (moqInput && data.new_moq_quantity) {
            moqInput.value = data.new_moq_quantity;
        }
        
        const shippingInput = document.querySelector('input[name="new_shipping_moq_yen"]');
        if (shippingInput && data.new_shipping_moq_yen) {
            shippingInput.value = data.new_shipping_moq_yen;
        }
        
        const additionalInput = document.querySelector('input[name="new_additional_price_moq_yen"]');
        if (additionalInput && data.new_additional_price_moq_yen) {
            additionalInput.value = data.new_additional_price_moq_yen;
        }
        
        const unitPriceYenInput = document.querySelector('input[name="new_unit_price_yen"]');
        if (unitPriceYenInput && data.new_unit_price_yen) {
            unitPriceYenInput.value = data.new_unit_price_yen;
        }
        
        // Freight method
        const freightSelect = document.getElementById('freight_method');
        if (freightSelect && data.new_freight_method) {
            freightSelect.value = data.new_freight_method;
        }
        
        // Final pricing - set selling price first as it's needed for calculations
        const sellingPriceInput = document.getElementById('selling_price_unit');
        if (sellingPriceInput && data.new_selling_price) {
            sellingPriceInput.value = data.new_selling_price;
        }
        
        // Don't set CBM/Weight/Pricing calculations directly - let calculateTotals() handle it
        // This ensures additional cartons are properly included
        
        console.log('Field population completed, triggering fresh calculations...');
        
        // Trigger fresh calculation to ensure all cartons (main + additional) are included
        setTimeout(function() {
            if (typeof window.calculateTotals === 'function' && window.selectedCartonData) {
                console.log('Calling calculateTotals to recalculate CBM with additional cartons...');
                console.log('Current selectedCartonData:', window.selectedCartonData);
                window.calculateTotals();
            } else {
                console.log('calculateTotals function or selectedCartonData not available yet');
                console.log('calculateTotals available:', typeof window.calculateTotals);
                console.log('selectedCartonData:', window.selectedCartonData);
            }
        }, 200);
        
        // Show success message
        Swal.fire({
            title: 'Data Loaded!',
            text: 'Existing pricing data has been loaded for this product: ' + data.display_name,
            icon: 'info',
            confirmButtonText: 'OK',
            timer: 4000,
            timerProgressBar: true
        });
    }
    
    <?php else: ?>
    
    // Handle case where product_id is passed but no pricing data exists
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('product_id');
    
    if (productId) {
        console.log('No pricing data found, but auto-selecting product:', productId);
        // Auto-select the product but show message that no pricing data exists
        setTimeout(function() {
            const productSelect = document.getElementById('product');
            if (productSelect) {
                const option = productSelect.querySelector(`option[value="${productId}"]`);
                if (option) {
                    console.log('Found product option:', option);
                    // Auto-select hierarchical dropdowns based on product data
                    const sectionId = option.dataset.section;
                    const categoryId = option.dataset.category;
                    const subcategoryId = option.dataset.subcategory;
                    
                    console.log('Product hierarchy:', { sectionId, categoryId, subcategoryId });
                    
                    if (sectionId) {
                        $('#section').val(sectionId).trigger('change');
                    }
                    
                    setTimeout(function() {
                        if (categoryId) {
                            $('#category').val(categoryId).trigger('change');
                        }
                        
                        setTimeout(function() {
                            if (subcategoryId) {
                                $('#subcategory').val(subcategoryId).trigger('change');
                            }
                            
                            setTimeout(function() {
                                $('#product').val(productId).trigger('change');
                                
                                // Show message that this is a new pricing entry
                                Swal.fire({
                                    title: 'New Pricing Entry',
                                    text: 'No existing pricing data found for this product. You can create a new pricing record.',
                                    icon: 'info',
                                    confirmButtonText: 'OK',
                                    timer: 4000,
                                    timerProgressBar: true
                                });
                            }, 500);
                        }, 500);
                    }, 500);
                } else {
                    console.error('Product option not found for ID:', productId);
                }
            }
        }, 1000);
    }
    
    <?php endif; ?>
});
</script>

</body>
</html>