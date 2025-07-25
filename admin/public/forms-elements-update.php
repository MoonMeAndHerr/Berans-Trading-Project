<?php include __DIR__ . '/../private/forms_elements_update_backend.php';?>

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
                                            <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Update Price Detail </h2>
                                        </div>

                                        <div class="card-body">
                                            <form method="POST" id="priceForm">
                                                <div class="row g-3">

                                                <!-- Price ID dropdown -->
                                                <div class="col-md-6">
                                                    <label for="price_id" class="form-label">Select Price ID to Update</label>
                                                    <select id="price_id" name="price_id" class="form-select" required>
                                                        <option value="">-- Select Price ID --</option>
                                                        <?php foreach ($priceRecords as $price): ?>
                                                            <option value="<?= htmlspecialchars($price['price_id']) ?>"
                                                                <?= ($price_id == $price['price_id']) ? 'selected' : '' ?>>
                                                                <?= "Price ID: " . htmlspecialchars($price['price_id']) . " (Product ID: " . htmlspecialchars($price['product_id']) . ")" ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

<!-- Product (read-only, auto-filled) -->
<div class="col-md-6">
  <label for="product_id_display" class="form-label">Product (Item Name & Size)</label>
  <input type="text"
         id="product_id_display"
         class="form-control"
         value="<?php
             if (!empty($priceData)) {
                 $prodName = '';
                 foreach ($productOptions as $p) {
                     if ($p['product_id'] == $priceData['product_id']) {
                         $prodName = $p['name'] . ' - ' . $p['size_volume'];
                         break;
                     }
                 }
                 echo htmlspecialchars($prodName);
             }
         ?>"
         readonly>
</div>

<!-- Supplier (read-only, auto-filled) -->
<div class="col-md-6">
  <label for="supplier_id_display" class="form-label">Supplier</label>
  <input type="text"
         id="supplier_id_display"
         class="form-control"
         value="<?php
             if (!empty($priceData)) {
                 $suppName = '';
                 foreach ($supplierOptions as $s) {
                     if ($s['supplier_id'] == $priceData['supplier_id']) {
                         $suppName = $s['supplier_name'];
                         break;
                     }
                 }
                 echo htmlspecialchars($suppName);
             }
         ?>"
         readonly>
</div>

<!-- ✅ Hidden fields to actually submit IDs -->
<input type="hidden" name="product_id" id="product_id_hidden"
       value="<?= !empty($priceData) ? htmlspecialchars($priceData['product_id']) : '' ?>">

<input type="hidden" name="supplier_id" id="supplier_id_hidden"
       value="<?= !empty($priceData) ? htmlspecialchars($priceData['supplier_id']) : '' ?>">



                                            <!-- Quantity -->
                                            <div class="col-md-6">
                                                <label class="form-label">Minimum Order Quantity</label>
                                                <input type="number" class="form-control" name="quantity" id="quantity" value="0" min="1" required>
                                            </div>

                                            <!-- Carton Width -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Width (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_width" id="carton_width" value="0" min="0" required>
                                            </div>

                                            <!-- Carton Height -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_height" id="carton_height" value="0" min="0" required>
                                            </div>

                                            <!-- Carton Length -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Length (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_length" id="carton_length" value="0" min="0" required>
                                            </div>

                                            <!-- PCS per Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">PCS per Carton</label>
                                                <input type="number" class="form-control" name="pcs_per_carton" id="pcs_per_carton" value="0" min="1" required>
                                            </div>

                                            <!-- Number of Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">Number of Carton</label>
                                                <input type="number" class="form-control" name="no_of_carton" id="no_of_carton" value="0" min="1" required>
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
                                                <input type="number" step="0.01" class="form-control" name="shipping_price" id="shipping_price" value="0" min="0" required>
                                            </div>

                                            <!-- Additional Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Additional Price (Yen)</label>
                                                <input type="number" step="0.01" class="form-control" name="additional_price" id="additional_price" value="0" min="0" required>
                                            </div>

                                            <!-- Weight per Carton (kg) -->
                                            <div class="col-md-6">
                                                <label class="form-label">Weight per Carton (kg)</label>
                                                <input type="number" step="0.01" class="form-control" name="weight_carton" id="weight_carton" value="0" min="0" required>
                                            </div>

                                            <!-- Conversion Rate -->
                                            <div class="col-md-6">
                                                <label class="form-label">Conversion Rate (Yen to MYR)</label>
                                                <input type="number" step="0.0001" class="form-control" name="conversion_rate" id="conversion_rate" value="0" min="0" required>
                                            </div>
                                            <!-- Estimated Arrival -->
                                            <div class="col-md-6">
                                                <label class="form-label">Estimated Arrival Date</label>
                                                <input type="date" class="form-control" id="estimated_arrival" name="estimated_arrival">
                                            </div>




                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Update Price Record</button>
                                                </div>


                                                </div>
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
<script>
const priceDataMap = <?= json_encode(array_column($priceRecords, null, 'price_id')); ?>;

document.getElementById('price_id').addEventListener('change', function() {
    const selectedId = this.value;
    const data = priceDataMap[selectedId];

    if (data) {
        // ✅ Auto-fill product & supplier (display)
        document.getElementById('product_id_display').value =
            (data.product_name ?? '') + (data.size_volume ? ' - ' + data.size_volume : '');
        document.getElementById('supplier_id_display').value = data.supplier_name ?? '';

        // ✅ Set hidden values for submission
        const productHidden = document.getElementById('product_id_hidden');
        const supplierHidden = document.getElementById('supplier_id_hidden');
        if (productHidden) productHidden.value = data.product_id ?? '';
        if (supplierHidden) supplierHidden.value = data.supplier_id ?? '';

        // ✅ Auto-fill other fields
        document.getElementById('quantity').value        = data.quantity ?? '';
        document.getElementById('carton_width').value    = data.carton_width ?? '';
        document.getElementById('carton_height').value   = data.carton_height ?? '';
        document.getElementById('carton_length').value   = data.carton_length ?? '';
        document.getElementById('pcs_per_carton').value  = data.pcs_per_carton ?? '';
        document.getElementById('no_of_carton').value    = data.no_of_carton ?? '';
        document.getElementById('designlogo').value      = data.designlogo ?? '';
        document.getElementById('price').value           = data.price ?? '';
        document.getElementById('shipping_price').value  = data.shipping_price ?? '';
        document.getElementById('additional_price').value = data.additional_price ?? '';
        document.getElementById('weight_carton').value   = data.weight_carton ?? '';
        document.getElementById('conversion_rate').value = data.conversion_rate ?? '';
        document.getElementById('estimated_arrival').value = data.estimated_arrival ?? '';

    } else {
        // ✅ Clear fields if no valid selection
        [
            'product_id_display','supplier_id_display','quantity','carton_width','carton_height',
            'carton_length','pcs_per_carton','no_of_carton','designlogo','price',
            'shipping_price','additional_price','weight_carton','conversion_rate','estimated_arrival'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // ✅ Clear hidden IDs as well
        const productHidden = document.getElementById('product_id_hidden');
        const supplierHidden = document.getElementById('supplier_id_hidden');
        if (productHidden) productHidden.value = '';
        if (supplierHidden) supplierHidden.value = '';
    }
});
</script>



</body>

</html>