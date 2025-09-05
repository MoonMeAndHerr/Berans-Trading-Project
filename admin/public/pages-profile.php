<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include your DB config
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

// Create new database connection
$pdo = openDB(); // Use the function from your config file

// Determine staff_id from GET or session
$staff_id = 0;
if (!empty($_GET['staff_id'])) {
    $staff_id = (int)$_GET['staff_id'];
} elseif (!empty($_SESSION['staff_id'])) {
    $staff_id = (int)$_SESSION['staff_id'];
}

$staff = null;

if ($staff_id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT s.*, c.company_name, c.bank_name, c.bank_account_name, 
                   c.bank_account_number, c.address, c.contact
            FROM staff s
            LEFT JOIN company c ON s.company_id = c.company_id
            WHERE s.staff_id = :staff_id
            LIMIT 1
        ");
        $stmt->execute([':staff_id' => $staff_id]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Staff fetch error: " . $e->getMessage());
        // You might want to display a user-friendly error message
        die("Error loading staff details. Please try again later.");
    } finally {
        closeDB($pdo); // Close the connection when done
    }
}
?>

        <?php 
            include __DIR__ . '/../include/header.php'; 
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="profile-foreground position-relative mx-n4 mt-n4">
                        <div class="profile-wid-bg">
                            <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" />
                        </div>
                    </div>
                    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                        <div class="row g-4">
                            <div class="col-auto">
                                <div class="avatar-lg">
                                    <img src="assets/images/users/avatar-1.jpg" alt="user-img"
                                        class="img-thumbnail rounded-circle" />
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col">
                                <div class="p-2">
                                    <h3 class="text-white mb-1"><?= htmlspecialchars($staff['staff_name'] ?? 'Staff Member'); ?></h3>
                                    <p class="text-white-75"><?= htmlspecialchars($staff['staff_designation'] ?? 'Employee'); ?></p>
                                    <div class="hstack text-white-50 gap-1">
                                        <div class="me-2">
                                            <i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>
                                            <?= htmlspecialchars($staff['address'] ?? 'Location not specified') ?>
                                        </div>
                                        <div>
                                            <i class="ri-building-line me-1 text-white-75 fs-16 align-middle"></i>
                                            <?= htmlspecialchars($staff['company_name'] ?? 'No Company') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--end col-->

                        </div>
                        <!--end row-->
                    </div>

                    

                    <div class="row">
                        <div class="col-lg-12">
                            <div>
                                <div class="d-flex">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab"
                                                role="tab">
                                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                    class="d-none d-md-inline-block">Overview</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities"
                                                role="tab">
                                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span
                                                    class="d-none d-md-inline-block">Coming Soon</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                                    class="d-none d-md-inline-block">Coming Soon</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="flex-shrink-0">
                                        <a href="pages-profile-settings.html" class="btn btn-success"><i
                                                class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                                    </div>
                                </div>
                                <!-- Tab panes -->
                                <div class="tab-content pt-4 text-muted">
                                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-xxl-3">
                                            <?php
                                            // Define the total fields you want to track
                                            $totalFields = 6;
                                            $filled = 0;

                                            if (!empty($staff['staff_name'])) $filled++;
                                            if (!empty($staff['staff_number'])) $filled++;
                                            if (!empty($staff['email'])) $filled++;
                                            if (!empty($staff['staff_about'])) $filled++;
                                            if (!empty($staff['staff_profile_picture'])) $filled++;
                                            if (!empty($staff['company_id'])) $filled++;

                                            $percentage = round(($filled / $totalFields) * 100);
                                            ?>

                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-5">Complete Your Profile</h5>
                                                    <div class="progress animated-progress custom-progress progress-label">
                                                        <div class="progress-bar bg-danger" 
                                                            role="progressbar"
                                                            style="width: <?= $percentage ?>%"
                                                            aria-valuenow="<?= $percentage ?>" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            <div class="label"><?= $percentage ?>%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                <?php if ($staff): ?>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">Info</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Full Name :</th>
                                                                        <td class="text-muted"><?= htmlspecialchars($staff['staff_name']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Mobile :</th>
                                                                        <td class="text-muted"><?= htmlspecialchars($staff['staff_number']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">E-mail :</th>
                                                                        <td class="text-muted"><?= htmlspecialchars($staff['email']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Designation :</th>
                                                                        <td class="text-muted"><?= htmlspecialchars($staff['staff_designation']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Joining Date :</th>
                                                                        <td class="text-muted">
                                                                            <?= !empty($staff['created_at']) ? date('d M Y', strtotime($staff['created_at'])) : 'N/A'; ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                                <?php else: ?>
                                                    <div class="alert alert-warning">⚠️ Staff info not found.</div>
                                                <?php endif; ?>


                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-4">Skills</h5>
                                                        <div class="d-flex flex-wrap gap-2 fs-15">
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">Photoshop</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">illustrator</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">HTML</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">CSS</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">Javascript</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">Php</a>
                                                            <a href="javascript:void(0);"
                                                                class="badge badge-soft-primary">Python</a>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                      
                                                <!--end card-->
                                            </div>
                                            
                                            <!--end col-->
                                            <div class="col-xxl-9">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">About</h5>
                                                        <p><?= nl2br(htmlspecialchars($staff['staff_about'] ?? 'No about info available.')); ?></p>

                                                        <div class="row">
                                                            <div class="col-6 col-md-4">
                                                                <div class="d-flex mt-4">
                                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                                            <i class="ri-user-2-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <p class="mb-1">Designation :</p>
                                                                        <h6 class="text-truncate mb-0"><?= htmlspecialchars($staff['staff_designation'] ?? 'Employee'); ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div><!--end col-->
                                                        </div><!--end row-->
                                                    </div><!--end card-body-->
                                                </div><!-- end card -->
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div>
                                    <!--end tab-pane-->
                                </div>
                                <!--end tab-content-->
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                </div><!-- container-fluid -->
            </div><!-- End Page-content -->
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- End Page-content -->
    <?php include __DIR__ . '/../include/themesetting.php';?>
    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>