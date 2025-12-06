<?php 
    include __DIR__ . '/../private/forms-add-supplier.php';
    include __DIR__ . '/../include/header.php';
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Supplier Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="dashboard-projects.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Suppliers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Suppliers</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i> Active
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                        <span class="counter-value" data-target="<?= $supplierCount ?>">0</span>
                                    </h4>
                                    <span class="badge bg-success-subtle text-success mb-0">
                                        <i class="ri-checkbox-circle-line align-middle"></i> All Active
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="ri-store-2-line text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Last Added</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-info fs-14 mb-0">
                                        <i class="ri-time-line fs-13 align-middle"></i> Recent
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                        <?= $lastSupplierDate ? date('d M', strtotime($lastSupplierDate)) : 'N/A' ?>
                                    </h4>
                                    <span class="badge bg-info-subtle text-info mb-0">
                                        <?= $lastSupplierDate ? date('Y', strtotime($lastSupplierDate)) : 'No Data' ?>
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="ri-calendar-check-line text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-12">
                    <div class="card card-animate bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-white-50 mb-0">Quick Actions</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button class="btn btn-light btn-sm flex-fill" onclick="showAddForm()">
                                    <i class="ri-add-circle-line align-middle me-1"></i> Add New
                                </button>
                                <button class="btn btn-light btn-sm flex-fill" onclick="showUpdateForm()">
                                    <i class="ri-edit-2-line align-middle me-1"></i> Update
                                </button>
                                <button class="btn btn-light btn-sm flex-fill" onclick="refreshTable()">
                                    <i class="ri-refresh-line align-middle me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($errors)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <strong>Error!</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success_add): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            <?= htmlspecialchars($success_add) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success_update): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            <?= htmlspecialchars($success_update) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success_delete): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ri-delete-bin-line me-2"></i>
                            <?= htmlspecialchars($success_delete) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Add Supplier Form (Collapsible) -->
            <div class="row" id="addFormContainer" style="display: none;">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success-subtle">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-add-circle-line align-middle me-2"></i> Add New Supplier
                                </h5>
                                <button type="button" class="btn btn-sm btn-ghost-danger" onclick="hideAddForm()">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" id="addSupplierForm">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="supplier_name" class="form-control" placeholder="Enter supplier name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" name="supplier_contact_person" class="form-control" placeholder="Enter contact person">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" placeholder="+60 12-345 6789">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="example@supplier.com">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Enter complete address"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control" placeholder="e.g., Kuala Lumpur">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Region/State</label>
                                        <input type="text" name="region" class="form-control" placeholder="e.g., Selangor">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="postcode" class="form-control" placeholder="e.g., 50000">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control" placeholder="e.g., Malaysia">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes or special instructions (optional)"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-light" onclick="hideAddForm()">Cancel</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-save-line align-middle me-1"></i> Save Supplier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Supplier Form (Collapsible) -->
            <div class="row" id="updateFormContainer" style="display: none;">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary-subtle">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-edit-2-line align-middle me-2"></i> Update Supplier
                                </h5>
                                <button type="button" class="btn btn-sm btn-ghost-danger" onclick="hideUpdateForm()">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Select Supplier to Update</label>
                                <select id="supplierSelectUpdate" class="form-select">
                                    <option value="">-- Choose a supplier --</option>
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
                            </div>

                            <form id="updateForm" action="" method="POST">
                                <input type="hidden" name="supplier_id" id="update_supplier_id">
                                <input type="hidden" name="xero_relation" id="update_supplier_xero_relation">
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="supplier_name" id="update_supplier_name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" name="supplier_contact_person" id="update_supplier_contact" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" id="update_supplier_phone" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="update_supplier_email" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" id="update_supplier_address" class="form-control" rows="2"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" id="update_supplier_city" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Region</label>
                                        <input type="text" name="region" id="update_supplier_region" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Postcode</label>
                                        <input type="text" name="postcode" id="update_supplier_postcode" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" id="update_supplier_country" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" id="update_supplier_notes" class="form-control" rows="3"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-end">
                                            <button type="button" class="btn btn-light" onclick="hideUpdateForm()">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line align-middle me-1"></i> Update Supplier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier List Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-list-check-2 align-middle me-2"></i> Supplier Directory
                                </h5>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-success btn-sm" onclick="showAddForm()">
                                        <i class="ri-add-line align-middle me-1"></i> Add Supplier
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="supplierTable" class="table table-hover table-bordered nowrap align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Country</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($allSuppliers as $supplier): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($supplier['supplier_name']) ?></strong></td>
                                            <td><?= htmlspecialchars($supplier['supplier_contact_person'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php if ($supplier['phone']): ?>
                                                    <i class="ri-phone-line text-success me-1"></i><?= htmlspecialchars($supplier['phone']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($supplier['email']): ?>
                                                    <i class="ri-mail-line text-primary me-1"></i><?= htmlspecialchars($supplier['email']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($supplier['city'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($supplier['country'] ?? 'N/A') ?></td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-center">
                                                    <button class="btn btn-sm btn-soft-info" 
                                                            onclick='viewSupplierDetails(<?= json_encode($supplier) ?>)'
                                                            data-bs-toggle="tooltip" title="View Details">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-soft-primary" 
                                                            onclick="editSupplier(<?= $supplier['supplier_id'] ?>)"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ri-edit-2-line"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-soft-danger" 
                                                            onclick="deleteSupplier(<?= $supplier['supplier_id'] ?>, '<?= htmlspecialchars($supplier['supplier_name']) ?>')"
                                                            data-bs-toggle="tooltip" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include __DIR__ . '/../include/footer.php';?>
</div>

<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-subtle">
                <h5 class="modal-title">
                    <i class="ri-information-line me-2"></i>Supplier Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-building-line me-1"></i>Supplier Name</label>
                            <h6 class="fw-semibold" id="view_supplier_name"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-user-line me-1"></i>Contact Person</label>
                            <h6 id="view_contact_person"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-phone-line me-1"></i>Phone</label>
                            <h6 id="view_phone"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-mail-line me-1"></i>Email</label>
                            <h6 id="view_email"></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-map-pin-line me-1"></i>Address</label>
                            <h6 id="view_address"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">City</label>
                            <h6 id="view_city"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Region/State</label>
                            <h6 id="view_region"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Postal Code</label>
                            <h6 id="view_postcode"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Country</label>
                            <h6 id="view_country"></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-file-text-line me-1"></i>Notes</label>
                            <div class="alert alert-secondary mb-0" id="view_notes"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editSupplierFromModal()">
                    <i class="ri-edit-2-line me-1"></i>Edit Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../include/themesetting.php';?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/libs/prismjs/prism.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/app.js"></script>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Global variable
var currentSupplier = null;

// Show/Hide Form Functions
function showAddForm() {
    $('#addFormContainer').slideDown(300);
    $('#updateFormContainer').slideUp(300);
    $('html, body').animate({
        scrollTop: $('#addFormContainer').offset().top - 100
    }, 300);
}

function hideAddForm() {
    $('#addFormContainer').slideUp(300);
}

function showUpdateForm() {
    $('#updateFormContainer').slideDown(300);
    $('#addFormContainer').slideUp(300);
    $('html, body').animate({
        scrollTop: $('#updateFormContainer').offset().top - 100
    }, 300);
}

function hideUpdateForm() {
    $('#updateFormContainer').slideUp(300);
    $('#supplierSelectUpdate').val(null).trigger('change');
}

function refreshTable() {
    location.reload();
}

// View Supplier Details
function viewSupplierDetails(supplier) {
    currentSupplier = supplier;
    $('#view_supplier_name').text(supplier.supplier_name || 'N/A');
    $('#view_contact_person').text(supplier.supplier_contact_person || 'N/A');
    $('#view_phone').text(supplier.phone || 'N/A');
    $('#view_email').text(supplier.email || 'N/A');
    $('#view_address').text(supplier.address || 'N/A');
    $('#view_city').text(supplier.city || 'N/A');
    $('#view_region').text(supplier.region || 'N/A');
    $('#view_postcode').text(supplier.postcode || 'N/A');
    $('#view_country').text(supplier.country || 'N/A');
    $('#view_notes').text(supplier.notes || 'No notes available');
    $('#viewSupplierModal').modal('show');
}

// Edit Supplier
function editSupplier(supplierId) {
    showUpdateForm();
    setTimeout(() => {
        $('#supplierSelectUpdate').val(supplierId).trigger('change');
    }, 350);
}

function editSupplierFromModal() {
    if (currentSupplier) {
        $('#viewSupplierModal').modal('hide');
        editSupplier(currentSupplier.supplier_id);
    }
}

// Delete Supplier
function deleteSupplier(supplierId, supplierName) {
    localStorage.setItem('supplierTableFromEdit', 'true');
    
    Swal.fire({
        title: 'Delete Supplier?',
        html: `Are you sure you want to delete <strong>${supplierName}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f06548',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_supplier_id';
            input.value = supplierId;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        } else {
            localStorage.removeItem('supplierTableFromEdit');
        }
    });
}

// Document Ready
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter persistence logic
    const shouldRestore = localStorage.getItem('supplierTableFromEdit') === 'true';
    
    if (!shouldRestore) {
        localStorage.removeItem('DataTables_supplierTable');
    }
    
    // Initialize DataTable
    var supplierTable = $('#supplierTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        order: [[0, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search suppliers...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ suppliers",
            infoEmpty: "No suppliers found",
            infoFiltered: "(filtered from _MAX_ total suppliers)",
            zeroRecords: "No matching suppliers found"
        },
        stateSave: true,
        stateDuration: -1,
        stateSaveCallback: function(settings, data) {
            localStorage.setItem('DataTables_supplierTable', JSON.stringify(data));
        },
        stateLoadCallback: function(settings) {
            if (!shouldRestore) return null;
            try {
                return JSON.parse(localStorage.getItem('DataTables_supplierTable'));
            } catch (e) {
                return null;
            }
        },
        initComplete: function() {
            if (shouldRestore) {
                localStorage.removeItem('supplierTableFromEdit');
            }
        }
    });

    // Initialize Select2
    $('#supplierSelectUpdate').select2({
        placeholder: "-- Choose a supplier --",
        allowClear: true,
        width: '100%'
    });

    // Populate update form when supplier selected
    $('#supplierSelectUpdate').on('select2:select', function(e) {
        const selectedOption = $(this).find('option:selected');
        $('#update_supplier_id').val(selectedOption.val());
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
    });

    // Clear form when selection cleared
    $('#supplierSelectUpdate').on('select2:clear', function() {
        $('#updateForm')[0].reset();
        $('#update_supplier_id').val('');
        $('#update_supplier_xero_relation').val('');
    });

    // Add form submission
    $('#addSupplierForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Add Supplier?',
            text: 'Are you sure you want to add this supplier?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ab57d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Update form submission
    $('#updateForm').on('submit', function(e) {
        e.preventDefault();
        localStorage.setItem('supplierTableFromEdit', 'true');
        
        Swal.fire({
            title: 'Update Supplier?',
            text: 'Are you sure you want to update this supplier?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ab57d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            } else {
                localStorage.removeItem('supplierTableFromEdit');
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

</body>
</html>
