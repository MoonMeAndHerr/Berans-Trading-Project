<?php include __DIR__ . '/../private/staff-add-backend.php';?>

<?php 
    include __DIR__ . '/../include/header.php'; 
?>

<!-- Start right Content here -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Staff Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Staff</li>
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
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Staff</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-team-line fs-13 align-middle"></i> Active
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                        <?= $totalRows ?>
                                    </h4>
                                    <span class="badge bg-success-subtle text-success mb-0">
                                        <i class="ri-user-line align-middle"></i> Registered
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="ri-team-line text-success"></i>
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
                                    <p class="text-uppercase fw-medium text-muted mb-0">Companies</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-info fs-14 mb-0">
                                        <i class="ri-building-line fs-13 align-middle"></i> Linked
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                        <?= count($companies) ?>
                                    </h4>
                                    <span class="badge bg-info-subtle text-info mb-0">
                                        <i class="ri-building-2-line align-middle"></i> Available
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="ri-building-4-line text-info"></i>
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
                                    <p class="text-uppercase fw-medium text-muted mb-0">Quick Actions</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-primary fs-14 mb-0">
                                        <i class="ri-flashlight-line fs-13 align-middle"></i> Ready
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                        <i class="ri-add-line align-middle"></i> Add Staff
                                    </button>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="ri-user-add-line text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['successDelete'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    <?= htmlspecialchars($_SESSION['successDelete']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['successDelete']); ?>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Staff Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">
                                <i class="ri-team-line me-2"></i>Staff Directory
                            </h5>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                    <i class="ri-add-line align-middle"></i> Add New Staff
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($staffList)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap align-middle mb-0" id="staffTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Staff #</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Company</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($staffList as $staff): ?>
                                            <tr>
                                                <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($staff['staff_number']) ?></span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                <?= strtoupper(substr($staff['staff_name'], 0, 1)) ?>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?= htmlspecialchars($staff['staff_name']) ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($staff['staff_designation'] ?: '-') ?></td>
                                                <td><?= htmlspecialchars($staff['username'] ?: '-') ?></td>
                                                <td><?= htmlspecialchars($staff['email']) ?></td>
                                                <td>
                                                    <?php
                                                    $roleBadge = [
                                                        'admin' => 'danger',
                                                        'manager' => 'warning',
                                                        'sales' => 'success',
                                                        'warehouse' => 'info',
                                                        'staff' => 'secondary'
                                                    ];
                                                    $badgeClass = $roleBadge[$staff['role']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $badgeClass ?>-subtle text-<?= $badgeClass ?>">
                                                        <?= htmlspecialchars(ucfirst($staff['role'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($staff['company_name'] ?: '-') ?></td>
                                                <td>
                                                    <div class="hstack gap-2">
                                                        <a href="staff-edit-update.php?staff_id=<?= urlencode($staff['staff_id']) ?>" 
                                                           class="btn btn-sm btn-primary edit-staff-btn" 
                                                           title="Edit Staff">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-info" 
                                                                onclick="viewStaff(<?= htmlspecialchars(json_encode($staff)) ?>)" 
                                                                title="View Details">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-soft-primary text-primary fs-3 rounded-circle">
                                            <i class="ri-user-search-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-1">No Staff Found</h5>
                                    <p class="text-muted mb-3">Start by adding your first staff member</p>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                        <i class="ri-add-line align-middle"></i> Add Staff
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include __DIR__ . '/../include/footer.php';?>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addStaffModalLabel">
                    <i class="ri-user-add-line me-2"></i>Add New Staff Member
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="addStaffForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Staff Name -->
                        <div class="col-md-6">
                            <label for="staff_name" class="form-label">Staff Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="staff_name" name="staff_name" 
                                   placeholder="Enter full name" required>
                        </div>

                        <!-- Designation -->
                        <div class="col-md-6">
                            <label for="staff_designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="staff_designation" name="staff_designation" 
                                   placeholder="e.g., Sales Manager">
                        </div>

                        <!-- About -->
                        <div class="col-12">
                            <label for="staff_about" class="form-label">About</label>
                            <textarea class="form-control" id="staff_about" name="staff_about" 
                                      rows="2" placeholder="Brief description about staff member"></textarea>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="staff_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="staff_number" name="staff_number" 
                                   placeholder="Enter phone number" required>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Enter username">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="email@example.com" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter password" required>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">-- Select Role --</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="sales">Sales</option>
                                <option value="warehouse">Warehouse</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <!-- Company -->
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">Company</label>
                            <select class="form-select" id="company_id" name="company_id">
                                <option value="">-- No Company (Optional) --</option>
                                <?php foreach ($companies as $c): ?>
                                    <option value="<?= htmlspecialchars($c['company_id']) ?>">
                                        <?= htmlspecialchars($c['company_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Add Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Staff Modal -->
<div class="modal fade" id="viewStaffModal" tabindex="-1" aria-labelledby="viewStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewStaffModalLabel">
                    <i class="ri-user-line me-2"></i>Staff Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg mx-auto mb-2">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-1" id="viewStaffInitial">
                            A
                        </span>
                    </div>
                    <h5 class="mb-1" id="viewStaffName">-</h5>
                    <p class="text-muted mb-0" id="viewStaffDesignation">-</p>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" width="40%">Staff Number:</th>
                                <td class="text-muted" id="viewStaffNumber">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Username:</th>
                                <td class="text-muted" id="viewStaffUsername">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Email:</th>
                                <td class="text-muted" id="viewStaffEmail">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Role:</th>
                                <td id="viewStaffRole">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Company:</th>
                                <td class="text-muted" id="viewStaffCompany">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="viewStaffEditBtn" class="btn btn-primary">
                    <i class="ri-pencil-line me-1"></i>Edit Staff
                </a>
            </div>
        </div>
    </div>
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

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="assets/js/app.js"></script>

<script>
$(document).ready(function() {
    // Check if returning from edit page
    var staffTableFromEdit = localStorage.getItem('staffTableFromEdit');
    
    // Initialize DataTable with state save
    var table = $('#staffTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        stateSave: true,
        stateDuration: 60 * 60, // 1 hour
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search staff...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ staff members",
            infoEmpty: "No staff members found",
            infoFiltered: "(filtered from _TOTAL_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        stateSaveCallback: function(settings, data) {
            localStorage.setItem('DataTables_staffTable', JSON.stringify(data));
        },
        stateLoadCallback: function(settings) {
            // If returning from edit, load saved state
            if (staffTableFromEdit === 'true') {
                var savedState = localStorage.getItem('DataTables_staffTable');
                localStorage.removeItem('staffTableFromEdit'); // Clear flag
                return savedState ? JSON.parse(savedState) : null;
            }
            // Otherwise, don't load state (fresh page load)
            return null;
        }
    });

    // Handle edit button clicks - set flag for filter persistence
    $('.edit-staff-btn').on('click', function(e) {
        localStorage.setItem('staffTableFromEdit', 'true');
    });

    // Show modal on form submission errors/success
    <?php if (!empty($errors) || $success): ?>
        var addStaffModal = new bootstrap.Modal(document.getElementById('addStaffModal'));
        addStaffModal.show();
    <?php endif; ?>
});

// View Staff Details Function
function viewStaff(staff) {
    document.getElementById('viewStaffInitial').textContent = staff.staff_name.charAt(0).toUpperCase();
    document.getElementById('viewStaffName').textContent = staff.staff_name;
    document.getElementById('viewStaffDesignation').textContent = staff.staff_designation || '-';
    document.getElementById('viewStaffNumber').textContent = staff.staff_number;
    document.getElementById('viewStaffUsername').textContent = staff.username || '-';
    document.getElementById('viewStaffEmail').textContent = staff.email;
    
    // Role badge
    var roleBadges = {
        'admin': 'danger',
        'manager': 'warning',
        'sales': 'success',
        'warehouse': 'info',
        'staff': 'secondary'
    };
    var badgeClass = roleBadges[staff.role] || 'secondary';
    document.getElementById('viewStaffRole').innerHTML = 
        '<span class="badge bg-' + badgeClass + '-subtle text-' + badgeClass + '">' + 
        staff.role.charAt(0).toUpperCase() + staff.role.slice(1) + '</span>';
    
    document.getElementById('viewStaffCompany').textContent = staff.company_name || '-';
    document.getElementById('viewStaffEditBtn').href = 'staff-edit-update.php?staff_id=' + staff.staff_id;
    
    var viewModal = new bootstrap.Modal(document.getElementById('viewStaffModal'));
    viewModal.show();
}
</script>

</body>
</html>
