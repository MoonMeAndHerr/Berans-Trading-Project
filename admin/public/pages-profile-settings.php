<?php

    // Include your DB config
    require_once __DIR__ . '/../../global/main_configuration.php';
    require_once __DIR__ . '/../private/auth_check.php';
    include __DIR__ . '/../include/header.php'; 

    $pdo = openDB();
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $user = $stmt->fetch();

?>

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <div class="position-relative mx-n4 mt-n4">
                        <div class="profile-wid-bg profile-setting-img">
                            <img src="assets/images/profile-bg.jpg" class="profile-wid-img" alt="">
                            <div class="overlay-content">
                                <div class="text-end p-3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-3">
                            <div class="card mt-n5">
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <form action="../private/pages-profile-settings-backend" method="POST" enctype="multipart/form-data">
                                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                            <img src="../../media/<?php echo htmlspecialchars($user['staff_profile_picture']); ?>"
                                                class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                                alt="user-profile-image">
                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                <input id="profile-img-file-input" type="file" name="image" accept="image/*"
                                                    class="profile-img-file-input">
                                                <label for="profile-img-file-input"
                                                    class="profile-photo-edit avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-light text-body">
                                                        <i class="ri-camera-fill"></i>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <h5 class="fs-16 mb-1"><?php echo htmlspecialchars($user['staff_name']); ?></h5>
                                        <p class="text-muted mb-0"><?php echo htmlspecialchars($user['staff_designation']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                        <div class="col-xxl-9">
                            <div class="card mt-xxl-n5">
                                <div class="card-header">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails"
                                                role="tab">
                                                <i class="fas fa-home"></i>
                                                Personal Details
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#themecustomizer"
                                                role="tab">
                                                <i class="fas fa-home"></i>
                                                Theme Customizer
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                                <i class="far fa-user"></i>
                                                Change Password
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <?php 

                                    if (isset($_SESSION['success_message'])) {
                                        echo '<div class="alert alert-success alert-dismissible fade show m-3" role="alert">'
                                            . htmlspecialchars($_SESSION['success_message']) .
                                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                        unset($_SESSION['success_message']);
                                    }

                                    if (isset($_SESSION['error_message'])) {
                                        echo '<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">'
                                            . htmlspecialchars($_SESSION['error_message']) .
                                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                        unset($_SESSION['error_message']);
                                    }

                                ?>

                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">
                                                                Name</label>
                                                            <input type="text" class="form-control" id="firstnameInput"
                                                                placeholder="Enter your name" name="staff_name" value="<?php echo htmlspecialchars($user['staff_name']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">
                                                                Username</label>
                                                            <input type="text" class="form-control" id="lastnameInput"
                                                                placeholder="Enter your username" name="staff_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Email
                                                                Address</label>
                                                            <input type="email" class="form-control" id="emailInput"
                                                                placeholder="Enter your email" name="staff_email"
                                                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="mb-3 pb-2">
                                                            <label for="exampleFormControlTextarea"
                                                                class="form-label">Description</label>
                                                            <textarea class="form-control"
                                                                id="exampleFormControlTextarea"
                                                                placeholder="Enter your description" name="staff_description" required
                                                                rows="3"><?php echo htmlspecialchars($user['staff_about']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="submit_change_details">
                                                    <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($user['staff_id']); ?>">
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit"
                                                                class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->
                                        <div class="tab-pane" id="themecustomizer" role="tabpanel">
                                            <form action="../private/pages-profile-settings-backend" method="POST">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Layout</label>
                                                            <select class="form-control" name="websitelayout">
                                                                <option value="vertical">Vertical</option>
                                                                <option value="horizontal">Horizontal</option>
                                                                <option value="twocolumn">Two Column</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Skin</label>
                                                            <select class="form-control" name="websiteskin">
                                                                <option value="light">Light</option>
                                                                <option value="dark">Dark</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Layout Width</label>
                                                            <select class="form-control" name="layoutwidth">
                                                                <option value="fluid">Fluid</option>
                                                                <option value="boxed">Boxed</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Layout Position</label>
                                                            <select class="form-control" name="layoutposition">
                                                                <option value="fixed">Fixed</option>
                                                                <option value="scrollable">Scrollable</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website TopBar Colour</label>
                                                            <select class="form-control" name="topbarcolour">
                                                                <option value="light">Light</option>
                                                                <option value="dark">Dark</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Sidebar Size</label>
                                                            <select class="form-control" name="sidebarsize">
                                                                <option value="lg">Default</option>
                                                                <option value="md">Compact</option>
                                                                <option value="sm">Small (Icon View)</option>
                                                                <option value="sm-hover">Small Hover Hiew</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Sidebar Colour</label>
                                                            <select class="form-control" name="sidebarcolour">
                                                                <option value="light">Light</option>
                                                                <option value="dark">Dark</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="layout" class="form-label">
                                                                Website Sidebar View</label>
                                                            <select class="form-control" name="sidebarview">
                                                                <option value="default">Default</option>
                                                                <option value="detached">Detached</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <input type="hidden" name="submit_change_themecustomizer">
                                                    <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($user['staff_id']); ?>">
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit"
                                                                class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->
                                        <div class="tab-pane" id="changePassword" role="tabpanel">
                                            <form action="../private/pages-profile-settings-backend" method="POST">
                                                <div class="row g-2">
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <label for="oldpasswordInput" class="form-label">Old
                                                                Password*</label>
                                                            <input type="password" name="staff_old_pass" class="form-control" required
                                                                id="oldpasswordInput"
                                                                placeholder="Enter current password">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <label for="newpasswordInput" class="form-label">New
                                                                Password*</label>
                                                            <input type="password" name="staff_new_pass" class="form-control" required
                                                                id="newpasswordInput" placeholder="Enter new password">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <label for="confirmpasswordInput" class="form-label">Confirm
                                                                Password*</label>
                                                            <input type="password" class="form-control"
                                                                id="confirmpasswordInput" name="staff_retype_pass" required
                                                                placeholder="Confirm password">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="submit_change_password">
                                                    <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($user['staff_id']); ?>">
                                                    <input type="hidden" name="current_hashed_pass" value="<?php echo htmlspecialchars($user['password_hash']); ?>">
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-success">Change
                                                                Password</button>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                </div>
                <!-- container-fluid -->
            </div><!-- End Page-content -->

           <?php include __DIR__ . '/../include/footer.php';?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <?php

        include __DIR__ . '/../include/themesetting.php';

    ?>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- profile-setting init js -->
    <script src="assets/js/pages/profile-setting.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
</body>

</html>