<?php include __DIR__ . '/../include/header.php';?>

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
                                <h4 class="mb-sm-0">Xero Authorization and Refresh</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Xero</a></li>
                                        <li class="breadcrumb-item active">Auth</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->


                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-9">
                                                <h4 class="mt-4 fw-semibold">Xero API Authorization</h4>
                                                <p class="text-muted mt-3">When authorizing connection API with Xero,
                                                    you are allowing this application to access your Xero data. Once
                                                    authorized, you can manage and interact with your Xero account and set 
                                                    to expired after 30 minutes. You can refresh the token to maintain
                                                    access without needing to reauthorize.</p>
                                                </p>
                                                <?php

                                                    if (isset($_SESSION['success'])) {

                                                ?>

                                                <div class="alert alert-success"><?php echo $_SESSION['success'];?></div>

                                                <?php

                                                    unset($_SESSION['success']);

                                                }

                                                    if (isset($_SESSION['failed'])) {

                                                ?>

                                                    <div class="alert alert-danger"><?php echo $_SESSION['failed'];?></div>

                                                <?php

                                                    unset($_SESSION['failed']);
                                                    
                                                }
                                                ?>
                                                <?php

                                                    if($xero_ttl < date('Y-m-d H:i:s')){

                                                ?>

                                                    Connection Status:<p class="text-danger">Token Expired / Disconnected</p>
                                                    <div class="mt-4">
                                                        <a href="xero-auth.php">
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal">
                                                            Authorize Xero API
                                                        </button>
                                                        </a>
                                                    </div>

                                                <?php 

                                                    } else {

                                                ?>
                                            
                                                    Connection Status: <p class="text-success">Connected</p>
                                                    <div class="mt-4">
                                                        <a href="xero-auth.php">
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal">
                                                            Refresh Xero API
                                                        </button>
                                                        </a>
                                                    </div>

                                                <?php

                                                    }

                                                ?>

     

                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-5 mb-2">
                                            <div class="col-sm-7 col-8">
                                                <img src="assets/images/verification-img.png" alt=""
                                                    class="img-fluid" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include __DIR__ . '/../include/footer.php';?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
            data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <?php include __DIR__ . '/../include/themesetting.php';?>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- dropzone min -->
    <script src="assets/libs/dropzone/dropzone-min.js"></script>

    <!--crypto-kyc init-->
    <script src="assets/js/pages/crypto-kyc.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
</body>

</html>