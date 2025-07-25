<?php include __DIR__ . '/../private/forms-add-supplier.php';?>

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

                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Add New Supplier</h4>
                                    </div><!-- end card header -->

                                            <div class="card-body">
                                                <p class="text-muted">
                                                    Please fill in the supplier information below. Fields marked with <span class="text-danger">*</span> are required.
                                                </p>

                                                <!-- ✅ Show error messages -->
                                                <?php if (!empty($errors)): ?>
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            <?php foreach ($errors as $error): ?>
                                                                <li><?= htmlspecialchars($error) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- ✅ Show success message -->
                                                <?php if ($success_add): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_add) ?></div>
                                                <?php endif; ?>

                                                <div class="live-preview">
                                                    <form action="" method="POST">
                                                        <div class="row">
                                                            <!-- Supplier Name -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="supplierNameInput" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="supplier_name" class="form-control" id="supplierNameInput"
                                                                        placeholder="Enter supplier name"
                                                                        value="<?= htmlspecialchars($_POST['supplier_name'] ?? '') ?>" required>
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Contact Person -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="contactPersonInput" class="form-label">Contact Person</label>
                                                                    <input type="text" name="supplier_contact_person" class="form-control" id="contactPersonInput"
                                                                        placeholder="Enter contact person"
                                                                        value="<?= htmlspecialchars($_POST['supplier_contact_person'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Phone -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="phoneInput" class="form-label">Phone Number</label>
                                                                    <input type="tel" name="phone" class="form-control" id="phoneInput"
                                                                        placeholder="+60 12-345 6789"
                                                                        value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Email -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="emailInput" class="form-label">Email</label>
                                                                    <input type="email" name="email" class="form-control" id="emailInput"
                                                                        placeholder="example@supplier.com"
                                                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Address -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="addressInput" class="form-label">Address</label>
                                                                    <textarea name="address" class="form-control" id="addressInput" rows="2"
                                                                        placeholder="Enter supplier address"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Notes -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="notesInput" class="form-label">Notes</label>
                                                                    <textarea name="notes" class="form-control" id="notesInput" rows="3"
                                                                        placeholder="Additional notes (optional)"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Submit -->
                                                            <div class="col-lg-12">
                                                                <div class="text-end">
                                                                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                                                                </div>
                                                            </div><!-- end col -->
                                                        </div><!-- end row -->
                                                    </form>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->

                                    


                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-muted mb-0">Berans Trading Staff</p>
                                                            <h2 class="mt-4 ff-secondary fw-semibold">
                                                            <span class="counter-value" data-target="<?= $staffCount ?>">
                                                                <?= $staffCount ?>
                                                            </span> Staff Created
                                                            </h2>
                                                        <p class="mb-0 text-muted">
                                                            Last staff added on: <strong>
                                                                <?= $lastAddedDate ? date('d M Y, H:i', strtotime($lastAddedDate)) : 'N/A' ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                                                <i data-feather="users" class="text-info"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->



                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-muted mb-0">Suppliers</p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold">
                                                            <span class="counter-value" data-target="<?= $supplierCount ?>">0</span> Total Suppliers
                                                        </h2>
                                                        <p class="mb-0 text-muted">
                                                            Last supplier added on: <strong>
                                                                <?= $lastSupplierDate ? date('d M Y, H:i', strtotime($lastSupplierDate)) : 'N/A' ?>
                                                            </strong>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                                                <i data-feather="archive" class="text-info"></i> <!-- use an icon that fits -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->

                                <div class="col-xxl-6">
                                    <div class="card">
                                        <!-- ✅ Card Header -->
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Update Existing Supplier</h4>
                                        </div><!-- end card header -->

                                        <!-- ✅ Card Body -->
                                        <div class="card-body">
                                            <p class="text-muted">
                                                Select an existing supplier to update their information, or modify details directly.
                                            </p>

                                            <!-- ✅ Show error messages -->
                                            <?php if (!empty($errors)): ?>
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        <?php foreach ($errors as $e): ?>
                                                            <li><?= htmlspecialchars($e) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>s
                                            <?php endif; ?><!-- end error messages -->

                                            <!-- ✅ Show success message -->
                                            <?php if ($success_update): ?>
                                                <div class="alert alert-success"><?= htmlspecialchars($success_update) ?></div>
                                            <?php endif; ?>
                                            <!-- end success message -->

                                            <!-- ✅ Supplier dropdown -->
                                            <div class="mb-3">
                                                <label for="supplierSelectUpdate" class="form-label">Choose Supplier</label>
                                                <select id="supplierSelectUpdate" class="form-select">
                                                    <option value="">-- Select a supplier (optional) --</option>
                                                    <?php foreach ($allSuppliers as $sup): ?>
                                                        <option 
                                                            value="<?= $sup['supplier_id'] ?>"
                                                            data-name="<?= htmlspecialchars($sup['supplier_name'], ENT_QUOTES) ?>"
                                                            data-contact="<?= htmlspecialchars($sup['supplier_contact_person'], ENT_QUOTES) ?>"
                                                            data-phone="<?= htmlspecialchars($sup['phone'], ENT_QUOTES) ?>"
                                                            data-email="<?= htmlspecialchars($sup['email'], ENT_QUOTES) ?>"
                                                            data-address="<?= htmlspecialchars($sup['address'], ENT_QUOTES) ?>"
                                                            data-notes="<?= htmlspecialchars($sup['notes'], ENT_QUOTES) ?>"
                                                        >
                                                            <?= htmlspecialchars($sup['supplier_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div><!-- end supplier dropdown -->

                                            <!-- ✅ Update Form -->
                                            <form id="updateForm" action="" method="POST">
                                                <input type="hidden" name="supplier_id" id="update_supplier_id">

                                                <!-- Supplier Name -->
                                                <div class="mb-3">
                                                    <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="supplier_name" id="update_supplier_name" class="form-control">
                                                </div><!-- end supplier name -->

                                                <!-- Contact Person -->
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person</label>
                                                    <input type="text" name="supplier_contact_person" id="update_supplier_contact" class="form-control">
                                                </div><!-- end contact person -->

                                                <!-- Phone -->
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" id="update_supplier_phone" class="form-control">
                                                </div><!-- end phone -->

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" id="update_supplier_email" class="form-control">
                                                </div><!-- end email -->

                                                <!-- Address -->
                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" id="update_supplier_address" class="form-control"></textarea>
                                                </div><!-- end address -->

                                                <!-- Notes -->
                                                <div class="mb-3">
                                                    <label class="form-label">Notes</label>
                                                    <textarea name="notes" id="update_supplier_notes" class="form-control"></textarea>
                                                </div><!-- end notes -->

                                                <!-- Submit Button -->
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                                                </div><!-- end submit button -->
                                            </form><!-- end update form -->

                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->


                                <div class="col-xxl-6">
                                    <div class="card border-danger">
                                        <div class="card-header align-items-center d-flex bg-danger-subtle">
                                            <h4 class="card-title mb-0 flex-grow-1 text-danger">Delete Supplier</h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                            <p class="text-muted">
                                                Select a supplier you wish to <strong>permanently delete</strong> from the database.
                                            </p>

                                            <!-- ✅ Show errors -->
                                            <?php if (!empty($errors)): ?>
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        <?php foreach ($errors as $error): ?>
                                                            <li><?= htmlspecialchars($error) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>

                                            <!-- ✅ Show success -->
                                            <?php if (!empty($_SESSION['success_message'])): ?>
                                                <div class="alert alert-success">
                                                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                                                    <?php unset($_SESSION['success_message']); ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- ✅ Supplier dropdown -->
                                            <div class="mb-3">
                                                <label for="deleteSupplierSelect" class="form-label">Choose Supplier to Delete</label>
                                                <select id="deleteSupplierSelect" class="form-select">
                                                    <option value="">-- Select a supplier --</option>
                                                    <?php foreach ($allSuppliers as $sup): ?>
                                                        <option 
                                                            value="<?= $sup['supplier_id'] ?>"
                                                            data-name="<?= htmlspecialchars($sup['supplier_name'], ENT_QUOTES) ?>"
                                                            data-contact="<?= htmlspecialchars($sup['supplier_contact_person'], ENT_QUOTES) ?>"
                                                            data-phone="<?= htmlspecialchars($sup['phone'], ENT_QUOTES) ?>"
                                                            data-email="<?= htmlspecialchars($sup['email'], ENT_QUOTES) ?>"
                                                            data-address="<?= htmlspecialchars($sup['address'], ENT_QUOTES) ?>"
                                                            data-notes="<?= htmlspecialchars($sup['notes'], ENT_QUOTES) ?>"
                                                        >
                                                            <?= htmlspecialchars($sup['supplier_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- ✅ Delete Form -->
                                            <form action="" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete this supplier?');">
                                                <input type="hidden" name="delete_supplier_id" id="delete_supplier_id">

                                                <div class="mb-3">
                                                    <label class="form-label">Supplier Name</label>
                                                    <input type="text" id="delete_supplier_name" class="form-control" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person</label>
                                                    <input type="text" id="delete_supplier_contact" class="form-control" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" id="delete_supplier_phone" class="form-control" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="text" id="delete_supplier_email" class="form-control" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea id="delete_supplier_address" class="form-control" rows="2" readonly></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Notes</label>
                                                    <textarea id="delete_supplier_notes" class="form-control" rows="2" readonly></textarea>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-danger">Delete Supplier</button>
                                                </div>
                                            </form>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

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
    <script src="../private/js/forms-supplier.js"></script>
  
</script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>