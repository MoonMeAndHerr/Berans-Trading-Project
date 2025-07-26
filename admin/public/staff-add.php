<?php include __DIR__ . '/../private/staff-add-backend.php';?>


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
                                    <h4 class="mb-sm-0">Add Staff</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                                            <li class="breadcrumb-item active">Add Staff</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="card mt-1 shadow">
                <div class="card-header d-flex align-items-center mx-3 mt-2">
                    <h3 class="card-title mb-0 me-3" style="white-space: nowrap;">Staff List</h3>
                    <input type="text" id="staffSearch" class="form-control w-25 ms-3" placeholder="Search staff...">
                </div>

                <!-- ✅ SHOW DELETE MESSAGE -->
                <?php if (isset($_SESSION['successDelete'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['successDelete']) ?>
                    </div>
                    <?php unset($_SESSION['successDelete']); ?>
                <?php endif; ?>

                <!-- ✅ Add padding inside the card body -->
                <div class="card-body px-4 py-3">
                    <?php if (!empty($staffList)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0" id="staffTable">
                        <thead class="table-light">
                            <tr>
                            <th>Staff Number</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Actions</th> <!-- New column for edit -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staffList as $staff): ?>
                            <tr>
                            <td><?= htmlspecialchars($staff['staff_number']) ?></td>
                            <td><?= htmlspecialchars($staff['staff_name']) ?></td>
                            <td><?= htmlspecialchars($staff['staff_designation'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($staff['username'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($staff['email']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($staff['role'])) ?></td>
                            <td><?= htmlspecialchars($staff['company_name'] ?: '-') ?></td>
                            <td>
                                <a href="staff-edit-update.php?staff_id=<?= urlencode($staff['staff_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>

                    <!-- ✅ Pagination -->
                        <?php if ($totalPages >= 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination justify-content-start">
                            <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages ? 'disabled' : '') ?>">
                                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>">Next</a>
                            </li>
                            </ul>
                        </nav>
                        <?php endif; ?>


                    <?php else: ?>
                    <p class="mb-0">No staff found.</p>
                    <?php endif; ?>
                </div>
            </div>
                <!-- ADD STAFF -->
                <!-- ADD STAFF -->
                <!-- ADD STAFF -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mt-2 shadow">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Add New Staff</h4>
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

                                    <form action="" method="post">
                                        <div class="row g-3">
                                            <!-- Staff Name -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="staff_name" name="staff_name" placeholder="Enter staff name" required>
                                                    <label for="staff_name">Staff Name</label>
                                                </div>
                                            </div>

                                            <!-- Designation -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="staff_designation" name="staff_designation" placeholder="Enter designation">
                                                    <label for="staff_designation">Designation</label>
                                                </div>
                                            </div>

                                            <!-- About -->
                                            <div class="col-lg-12">
                                                <div class="form-floating">
                                                    <textarea class="form-control" id="staff_about" name="staff_about" placeholder="About this staff" style="height: 100px"></textarea>
                                                    <label for="staff_about">About</label>
                                                </div>
                                            </div>

                                            <!-- Staff Number -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="staff_number" 
                                                    name="staff_number" placeholder="Staff Phone Number" value="<?= htmlspecialchars($staff_number ?? '') ?>" required>
                                                    <label for="staff_number">Staff Phone Number</label>
                                                </div>
                                            </div>


                                            <!-- Username -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                                                    <label for="username">Username</label>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>

                                            <!-- Password -->
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                                    <label for="password">Password</label>
                                                </div>
                                            </div>

                                            <!-- Role -->
                                            <div class="col-lg-4">
                                                <div class="form-floating">
                                                    <select class="form-select" id="role" name="role" required>
                                                        <option value="">-- Select Role --</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="manager">Manager</option>
                                                        <option value="sales">Sales</option>
                                                        <option value="warehouse">Warehouse</option>
                                                        <option value="staff">Staff</option> 
                                                    </select>
                                                    <label for="role">Role</label>
                                                </div>
                                            </div>

                                            <!-- Company (foreign key) -->
                                            <div class="col-lg-4">
                                                <div class="form-floating">
                                                    <select class="form-select" id="company_id" name="company_id">
                                                        <option value="">-- No Company (Optional) --</option>
                                                        <?php foreach ($companies as $c): ?>
                                                            <option value="<?= htmlspecialchars($c['company_id']) ?>">
                                                                <?= htmlspecialchars($c['company_name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <label for="company_id">Company</label>
                                                </div>
                                            </div>

                                            <!-- Submit -->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Add Staff</button>
                                                </div>
                                            </div>
                                        </div>
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

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
    <script>
document.getElementById('staffSearch').addEventListener('input', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#staffTable tbody tr');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
</script>


</body>

</html>