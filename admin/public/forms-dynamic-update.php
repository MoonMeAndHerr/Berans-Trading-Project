<?php

include __DIR__ . '/../private/price-dynamic-update-backend.php';
include __DIR__ . '/../include/header.php';
?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Dynamic Price Update</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                        <li class="breadcrumb-item active">Dynamic Price Update</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                                              
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Dynamic Price Update</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" id="updatePriceForm">
                                        <!-- Conversion Rate -->
                                        <div class="mb-3">
                                            <label class="form-label">Conversion Rate (YEN to MYR) </label>
                                            <input type="number" step="0.0001" class="form-control" 
                                                   name="conversion_rate" 
                                                   placeholder="Leave empty to keep current rate (<?= number_format($currentConversionRate, 4) ?>)">
                                            <small class="text-muted">Current rate: <?= number_format($currentConversionRate, 4) ?></small>
                                        </div>

                                        <!-- Freight Rates -->
                                        <div class="mb-3">
                                            <label class="form-label">Freight Rates</label>
                                            <?php foreach ($shippingRates as $rate): ?>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text" style="width: 230px; justify-content: flex-start;">
                                                    <?= htmlspecialchars($rate['shipping_code']) ?> - <?= htmlspecialchars($rate['shipping_name']) ?>
                                                </span>
                                                <input type="number" step="0.01" class="form-control" 
                                                    name="shipping_rates[<?= $rate['shipping_price_id'] ?>]" 
                                                    value="<?= $rate['freight_rate'] ?>" required>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="text-end">
                                            <button type="button" class="btn btn-primary" id="updatePricesBtn">
                                                Update All Prices
                                            </button>
                                        </div>
                                        
                                        <!-- Hidden field to prevent accidental submission -->
                                        <input type="hidden" name="update_prices" value="1">
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
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/js/app.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success/Error messages
            <?php if (isset($_SESSION['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: <?= json_encode($_SESSION['success']) ?>,
                    confirmButtonColor: '#3085d6'
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: <?= json_encode($_SESSION['error']) ?>,
                    confirmButtonColor: '#d33'
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            // SweetAlert confirmation for price update
            document.getElementById('updatePricesBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will update all product prices in the database. This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update prices!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Updating Prices...',
                            text: 'Please wait while we update all product prices.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        document.getElementById('updatePriceForm').submit();
                    }
                });
            });
        });
    </script>

</body>
</html>