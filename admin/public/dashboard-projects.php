<?php 

    include __DIR__ . '/../include/header.php';
    include __DIR__ . '/../private/dashboard-projects.php';

    $staff = getTotalStaff();
    $supplier = getTotalSupplier();
    $customer = getTotalCustomer();
    $product = getTotalProduct();
    $revenue = getTotalRevenue();
    $invoice = getTotalInvoice();

?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card crm-widget">
                                    <div class="card-body p-0">
                                        <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                            <div class="col">
                                                <div class="py-4 px-3">
                                                    <h5 class="text-muted text-uppercase fs-13">Total Staff</h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-file-user-line display-6 text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h2 class="mb-0"><span class="counter-value" data-target="<?php echo $staff; ?>">0</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col">
                                                <div class="mt-3 mt-md-0 py-4 px-3">
                                                    <h5 class="text-muted text-uppercase fs-13">Total Customer </h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-shield-user-line display-6 text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h2 class="mb-0"><span class="counter-value" data-target="<?php echo $customer; ?>">0</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col">
                                                <div class="mt-3 mt-md-0 py-4 px-3">
                                                    <h5 class="text-muted text-uppercase fs-13">Total Supplier </h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-store-line display-6 text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h2 class="mb-0"><span class="counter-value" data-target="<?php echo $supplier; ?>">0</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col">
                                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                                    <h5 class="text-muted text-uppercase fs-13">Total Product</h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-survey-line display-6 text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h2 class="mb-0"><span class="counter-value" data-target="<?php echo $product; ?>">0</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col">
                                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                                    <h5 class="text-muted text-uppercase fs-13">Total Revenue </h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h2 class="mb-0">RM <span class="counter-value" data-target="<?php echo $revenue; ?>">0</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end col -->
                                        </div><!-- end row -->
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div><!-- end row -->

                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="card card-height-100">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Monthly Revenue</h4>
                                        <div class="flex-shrink-0">
                                        </div>
                                    </div><!-- end card header -->
                                    <div class="card-body px-0">
                                        <ul class="list-inline main-chart text-center mb-0">
                                            <li class="list-inline-item chart-border-left me-0 border-0">
                                                <h4 class="text-primary">RM <?php echo $invoice; ?> <span class="text-muted d-inline-block fs-13 align-middle ms-2">Invoice Issued</span></h4>
                                            </li>
                                            <li class="list-inline-item chart-border-left me-0">
                                                <h4>RM <?php echo $revenue; ?><span class="text-muted d-inline-block fs-13 align-middle ms-2">Revenue Received</span>
                                                </h4>
                                            </li>
                                        </ul>
                                        <div id="revenue-issued-charts" data-colors='["--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                        </a>
                    </div>
                                                           
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

            </div>
            <!-- end main content-->    
                </div><!--end container fluid-->         
            </div><!-- End Page-content -->
           <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- END layout-wrapper -->
    <?php include __DIR__ . '/../include/themesetting.php';?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: {
            type: 'line',
            height: 350
        },
        series: [
            {
                name: 'Issued',
                data: <?php echo json_encode($issuedData); ?>
            },
            {
                name: 'Received',
                data: <?php echo json_encode($receivedData); ?>
            }
        ],
        xaxis: {
            categories: <?php echo json_encode($months); ?>
        }
    };

    var chart = new ApexCharts(
        document.querySelector("#revenue-issued-charts"),
        options
    );

    chart.render();
});
</script>






    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Dashboard init -->
    <script  src="assets/js/pages/dashboard-crm.init.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/js/app.js"></script>

</body>

</html>