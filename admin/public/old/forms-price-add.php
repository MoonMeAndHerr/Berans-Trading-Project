

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>Berans Trading</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />


</head>
    <?php include __DIR__ . '/../private/forms_price_add_backend.php';?>
<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php 
        include __DIR__ . '/../include/header.php';
        include __DIR__ . '/../include/sidebar.php'; 
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

                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($successMsg): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
                            <?php elseif ($errorMsg): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
                            <?php endif; ?>

                            <div class="card">
                                <div class="card-header d-flex justify-content-center align-items-center">
                                    <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Enter Product Price Details</h2>
                                </div>

                                <div class="card-body">
                                    <form method="POST" id="priceForm">
                                        <div class="row g-3">

                                            <!-- Product -->
                                            <div class="col-md-6">
                                                <label for="product_id" class="form-label">Product (Item Name & Size)</label>
                                                <select class="form-select" id="product_id" name="product_id" required>
                                                    <option value="">Select product</option>
                                                    <?php foreach ($productOptions as $p): ?>
                                                        <option value="<?= $p['product_id'] ?>">
                                                            <?= htmlspecialchars($p['name'] . ' - ' . $p['size_volume']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- supplier -->
                                            <div class="col-md-6">
                                                <label for="supplier_id" class="form-label">Supplier</label>
                                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                                    <option value="">Select supplier</option>
                                                    <?php foreach ($supplierOptions as $s): ?>
                                                        <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="col-md-4">
                                                <label class="form-label">Minimum Order Quantity</label>
                                                <input type="number" class="form-control" name="quantity" id="quantity" value="10000" min="1" required>
                                            </div>

                                            <!-- Carton Width -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Width (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_width" id="carton_width" value="10.00" min="0" required>
                                            </div>

                                            <!-- Carton Height -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_height" id="carton_height" value="10.00" min="0" required>
                                            </div>

                                            <!-- Carton Length -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Length (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_length" id="carton_length" value="10.00" min="0" required>
                                            </div>

                                            <!-- PCS per Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">PCS per Carton</label>
                                                <input type="number" class="form-control" name="pcs_per_carton" id="pcs_per_carton" value="1000" min="1" required>
                                            </div>

                                            <!-- Number of Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">Number of Carton</label>
                                                <input type="number" class="form-control" name="no_of_carton" id="no_of_carton" value="10" min="1" required>
                                            </div>

                                            <!-- Design & Logo -->
                                            <div class="col-md-12">
                                                <label class="form-label">Design & Logo Details</label>
                                                <textarea class="form-control" name="designlogo" id="designlogo" rows="2" placeholder="Enter design and logo details"></textarea>
                                            </div>

                                            <!-- Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Price (Yen)</label>
                                                <input type="number" step="0.0000001" class="form-control" name="price" id="price" value="0.1680" min="0" required>
                                            </div>

                                            <!-- Shipping Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Shipping Price (Yen)</label>
                                                <input type="number" step="0.01" class="form-control" name="shipping_price" id="shipping_price" value="0.56" min="0" required>
                                            </div>

                                            <!-- Additional Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Additional Price (Yen)</label>
                                                <input type="number" step="0.01" class="form-control" name="additional_price" id="additional_price" value="100" min="0" required>
                                            </div>

                                            <!-- Weight per Carton (kg) -->
                                            <div class="col-md-6">
                                                <label class="form-label">Weight per Carton (kg)</label>
                                                <input type="number" step="0.01" class="form-control" name="weight_carton" id="weight_carton" value="100.00" min="0" required>
                                            </div>

                                            <!-- Conversion Rate -->
                                            <div class="col-md-6">
                                                <label class="form-label">Conversion Rate (Yen to MYR)</label>
                                                <input type="number" step="0.0001" class="form-control" name="conversion_rate" id="conversion_rate" value="1.6" min="0" required>
                                            </div>
                                            <!-- Estimated Arrival -->
                                            <div class="col-md-6">
                                                <label class="form-label">Estimated Arrival Date</label>
                                                <input type="date" class="form-control" id="estimated_arrival" name="estimated_arrival">
                                            </div>                                

                                            <!-- ✅ ADDITIONAL CARTON -->

                                            <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Additional Carton</h2>
                                            </div>            
                                                                               

                                            <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 1</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton1_width" id="add_carton1_width" class="form-control" value="15">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton1_height" id="add_carton1_height" class="form-control" value="20">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton1_length" id="add_carton1_length" class="form-control" value="10">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton1_pcs" id="add_carton1_pcs" class="form-control" value="1000">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton1_no" id="add_carton1_no" class="form-control" value="10">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 1</label>
                                                            <input type="text" readonly id="add_carton1_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton1_total_cbm" id="add_carton1_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 2</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton2_width" id="add_carton2_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton2_height" id="add_carton2_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton2_length" id="add_carton2_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton2_pcs" id="add_carton2_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton2_no" id="add_carton2_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 2</label>
                                                            <input type="text" readonly id="add_carton2_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton2_total_cbm" id="add_carton2_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                        <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 3 </h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton3_width" id="add_carton3_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton3_height" id="add_carton3_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton3_length" id="add_carton3_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton3_pcs" id="add_carton3_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton3_no" id="add_carton3_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 3</label>
                                                            <input type="text" readonly id="add_carton3_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton3_total_cbm" id="add_carton3_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 4 -->
                                            <div class="additional-carton" id="add-carton4">
                                                <h5>Additional Carton 4</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton4_width" id="add_carton4_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton4_height" id="add_carton4_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton4_length" id="add_carton4_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton4_pcs" id="add_carton4_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton4_no" id="add_carton4_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 4</label>
                                                            <input type="text" readonly id="add_carton4_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton4_total_cbm" id="add_carton4_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 5 -->
                                            <div class="additional-carton" id="add-carton5">
                                                <h5>Additional Carton 5</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton5_width" id="add_carton5_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton5_height" id="add_carton5_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton5_length" id="add_carton5_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton5_pcs" id="add_carton5_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton5_no" id="add_carton5_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 5</label>
                                                            <input type="text" readonly id="add_carton5_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton5_total_cbm" id="add_carton5_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 6 -->
                                            <div class="additional-carton" id="add-carton6">
                                                <h5>Additional Carton 6</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton6_width" id="add_carton6_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton6_height" id="add_carton6_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton6_length" id="add_carton6_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton6_pcs" id="add_carton6_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton6_no" id="add_carton6_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 6</label>
                                                            <input type="text" readonly id="add_carton6_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton6_total_cbm" id="add_carton6_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- ✅ SHIPPING METHOD -->

                                            <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Shipping Method</h2>
                                            </div>               

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Shipping Method</label>
                                                    <select class="form-select" id="shipping_code" name="shipping_code" required>
                                                        <option value="">-- Select Shipping Code --</option>
                                                        <?php foreach ($shippingPrices as $option): ?>
                                                            <option value="<?= htmlspecialchars($option['shipping_code']) ?>">
                                                                <?= htmlspecialchars($option['shipping_code']) ?> - 
                                                                <?php
                                                                // Generate descriptive label
                                                                if (strpos($option['shipping_code'], 'M1') === 0) {
                                                                    echo 'Sea Normal Goods (RM ' . number_format($option['price_cbm_normal_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'M2') === 0) {
                                                                    echo 'Sea Sensitive Goods (RM ' . number_format($option['price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'S1') === 0) {
                                                                    echo 'SG Sea Normal Goods (RM ' . number_format($option['sg_price_cbm_normal_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'S2') === 0) {
                                                                    echo 'SG Sea Sensitive Goods (RM ' . number_format($option['sg_price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'OCSG1') === 0) {
                                                                    echo 'OCOOL SG Sea Normal Goods (RM ' . number_format($option['ocool_sg_price_cbm_normal_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'OCSG2') === 0) {
                                                                    echo 'OCOOL SG Sensitive Goods (RM ' . number_format($option['ocool_sg_price_cbm_sensitive_goods'], 2) . '/CBM)';
                                                                } elseif (strpos($option['shipping_code'], 'M3a') === 0) {
                                                                    echo 'Air VM Normal Goods (RM ' . number_format($option['price_kg_normal_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'M3b') === 0) {
                                                                    echo 'Air KG Normal Goods (RM ' . number_format($option['price_kg_normal_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'M4a') === 0) {
                                                                    echo 'Air VM Sensitive Goods (RM ' . number_format($option['price_kg_sensitive_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'M4b') === 0) {
                                                                    echo 'Air KG Sensitive Goods (RM ' . number_format($option['price_kg_sensitive_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'S3a') === 0) {
                                                                    echo 'SG Air VM Normal Goods (RM ' . number_format($option['sg_price_kg_normal_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'S3b') === 0) {
                                                                    echo 'SG Air KG Normal Goods (RM ' . number_format($option['sg_price_kg_normal_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'S4a') === 0) {
                                                                    echo 'SG Air VM Sensitive Goods (RM ' . number_format($option['sg_price_kg_sensitive_goods'], 2) . '/KG)';
                                                                } elseif (strpos($option['shipping_code'], 'S4b') === 0) {
                                                                    echo 'SG Air KG Sensitive Goods (RM ' . number_format($option['sg_price_kg_sensitive_goods'], 2) . '/KG)';
                                                                } else {
                                                                    echo 'Custom Shipping';
                                                                }
                                                                ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Shipping Costs - Simple 3-column layout -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Sea Shipping (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="text" class="form-control" id="price_total_sea_shipping" readonly>
                                                        <input type="hidden" name="price_total_sea_shipping" id="price_total_sea_shipping_hidden">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Air Shipping VM (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="text" class="form-control" id="price_total_air_shipping_vm" readonly>
                                                        <input type="hidden" name="price_total_air_shipping_vm" id="price_total_air_shipping_vm_hidden">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Air Shipping KG (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="text" class="form-control" id="price_total_air_shipping_kg" readonly>
                                                        <input type="hidden" name="price_total_air_shipping_kg" id="price_total_air_shipping_kg_hidden">
                                                    </div>
                                                </div>
                                            </div>
                                        

                                            <!-- ✅ FINAL PRICING -->
                                             
                                             <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Final Price</h2>
                                            </div>  

                                            <div class="row g-3">
                                            <!-- Final Total Price -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Total Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="final_total_price" readonly>
                                                            <input type="hidden" name="final_total_price" id="final_total_price_hidden">
                                                    </div>
                                                </div>

                                            <!-- Final Unit Price -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Unit Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                                <input type="text" class="form-control" id="final_unit_price" readonly>
                                                                <input type="hidden" name="final_unit_price" id="final_unit_price_hidden">
                                                        </div>
                                                </div>

                                                <!-- Final Selling Total -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Selling Total</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                                <input type="text" class="form-control" id="final_selling_total" readonly>
                                                                <input type="hidden" name="final_selling_total" id="final_selling_total_hidden">
                                                        </div>
                                                </div>

                                                <!-- Final Selling Unit -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Selling Unit</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                            <input type="number" step="0.01" class="form-control" id="final_selling_unit" name="final_selling_unit">
                                                        </div>
                                                </div>

                                                <!-- Final Profit Per Unit RM -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Profit Per Unit</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="final_profit_per_unit" readonly>
                                                            <input type="hidden" name="final_profit_per_unit" id="final_profit_per_unit_hidden">
                                                        </div>
                                                </div>

                                                <!-- Final Total Profit -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Total Profit</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="final_total_profit" readonly>
                                                            <input type="hidden" name="final_total_profit" id="final_total_profit_hidden">
                                                        </div>
                                                </div>

                                                <!-- Final Profit (%) -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Final Profit (%)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">%</span>
                                                            <input type="text" class="form-control" id="final_profit_percent" readonly>
                                                            <input type="hidden" name="final_profit_percent" id="final_profit_percent_hidden">
                                                        </div>
                                                </div>

                                                <!-- Zakat (10%) -->
                                                <div class="col-md-6">
                                                    <label class="form-label">Zakat (10%)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="zakat" readonly>
                                                            <input type="hidden" name="zakat" id="zakat_hidden">
                                                        </div>
                                                </div>

                                            </div>
                                                           
                                            

                                              <!-- ✅ CALCULATED RESULTS WITH HIDDEN FIELDS -->                                          
                                        <div class="card-header d-flex justify-content-center align-items-center">
                                            <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Calculated Price</h2>
                                        </div>   
                                            
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="price_rm" readonly>
                                                            <input type="hidden" name="price_rm" id="price_rm_hidden">
                                                    </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Price (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="total_price_yen" readonly>
                                                            <input type="hidden" name="total_price_yen" id="total_price_yen_hidden">
                                                     </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">50% Deposit (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="deposit_50_yen" readonly>
                                                            <input type="hidden" name="deposit_50_yen" id="deposit_50_yen_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">50% Deposit (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="deposit_50_rm" readonly>
                                                            <input type="hidden" name="deposit_50_rm" id="deposit_50_rm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">CBM per Carton (m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">m³</span>
                                                            <input type="text" class="form-control" id="cbm_carton" readonly>
                                                            <input type="hidden" name="cbm_carton" id="cbm_carton_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total CBM (m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">m³</span>
                                                            <input type="text" class="form-control" id="total_cbm" readonly>
                                                            <input type="hidden" name="total_cbm" id="total_cbm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Vm per Carton (kg/m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg/m³</span>
                                                            <input type="text" class="form-control" id="vm_carton" readonly>
                                                            <input type="hidden" name="vm_carton" id="vm_carton_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Vm (kg/m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg/m³</span>
                                                            <input type="text" class="form-control" id="total_vm" readonly>
                                                            <input type="hidden" name="total_vm" id="total_vm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Weight (kg)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg</span>
                                                            <input type="text" class="form-control" id="total_weight" readonly>
                                                            <input type="hidden" name="total_weight" id="total_weight_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">SG TAX (9%) (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="sg_tax" readonly>
                                                            <input type="hidden" name="sg_tax" id="sg_tax_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Supplier 1st (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="supplier_1st" readonly>
                                                            <input type="hidden" name="supplier_1st_yen" id="supplier_1st_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Supplier 2nd (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="supplier_2nd" readonly>
                                                            <input type="hidden" name="supplier_2nd_yen" id="supplier_2nd_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Customer 1st (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="customer_1st" readonly>
                                                            <input type="hidden" name="customer_1st_rm" id="customer_1st_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Customer 2nd (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="customer_2nd" readonly>
                                                            <input type="hidden" name="customer_2nd_rm" id="customer_2nd_hidden">
                                                    </div>       
                                            </div>
                            
                                            
                                            <div class="col-12 text-end mt-3">
                                                <button type="submit" class="btn btn-primary">Save Price</button>
                                            </div>    
                                        </div>                                                  



                                        </div><!-- row -->
                                    </form>
                                </div><!-- card-body -->
                            </div><!-- card -->
                        </div><!-- col -->

                    </div><!--end row-->




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
    <script src="assets/js/calculate.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>