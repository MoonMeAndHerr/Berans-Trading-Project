<?php 
include __DIR__ . '/../include/header.php';
require_once __DIR__ . '/../private/usos-backend.php';
$usosConfigs = getUsosConfigs();
?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

<style>
    .usos-card {
        border: 1px solid var(--vz-border-color);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: var(--vz-card-bg);
    }
    
    .usos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--vz-border-color);
    }
    
    .usos-header h5 {
        color: var(--vz-body-color);
        margin: 0;
    }
    
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background: var(--vz-card-bg);
    }
    
    .schedule-table th {
        background-color: var(--vz-light);
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        border: 1px solid var(--vz-border-color);
        color: var(--vz-body-color);
    }
    
    .schedule-table td {
        padding: 0.75rem;
        border: 1px solid var(--vz-border-color);
        color: var(--vz-body-color);
        background: var(--vz-card-bg);
    }
    
    .schedule-table tr:hover td {
        background-color: var(--vz-light);
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
        background: var(--vz-light);
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--vz-border-color);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--vz-secondary-color);
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--vz-body-color);
    }
    
    /* Dark mode specific adjustments */
    [data-layout-mode="dark"] .badge-pending {
        background-color: rgba(254, 243, 199, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }
    
    [data-layout-mode="dark"] .badge-completed {
        background-color: rgba(209, 250, 229, 0.2);
        color: #34d399;
        border: 1px solid rgba(52, 211, 153, 0.3);
    }
    
    [data-layout-mode="dark"] .schedule-table th {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    [data-layout-mode="dark"] .schedule-table tr:hover td {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    [data-layout-mode="dark"] .stat-box {
        background: rgba(255, 255, 255, 0.05);
    }
    
    /* Form control dark mode */
    [data-layout-mode="dark"] .actual-arrival-input {
        background-color: var(--vz-input-bg);
        border-color: var(--vz-input-border);
        color: var(--vz-body-color);
    }
    
    [data-layout-mode="dark"] .actual-arrival-input:focus {
        background-color: var(--vz-input-bg);
        border-color: var(--vz-input-focus-border);
        color: var(--vz-body-color);
    }
    
    /* SweetAlert2 dark mode support */
    [data-layout-mode="dark"] .swal2-popup {
        background-color: var(--vz-card-bg) !important;
        color: var(--vz-body-color) !important;
    }
    
    [data-layout-mode="dark"] .swal2-title {
        color: var(--vz-body-color) !important;
    }
    
    [data-layout-mode="dark"] .swal2-html-container {
        color: var(--vz-body-color) !important;
    }
    
    [data-layout-mode="dark"] .swal2-input,
    [data-layout-mode="dark"] .swal2-textarea {
        background-color: var(--vz-input-bg) !important;
        border-color: var(--vz-input-border) !important;
        color: var(--vz-body-color) !important;
    }
    
    /* Card Hover Effect */
    .hover-shadow-lg {
        transition: all 0.3s ease;
    }
    .hover-shadow-lg:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important;
    }
    
    [data-layout-mode="dark"] .hover-shadow-lg:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(255,255,255,0.1) !important;
    }
    
    /* Horizontal Scroll Styling */
    .overflow-auto::-webkit-scrollbar {
        height: 8px;
    }
    .overflow-auto::-webkit-scrollbar-track {
        background: var(--vz-light);
        border-radius: 10px;
    }
    .overflow-auto::-webkit-scrollbar-thumb {
        background: var(--vz-secondary);
        border-radius: 10px;
    }
    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: var(--vz-primary);
    }
    
    [data-layout-mode="dark"] .overflow-auto::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
    }
    [data-layout-mode="dark"] .overflow-auto::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
    }
    [data-layout-mode="dark"] .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.3);
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

            <!-- Create USOS Modal -->
            <div class="modal fade" id="createUsosModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ri-add-circle-line me-2"></i>Create New USOS Configuration
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
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
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Shipping Method <span class="text-danger">*</span></label>
                                        <select class="form-select" id="shipping_code" name="shipping_code" required>
                                            <option value="">Select Shipping Method</option>
                                        </select>
                                        <small class="text-muted" id="shipping_info">Select shipping method to see delivery days</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="alert alert-info" id="lead_time_summary" style="display: none;">
                                            <i class="ri-information-line me-2"></i>
                                            <strong>Total Lead Time:</strong> 
                                            <span id="production_days_display">0</span> days (Production) + 
                                            <span id="delivery_days_display">0</span> days (Delivery) = 
                                            <span id="total_lead_time_display" class="text-primary fw-bold">0</span> days
                                        </div>
                                    </div>
                                    
                                    <!-- Product Selection Section -->
                                    <div class="col-12 mt-4">
                                        <h6 class="mb-3 text-muted"><i class="ri-shopping-bag-line me-2"></i>Product Selection (Optional)</h6>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" id="create_section">
                                            <option value="">Choose Section...</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" id="create_category">
                                            <option value="">Choose Category...</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Subcategory</label>
                                        <select class="form-select" id="create_subcategory">
                                            <option value="">Choose Subcategory...</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Product</label>
                                        <select class="form-select" id="create_product">
                                            <option value="">Choose Product...</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary w-100" id="addProductToUsos">
                                            <i class="ri-add-line me-1"></i>Add Product
                                        </button>
                                    </div>
                                    
                                    <!-- Item Summary -->
                                    <div class="col-12 mt-3" id="itemSummarySection" style="display: none;">
                                        <h6 class="mb-3 text-muted"><i class="ri-list-check me-2"></i>Item Summary</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm" id="usosItemList">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Product Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="card bg-light mt-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0"><i class="ri-shopping-bag-line me-2"></i>Item Summary</h6>
                                                    <div>
                                                        <span class="text-muted me-3">Total Items:</span>
                                                        <span class="h5 text-success mb-0" id="itemCount">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Cancel
                            </button>
                            <button type="submit" form="usosForm" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Create USOS Configuration
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of USOS Configurations -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-check-2 me-2"></i>Active USOS Configurations
                            </h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUsosModal">
                                <i class="ri-add-line me-1"></i>Add New USOS Configuration
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (empty($usosConfigs)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="ri-inbox-line" style="font-size: 3rem;"></i>
                                    <p class="mt-3">No USOS configurations found. Create one to get started!</p>
                                </div>
                            <?php else: ?>
                                <!-- Filter Controls -->
                                <div class="row g-2 mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" id="filterCustomer" placeholder="🔍 Filter by customer...">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" id="filterStatus">
                                            <option value="">All Status</option>
                                            <option value="active">Active</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" id="filterSort">
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                            <option value="customer">Customer A-Z</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-soft-secondary btn-sm w-100" onclick="resetFilters()">
                                            <i class="ri-refresh-line me-1"></i>Reset
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="vstack gap-2" id="usosCardsContainer">
                                    <?php foreach($usosConfigs as $index => $config): 
                                        $schedule = getUsosSchedule($config['usos_id']);
                                        $nextSchedule = !empty($schedule) ? $schedule[0] : null;
                                        $totalSchedules = count($schedule);
                                        $completedSchedules = count(array_filter($schedule, fn($s) => $s['is_completed']));
                                        $status = ($nextSchedule && !$nextSchedule['is_completed']) ? 'active' : 'completed';
                                        $customerName = htmlspecialchars($config['customer_company_name'] ?? $config['customer_name']);
                                    ?>
                                        <div class="card border shadow-sm hover-shadow-lg usos-card" 
                                             style="transition: all 0.3s ease;"
                                             data-customer="<?= strtolower($customerName) ?>"
                                             data-status="<?= $status ?>"
                                             data-created="<?= strtotime($config['created_at']) ?>">
                                            <div class="card-body">
                                                <div class="row align-items-center g-3">
                                                    <!-- Left: Customer Info with Avatar -->
                                                    <div class="col-lg-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                                <i class="ri-building-line fs-24"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-1 fw-semibold">
                                                                    <?= htmlspecialchars($config['customer_company_name'] ?? $config['customer_name']) ?>
                                                                </h5>
                                                                <small class="text-muted">
                                                                    <i class="ri-calendar-line me-1"></i><?= date('d M Y', strtotime($config['created_at'])) ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Middle: Stats in Horizontal Row -->
                                                    <div class="col-lg-6">
                                                        <div class="row g-2">
                                                            <div class="col-3">
                                                                <div class="p-2 bg-primary-subtle rounded-3 text-center">
                                                                    <p class="text-muted mb-0 fs-11">Monthly</p>
                                                                    <h6 class="mb-0 text-primary fw-bold"><?= number_format($config['monthly_usage']) ?></h6>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="p-2 bg-success-subtle rounded-3 text-center">
                                                                    <p class="text-muted mb-0 fs-11">Daily</p>
                                                                    <h6 class="mb-0 text-success fw-bold"><?= number_format($config['daily_usage'], 0) ?></h6>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="p-2 bg-info-subtle rounded-3 text-center">
                                                                    <p class="text-muted mb-0 fs-11">Total Lead</p>
                                                                    <h6 class="mb-0 text-info fw-bold"><?= ($config['production_lead_time_days'] + ($config['delivery_days'] ?? 0)) ?>d</h6>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="p-2 bg-warning-subtle rounded-3 text-center">
                                                                    <p class="text-muted mb-0 fs-11">Total Qty</p>
                                                                    <h6 class="mb-0 text-warning fw-bold"><?= number_format($config['total_quantity_ordered']) ?></h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Shipping & Schedule Info -->
                                                        <div class="d-flex gap-3 mt-2 pt-2 border-top flex-wrap">
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri-ship-line text-info me-2"></i>
                                                                <div>
                                                                    <small class="text-muted d-block fs-10">Shipping</small>
                                                                    <span class="fw-semibold fs-12"><?= htmlspecialchars($config['shipping_name'] ?? $config['shipping_code'] ?? 'N/A') ?> (<?= ($config['delivery_days'] ?? 0) ?>d)</span>
                                                                </div>
                                                            </div>
                                                            <div class="vr"></div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri-list-check text-secondary me-2"></i>
                                                                <div>
                                                                    <small class="text-muted d-block fs-10">Schedules</small>
                                                                    <span class="fw-semibold fs-12"><?= $completedSchedules ?>/<?= $totalSchedules ?> completed</span>
                                                                </div>
                                                            </div>
                                                            <?php if ($nextSchedule && !$nextSchedule['is_completed']): ?>
                                                                <div class="vr"></div>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri-calendar-check-line text-success me-2"></i>
                                                                    <div>
                                                                        <small class="text-muted d-block fs-10">Next Arrival</small>
                                                                        <span class="fw-semibold fs-12"><?= date('d M Y', strtotime($nextSchedule['arrival_date'])) ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Right: Status & Actions -->
                                                    <div class="col-lg-3">
                                                        <div class="d-flex flex-column gap-2 align-items-end">
                                                            <?php if ($nextSchedule && !$nextSchedule['is_completed']): ?>
                                                                <span class="badge bg-warning-subtle text-warning fs-13 px-3 py-2">
                                                                    <i class="ri-time-line me-1"></i>Active
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success-subtle text-success fs-13 px-3 py-2">
                                                                    <i class="ri-check-line me-1"></i>Completed
                                                                </span>
                                                            <?php endif; ?>
                                                            <div class="btn-group" role="group">
                                                                <button class="btn btn-info btn-sm" onclick="viewUsos(<?= $config['usos_id'] ?>)">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm" onclick="editUsos(<?= $config['usos_id'] ?>)">
                                                                    <i class="ri-edit-line"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" onclick="deleteUsos(<?= $config['usos_id'] ?>)">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div style="display:none" class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Monthly Usage</th>
                                                <th>Daily Usage</th>
                                                <th>Lead Time</th>
                                                <th>Next Order</th>
                                                <th>Next Arrival</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($usosConfigs as $index => $config): 
                                                $schedule = getUsosSchedule($config['usos_id']);
                                                $nextSchedule = !empty($schedule) ? $schedule[0] : null; // Get latest (first) schedule
                                            ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($config['customer_company_name'] ?? $config['customer_name']) ?></strong>
                                                    </td>
                                                    <td><?= number_format($config['monthly_usage']) ?> units</td>
                                                    <td><?= number_format($config['daily_usage'], 0) ?> units/day</td>
                                                    <td><?= $config['production_lead_time_days'] ?> days</td>
                                                    <td>
                                                        <?php if ($nextSchedule && !$nextSchedule['is_completed']): ?>
                                                            <span class="text-primary fw-bold">
                                                                <?= date('d M Y', strtotime($nextSchedule['order_date'])) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($nextSchedule && !$nextSchedule['is_completed']): ?>
                                                            <span class="text-success fw-bold">
                                                                <?= date('d M Y', strtotime($nextSchedule['arrival_date'])) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($nextSchedule && !$nextSchedule['is_completed']): ?>
                                                            <span class="badge bg-warning-subtle text-warning">
                                                                <i class="ri-time-line"></i> Pending
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success-subtle text-success">
                                                                <i class="ri-check-line"></i> Completed
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-soft-info" onclick="viewUsos(<?= $config['usos_id'] ?>)" title="View Details">
                                                                <i class="ri-eye-line"></i>
                                                            </button>
                                                            <button class="btn btn-soft-primary" onclick="editUsos(<?= $config['usos_id'] ?>)" title="Edit">
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                            <button class="btn btn-soft-danger" onclick="deleteUsos(<?= $config['usos_id'] ?>)" title="Delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div> <!-- END OLD TABLE HIDDEN -->
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->           
    </div><!-- End Page-content -->

    <!-- View Details Modal -->
    <div class="modal fade" id="viewUsosModal" tabindex="-1" aria-labelledby="viewUsosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUsosModalLabel">
                        <i class="ri-eye-line me-2"></i>USOS Configuration Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewUsosContent">
                    <!-- Content will be loaded here dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editUsosModal" tabindex="-1" aria-labelledby="editUsosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUsosModalLabel">
                        <i class="ri-edit-line me-2"></i>Edit USOS Configuration
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUsosForm">
                    <div class="modal-body" id="editUsosContent">
                        <!-- Content will be loaded here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        // Detect dark mode for Flatpickr
        const isDarkMode = document.documentElement.getAttribute('data-layout-mode') === 'dark';
        
        // Initialize Flatpickr for order date with dark mode support
        flatpickr("#order_date", {
            dateFormat: "Y-m-d",
            defaultDate: new Date(),
            theme: isDarkMode ? "dark" : "light"
        });
        
        // Watch for theme changes and reinitialize Flatpickr
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-layout-mode') {
                    const newTheme = document.documentElement.getAttribute('data-layout-mode') === 'dark' ? 'dark' : 'light';
                    flatpickr("#order_date", {
                        dateFormat: "Y-m-d",
                        defaultDate: new Date(),
                        theme: newTheme
                    });
                }
            });
        });
        
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-layout-mode']
        });
        
        
        // Calculate daily usage automatically
        document.getElementById('monthly_usage').addEventListener('input', function() {
            const monthlyUsage = parseFloat(this.value) || 0;
            const dailyUsage = Math.round(monthlyUsage / 30);
            document.getElementById('daily_usage_display').value = dailyUsage;
        });
        
        // Update delivery days display when shipping method changes
        document.getElementById('shipping_code').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const deliveryDays = selectedOption.getAttribute('data-delivery-days') || 0;
            const shippingInfo = document.getElementById('shipping_info');
            
            if (deliveryDays > 0) {
                shippingInfo.innerHTML = `<i class="ri-truck-line me-1"></i>Delivery time: <strong>${deliveryDays} days</strong>`;
                shippingInfo.classList.add('text-success');
            } else {
                shippingInfo.innerHTML = 'Select shipping method to see delivery days';
                shippingInfo.classList.remove('text-success');
            }
            
            updateTotalLeadTime();
        });
        
        // Update total lead time calculation
        document.getElementById('production_lead_time').addEventListener('input', updateTotalLeadTime);
        
        function updateTotalLeadTime() {
            const productionDays = parseInt(document.getElementById('production_lead_time').value) || 0;
            const shippingSelect = document.getElementById('shipping_code');
            const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
            const deliveryDays = parseInt(selectedOption.getAttribute('data-delivery-days')) || 0;
            const totalLeadTime = productionDays + deliveryDays;
            
            const summaryDiv = document.getElementById('lead_time_summary');
            if (productionDays > 0 && deliveryDays > 0) {
                document.getElementById('production_days_display').textContent = productionDays;
                document.getElementById('delivery_days_display').textContent = deliveryDays;
                document.getElementById('total_lead_time_display').textContent = totalLeadTime;
                summaryDiv.style.display = 'block';
            } else {
                summaryDiv.style.display = 'none';
            }
        }
        
        // Load customers
        loadCustomers();
        
        // Load shipping methods
        loadShippingMethods();
        
        // Load product hierarchy
        loadProductHierarchy();
        
        // Reset form when modal is closed
        document.getElementById('createUsosModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('usosForm').reset();
            document.getElementById('daily_usage_display').value = '';
            document.getElementById('lead_time_summary').style.display = 'none';
            document.getElementById('itemSummarySection').style.display = 'none';
            usosItems = [];
            itemCounter = 1;
            document.querySelector('#usosItemList tbody').innerHTML = '';
        });
        
        // Handle form submission
        document.getElementById('usosForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Add items data if any
            if (usosItems.length > 0) {
                formData.append('items', JSON.stringify(usosItems));
            }
            
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
                    // Close modal first
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createUsosModal'));
                    if (modal) modal.hide();
                    
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
        
        // Initialize actual arrival inputs
        initActualArrivalInputs();
    });
    
    function initActualArrivalInputs() {
        // Handle actual arrival date input
        document.querySelectorAll('.actual-arrival-input').forEach(input => {
            // Remove old listeners to avoid duplicates
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
            
            newInput.addEventListener('change', function() {
                const scheduleId = this.dataset.scheduleId;
                const usosId = this.dataset.usosId;
                const actualArrivalDate = this.value;
                const plannedArrivalDate = this.dataset.plannedArrival;
                
                if (!actualArrivalDate) return;
                
                // Get USOS config data from the row or card
                let totalQty, dailyUsage, leadTime;
                
                const row = this.closest('tr');
                if (row && row.dataset.totalQty) {
                    // Data from modal table row
                    totalQty = parseFloat(row.dataset.totalQty);
                    dailyUsage = parseFloat(row.dataset.dailyUsage);
                    leadTime = parseInt(row.dataset.leadTime);
                } else {
                    // Data from main page card
                    const usosCard = this.closest('.usos-card');
                    totalQty = parseFloat(usosCard.querySelector('.stat-value').textContent.replace(/,/g, ''));
                    dailyUsage = parseFloat(usosCard.querySelectorAll('.stat-value')[2].textContent.replace(/,/g, ''));
                    leadTime = parseInt(usosCard.querySelectorAll('.stat-value')[3].textContent);
                }
                
                // Compare actual vs planned arrival
                const actualDate = new Date(actualArrivalDate);
                const plannedDate = new Date(plannedArrivalDate);
                const diffTime = actualDate - plannedDate;
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                
                let arrivalStatus = '';
                let arrivalStatusColor = '';
                if (diffDays < 0) {
                    arrivalStatus = `✅ Early by ${Math.abs(diffDays)} day${Math.abs(diffDays) > 1 ? 's' : ''}`;
                    arrivalStatusColor = '#10b981'; // Green
                } else if (diffDays > 0) {
                    arrivalStatus = `⚠️ Late by ${diffDays} day${diffDays > 1 ? 's' : ''}`;
                    arrivalStatusColor = '#f59e0b'; // Orange
                } else {
                    arrivalStatus = '✓ On Time';
                    arrivalStatusColor = '#10b981'; // Green
                }
                
                // Calculate what will happen
                const daysUntilRunout = Math.floor(totalQty / dailyUsage);
                
                // Current run out date (when this stock runs out)
                const currentRunOutDate = new Date(actualArrivalDate);
                currentRunOutDate.setDate(currentRunOutDate.getDate() + daysUntilRunout);
                
                // Next arrival = current run out (just-in-time delivery, no gap)
                const nextArrivalDate = new Date(currentRunOutDate);
                
                // Next order date: arrival - lead time (order early enough)
                const nextOrderDate = new Date(nextArrivalDate);
                nextOrderDate.setDate(nextOrderDate.getDate() - leadTime);
                
                // Next run out date (when NEXT stock runs out)
                const nextRunOutDate = new Date(nextArrivalDate);
                nextRunOutDate.setDate(nextRunOutDate.getDate() + daysUntilRunout);
                
                const formatDate = (date) => {
                    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                };
                
                // Detect dark mode
                const isDarkMode = document.documentElement.getAttribute('data-layout-mode') === 'dark';
                
                // Define colors based on theme
                const colors = isDarkMode ? {
                    box1Bg: 'rgba(59, 130, 246, 0.15)',
                    box1Border: '#60a5fa',
                    box1Text: '#93c5fd',
                    box2Bg: 'rgba(245, 158, 11, 0.15)',
                    box2Border: '#fbbf24',
                    box2Text: '#fcd34d',
                    box3Bg: 'rgba(16, 185, 129, 0.15)',
                    box3Border: '#34d399',
                    box3Text: '#6ee7b7',
                    mutedText: '#9ca3af',
                    strongText: '#e5e7eb'
                } : {
                    box1Bg: '#f0f9ff',
                    box1Border: '#3b82f6',
                    box1Text: '#1e40af',
                    box2Bg: '#fef3c7',
                    box2Border: '#f59e0b',
                    box2Text: '#92400e',
                    box3Bg: '#d1fae5',
                    box3Border: '#10b981',
                    box3Text: '#065f46',
                    mutedText: '#6b7280',
                    strongText: '#111827'
                };
                
                Swal.fire({
                    title: 'Confirm Actual Arrival',
                    html: `
                        <div style="text-align: left; padding: 1rem;">
                            <p class="mb-3" style="color: ${colors.strongText};"><strong>📦 What will happen when you confirm:</strong></p>
                            
                            <div style="background: ${colors.box1Bg}; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid ${colors.box1Border}; margin-bottom: 1rem;">
                                <p style="margin: 0; font-size: 0.9rem; color: ${colors.box1Text};">
                                    <strong>1. Current Delivery Marked Complete</strong><br>
                                    <span style="color: ${colors.mutedText};">Planned arrival: <strong>${formatDate(plannedDate)}</strong></span><br>
                                    <span style="color: ${colors.mutedText};">Actual arrival: <strong>${formatDate(actualDate)}</strong></span><br>
                                    <span style="color: ${arrivalStatusColor}; font-weight: bold;">${arrivalStatus}</span>
                                </p>
                            </div>
                            
                            <div style="background: ${colors.box2Bg}; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid ${colors.box2Border}; margin-bottom: 1rem;">
                                <p style="margin: 0; font-size: 0.9rem; color: ${colors.box2Text};">
                                    <strong>2. Current Stock Will Run Out</strong><br>
                                    <span style="color: ${colors.mutedText};">Run Out Date: <strong>${formatDate(currentRunOutDate)}</strong> (in ${daysUntilRunout} days)</span><br>
                                    <span style="color: ${colors.mutedText}; font-size: 0.85rem;">Based on: ${totalQty.toLocaleString()} units ÷ ${dailyUsage.toLocaleString()} daily usage</span>
                                </p>
                            </div>
                            
                            <div style="background: ${colors.box3Bg}; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid ${colors.box3Border};">
                                <p style="margin: 0; font-size: 0.9rem; color: ${colors.box3Text};">
                                    <strong>3. Next Order Schedule Created (Just-in-Time)</strong><br>
                                    <span style="color: ${colors.mutedText};">📅 Order Date: <strong>${formatDate(nextOrderDate)}</strong></span><br>
                                    <span style="color: ${colors.mutedText};">📦 Arrival Date: <strong>${formatDate(nextArrivalDate)}</strong> (same as run out date)</span><br>
                                    <span style="color: ${colors.mutedText};">⚠️ Next Run Out: <strong>${formatDate(nextRunOutDate)}</strong> (${daysUntilRunout} days later)</span><br>
                                    <span style="color: ${colors.mutedText};">⏱️ Lead Time: <strong>${leadTime} days</strong></span>
                                </p>
                            </div>
                            
                            <p style="margin-top: 1rem; font-size: 0.85rem; color: ${colors.mutedText}; font-style: italic;">
                                💡 Just-in-Time Delivery: New stock arrives exactly when current stock runs out, ensuring zero downtime!
                            </p>
                        </div>
                    `,
                    icon: 'question',
                    width: '600px',
                    showCancelButton: true,
                    confirmButtonText: '✓ Confirm & Create Next Schedule',
                    cancelButtonText: '✕ Cancel',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateActualArrival(scheduleId, actualArrivalDate, usosId);
                    } else {
                        newInput.value = '';
                    }
                });
            });
        });
    }
    
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
    
    function loadShippingMethods() {
        fetch('../private/usos-backend.php?action=get_shipping_methods')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const selects = ['shipping_code', 'edit_shipping_code'];
                    selects.forEach(selectId => {
                        const select = document.getElementById(selectId);
                        if (select) {
                            // Clear existing options except the first one
                            while (select.options.length > 1) {
                                select.remove(1);
                            }
                            // Add shipping methods with delivery days data attribute
                            data.shipping_methods.forEach(method => {
                                const option = document.createElement('option');
                                option.value = method.shipping_code;
                                option.setAttribute('data-delivery-days', method.delivery_days);
                                option.textContent = `${method.shipping_code} - ${method.shipping_name} (${method.delivery_days} days)`;
                                select.appendChild(option);
                            });
                        }
                    });
                }
            });
    }
    
    // Product Selection Variables
    let productHierarchyData = {};
    let usosItems = [];
    let itemCounter = 1;
    
    // Load Product Hierarchy
    function loadProductHierarchy() {
        fetch('../private/usos-backend.php?action=get_product_hierarchy')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    productHierarchyData = data;
                    populateSections();
                    setupProductCascade();
                }
            });
    }
    
    function populateSections() {
        const sectionSelect = document.getElementById('create_section');
        if (sectionSelect) {
            productHierarchyData.sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.section_id;
                option.textContent = section.section_name;
                sectionSelect.appendChild(option);
            });
        }
    }
    
    function setupProductCascade() {
        const sectionSelect = document.getElementById('create_section');
        const categorySelect = document.getElementById('create_category');
        const subcategorySelect = document.getElementById('create_subcategory');
        const productSelect = document.getElementById('create_product');
        
        // Section change
        sectionSelect?.addEventListener('change', function() {
            const sectionId = this.value;
            categorySelect.innerHTML = '<option value="">Choose Category...</option>';
            subcategorySelect.innerHTML = '<option value="">Choose Subcategory...</option>';
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (sectionId) {
                const categories = productHierarchyData.categories.filter(c => c.section_id == sectionId);
                categories.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.category_id;
                    option.textContent = cat.category_name;
                    categorySelect.appendChild(option);
                });
            }
        });
        
        // Category change
        categorySelect?.addEventListener('change', function() {
            const categoryId = this.value;
            subcategorySelect.innerHTML = '<option value="">Choose Subcategory...</option>';
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (categoryId) {
                const subcategories = productHierarchyData.subcategories.filter(sc => sc.category_id == categoryId);
                subcategories.forEach(subcat => {
                    const option = document.createElement('option');
                    option.value = subcat.subcategory_id;
                    option.textContent = subcat.subcategory_name;
                    subcategorySelect.appendChild(option);
                });
            }
        });
        
        // Subcategory change
        subcategorySelect?.addEventListener('change', function() {
            const subcategoryId = this.value;
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (subcategoryId) {
                const products = productHierarchyData.products.filter(p => p.subcategory_id == subcategoryId);
                products.forEach(prod => {
                    const option = document.createElement('option');
                    option.value = prod.product_id;
                    option.textContent = prod.display_name;
                    option.setAttribute('data-moq', prod.moq || 0);
                    option.setAttribute('data-price', prod.price || 0);
                    option.setAttribute('data-price-id', prod.price_id || '');
                    productSelect.appendChild(option);
                });
            }
        });
        
    }
    
    // Add Product to USOS Items
    document.getElementById('addProductToUsos')?.addEventListener('click', function() {
        const productSelect = document.getElementById('create_product');
        
        const productId = productSelect.value;
        
        // Validation
        if (!productId) {
            Swal.fire('Error', 'Please select a product', 'error');
            return;
        }
        
        // Get product details
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productName = selectedOption.textContent;
        const priceId = selectedOption.getAttribute('data-price-id');
        const unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        
        // Add to items array
        const item = {
            product_id: productId,
            price_id: priceId,
            product_name: productName,
            quantity: 1,
            unit_price: unitPrice
        };
        
        usosItems.push(item);
        
        // Add to table
        const tbody = document.querySelector('#usosItemList tbody');
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${itemCounter}</td>
            <td>${productName}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeUsosItem(this, ${itemCounter - 1})">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        `;
        
        itemCounter++;
        
        // Show item summary section
        document.getElementById('itemSummarySection').style.display = 'block';
        
        // Update item count
        updateItemSubtotal();
        
        // Reset form
        productSelect.value = '';
        document.getElementById('create_category').value = '';
        document.getElementById('create_subcategory').value = '';
    });
    
    window.removeUsosItem = function(btn, index) {
        usosItems.splice(index, 1);
        btn.closest('tr').remove();
        updateItemSubtotal();
        
        if (usosItems.length === 0) {
            document.getElementById('itemSummarySection').style.display = 'none';
            itemCounter = 1;
        }
    }
    
    function updateItemSubtotal() {
        document.getElementById('itemCount').textContent = usosItems.length;
    }
    
    // Edit Modal Product Selection
    let editUsosItems = [];
    let editItemCounter = 1;
    let currentEditUsosId = null;
    
    function setupEditProductCascade() {
        const sectionSelect = document.getElementById('edit_section');
        const categorySelect = document.getElementById('edit_category');
        const subcategorySelect = document.getElementById('edit_subcategory');
        const productSelect = document.getElementById('edit_product');
        
        // Populate sections
        if (sectionSelect && productHierarchyData.sections) {
            productHierarchyData.sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.section_id;
                option.textContent = section.section_name;
                sectionSelect.appendChild(option);
            });
        }
        
        // Section change
        sectionSelect?.addEventListener('change', function() {
            const sectionId = this.value;
            categorySelect.innerHTML = '<option value="">Choose Category...</option>';
            subcategorySelect.innerHTML = '<option value="">Choose Subcategory...</option>';
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (sectionId) {
                const categories = productHierarchyData.categories.filter(c => c.section_id == sectionId);
                categories.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.category_id;
                    option.textContent = cat.category_name;
                    categorySelect.appendChild(option);
                });
            }
        });
        
        // Category change
        categorySelect?.addEventListener('change', function() {
            const categoryId = this.value;
            subcategorySelect.innerHTML = '<option value="">Choose Subcategory...</option>';
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (categoryId) {
                const subcategories = productHierarchyData.subcategories.filter(sc => sc.category_id == categoryId);
                subcategories.forEach(subcat => {
                    const option = document.createElement('option');
                    option.value = subcat.subcategory_id;
                    option.textContent = subcat.subcategory_name;
                    subcategorySelect.appendChild(option);
                });
            }
        });
        
        // Subcategory change
        subcategorySelect?.addEventListener('change', function() {
            const subcategoryId = this.value;
            productSelect.innerHTML = '<option value="">Choose Product...</option>';
            
            if (subcategoryId) {
                const products = productHierarchyData.products.filter(p => p.subcategory_id == subcategoryId);
                products.forEach(prod => {
                    const option = document.createElement('option');
                    option.value = prod.product_id;
                    option.textContent = prod.display_name;
                    option.setAttribute('data-price-id', prod.price_id || '');
                    option.setAttribute('data-price', prod.price || 0);
                    productSelect.appendChild(option);
                });
            }
        });
        
        // Add product button
        document.getElementById('addEditProduct')?.addEventListener('click', function() {
            const productSelect = document.getElementById('edit_product');
            
            const productId = productSelect.value;
            
            if (!productId) {
                Swal.fire('Error', 'Please select a product', 'error');
                return;
            }
            
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productName = selectedOption.textContent;
            const priceId = selectedOption.getAttribute('data-price-id');
            const unitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            const item = {
                product_id: productId,
                price_id: priceId,
                product_name: productName,
                quantity: 1,
                unit_price: unitPrice
            };
            
            editUsosItems.push(item);
            renderEditItems();
            
            // Reset
            productSelect.value = '';
        });
    }
    
    function loadEditUsosItems(usosId) {
        currentEditUsosId = usosId;
        fetch(`../private/usos-backend.php?action=get_usos_items&usos_id=${usosId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    editUsosItems = data.items.map(item => ({
                        product_id: item.product_id,
                        price_id: item.price_id,
                        product_name: item.product_name,
                        quantity: parseFloat(item.quantity),
                        unit_price: parseFloat(item.unit_price)
                    }));
                    renderEditItems();
                }
            });
    }
    
    function renderEditItems() {
        const container = document.getElementById('editItemList');
        if (!container) return;
        
        if (editUsosItems.length === 0) {
            container.innerHTML = '<div class="alert alert-info"><i class="ri-information-line me-2"></i>No products added yet</div>';
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>No</th><th>Product Name</th><th>Action</th></tr></thead><tbody>';
        
        editUsosItems.forEach((item, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.product_name}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeEditItem(${index})">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        html += `<div class="card bg-light mt-2"><div class="card-body p-2"><strong>Total Items: ${editUsosItems.length}</strong></div></div>`;
        
        container.innerHTML = html;
    }
    
    window.removeEditItem = function(index) {
        editUsosItems.splice(index, 1);
        renderEditItems();
    }
    
    function saveEditItems() {
        if (!currentEditUsosId) return Promise.resolve();
        
        const formData = new FormData();
        formData.append('usos_id', currentEditUsosId);
        formData.append('items', JSON.stringify(editUsosItems));
        
        return fetch('../private/usos-backend.php?action=save_usos_items', {
            method: 'POST',
            body: formData
        }).then(res => res.json());
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
                const isDarkMode = document.documentElement.getAttribute('data-layout-mode') === 'dark';
                const mutedColor = isDarkMode ? '#9ca3af' : '#6b7280';
                const strongColor = isDarkMode ? '#e5e7eb' : '#111827';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Delivery Recorded Successfully!',
                    html: `
                        <div style="text-align: left; padding: 0.5rem;">
                            <p style="margin-bottom: 0.5rem; color: ${strongColor};"><strong>✅ Actions Completed:</strong></p>
                            <ul style="text-align: left; padding-left: 1.5rem; margin: 0; color: ${strongColor};">
                                <li>Current delivery marked as complete</li>
                                <li>Actual arrival date recorded</li>
                                <li>Next order schedule automatically created</li>
                            </ul>
                            <p style="margin-top: 1rem; font-size: 0.9rem; color: ${mutedColor};">
                                📋 View the updated schedule below to see your next order date and delivery timeline.
                            </p>
                        </div>
                    `,
                    timer: 3000,
                    timerProgressBar: true
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
    
    function viewUsos(usosId) {
        // Show loading
        const modal = new bootstrap.Modal(document.getElementById('viewUsosModal'));
        document.getElementById('viewUsosContent').innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        modal.show();
        
        // Fetch USOS details
        fetch(`../private/usos-backend.php?action=get_usos&usos_id=${usosId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const config = data.data;
                    const schedule = data.schedule || [];
                    
                    let scheduleHtml = '';
                    if (schedule.length > 0) {
                        scheduleHtml = `
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="viewScheduleTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Order Date</th>
                                            <th>Planned Arrival</th>
                                            <th>Run Out Date</th>
                                            <th>Actual Arrival</th>
                                            <th>Timing</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        
                        schedule.forEach((entry, index) => {
                            let timingBadge = '-';
                            if (entry.actual_arrival_date) {
                                const planned = new Date(entry.arrival_date);
                                const actual = new Date(entry.actual_arrival_date);
                                const diffDays = Math.floor((actual - planned) / (1000 * 60 * 60 * 24));
                                
                                if (diffDays < 0) {
                                    timingBadge = `<span class="badge bg-success-subtle text-success"><i class="ri-arrow-down-line"></i> Early (${Math.abs(diffDays)}d)</span>`;
                                } else if (diffDays > 0) {
                                    timingBadge = `<span class="badge bg-warning-subtle text-warning"><i class="ri-arrow-up-line"></i> Late (${diffDays}d)</span>`;
                                } else {
                                    timingBadge = `<span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> On Time</span>`;
                                }
                            }
                            
                            // View modal is read-only - show text only
                            const actualArrivalCell = entry.actual_arrival_date 
                                ? `<span class="text-success fw-bold">${new Date(entry.actual_arrival_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</span>`
                                : `<span class="text-muted">Not recorded</span>`;
                            
                            scheduleHtml += `
                                <tr class="schedule-row">
                                    <td>${index + 1}</td>
                                    <td>${new Date(entry.order_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${new Date(entry.arrival_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${new Date(entry.run_out_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${actualArrivalCell}</td>
                                    <td>${timingBadge}</td>
                                    <td>${entry.is_completed ? '<span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> Completed</span>' : '<span class="badge bg-warning-subtle text-warning"><i class="ri-time-line"></i> Pending</span>'}</td>
                                </tr>`;
                        });
                        
                        scheduleHtml += `
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="viewScheduleInfo">Showing <span id="viewScheduleStart">1</span> to <span id="viewScheduleEnd">6</span> of <span id="viewScheduleTotal">${schedule.length}</span> entries</div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0" id="viewSchedulePagination"></ul>
                                </nav>
                            </div>`;
                    } else {
                        scheduleHtml = '<p class="text-muted text-center">No schedule entries yet</p>';
                    }
                    
                    document.getElementById('viewUsosContent').innerHTML = `
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6><i class="ri-building-line text-primary me-2"></i>Customer Information</h6>
                                <table class="table table-borderless table-sm">
                                    <tr><td class="text-muted" width="40%">Company Name:</td><td><strong>${config.customer_company_name || config.customer_name}</strong></td></tr>
                                    <tr><td class="text-muted">Created:</td><td>${new Date(config.created_at).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="ri-box-line text-primary me-2"></i>Order Configuration</h6>
                                <table class="table table-borderless table-sm">
                                    <tr><td class="text-muted" width="40%">Order Quantity:</td><td><strong>${parseFloat(config.total_quantity_ordered).toLocaleString()} units</strong></td></tr>
                                    <tr><td class="text-muted">Monthly Usage:</td><td><strong>${parseFloat(config.monthly_usage).toLocaleString()} units</strong></td></tr>
                                    <tr><td class="text-muted">Daily Usage:</td><td><strong>${Math.round(config.daily_usage).toLocaleString()} units/day</strong></td></tr>
                                    <tr><td class="text-muted">Production Lead Time:</td><td><strong>${config.production_lead_time_days} days</strong></td></tr>
                                    <tr><td class="text-muted">Shipping Method:</td><td><strong>${config.shipping_name || config.shipping_code || 'N/A'}</strong></td></tr>
                                    <tr><td class="text-muted">Delivery Days:</td><td><strong>${config.delivery_days || 0} days</strong></td></tr>
                                    <tr><td class="text-muted">Total Lead Time:</td><td><strong class="text-primary">${(parseInt(config.production_lead_time_days) + parseInt(config.delivery_days || 0))} days</strong></td></tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3 text-muted"><i class="ri-shopping-bag-line me-2"></i>Product Items</h6>
                        <div id="viewItemList"></div>
                        <hr>
                        <h6 class="mb-3 text-muted"><i class="ri-calendar-line me-2"></i>Delivery Schedule History</h6>
                        ${scheduleHtml}
                    `;
                    
                    // Load and display items for view modal
                    fetch(`../private/usos-backend.php?action=get_usos_items&usos_id=${config.usos_id}`)
                        .then(res => res.json())
                        .then(data => {
                            const container = document.getElementById('viewItemList');
                            if (data.success && data.items.length > 0) {
                                let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>No</th><th>Product Name</th></tr></thead><tbody>';
                                data.items.forEach((item, index) => {
                                    html += `<tr><td>${index + 1}</td><td>${item.product_name}</td></tr>`;
                                });
                                html += `</tbody></table></div><div class="alert alert-info"><strong>Total Items: ${data.items.length}</strong></div>`;
                                container.innerHTML = html;
                            } else {
                                container.innerHTML = '<div class="alert alert-secondary"><i class="ri-information-line me-2"></i>No products associated with this configuration</div>';
                            }
                        });
                    
                    // Initialize pagination for view modal if schedule exists
                    if (schedule.length > 0) {
                        setTimeout(() => initSchedulePagination('viewScheduleTable', 'viewSchedulePagination', 'viewScheduleStart', 'viewScheduleEnd', 'viewScheduleTotal', 6), 100);
                    }
                } else {
                    document.getElementById('viewUsosContent').innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                }
            })
            .catch(error => {
                document.getElementById('viewUsosContent').innerHTML = `<div class="alert alert-danger">Failed to load details: ${error.message}</div>`;
            });
    }
    
    function editUsos(usosId) {
        // Show loading
        const modal = new bootstrap.Modal(document.getElementById('editUsosModal'));
        document.getElementById('editUsosContent').innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        modal.show();
        
        // Fetch USOS details
        fetch(`../private/usos-backend.php?action=get_usos&usos_id=${usosId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const config = data.data;
                    const schedule = data.schedule || [];
                    
                    // Build schedule table with editable actual arrival dates
                    let scheduleHtml = '';
                    if (schedule.length > 0) {
                        scheduleHtml = `
                            <hr>
                            <h6 class="mb-3"><i class="ri-calendar-line text-primary me-2"></i>Delivery Schedule</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="editScheduleTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Order Date</th>
                                            <th>Planned Arrival</th>
                                            <th>Run Out Date</th>
                                            <th>Actual Arrival</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        
                        schedule.forEach((entry, index) => {
                            const actualArrivalCell = entry.actual_arrival_date 
                                ? `<span class="text-success fw-bold">${new Date(entry.actual_arrival_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</span>`
                                : `<input type="date" class="form-control form-control-sm actual-arrival-input" 
                                    data-schedule-id="${entry.schedule_id}" 
                                    data-usos-id="${config.usos_id}" 
                                    data-planned-arrival="${entry.arrival_date}">`;
                            
                            // Editable status dropdown
                            const statusCell = `
                                <select class="form-select form-select-sm status-select" 
                                        data-schedule-id="${entry.schedule_id}" 
                                        style="width: auto;">
                                    <option value="0" ${!entry.is_completed ? 'selected' : ''}>Pending</option>
                                    <option value="1" ${entry.is_completed ? 'selected' : ''}>Completed</option>
                                </select>`;
                            
                            scheduleHtml += `
                                <tr class="schedule-row" data-total-qty="${config.total_quantity_ordered}" data-daily-usage="${config.daily_usage}" data-lead-time="${config.production_lead_time_days}">
                                    <td>${index + 1}</td>
                                    <td>${new Date(entry.order_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${new Date(entry.arrival_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${new Date(entry.run_out_date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                                    <td>${actualArrivalCell}</td>
                                    <td>${statusCell}</td>
                                </tr>`;
                        });
                        
                        scheduleHtml += `
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="editScheduleInfo">Showing <span id="editScheduleStart">1</span> to <span id="editScheduleEnd">6</span> of <span id="editScheduleTotal">${schedule.length}</span> entries</div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0" id="editSchedulePagination"></ul>
                                </nav>
                            </div>`;
                    }
                    
                    document.getElementById('editUsosContent').innerHTML = `
                        <input type="hidden" name="usos_id" value="${config.usos_id}">
                        
                        <h6 class="mb-3"><i class="ri-settings-3-line text-primary me-2"></i>Configuration Settings</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <input type="text" class="form-control" value="${config.customer_company_name || config.customer_name}" readonly>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Quantity Ordered <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="total_quantity_ordered" value="${config.total_quantity_ordered}" required step="0.01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Monthly Usage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="monthly_usage" value="${config.monthly_usage}" required step="0.01">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Production Lead Time (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="production_lead_time_days" value="${config.production_lead_time_days}" required min="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Method <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_shipping_code" name="shipping_code" required>
                                    <option value="">Select Shipping Method</option>
                                </select>
                                <small class="text-muted" id="edit_shipping_info">Current: ${config.shipping_name || 'N/A'} (${config.delivery_days || 0} days)</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mb-3">
                            <i class="ri-alert-line me-2"></i>
                            <strong>Important:</strong> Changing production lead time or shipping method will only affect <strong>future schedules</strong>. Existing completed schedules remain unchanged.
                        </div>
                        
                        <div class="alert alert-success" id="edit_lead_time_summary">
                            <i class="ri-information-line me-2"></i>
                            <strong>Current Total Lead Time:</strong> 
                            <span id="edit_production_days_display">${config.production_lead_time_days}</span> days (Production) + 
                            <span id="edit_delivery_days_display">${config.delivery_days || 0}</span> days (Delivery) = 
                            <span id="edit_total_lead_time_display" class="text-primary fw-bold">${parseInt(config.production_lead_time_days) + parseInt(config.delivery_days || 0)}</span> days
                        </div>
                        
                        <!-- Product Selection Section -->
                        <h6 class="mb-3 text-muted mt-4"><i class="ri-shopping-bag-line me-2"></i>Manage Products</h6>
                        
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <select class="form-select form-select-sm" id="edit_section">
                                    <option value="">Choose Section...</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select class="form-select form-select-sm" id="edit_category">
                                    <option value="">Choose Category...</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select class="form-select form-select-sm" id="edit_subcategory">
                                    <option value="">Choose Subcategory...</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <select class="form-select form-select-sm" id="edit_product">
                                    <option value="">Choose Product...</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-sm btn-primary w-100" id="addEditProduct">
                                    <i class="ri-add-line me-1"></i>Add Product
                                </button>
                            </div>
                        </div>
                        
                        <div id="editItemList" class="mb-3"></div>
                        
                        ${scheduleHtml}
                    `;
                    
                    // Load shipping methods and set the current value
                    loadShippingMethods();
                    setTimeout(() => {
                        const shippingSelect = document.getElementById('edit_shipping_code');
                        if (shippingSelect && config.shipping_code) {
                            shippingSelect.value = config.shipping_code;
                        }
                        
                        // Add event listeners for dynamic updates in edit modal
                        const editProductionInput = document.querySelector('[name="production_lead_time_days"]');
                        const editShippingSelect = document.getElementById('edit_shipping_code');
                        
                        // Function to update edit modal lead time display
                        function updateEditLeadTime() {
                            const productionDays = parseInt(editProductionInput.value) || 0;
                            const selectedOption = editShippingSelect.options[editShippingSelect.selectedIndex];
                            const deliveryDays = parseInt(selectedOption.getAttribute('data-delivery-days')) || 0;
                            const totalLeadTime = productionDays + deliveryDays;
                            
                            // Update display elements
                            document.getElementById('edit_production_days_display').textContent = productionDays;
                            document.getElementById('edit_delivery_days_display').textContent = deliveryDays;
                            document.getElementById('edit_total_lead_time_display').textContent = totalLeadTime;
                            
                            // Update shipping info helper text
                            const shippingName = selectedOption.text;
                            if (selectedOption.value !== '') {
                                document.getElementById('edit_shipping_info').textContent = 'Selected: ' + shippingName + ' (' + deliveryDays + ' days)';
                            }
                        }
                        
                        // Add event listeners
                        if (editProductionInput) {
                            editProductionInput.addEventListener('input', updateEditLeadTime);
                        }
                        
                        if (editShippingSelect) {
                            editShippingSelect.addEventListener('change', updateEditLeadTime);
                        }
                        
                        // Setup product selection for edit modal
                        setupEditProductCascade();
                        loadEditUsosItems(config.usos_id);
                    }, 500);
                    
                    // Initialize pagination for edit modal if schedule exists
                    if (schedule.length > 0) {
                        setTimeout(() => initSchedulePagination('editScheduleTable', 'editSchedulePagination', 'editScheduleStart', 'editScheduleEnd', 'editScheduleTotal', 6), 100);
                    }
                    
                    // Reinitialize actual arrival listeners for the edit modal
                    initActualArrivalInputs();
                    
                    // Initialize status change listeners
                    initStatusChangeListeners();
                } else {
                    document.getElementById('editUsosContent').innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                }
            })
            .catch(error => {
                document.getElementById('editUsosContent').innerHTML = `<div class="alert alert-danger">Failed to load details: ${error.message}</div>`;
            });
    }
    
    // Handle edit form submission
    document.getElementById('editUsosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // First update USOS config
        fetch('../private/usos-backend.php?action=update_usos', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Then save items
                return saveEditItems().then(itemResult => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Configuration and items updated successfully',
                        timer: 2000
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editUsosModal')).hide();
                        window.location.reload();
                    });
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
                text: 'Failed to update: ' + error.message
            });
        });
    });
    
    // Initialize status change listeners
    function initStatusChangeListeners() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const scheduleId = this.dataset.scheduleId;
                const isCompleted = this.value;
                
                updateScheduleStatus(scheduleId, isCompleted);
            });
        });
    }
    
    // Update schedule status
    function updateScheduleStatus(scheduleId, isCompleted) {
        const formData = new FormData();
        formData.append('schedule_id', scheduleId);
        formData.append('is_completed', isCompleted);
        
        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('../private/usos-backend.php?action=update_schedule_status', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Schedule status updated successfully',
                    timer: 1500,
                    showConfirmButton: false
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
                text: 'Failed to update status: ' + error.message
            });
        });
    }
    
    // Pagination function for schedule tables
    function initSchedulePagination(tableId, paginationId, startId, endId, totalId, itemsPerPage) {
        const table = document.getElementById(tableId);
        if (!table) return;
        
        const rows = Array.from(table.querySelectorAll('tbody .schedule-row'));
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);
        let currentPage = 1;
        
        function showPage(page) {
            currentPage = page;
            
            // Hide all rows
            rows.forEach(row => row.style.display = 'none');
            
            // Show rows for current page
            const start = (page - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, totalRows);
            
            for (let i = start; i < end; i++) {
                rows[i].style.display = '';
            }
            
            // Update info text
            document.getElementById(startId).textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById(endId).textContent = end;
            document.getElementById(totalId).textContent = totalRows;
            
            // Update pagination buttons
            renderPagination();
        }
        
        function renderPagination() {
            const pagination = document.getElementById(paginationId);
            if (!pagination) return;
            
            let html = '';
            
            // Previous button
            html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                     </li>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                             </li>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            // Next button
            html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                     </li>`;
            
            pagination.innerHTML = html;
            
            // Add click listeners
            pagination.querySelectorAll('a.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.dataset.page);
                    if (page >= 1 && page <= totalPages) {
                        showPage(page);
                    }
                });
            });
        }
        
        // Show first page
        showPage(1);
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

    // Filter functionality
    function filterCards() {
        const customerFilter = document.getElementById('filterCustomer').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const sortFilter = document.getElementById('filterSort').value;
        
        const cards = Array.from(document.querySelectorAll('.usos-card'));
        
        // Filter
        cards.forEach(card => {
            const customerMatch = card.dataset.customer.includes(customerFilter);
            const statusMatch = !statusFilter || card.dataset.status === statusFilter;
            
            if (customerMatch && statusMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Sort visible cards
        const visibleCards = cards.filter(card => card.style.display !== 'none');
        const container = document.getElementById('usosCardsContainer');
        
        visibleCards.sort((a, b) => {
            if (sortFilter === 'newest') {
                return parseInt(b.dataset.created) - parseInt(a.dataset.created);
            } else if (sortFilter === 'oldest') {
                return parseInt(a.dataset.created) - parseInt(b.dataset.created);
            } else if (sortFilter === 'customer') {
                return a.dataset.customer.localeCompare(b.dataset.customer);
            }
            return 0;
        });
        
        // Re-append in sorted order
        visibleCards.forEach(card => container.appendChild(card));
        
        // Show no results message if needed
        const noResults = visibleCards.length === 0;
        let noResultsDiv = document.getElementById('noResultsMessage');
        
        if (noResults && !noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResultsMessage';
            noResultsDiv.className = 'text-center text-muted py-4';
            noResultsDiv.innerHTML = '<i class="ri-search-line fs-24"></i><p class="mt-2">No configurations match your filters</p>';
            container.appendChild(noResultsDiv);
        } else if (!noResults && noResultsDiv) {
            noResultsDiv.remove();
        }
    }
    
    function resetFilters() {
        document.getElementById('filterCustomer').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterSort').value = 'newest';
        filterCards();
    }
    
    // Attach filter listeners
    document.getElementById('filterCustomer').addEventListener('input', filterCards);
    document.getElementById('filterStatus').addEventListener('change', filterCards);
    document.getElementById('filterSort').addEventListener('change', filterCards);
</script>
</body>
</html>
