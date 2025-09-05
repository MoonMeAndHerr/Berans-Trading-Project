<?php 
    include __DIR__ . '/../include/header.php'; 
    include __DIR__ . '/../private/forms_price_update_backend.php';
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

               <form method="post" action="" id="priceUpdateForm">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($priceData['product_id'] ?? '') ?>">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($priceData['product_id'] ?? '') ?>">

                    <!-- Enter Product Price Details -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h4 class="card-title mb-0 flex-grow-1">Enter Product Price Details</h4>
                                </div>

                            <?php if (!empty($successMsg)): ?>
                                <div class="alert alert-success mt-2" role="alert">
                                    <?= htmlspecialchars($successMsg) ?>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Price ID Dropdown (trigger submit on change) -->
                                    <div class="col-sm-6">
                                        <label for="price_id" class="form-label">Price ID</label>
                                            <select name="price_id" id="price_id" class="form-select" onchange="loadPriceData(this.value)">
                                                <option value="">-- Select Price ID --</option>
                                                <?php foreach ($priceList as $p): ?>
                                                    <option value="<?= htmlspecialchars($p['price_id']) ?>"
                                                        <?= (isset($priceData['price_id']) && $priceData['price_id'] == $p['price_id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars(($p['product_name'] ?? 'Unknown Product') . ' (' . $p['price_id'] . ')') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                    </div>

                                    



                                    <!-- Supplier Dropdown -->
                                    <div class="col-sm-6">
                                        <label for="supplier_id" class="form-label">Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                                            <option value="">-- Select Supplier --</option>
                                            <?php foreach ($supplierOptions as $sup): ?>
                                                <option value="<?= htmlspecialchars($sup['supplier_id']) ?>"
                                                    <?= (isset($priceData['supplier_id']) && $priceData['supplier_id'] == $sup['supplier_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($sup['supplier_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Minimum Order Quantity -->
                                    <div class="col-sm-6">
                                        <label for="quantity" class="form-label">Minimum Order Quantity</label>
                                        <input type="number" step="1" name="quantity" id="quantity" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['quantity'] ?? '') ?>" required>
                                    </div>

                                    <!-- Carton Width -->
                                    <div class="col-sm-4">
                                        <label for="carton_width" class="form-label">Carton Width (cm)</label>
                                        <input type="number" step="0.01" name="carton_width" id="carton_width" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['carton_width'] ?? '') ?>" required>
                                    </div>

                                    <!-- Carton Height -->
                                    <div class="col-sm-4">
                                        <label for="carton_height" class="form-label">Carton Height (cm)</label>
                                        <input type="number" step="0.01" name="carton_height" id="carton_height" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['carton_height'] ?? '') ?>" required>
                                    </div>

                                    <!-- Carton Length -->
                                    <div class="col-sm-4">
                                        <label for="carton_length" class="form-label">Carton Length (cm)</label>
                                        <input type="number" step="0.01" name="carton_length" id="carton_length" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['carton_length'] ?? '') ?>" required>
                                    </div>

                                    <!-- PCS per Carton -->
                                    <div class="col-sm-6">
                                        <label for="pcs_per_carton" class="form-label">PCS per Carton</label>
                                        <input type="number" step="1" name="pcs_per_carton" id="pcs_per_carton" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['pcs_per_carton'] ?? '') ?>" required>
                                    </div>

                                    <!-- Number of Carton -->
                                    <div class="col-sm-6">
                                        <label for="no_of_carton" class="form-label">Number of Carton</label>
                                        <input type="number" step="1" name="no_of_carton" id="no_of_carton" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['no_of_carton'] ?? '') ?>" required>
                                    </div>

                                    <!-- Design & Logo Details -->
                                    <div class="col-sm-12">
                                        <label for="designlogo" class="form-label">Design & Logo Details</label>
                                        <input type="text" name="designlogo" id="designlogo" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['designlogo'] ?? '') ?>">
                                    </div>

                                    <!-- Price (Yen) -->
                                    <div class="col-sm-4">
                                        <label for="price" class="form-label">Price (Yen)</label>
                                        <input type="number" step="0.000001" name="price" id="price" class="form-control"
                                            value="<?= htmlspecialchars($priceData['price'] ?? '') ?>" required>
                                    </div>

                                    <!-- Shipping Price (Yen) -->
                                    <div class="col-sm-4">
                                        <label for="shipping_price" class="form-label">Shipping Price (Yen)</label>
                                        <input type="number" step="0.000001" name="shipping_price" id="shipping_price" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['shipping_price'] ?? '') ?>" required>
                                    </div>

                                    <!-- Additional Price (Yen) -->
                                    <div class="col-sm-4">
                                        <label for="additional_price" class="form-label">Additional Price (Yen)</label>
                                        <input type="number" step="0.000001" name="additional_price" id="additional_price" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['additional_price'] ?? '') ?>" required>
                                    </div>

                                    <!-- Weight per Carton (kg) -->
                                    <div class="col-sm-6">
                                        <label for="weight_carton" class="form-label">Weight per Carton (kg)</label>
                                        <input type="number" step="0.01" name="weight_carton" id="weight_carton" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['weight_carton'] ?? '') ?>" required>
                                    </div>

                                    <!-- Conversion Rate (Yen to MYR) -->
                                    <div class="col-sm-6">
                                        <label for="conversion_rate" class="form-label">Conversion Rate (Yen to MYR)</label>
                                        <input type="number" step="0.0001" name="conversion_rate" id="conversion_rate" class="form-control" 
                                            value="<?= htmlspecialchars($priceData['conversion_rate'] ?? '') ?>" required>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Enter Additional Carton -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">Enter Additional Carton</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <?php for ($i = 1; $i <= 6; $i++): 
                                $prefix = "add_carton{$i}_";
                            ?>
                                <div class="col-sm-12">
                                    <h5>Additional Carton <?= $i ?></h5>
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>width" class="form-label">Carton Width (W)</label>
                                    <input type="number" step="0.01" name="<?= $prefix ?>width" id="<?= $prefix ?>width" class="form-control"
                                        value="<?= htmlspecialchars($priceData[$prefix . 'width'] ?? '') ?>">
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>height" class="form-label">Carton Height (H)</label>
                                    <input type="number" step="0.01" name="<?= $prefix ?>height" id="<?= $prefix ?>height" class="form-control"
                                        value="<?= htmlspecialchars($priceData[$prefix . 'height'] ?? '') ?>">
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>length" class="form-label">Carton Length (L)</label>
                                    <input type="number" step="0.01" name="<?= $prefix ?>length" id="<?= $prefix ?>length" class="form-control"
                                        value="<?= htmlspecialchars($priceData[$prefix . 'length'] ?? '') ?>">
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>pcs" class="form-label">PCS / Carton</label>
                                    <input type="number" step="1" name="<?= $prefix ?>pcs" id="<?= $prefix ?>pcs" class="form-control"
                                        value="<?= htmlspecialchars($priceData[$prefix . 'pcs'] ?? '') ?>">
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>no" class="form-label">No Of Carton</label>
                                    <input type="number" step="1" name="<?= $prefix ?>no" id="<?= $prefix ?>no" class="form-control"
                                        value="<?= htmlspecialchars($priceData[$prefix . 'no'] ?? '') ?>">
                                </div>

                                <div class="col-sm-2">
                                    <label for="<?= $prefix ?>total_cbm" class="form-label">Total CBM Carton <?= $i ?></label>
                                    <input type="number" step="0.0001" name="<?= $prefix ?>total_cbm" id="<?= $prefix ?>total_cbm" class="form-control" readonly
                                        value="<?= htmlspecialchars($priceData[$prefix . 'total_cbm'] ?? '') ?>">
                                </div>
                            <?php endfor; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enter Calculated Price -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">Calculated Price</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-4">
                                <label for="price_rm" class="form-label">Price RM</label>
                                <input type="number" step="0.01" name="price_rm" id="price_rm" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['price_rm'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="total_price_yen" class="form-label">Total Price (Yen)</label>
                                <input type="number" step="0.01" name="total_price_yen" id="total_price_yen" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['total_price_yen'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="deposit_50_yen" class="form-label">50% Deposit (Yen)</label>
                                <input type="number" step="0.01" name="deposit_50_yen" id="deposit_50_yen" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['deposit_50_yen'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="deposit_50_rm" class="form-label">50% Deposit (RM)</label>
                                <input type="number" step="0.01" name="deposit_50_rm" id="deposit_50_rm" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['deposit_50_rm'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="cbm_carton" class="form-label">CBM per Carton (m³)</label>
                                <input type="number" step="0.0001" name="cbm_carton" id="cbm_carton" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['cbm_carton'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="total_cbm" class="form-label">Total CBM (m³)</label>
                                <input type="number" step="0.0001" name="total_cbm" id="total_cbm" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['total_cbm'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="vm_carton" class="form-label">Vm per Carton (kg/m³)</label>
                                <input type="number" step="0.0001" name="vm_carton" id="vm_carton" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['vm_carton'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="total_vm" class="form-label">Total Vm (kg/m³)</label>
                                <input type="number" step="0.0001" name="total_vm" id="total_vm" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['total_vm'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="total_weight" class="form-label">Total Weight (kg)</label>
                                <input type="number" step="0.01" name="total_weight" id="total_weight" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['total_weight'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="sg_tax" class="form-label">SG TAX (9%) (Yen)</label>
                                <input type="number" step="0.01" name="sg_tax" id="sg_tax" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['sg_tax'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="supplier_1st_yen" class="form-label">Supplier 1st (Yen)</label>
                                <input type="number" step="0.01" name="supplier_1st_yen" id="supplier_1st_yen" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['supplier_1st_yen'] ?? '') ?>">
                            </div>

                            <div class="col-sm-4">
                                <label for="supplier_2nd_yen" class="form-label">Supplier 2nd (Yen)</label>
                                <input type="number" step="0.01" name="supplier_2nd_yen" id="supplier_2nd_yen" class="form-control" readonly
                                    value="<?= htmlspecialchars($priceData['supplier_2nd_yen'] ?? '') ?>">
                            </div>

                                <div class="col-sm-4">
                                    <label for="customer_1st" class="form-label">Customer 1st (RM)</label>
                                    <input type="number" step="0.0001" id="customer_1st" class="form-control" readonly
                                        value="<?= htmlspecialchars($priceData['customer_1st_rm'] ?? '') ?>">
                                    <input type="hidden" name="customer_1st_rm" id="customer_1st_hidden"
                                        value="<?= htmlspecialchars($priceData['customer_1st_rm'] ?? '') ?>">
                                </div>

                                <div class="col-sm-4">
                                    <label for="customer_2nd" class="form-label">Customer 2nd (RM)</label>
                                    <input type="number" step="0.0001" id="customer_2nd" class="form-control" readonly
                                        value="<?= htmlspecialchars($priceData['customer_2nd_rm'] ?? '') ?>">
                                    <input type="hidden" name="customer_2nd_rm" id="customer_2nd_hidden"
                                        value="<?= htmlspecialchars($priceData['customer_2nd_rm'] ?? '') ?>">
                                </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Method -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Shipping Method</h4>
                    </div>
                    <div class="card-body">
                        <div class="live-preview">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="shipping_code" class="form-label">Shipping Method</label>
                                    <select class="form-select" id="shipping_code" name="shipping_code" required>
                                        <option value="">-- Select Shipping Code --</option>
                                        <?php foreach ($shippingPrices as $option): 
                                            $code = $option['shipping_code'];
                                            $isSelected = ($code === $selectedShippingCode) ? 'selected' : '';
                                        ?>
                                            <option value="<?= htmlspecialchars($code) ?>" <?= $isSelected ?>>
                                                <?= htmlspecialchars($code) ?> - 
                                                <?php
                                                    if (strpos($code, 'M1') === 0) {
                                                        echo 'Sea Normal Goods (RM ' . number_format($option['price_cbm_normal_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'M2') === 0) {
                                                        echo 'Sea Sensitive Goods (RM ' . number_format($option['price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'S1') === 0) {
                                                        echo 'SG Sea Normal Goods (RM ' . number_format($option['sg_price_cbm_normal_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'S2') === 0) {
                                                        echo 'SG Sea Sensitive Goods (RM ' . number_format($option['sg_price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'OCSG1') === 0) {
                                                        echo 'OCOOL SG Sea Normal Goods (RM ' . number_format($option['ocool_sg_price_cbm_normal_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'OCSG2') === 0) {
                                                        echo 'OCOOL SG Sensitive Goods (RM ' . number_format($option['ocool_sg_price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                    } elseif (strpos($code, 'M3a') === 0) {
                                                        echo 'Air VM Normal Goods (RM ' . number_format($option['price_kg_normal_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'M3b') === 0) {
                                                        echo 'Air KG Normal Goods (RM ' . number_format($option['price_kg_normal_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'M4a') === 0) {
                                                        echo 'Air VM Sensitive Goods (RM ' . number_format($option['price_kg_sensitive_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'M4b') === 0) {
                                                        echo 'Air KG Sensitive Goods (RM ' . number_format($option['price_kg_sensitive_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'S3a') === 0) {
                                                        echo 'SG Air VM Normal Goods (RM ' . number_format($option['sg_price_kg_normal_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'S3b') === 0) {
                                                        echo 'SG Air KG Normal Goods (RM ' . number_format($option['sg_price_kg_normal_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'S4a') === 0) {
                                                        echo 'SG Air VM Sensitive Goods (RM ' . number_format($option['sg_price_kg_sensitive_goods'], 2) . '/KG)';
                                                    } elseif (strpos($code, 'S4b') === 0) {
                                                        echo 'SG Air KG Sensitive Goods (RM ' . number_format($option['sg_price_kg_sensitive_goods'], 2) . '/KG)';
                                                    } else {
                                                        echo 'Custom Shipping';
                                                    }
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>


                            </div>

                            <div class="col-sm-6">
                                <label for="price_total_sea_shipping" class="form-label">Sea Shipping (RM)</label>
                                <input type="text" id="price_total_sea_shipping" name="price_total_sea_shipping" class="form-control" readonly>
                            </div>

                            <div class="col-sm-6">
                                <label for="price_total_air_shipping_vm" class="form-label">Air Shipping VM (RM)</label>
                                <input type="text" id="price_total_air_shipping_vm" name="price_total_air_shipping_vm" class="form-control" readonly>
                            </div>

                            <div class="col-sm-6">
                                <label for="price_total_air_shipping_kg" class="form-label">Air Shipping KG (RM)</label>
                                <input type="text" id="price_total_air_shipping_kg" name="price_total_air_shipping_kg" class="form-control" readonly>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Price -->
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Final Price</h4>
        </div>
        <div class="card-body">
            <div class="live-preview">
            <div class="row g-3">
                <div class="col-sm-6">
                <label for="final_total_price" class="form-label">Final Total Price</label>
                <input type="text" class="form-control" id="final_total_price" name="final_total_price" readonly>
                </div>
                <div class="col-sm-6">
                <label for="final_unit_price" class="form-label">Final Unit Price</label>
                <input type="text" class="form-control" id="final_unit_price" name="final_unit_price" readonly>
                </div>
                <div class="col-sm-6">
                <label for="final_selling_total" class="form-label">Final Selling Total</label>
                <input type="text" class="form-control" id="final_selling_total" name="final_selling_total" readonly>
                </div>
                <div class="col-sm-6">
                <label for="final_selling_unit" class="form-label">Final Selling Unit</label>
                <input 
                    type="number" 
                    step="0.01" 
                    class="form-control" 
                    id="final_selling_unit" 
                    name="final_selling_unit"
                    value="<?= isset($priceData['final_selling_unit']) ? htmlspecialchars($priceData['final_selling_unit']) : '' ?>"
                    required
                >
                </div>

                <div class="col-sm-6">
                    <label for="final_profit_per_unit" class="form-label">Final Profit Per Unit</label>
                    <input type="text" class="form-control" id="final_profit_per_unit" name="final_profit_per_unit" readonly>
                </div>
                <div class="col-sm-6">
                <label for="final_total_profit" class="form-label">Final Total Profit</label>
                <input type="text" class="form-control" id="final_total_profit" name="final_total_profit" readonly>
                </div>
                <div class="col-sm-6">
                <label for="final_profit_percent" class="form-label">Final Profit (%)</label>
                <input type="text" class="form-control" id="final_profit_percent" name="final_profit_percent" readonly>
                </div>
                <div class="col-sm-6">
                    <label for="zakat" class="form-label">Zakat (10%)</label>
                    <input type="text" class="form-control" id="zakat" name="zakat" readonly>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>



        <!-- Submit button -->
        <div class="row mb-4">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary">Update Price</button>
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
    <script src="assets/js/calculate_updating.js"></script>
    <script src="assets/js/calculate.js"></script>
    <script src="assets/js/getprice.js"></script>


    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</body>

</html>