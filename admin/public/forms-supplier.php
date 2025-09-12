<?php 
    include __DIR__ . '/../private/forms-add-supplier.php';
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
                                    <h4 class="mb-sm-0">Supplier</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Supplier</li>
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
                                    <div class="card-body">
                                        <ul class="nav nav-pills animation-nav nav-justified gap-2 mb-3" role="tablist">
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#animation-home" role="tab">
                                                    Add 
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-bs-toggle="tab" href="#animation-profile" role="tab">
                                                    Update 
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-bs-toggle="tab" href="#animation-messages" role="tab">
                                                    Delete   
                                                </a>
                                            </li>
                                        </ul>

                                        <!------------- ADD PANE -------------->

                                        <div class="tab-content text-muted">
                                            <div class="tab-pane active" id="animation-home" role="tabpanel">
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

                                                <!-- ✅ Show Add success message -->
                                                <?php if ($success_add): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_add) ?></div>
                                                <?php endif; ?>

                                                <!-- ✅ Show Update success message -->
                                                <?php if ($success_update): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_update) ?></div>
                                                <?php endif; ?>
                                                <!-- end success message -->

                                                <!-- ✅ Show Delete Success -->
                                                <?php if ($success_delete): ?>
                                                    <div class="alert alert-danger"><?= htmlspecialchars($success_delete) ?></div>
                                                <?php endif; ?>
                                                <!-- end success message -->

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

                                                            <!-- City -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="cityInput" class="form-label">City</label>
                                                                    <input type="text" name="city" class="form-control" id="cityInput"
                                                                        placeholder="Mentakab"
                                                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Region -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="regionInput" class="form-label">Region</label>
                                                                    <input type="text" name="region" class="form-control" id="regionInput"
                                                                        placeholder="Kelantan"
                                                                        value="<?= htmlspecialchars($_POST['region'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- PostCode -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="postcodeInput" class="form-label">Postal Code</label>
                                                                    <input type="text" name="postcode" class="form-control" id="postcodeInput"
                                                                        placeholder="13200"
                                                                        value="<?= htmlspecialchars($_POST['postcode'] ?? '') ?>">
                                                                </div>
                                                            </div><!-- end col -->

                                                            <!-- Country -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="countryInput" class="form-label">Country</label>
                                                                    <input type="text" name="country" class="form-control" id="countryInput"
                                                                        placeholder="Malaysia"
                                                                        value="<?= htmlspecialchars($_POST['country'] ?? '') ?>">
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
                                            </div>

                                            <!------------- UPDATE PANE -------------->

                                            <div class="tab-pane" id="animation-profile" role="tabpanel">
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

                                                <!-- ✅ Show Add success message -->
                                                <?php if ($success_add): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_add) ?></div>
                                                <?php endif; ?>

                                                <!-- ✅ Show Update success message -->
                                                <?php if ($success_update): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_update) ?></div>
                                                <?php endif; ?>
                                                <!-- end success message -->

                                                <!-- ✅ Show Delete Success -->
                                                <?php if ($success_delete): ?>
                                                    <div class="alert alert-danger"><?= htmlspecialchars($success_delete) ?></div>
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
                                                                data-city="<?= htmlspecialchars($sup['city'], ENT_QUOTES) ?>"
                                                                data-region="<?= htmlspecialchars($sup['region'], ENT_QUOTES) ?>"
                                                                data-postcode="<?= htmlspecialchars($sup['postcode'], ENT_QUOTES) ?>"
                                                                data-country="<?= htmlspecialchars($sup['country'], ENT_QUOTES) ?>"
                                                                data-notes="<?= htmlspecialchars($sup['notes'], ENT_QUOTES) ?>"
                                                                data-xero="<?= htmlspecialchars($sup['xero_relation'], ENT_QUOTES) ?>"
                                                            >
                                                                <?= htmlspecialchars($sup['supplier_name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div><!-- end supplier dropdown -->

                                                <!-- ✅ Update Form -->
                                                <form id="updateForm" action="" method="POST">
                                                    <div class="row">
                                                        <input type="hidden" name="supplier_id" id="update_supplier_id">
                                                        <input type="hidden" name="xero_relation" id="update_supplier_xero_relation">
                                                        <!-- Supplier Name -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="supplier_name" id="update_supplier_name" class="form-control">
                                                        </div><!-- end supplier name -->

                                                        <!-- Contact Person -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Contact Person</label>
                                                                <input type="text" name="supplier_contact_person" id="update_supplier_contact" class="form-control">
                                                            </div>
                                                        </div><!-- end contact person -->

                                                        <!-- Phone -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Phone</label>
                                                                <input type="text" name="phone" id="update_supplier_phone" class="form-control">
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Email -->
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="email" id="update_supplier_email" class="form-control">
                                                            </div>
                                                        </div><!-- end email -->

                                                        <!-- Address -->
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address</label>
                                                                <textarea name="address" id="update_supplier_address" class="form-control"></textarea>
                                                            </div>
                                                        </div><!-- end address -->

                                                        <!-- City -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">City</label>
                                                                <input type="text" name="city" id="update_supplier_city" class="form-control">
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Region -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Region</label>
                                                                <input type="text" name="region" id="update_supplier_region" class="form-control">
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Postcode -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Postcode</label>
                                                                <input type="text" name="postcode" id="update_supplier_postcode" class="form-control">
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Country -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Country</label>
                                                                <input type="text" name="country" id="update_supplier_country" class="form-control">
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Notes -->
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Notes</label>
                                                                <textarea name="notes" id="update_supplier_notes" class="form-control"></textarea>
                                                            </div>
                                                        </div><!-- end notes -->

                                                        <!-- Submit Button -->
                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                                                        </div><!-- end submit button -->
                                                    </div>
                                                </form><!-- end update form -->                                            
                                            </div>


                                            <!------------- DELETE PANE -------------->


                                            <div class="tab-pane" id="animation-messages" role="tabpanel">
                                                
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

                                            <?php if (!empty($errors)): ?>
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            <?php foreach ($errors as $e): ?>
                                                                <li><?= htmlspecialchars($e) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>s
                                                <?php endif; ?><!-- end error messages -->

                                                <!-- ✅ Show Add success message -->
                                                <?php if ($success_add): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_add) ?></div>
                                                <?php endif; ?>

                                                <!-- ✅ Show Update success message -->
                                                <?php if ($success_update): ?>
                                                    <div class="alert alert-success"><?= htmlspecialchars($success_update) ?></div>
                                                <?php endif; ?>
                                                <!-- end success message -->

                                                <!-- ✅ Show Delete Success -->
                                                <?php if ($success_delete): ?>
                                                    <div class="alert alert-danger"><?= htmlspecialchars($success_delete) ?></div>
                                                <?php endif; ?>
                                                <!-- end success message -->

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
                                                            data-city="<?= htmlspecialchars($sup['city'], ENT_QUOTES) ?>"
                                                            data-region="<?= htmlspecialchars($sup['region'], ENT_QUOTES) ?>"
                                                            data-postcode="<?= htmlspecialchars($sup['postcode'], ENT_QUOTES) ?>"
                                                            data-country="<?= htmlspecialchars($sup['country'], ENT_QUOTES) ?>"
                                                            data-notes="<?= htmlspecialchars($sup['notes'], ENT_QUOTES) ?>"
                                                        >
                                                            <?= htmlspecialchars($sup['supplier_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- ✅ Delete Form -->
                                                <form action="" method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete this supplier?');">
                                                    <div class="row">
                                                        <input type="hidden" name="delete_supplier_id" id="delete_supplier_id">

                                                        <div class="mb-3">
                                                            <label class="form-label">Supplier Name</label>
                                                            <input type="text" id="delete_supplier_name" class="form-control" readonly>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Contact Person</label>
                                                                <input type="text" id="delete_supplier_contact" class="form-control" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Phone</label>
                                                                <input type="text" id="delete_supplier_phone" class="form-control" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="text" id="delete_supplier_email" class="form-control" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Address</label>
                                                            <textarea id="delete_supplier_address" class="form-control" rows="2" readonly></textarea>
                                                        </div>

                                                        <!-- City -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">City</label>
                                                                <input type="text" id="delete_supplier_city" class="form-control" readonly>
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Region -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Region</label>
                                                                <input type="text" id="delete_supplier_region" class="form-control" readonly>
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Postcode -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Postcode</label>
                                                                <input type="text" id="delete_supplier_postcode" class="form-control" readonly>
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <!-- Country -->
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Country</label>
                                                                <input type="text" id="delete_supplier_country" class="form-control" readonly>
                                                            </div>
                                                        </div><!-- end phone -->

                                                        <div class="mb-3">
                                                            <label class="form-label">Notes</label>
                                                            <textarea id="delete_supplier_notes" class="form-control" rows="2" readonly></textarea>
                                                        </div>

                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-danger">Delete Supplier</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <p class="text-muted">
                                             Please fill in the supplier information below. Fields marked with <span class="text-danger">*</span> are required.
                                        </p>




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

                                




                        </div><!--end row-->
                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- END layout-wrapper -->
    

    <?php include __DIR__ . '/../include/themesetting.php';?>

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom Select2 Bootstrap styling -->
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 6px 12px;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-image: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: 26px;
            padding-left: 0;
            padding-right: 20px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 8px;
        }
        
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd;
            color: white;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 6px 12px;
            font-size: 0.875rem;
        }
        
        .select2-container {
            width: 100% !important;
        }
        
        .select2-dropdown {
            max-width: 100%;
            min-width: 300px;
        }
        
        .select2-results {
            max-height: 200px;
        }
    </style>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="../private/js/forms-supplier.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for Update dropdown
            $('#supplierSelectUpdate').select2({
                placeholder: "-- Select a supplier (optional) --",
                allowClear: true,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function() {
                        return "No suppliers found";
                    },
                    searching: function() {
                        return "Searching...";
                    }
                }
            });

            // Initialize Select2 for Delete dropdown
            $('#deleteSupplierSelect').select2({
                placeholder: "-- Select a supplier to delete --",
                allowClear: true,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function() {
                        return "No suppliers found";
                    },
                    searching: function() {
                        return "Searching...";
                    }
                }
            });

            // Populate Update fields when supplier is selected
            $('#supplierSelectUpdate').on('select2:select', function(e) {
                const selectedValue = e.params.data.id;
                const selectedOption = $(this).find('option[value="' + selectedValue + '"]');
                
                if (selectedValue) {
                    $('#update_supplier_id').val(selectedValue);
                    $('#update_supplier_name').val(selectedOption.data('name') || '');
                    $('#update_supplier_contact').val(selectedOption.data('contact') || '');
                    $('#update_supplier_phone').val(selectedOption.data('phone') || '');
                    $('#update_supplier_email').val(selectedOption.data('email') || '');
                    $('#update_supplier_address').val(selectedOption.data('address') || '');
                    $('#update_supplier_city').val(selectedOption.data('city') || '');
                    $('#update_supplier_region').val(selectedOption.data('region') || '');
                    $('#update_supplier_postcode').val(selectedOption.data('postcode') || '');
                    $('#update_supplier_country').val(selectedOption.data('country') || '');
                    $('#update_supplier_notes').val(selectedOption.data('notes') || '');
                    $('#update_supplier_xero_relation').val(selectedOption.data('xero') || '');
                }
            });

            // Clear Update fields when selection is cleared
            $('#supplierSelectUpdate').on('select2:clear', function() {
                $('#update_supplier_id').val('');
                $('#update_supplier_name').val('');
                $('#update_supplier_contact').val('');
                $('#update_supplier_phone').val('');
                $('#update_supplier_email').val('');
                $('#update_supplier_address').val('');
                $('#update_supplier_city').val('');
                $('#update_supplier_region').val('');
                $('#update_supplier_postcode').val('');
                $('#update_supplier_country').val('');
                $('#update_supplier_notes').val('');
                $('#update_supplier_xero_relation').val('');
            });

            // Populate Delete fields when supplier is selected
            $('#deleteSupplierSelect').on('select2:select', function(e) {
                const selectedValue = e.params.data.id;
                const selectedOption = $(this).find('option[value="' + selectedValue + '"]');
                
                if (selectedValue) {
                    $('#delete_supplier_id').val(selectedValue);
                    $('#delete_supplier_name').val(selectedOption.data('name') || '');
                    $('#delete_supplier_contact').val(selectedOption.data('contact') || '');
                    $('#delete_supplier_phone').val(selectedOption.data('phone') || '');
                    $('#delete_supplier_email').val(selectedOption.data('email') || '');
                    $('#delete_supplier_address').val(selectedOption.data('address') || '');
                    $('#delete_supplier_city').val(selectedOption.data('city') || '');
                    $('#delete_supplier_region').val(selectedOption.data('region') || '');
                    $('#delete_supplier_postcode').val(selectedOption.data('postcode') || '');
                    $('#delete_supplier_country').val(selectedOption.data('country') || '');
                    $('#delete_supplier_notes').val(selectedOption.data('notes') || '');
                }
            });

            // Clear Delete fields when selection is cleared
            $('#deleteSupplierSelect').on('select2:clear', function() {
                $('#delete_supplier_id').val('');
                $('#delete_supplier_name').val('');
                $('#delete_supplier_contact').val('');
                $('#delete_supplier_phone').val('');
                $('#delete_supplier_email').val('');
                $('#delete_supplier_address').val('');
                $('#delete_supplier_city').val('');
                $('#delete_supplier_region').val('');
                $('#delete_supplier_postcode').val('');
                $('#delete_supplier_country').val('');
                $('#delete_supplier_notes').val('');
            });

            // Re-initialize Select2 when tabs are changed to ensure proper display
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr('href');
                if (target === '#animation-profile') {
                    // Update tab - reinitialize
                    setTimeout(() => {
                        $('#supplierSelectUpdate').select2('destroy').select2({
                            placeholder: "-- Select a supplier (optional) --",
                            allowClear: true,
                            width: '100%',
                            theme: 'default'
                        });
                    }, 100);
                } else if (target === '#animation-messages') {
                    // Delete tab - reinitialize
                    setTimeout(() => {
                        $('#deleteSupplierSelect').select2('destroy').select2({
                            placeholder: "-- Select a supplier to delete --",
                            allowClear: true,
                            width: '100%',
                            theme: 'default'
                        });
                    }, 100);
                }
            });
        });
    </script>

</body>

</html>