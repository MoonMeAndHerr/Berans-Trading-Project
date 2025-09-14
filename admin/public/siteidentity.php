<?php include __DIR__ . '/../private/sideidentity_backend.php';?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <?php 
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
                                    <h4 class="mb-sm-0">Site Config</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                            <li class="breadcrumb-item active">Site Config</li>
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
                        <div class="card mt-2 shadow">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Site Config</h4>
                            </div><!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">

                                    <!-- ✅ Show errors -->
                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php foreach ($errors as $err): ?>
                                                    <li><?= htmlspecialchars($err) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <!-- ✅ Show success -->
                                    <?php if ($success): ?>
                                        <div class="alert alert-success">
                                            <?= htmlspecialchars($success) ?>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row g-3">

                                        <?php

                                            $pdo = openDB();
                                            $stmt = $pdo->query("SELECT * FROM site_config WHERE company_id = 1 LIMIT 1");
                                            $site_identity = $stmt->fetch(PDO::FETCH_ASSOC);

                                        ?>
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter company name" value="<?php echo $site_identity['company_name']; ?>" required>
                                                    <label for="company_name">Company Name</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="company_tagline" name="company_tagline" placeholder="Enter company tagline" value="<?php echo $site_identity['company_tagline']; ?>" required>
                                                    <label for="company_tagline">Company Tagline</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter company bank" value="<?php echo $site_identity['bank_name']; ?>" required>
                                                    <label for="bank_name">Company Bank Name</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="Enter bank account name" value="<?php echo $site_identity['bank_account_name']; ?>" required>
                                                    <label for="bank_account_name">Company Bank Account Name</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="Enter bank account number" value="<?php echo $site_identity['bank_account_number']; ?>" required>
                                                    <label for="bank_account_number">Company Bank Account Number</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address" value="<?php echo $site_identity['address']; ?>" required>
                                                    <label for="address">Company Address</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter company contact number" value="<?php echo $site_identity['contact']; ?>" required>
                                                    <label for="contact">Company Contact (Omit Dash)</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter company email" value="<?php echo $site_identity['email']; ?>" required>
                                                    <label for="email">Company Email</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="logolight">Logo Light</label>
                                                <input type="file" class="form-control" id="logolight" name="logolight" >
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="logodark">Logo Dark</label>
                                                <input type="file" class="form-control" id="logodark" name="logodark"  >
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="icon">Favicon</label>
                                                <input type="file" class="form-control" id="icon" name="icon"  >
                                            </div>

                                            <!-- Submit -->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update Site Identity</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <form>
                                    
                                    </form>
                                    
                                </div>
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

    <!-- filepond js -->
    <script src="assets/libs/filepond/filepond.min.js"></script>
    <script src="assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
    <script src="assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
    <script src="assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>

    <script src="assets/js/pages/form-file-upload.init.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
    <?php if (!empty($_SESSION['custom_sa_success'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Site identity updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
        <?php unset($_SESSION['custom_sa_success']); ?>
    <?php endif; ?>


</body>

</html>