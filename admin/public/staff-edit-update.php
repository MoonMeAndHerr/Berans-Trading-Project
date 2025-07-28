<?php include __DIR__ . '/../private/staff-edit-update-backend.php';?>

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
                                    <h4 class="mb-sm-0">Edit</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                                            <li class="breadcrumb-item active">Add Staff</li>
                                            <li class="breadcrumb-item active">Edit</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

                        <div class="container mt-5">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">Edit Staff: <?= htmlspecialchars($staff['staff_name']) ?></h3>
                                </div>
                                <div class="card-body ps-5 pe-5 pt-4 pb-4">
                                
                                    <?php if ($errors): ?>
                                        <div class="alert alert-danger">
                                            <ul><?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?></ul>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                    <?php endif; ?>

                                    <!-- ✅ Single form for update -->
                                    <form method="post" action="">

                                        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staff['staff_id']) ?>">

                                        <div class="mb-3">
                                            <label for="staff_number" class="form-label">Staff Number</label>
                                            <input type="text" class="form-control" id="staff_number" name="staff_number" value="<?= htmlspecialchars($staff['staff_number']) ?>" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                        </div>

                                        <div class="mb-3">
                                            <label for="staff_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="staff_name" name="staff_name" value="<?= htmlspecialchars($staff['staff_name']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="staff_designation" class="form-label">Designation</label>
                                            <input type="text" class="form-control" id="staff_designation" name="staff_designation" value="<?= htmlspecialchars($staff['staff_designation']) ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="staff_about" class="form-label">About</label>
                                            <textarea class="form-control" id="staff_about" name="staff_about"><?= htmlspecialchars($staff['staff_about']) ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($staff['username']) ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-select" id="role" name="role" required>
                                                <?php
                                                $roles = ['admin', 'manager', 'sales', 'warehouse','staff'];
                                                foreach ($roles as $roleOption) {
                                                    $selected = ($staff['role'] === $roleOption) ? 'selected' : '';
                                                    echo "<option value=\"$roleOption\" $selected>" . ucfirst($roleOption) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_id" class="form-label">Company</label>
                                            <select class="form-select" id="company_id" name="company_id">
                                                <option value="">Select a company</option>
                                                <?php foreach ($companies as $company): ?>
                                                <option value="<?= $company['company_id'] ?>" <?= ($staff['company_id'] == $company['company_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($company['company_name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                Password <small>(leave blank to keep current password)</small>
                                            </label>
                                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                        </div>

     <div class="d-flex gap-2 align-items-center">
        <button type="submit" class="btn btn-primary">Update Staff</button>
        <a href="staff-add.php" class="btn btn-secondary">Cancel</a>

        <button type="submit" name="delete_staff" value="1" class="btn btn-danger"
            onclick="return confirm('Are you sure you want to delete this staff?');">
            Delete
        </button>
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

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>