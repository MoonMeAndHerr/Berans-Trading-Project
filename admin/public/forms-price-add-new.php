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
                                                        <input type="number" step="0.01" class="form-control" name="new_price_yen" placeholder="Price (YEN)" required>
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
                                                        <input type="number" step="0.01" class="form-control" name="new_shipping_moq_yen" placeholder="Shipping / MOQ (YEN)" required>
                                                        <label>Shipping / MOQ (YEN)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_additional_price_moq_yen" placeholder="Additional Price / MOQ (YEN)" required>
                                                        <label>Additional Price / MOQ (YEN)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.0001" class="form-control" 
                                                               id="conversion_rate" 
                                                               value="<?= number_format($currentConversionRate, 4) ?>" 
                                                               readonly>
                                                        <label>Conversion Rate (Auto-fetched)</label>
                                                    </div>
                                                    <input type="hidden" name="new_conversion_rate" value="<?= $currentConversionRate ?>">
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_unit_price_yen" placeholder="Unit Price (YEN)" readonly>
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
                                                        <input type="number" step="0.0001" class="form-control" name="new_total_cbm_moq" placeholder="Total CBM / MOQ (m³)" readonly>
                                                        <label>Total CBM / MOQ (m³)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_total_weight_moq" placeholder="Total Weight / MOQ (kg)" readonly>
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
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="selling_price_unit" id="selling_price_unit" 
                                                               placeholder="Selling Price (RM)">
                                                        <label>Selling Price (RM)</label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.0001" class="form-control" name="display_cbm_rate" id="display_cbm_rate" placeholder="Choosen CBM Rate" readonly>
                                                        <label>Choosen CBM Rate</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.0001" class="form-control" name="display_total_cbm" id="display_total_cbm" placeholder="Total CBM" readonly>
                                                        <label>Total CBM</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_unit_price_rm" placeholder="Unit Price (RM)" readonly>
                                                        <label>Unit Price (RM)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_unit_freight_cost_rm" placeholder="Unit Freight Cost (RM)" readonly>
                                                        <label>Unit Freight Cost (RM)</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-floating">
                                                        <input type="number" step="0.01" class="form-control" name="new_unit_profit_rm" placeholder="Unit Profit (RM)" readonly>
                                                        <label>Unit Profit (RM)</label>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-12 mt-5 text-center">
                                                <button type="submit" class="btn btn-primary">Save Pricing</button>
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

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
<script>
// --- Cascading Dropdowns with Choices.js & Product Filtering ---
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

    const baseCfg = { searchEnabled: true, shouldSort: false, itemSelectText: '', placeholder: true };
    const chSection = new Choices(sectionSelect, baseCfg);
    const chCategory = new Choices(categorySelect, baseCfg);
    const chSubcat = new Choices(subcategorySelect, baseCfg);
    const chProduct = new Choices(productSelect, baseCfg);

    function clearProductAndSupplier() {
        chProduct.removeActiveItems();
        productSelect.value = '';
        supplierNameInput.value = '';
        supplierIdInput.value = '';
    }

    function rebuildCategoryChoices() {
        const section = sectionSelect.value;
        chCategory.clearChoices();
        const filtered = allCategories.filter(c => !section || c.section === section);
        chCategory.setChoices([{ value: '', label: 'Choose Category...', disabled: true }], 'value', 'label', false);
        chCategory.setChoices(filtered.map(c => ({ value: c.value, label: c.label })), 'value', 'label', true);
        chSubcat.clearChoices();
        chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true }], 'value', 'label', false);
        clearProductAndSupplier();
    }

    function rebuildSubcategoryChoices() {
        const category = categorySelect.value;
        chSubcat.clearChoices();
        const filtered = allSubcategories.filter(sc => !category || sc.category === category);
        chSubcat.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true }], 'value', 'label', false);
        chSubcat.setChoices(filtered.map(sc => ({ value: sc.value, label: sc.label })), 'value', 'label', true);
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

        chProduct.clearChoices();
        chProduct.setChoices([{ value: '', label: 'Choose Product...', disabled: true }], 'value', 'label', false);
        chProduct.setChoices(filtered.map(p => ({ value: p.value, label: p.label })), 'value', 'label', true);
        clearProductAndSupplier();
    }

    sectionSelect.addEventListener('change', function () {
        rebuildCategoryChoices();
        rebuildProductChoices();
    });
    categorySelect.addEventListener('change', function () {
        rebuildSubcategoryChoices();
        rebuildProductChoices();
    });
    subcategorySelect.addEventListener('change', rebuildProductChoices);

    productSelect.addEventListener('change', function () {
        const selectedValue = chProduct.getValue(true);
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
    conversionRateInput.value = conversionRate.toFixed(4);

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
            unitPriceYenInput.value = unitPrice.toFixed(2);
        } else {
            unitPriceYenInput.value = '';
        }
    }

    function calculateTotals() {
        if (!selectedCartonData) return;
        const moq = parseInt(moqInput.value) || 0;
        if (moq <= 0) return;

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

        totalCbmField.value = totalCBM.toFixed(3);
        totalWeightField.value = totalWeight.toFixed(2);
        displayTotalCBMField.value = totalCBM.toFixed(6);

        // Get freight rate from selected option
        const freightOption = freightSelect.selectedOptions[0];
        if (!freightOption) return;
        
        const freightRate = parseFloat(freightOption.dataset.freightRate) || 0;
        displayCbmRateField.value = freightRate.toFixed(4);

        const unitPriceYen = parseFloat(unitPriceYenInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceField.value) || 0;

        // Determine if air freight based on shipping code
        const shippingCode = freightOption.value.toLowerCase();
        const isAirFreight = shippingCode.includes('air');

        // Calculate freight cost based on method
        const totalFreightCost = isAirFreight 
            ? freightRate * totalWeight 
            : freightRate * totalCBM;

        const totalSupplierPrice = unitPriceYen * moq / conversionRate;
        const totalPrice = totalFreightCost + totalSupplierPrice;
        const unitPriceRM = totalPrice / moq;
        const unitFreightRM = totalFreightCost / moq;
        const unitProfitRM = sellingPrice - unitPriceRM;

        unitPriceRMField.value = unitPriceRM.toFixed(2);
        unitFreightRMField.value = unitFreightRM.toFixed(2);
        unitProfitRMField.value = unitProfitRM.toFixed(2);
    }

    productSelect.addEventListener('change', function() {
        const pid = this.value;
        selectedCartonData = productCartonData[pid] || null;

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
    freightSelect.addEventListener('change', calculateTotals);
    sellingPriceField.addEventListener('input', calculateTotals);
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







</body>


</html>
</body>

</html>