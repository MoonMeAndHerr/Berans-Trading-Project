<?php 
include __DIR__ . '/../include/header.php';
require_once __DIR__ . '/../private/usos-backend.php';
$usosConfigs = getUsosConfigs();
?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .usos-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: white;
    }
    
    .usos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .schedule-table th {
        background-color: #f3f4f6;
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        border: 1px solid #e5e7eb;
    }
    
    .schedule-table td {
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
    }
    
    .schedule-table tr:hover {
        background-color: #f9fafb;
    }
    
    .badge-pending {
        background-color: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-completed {
        background-color: #d1fae5;
        color: #065f46;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .stat-box {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }
</style>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">USOS - Unit Systematic Ordering System</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Product and Order</a></li>
                                <li class="breadcrumb-item active">USOS</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add New USOS Configuration Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-add-circle-line me-2"></i>Create New USOS Configuration
                            </h5>
                            <button type="button" class="btn btn-sm btn-primary" id="toggleFormBtn">
                                <i class="ri-arrow-down-s-line"></i> Show Form
                            </button>
                        </div>
                        <div class="card-body" id="usosFormContainer" style="display: none;">
                            <form id="usosForm">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select class="form-select" id="customer_id" name="customer_id" required>
                                            <option value="">Select Customer</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Order Date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="order_date" name="order_date" required readonly>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Total Quantity Ordered <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="total_quantity" name="total_quantity" step="0.01" required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Monthly Usage <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="monthly_usage" name="monthly_usage" step="0.01" required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Daily Usage (Auto-calculated)</label>
                                        <input type="text" class="form-control" id="daily_usage_display" readonly>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Production Lead Time (Days) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="production_lead_time" name="production_lead_time" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i>Create USOS Configuration
                                        </button>
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="ri-refresh-line me-1"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of USOS Configurations -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-check-2 me-2"></i>Active USOS Configurations
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($usosConfigs)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="ri-inbox-line" style="font-size: 3rem;"></i>
                                    <p class="mt-3">No USOS configurations found. Create one to get started!</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($usosConfigs as $config): ?>
                                    <div class="usos-card" data-usos-id="<?= $config['usos_id'] ?>">
                                        <div class="usos-header">
                                            <div>
                                                <h5 class="mb-1">
                                                    <i class="ri-building-line text-primary me-2"></i>
                                                    <?= htmlspecialchars($config['customer_company_name'] ?? $config['customer_name']) ?>
                                                </h5>
                                                <small class="text-muted">
                                                    Created: <?= date('d M Y', strtotime($config['created_at'])) ?>
                                                </small>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-info me-2" onclick="editUsos(<?= $config['usos_id'] ?>)">
                                                    <i class="ri-edit-line"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteUsos(<?= $config['usos_id'] ?>)">
                                                    <i class="ri-delete-bin-line"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Configuration Stats -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-label">Order Quantity</div>
                                                    <div class="stat-value"><?= number_format($config['total_quantity_ordered'], 2) ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-label">Monthly Usage</div>
                                                    <div class="stat-value"><?= number_format($config['monthly_usage'], 2) ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-label">Daily Usage</div>
                                                    <div class="stat-value"><?= number_format($config['daily_usage'], 2) ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-label">Lead Time</div>
                                                    <div class="stat-value"><?= $config['production_lead_time_days'] ?> days</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Schedule Table -->
                                        <div>
                                            <h6 class="mb-2"><i class="ri-calendar-line me-2"></i>Delivery Schedule</h6>
                                            <div class="table-responsive">
                                                <table class="schedule-table">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Order Date</th>
                                                            <th>Arrival Date</th>
                                                            <th>Run Out Date</th>
                                                            <th>Actual Arrival</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="schedule-tbody" data-usos-id="<?= $config['usos_id'] ?>">
                                                        <?php 
                                                        $schedule = getUsosSchedule($config['usos_id']);
                                                        if (empty($schedule)):
                                                        ?>
                                                            <tr>
                                                                <td colspan="6" class="text-center text-muted">No schedule entries yet</td>
                                                            </tr>
                                                        <?php else: ?>
                                                            <?php foreach($schedule as $index => $entry): ?>
                                                                <tr>
                                                                    <td><?= $index + 1 ?></td>
                                                                    <td><?= date('d M Y', strtotime($entry['order_date'])) ?></td>
                                                                    <td><?= date('d M Y', strtotime($entry['arrival_date'])) ?></td>
                                                                    <td><?= date('d M Y', strtotime($entry['run_out_date'])) ?></td>
                                                                    <td>
                                                                        <?php if ($entry['actual_arrival_date']): ?>
                                                                            <span class="text-success fw-bold">
                                                                                <?= date('d M Y', strtotime($entry['actual_arrival_date'])) ?>
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <input type="date" 
                                                                                   class="form-control form-control-sm actual-arrival-input" 
                                                                                   data-schedule-id="<?= $entry['schedule_id'] ?>"
                                                                                   data-usos-id="<?= $config['usos_id'] ?>">
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($entry['is_completed']): ?>
                                                                            <span class="badge-completed">
                                                                                <i class="ri-checkbox-circle-fill me-1"></i>Completed
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <span class="badge-pending">
                                                                                <i class="ri-time-line me-1"></i>Pending
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
<!-- SweetAlert2 JS -->
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="assets/js/app.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for order date
        flatpickr("#order_date", {
            dateFormat: "Y-m-d",
            defaultDate: new Date()
        });
        
        // Toggle form visibility
        document.getElementById('toggleFormBtn').addEventListener('click', function() {
            const formContainer = document.getElementById('usosFormContainer');
            const btn = this;
            
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
                btn.innerHTML = '<i class="ri-arrow-up-s-line"></i> Hide Form';
            } else {
                formContainer.style.display = 'none';
                btn.innerHTML = '<i class="ri-arrow-down-s-line"></i> Show Form';
            }
        });
        
        // Calculate daily usage automatically
        document.getElementById('monthly_usage').addEventListener('input', function() {
            const monthlyUsage = parseFloat(this.value) || 0;
            const dailyUsage = (monthlyUsage / 30).toFixed(2);
            document.getElementById('daily_usage_display').value = dailyUsage;
        });
        
        // Load customers
        loadCustomers();
        
        // Handle form submission
        document.getElementById('usosForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            Swal.fire({
                title: 'Creating USOS Configuration...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('../private/usos-backend.php?action=create_usos', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to create USOS configuration: ' + error.message
                });
            });
        });
        
        // Handle actual arrival date input
        document.querySelectorAll('.actual-arrival-input').forEach(input => {
            input.addEventListener('change', function() {
                const scheduleId = this.dataset.scheduleId;
                const usosId = this.dataset.usosId;
                const actualArrivalDate = this.value;
                
                if (!actualArrivalDate) return;
                
                Swal.fire({
                    title: 'Confirm Actual Arrival',
                    text: 'This will mark the delivery as complete and create the next schedule entry.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateActualArrival(scheduleId, actualArrivalDate, usosId);
                    } else {
                        this.value = '';
                    }
                });
            });
        });
    });
    
    function loadCustomers() {
        fetch('../private/usos-backend.php?action=get_customers')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('customer_id');
                    data.customers.forEach(customer => {
                        const option = document.createElement('option');
                        option.value = customer.customer_id;
                        option.textContent = customer.customer_company_name || customer.customer_name;
                        select.appendChild(option);
                    });
                }
            });
    }
    
    function updateActualArrival(scheduleId, actualArrivalDate, usosId) {
        const formData = new FormData();
        formData.append('schedule_id', scheduleId);
        formData.append('actual_arrival_date', actualArrivalDate);
        formData.append('usos_id', usosId);
        
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('../private/usos-backend.php?action=update_actual_arrival', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.error
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to update actual arrival: ' + error.message
            });
        });
    }
    
    function deleteUsos(usosId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will delete the USOS configuration and all associated schedules.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('usos_id', usosId);
                
                fetch('../private/usos-backend.php?action=delete_usos', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error
                        });
                    }
                });
            }
        });
    }
    
    function editUsos(usosId) {
        Swal.fire({
            icon: 'info',
            title: 'Edit Feature',
            text: 'Edit functionality coming soon! For now, you can delete and create a new configuration.'
        });
    }
</script>
</body>
</html>
