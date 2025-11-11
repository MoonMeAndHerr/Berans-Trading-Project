<?php

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';
include __DIR__ . '/../include/header.php';
?>

    <!-- Custom Profit & Loss Styles -->
    <link href="assets/css/profit-loss.css" rel="stylesheet" type="text/css" />
    
    <style>
        /* Normal green for completed on time */
        #profitLossTable tbody tr.completed-on-time td {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
            transition: all 0.2s ease;
        }
        
        #profitLossTable tbody tr.completed-on-time {
            border-left: 5px solid #10b981;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        #profitLossTable tbody tr.completed-on-time:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }
        
        /* Teal/Emerald for completed late (after overdue) */
        #profitLossTable tbody tr.completed-late td {
            background-color: rgba(20, 184, 166, 0.15) !important;
            color: #0f766e !important;
            transition: all 0.2s ease;
        }
        
        #profitLossTable tbody tr.completed-late {
            border-left: 5px solid #14b8a6 !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        #profitLossTable tbody tr.completed-late:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.2);
        }
        
        /* Yellow styling for started/pending orders */
        #profitLossTable tbody tr.started-row td {
            background-color: rgba(251, 191, 36, 0.15);
            color: #78350f;
            transition: all 0.2s ease;
        }
        
        #profitLossTable tbody tr.started-row {
            border-left: 5px solid #f59e0b;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        #profitLossTable tbody tr.started-row:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.2);
        }
        
        /* Orange styling for overdue orders (past ETA) */
        #profitLossTable tbody tr.overdue-row td {
            background-color: rgba(249, 115, 22, 0.15) !important;
            color: #c2410c !important;
            transition: all 0.2s ease;
        }
        
        #profitLossTable tbody tr.overdue-row {
            border-left: 5px solid #ea580c !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        #profitLossTable tbody tr.overdue-row:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.2);
        }
        
        /* Status Modal Button Styling */
        .status-option {
            text-align: left;
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        
        .status-option:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .status-option.active {
            border-width: 2px !important;
            font-weight: 600;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }
    </style>



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Profit & Loss Management</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Order</a></li>
                                            <li class="breadcrumb-item active">Profit & Loss Management</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Clean Security Access -->
                        <div id="securitySection">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="clean-card p-4 text-center">
                                        <div class="mb-4">
                                            <i class="ri-lock-line" style="font-size: 48px; opacity: 0.6;"></i>
                                        </div>
                                        <h3 class="mb-2">Security Verification</h3>
                                        <p class="text-muted mb-4">Enter your access key to view profit & loss data</p>
                                        
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="password" class="form-control-clean" id="accessKey" 
                                                       placeholder="Access key" onkeypress="if(event.key === 'Enter') verifyAccess()">
                                                <button type="button" class="btn btn-clean-outline" id="toggleKeyVisibility">
                                                    <i class="ri-eye-line" id="keyIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-clean px-4" onclick="verifyAccess()">
                                            <i class="ri-unlock-line me-2"></i>Verify Access
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div id="mainContent" style="display: none;">
                            <!-- Filter Controls -->
                            <div class="card mb-4">
                                <div class="card-body py-3">
                                    <!-- Payment Summary Row -->
                                    <div class="row g-3 mb-3 border-bottom pb-3" id="paymentSummaryRow">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded bg-light p-2">
                                                        <i class="ri-hand-coin-line text-primary" style="font-size: 18px;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">
                                                        <span id="totalCommissionPayments">RM 0.00</span> / 
                                                        <span id="totalCommissionRemaining" class="text-danger">RM 0.00</span> / 
                                                        <span id="excludedCommissionRemaining" class="text-muted">(RM 0.00)</span>
                                                    </h6>
                                                    <small class="text-muted">Commission Paid / Remaining / (No Status)</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded bg-light p-2">
                                                        <i class="ri-truck-line text-success" style="font-size: 18px;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">
                                                        <span id="totalShippingPayments">RM 0.00</span> / 
                                                        <span id="totalShippingRemaining" class="text-danger">RM 0.00</span> / 
                                                        <span id="excludedShippingRemaining" class="text-muted">(RM 0.00)</span>
                                                    </h6>
                                                    <small class="text-muted">Shipping Paid / Remaining / (No Status)</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded bg-light p-2">
                                                        <i class="ri-building-line text-warning" style="font-size: 18px;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">
                                                        <span id="totalSupplierPayments">RM 0.00</span> / 
                                                        <span id="totalSupplierRemaining" class="text-danger">RM 0.00</span> / 
                                                        <span id="excludedSupplierRemaining" class="text-muted">(RM 0.00)</span>
                                                    </h6>
                                                    <small class="text-muted">Supplier Paid / Remaining / (No Status)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Zakat Summary Row -->
                                    <div class="row g-3 mt-2 border-bottom pb-3">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded bg-light p-2">
                                                        <i class="ri-funds-line text-info" style="font-size: 18px;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">
                                                        <span id="totalZakat" class="text-info">RM 0.00</span> / 
                                                        <span id="excludedZakat" class="text-muted">(RM 0.00)</span>
                                                    </h6>
                                                    <small class="text-muted">Zakat (With Status) / (No Status)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Count Display -->
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <div class="alert alert-info mb-0 py-2" style="background-color: rgba(13, 110, 253, 0.1); border: 1px solid rgba(13, 110, 253, 0.2);">
                                                <small class="text-muted">
                                                    <i class="ri-information-line me-1"></i>
                                                    Summary above: <strong id="includedOrderCount">0</strong> orders with status (Paid/Remaining) + 
                                                    <strong id="excludedOrderCount">0</strong> orders without status (shown in parentheses)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label fw-medium text-muted">Filter by Month</label>
                                            <input type="month" id="monthFilter" class="form-control" placeholder="Select month">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-medium text-muted">Filter by Status</label>
                                            <select id="statusFilter" class="form-select">
                                                <option value="">All Status</option>
                                                <option value="not_started">Not Started</option>
                                                <option value="pending">Pending</option>
                                                <option value="started">Started</option>
                                                <option value="overdue">Overdue</option>
                                                <option value="completed_on_time">Completed On Time</option>
                                                <option value="completed_late">Completed Late</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium text-muted">Search Orders</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="text" id="searchInput" class="form-control border-start-0" 
                                                       placeholder="Search by order number, customer name, or date...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium text-muted">Date Range</label>
                                            <div class="input-group">
                                                <input type="date" id="dateFromFilter" class="form-control" placeholder="From">
                                                <span class="input-group-text bg-light px-2">to</span>
                                                <input type="date" id="dateToFilter" class="form-control" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-clean btn-sm" onclick="applyFilters()" title="Apply Filters">
                                                    <i class="ri-filter-line"></i> Filter
                                                </button>
                                                <button class="btn btn-clean-outline btn-sm" onclick="clearFilters()" title="Clear Filters">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                                <button class="btn btn-clean-outline btn-sm" onclick="loadProfitLossData()" title="Refresh Data">
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission Summary Section -->
                            <div class="row mb-4" id="commissionSummarySection" style="display: none;">
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-primary bg-opacity-10 border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="card-title mb-0 text-primary fw-bold">
                                                    <i class="ri-team-line me-2"></i>Staff Commission Summary
                                                </h6>
                                                <button class="btn btn-outline-primary btn-sm" onclick="toggleCommissionSummary()">
                                                    <i class="ri-eye-line"></i> View Details
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-medium">Date From</label>
                                                    <input type="date" id="commissionDateFrom" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-medium">Date To</label>
                                                    <input type="date" id="commissionDateTo" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-medium">Staff (Optional)</label>
                                                    <select id="commissionStaffFilter" class="form-select">
                                                        <option value="">All Staff</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button class="btn btn-primary w-100" onclick="loadCommissionSummary()">
                                                        <i class="ri-calculator-line me-1"></i>Calculate
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Summary Display -->
                                            <div id="commissionSummaryDisplay" class="mt-4" style="display: none;">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <h5 class="text-primary mb-1" id="totalProfit">RM 0</h5>
                                                            <small class="text-muted">Total Profit</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <h5 class="text-success mb-1" id="totalCommissionDue">RM 0</h5>
                                                            <small class="text-muted">Commission Due</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <h5 class="text-info mb-1" id="totalCommissionPaid">RM 0</h5>
                                                            <small class="text-muted">Commission Paid</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <h5 class="text-danger mb-1" id="totalCommissionRemaining">RM 0</h5>
                                                            <small class="text-muted">Remaining to Pay</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Staff Breakdown -->
                                                <div class="mt-4">
                                                    <h6 class="fw-bold mb-3">Staff Breakdown</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Staff Name</th>
                                                                    <th>Orders</th>
                                                                    <th>Revenue</th>
                                                                    <th>Profit</th>
                                                                    <th>Commission Due</th>
                                                                    <th>Paid</th>
                                                                    <th>Remaining</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="staffCommissionBreakdown">
                                                                <tr>
                                                                    <td colspan="8" class="text-center text-muted">
                                                                        Select date range and click Calculate
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clean Orders Table -->
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="profitLossTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 9%">Order #</th>
                                                <th style="width: 16%">Customer</th>
                                                <th style="width: 11%">Order Date</th>
                                                <th style="width: 13%">Profit/Loss</th>
                                                <th style="width: 13%">Total Paid</th>
                                                <th style="width: 11%">Remaining</th>
                                                <th style="width: 12%">Staff Commission</th>
                                                <th style="width: 9%">Status</th>
                                                <th style="width: 6%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="profitLossTableBody">
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="ri-loader-4-line"></i> Loading data...
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="card-footer bg-light border-top-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div id="paginationInfo" class="text-muted">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                                <!-- Will be populated by JavaScript -->
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->

                <!-- Profit Details Modal -->
                <div class="modal fade" id="profitDetailsModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Profit & Loss Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="profitDetailsContent">
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading details...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Management Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light border-0 py-4">
                                <div>
                                    <h5 class="modal-title fw-bold text-dark mb-1">Payment Management</h5>
                                    <p class="text-muted small mb-0">Manage supplier, shipping, and commission payments</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <!-- Clean Tab Navigation -->
                                <div class="border-bottom px-4 pt-3">
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active border-0 px-4 py-3 fw-medium" data-bs-toggle="tab" href="#supplierPaymentTab" role="tab">
                                                <i class="ri-factory-line me-2 fs-5"></i>
                                                Supplier Payments
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link border-0 px-4 py-3 fw-medium" data-bs-toggle="tab" href="#shippingPaymentTab" role="tab">
                                                <i class="ri-ship-line me-2 fs-5"></i>
                                                Shipping Payments
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link border-0 px-4 py-3 fw-medium" data-bs-toggle="tab" href="#commissionPaymentTab" role="tab">
                                                <i class="ri-team-line me-2 fs-5"></i>
                                                Commission Payment
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Supplier Payment Tab -->
                                    <div class="tab-pane active" id="supplierPaymentTab" role="tabpanel">
                                        <div class="p-4">
                                            <div class="row g-4">
                                                <!-- Payment Summary -->
                                                <div class="col-md-5">
                                                    <div class="bg-light rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-calculator-line me-2"></i>Payment Summary
                                                        </h6>
                                                        <div id="supplierSummary" class="payment-summary">
                                                            <!-- Will be populated by JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Payment Form -->
                                                <div class="col-md-7">
                                                    <div class="border rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-money-dollar-circle-line me-2"></i>Make Payment
                                                        </h6>
                                                        <form id="supplierPaymentForm">
                                                            <input type="hidden" id="supplierInvoiceId" name="invoice_id">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Amount</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light border-end-0">RM</span>
                                                                    <input type="number" class="form-control border-start-0 ps-0" id="supplierPaymentAmount" 
                                                                           name="amount" step="0.0001" placeholder="0.0000" required>
                                                                </div>
                                                                <small class="text-muted">Amount in Malaysian Ringgit (up to 4 decimal places)</small>
                                                                <div class="mt-1">
                                                                    <small class="text-info"><i class="ri-information-line"></i> <strong>Tip:</strong> Enter negative amount (e.g., -200) to deduct/reverse a payment</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Note <span class="text-muted">(optional)</span></label>
                                                                <textarea class="form-control" id="supplierPaymentDescription" 
                                                                          name="description" rows="2" 
                                                                          placeholder="Add payment description or notes..."></textarea>
                                                            </div>
                                                            
                                                            <div class="alert alert-light border mb-3">
                                                                <div class="d-flex align-items-start">
                                                                    <i class="ri-information-line text-muted me-2 mt-1"></i>
                                                                    <div>
                                                                        <small class="fw-medium text-dark">Payment Impact</small>
                                                                        <div id="supplierPaymentImpact" class="small text-muted">Enter amount to see impact</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-dark w-100 py-2">
                                                                <i class="ri-secure-payment-line me-2"></i>Process Payment
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Payment History -->
                                            <div class="mt-4">
                                                <h6 class="fw-bold text-dark mb-3">
                                                    <i class="ri-history-line me-2"></i>Payment History
                                                </h6>
                                                <div class="bg-light rounded-3 p-3">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr class="border-0">
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DATE</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">AMOUNT</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DESCRIPTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="supplierPaymentHistory" class="border-0">
                                                                <!-- Will be populated by JavaScript -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Payment Tab -->
                                    <div class="tab-pane" id="shippingPaymentTab" role="tabpanel">
                                        <div class="p-4">
                                            <div class="row g-4">
                                                <!-- Payment Summary -->
                                                <div class="col-md-5">
                                                    <div class="bg-light rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-calculator-line me-2"></i>Payment Summary
                                                        </h6>
                                                        <div id="shippingSummary" class="payment-summary">
                                                            <!-- Will be populated by JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Payment Form -->
                                                <div class="col-md-7">
                                                    <div class="border rounded-3 p-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="ri-money-dollar-circle-line me-2"></i>Make Payment
                                                        </h6>
                                                        <form id="shippingPaymentForm">
                                                            <input type="hidden" id="shippingInvoiceId" name="invoice_id">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Amount</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light border-end-0">RM</span>
                                                                    <input type="number" class="form-control border-start-0 ps-0" id="shippingPaymentAmount" 
                                                                           name="amount" step="0.0001" placeholder="0.0000" required>
                                                                </div>
                                                                <small class="text-muted">Amount in Malaysian Ringgit (up to 4 decimal places)</small>
                                                                <div class="mt-1">
                                                                    <small class="text-info"><i class="ri-information-line"></i> <strong>Tip:</strong> Enter negative amount (e.g., -200) to deduct/reverse a payment</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-dark">Payment Note <span class="text-muted">(optional)</span></label>
                                                                <textarea class="form-control" id="shippingPaymentDescription" 
                                                                          name="description" rows="2" 
                                                                          placeholder="Add payment description or notes..."></textarea>
                                                            </div>
                                                            
                                                            <div class="alert alert-light border mb-3">
                                                                <div class="d-flex align-items-start">
                                                                    <i class="ri-information-line text-muted me-2 mt-1"></i>
                                                                    <div>
                                                                        <small class="fw-medium text-dark">Payment Impact</small>
                                                                        <div id="shippingPaymentImpact" class="small text-muted">Enter amount to see impact</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-dark w-100 py-2">
                                                                <i class="ri-secure-payment-line me-2"></i>Process Payment
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Payment History -->
                                            <div class="mt-4">
                                                <h6 class="fw-bold text-dark mb-3">
                                                    <i class="ri-history-line me-2"></i>Payment History
                                                </h6>
                                                <div class="bg-light rounded-3 p-3">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr class="border-0">
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DATE</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">AMOUNT</th>
                                                                    <th class="border-0 bg-transparent fw-medium text-muted small">DESCRIPTION</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="shippingPaymentHistory" class="border-0">
                                                                <!-- Will be populated by JavaScript -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Commission Payment Tab -->
                                    <div class="tab-pane" id="commissionPaymentTab" role="tabpanel">
                                        <div class="row g-0">
                                            <!-- Commission Summary -->
                                            <div class="col-lg-6 border-end">
                                                <div class="p-4">
                                                    <h6 class="fw-bold mb-3 text-success">
                                                        <i class="ri-team-line me-2"></i>Commission Summary
                                                    </h6>
                                                    <div id="commissionSummary" class="payment-summary">
                                                        <!-- Will be populated by JavaScript -->
                                                    </div>
                                                    
                                                    <!-- Commission Payment Form -->
                                                    <div class="mt-4">
                                                        <h6 class="fw-medium mb-3">Record Commission Payment</h6>
                                                        <form id="commissionPaymentForm">
                                                            <input type="hidden" id="commissionInvoiceId">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium">Payment Amount (RM)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-success text-white border-0">RM</span>
                                                                    <input type="number" class="form-control ps-3" id="commissionPaymentAmount" 
                                                                           step="0.0001" placeholder="0.0000">
                                                                </div>
                                                                <small class="text-muted">Amount in Malaysian Ringgit (up to 4 decimal places)</small>
                                                                <div class="mt-1">
                                                                    <small class="text-info"><i class="ri-information-line"></i> <strong>Tip:</strong> Enter negative amount (e.g., -200) to deduct/reverse a payment</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium">Payment Notes</label>
                                                                <textarea class="form-control" id="commissionPaymentDescription" 
                                                                          rows="3" placeholder="Optional notes about this commission payment..."></textarea>
                                                            </div>
                                                            
                                                            <div class="d-grid">
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="ri-money-dollar-circle-line me-2"></i>Record Commission Payment
                                                                </button>
                                                            </div>
                                                            
                                                            <div class="mt-3">
                                                                <div id="commissionPaymentImpact" class="small text-muted">Enter amount to see impact</div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Commission Payment History -->
                                            <div class="col-lg-6">
                                                <div class="p-4">
                                                    <h6 class="fw-bold mb-3 text-muted">
                                                        <i class="ri-history-line me-2"></i>Commission Payment History
                                                    </h6>
                                                    <div class="bg-light rounded-3 p-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm mb-0">
                                                                <thead>
                                                                    <tr class="border-0">
                                                                        <th class="border-0 bg-transparent fw-medium text-muted small">DATE</th>
                                                                        <th class="border-0 bg-transparent fw-medium text-muted small">AMOUNT</th>
                                                                        <th class="border-0 bg-transparent fw-medium text-muted small">NOTES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="commissionPaymentHistory" class="border-0">
                                                                    <!-- Will be populated by JavaScript -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Payment Modal -->
                <div class="modal fade" id="addPaymentModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addPaymentForm">
                                    <input type="hidden" id="paymentInvoiceId" name="invoice_id">
                                    <input type="hidden" id="paymentType" name="payment_type">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Payment Type</label>
                                        <input type="text" class="form-control" id="paymentTypeDisplay" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Amount (¥)</label>
                                        <input type="number" class="form-control" id="paymentAmount" 
                                               name="amount" step="0.01" min="0" required>
                                        <small class="text-muted">Enter amount in Japanese Yen</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Description (Optional)</label>
                                        <textarea class="form-control" id="paymentDescription" 
                                                  name="description" rows="3" 
                                                  placeholder="Payment description or notes..."></textarea>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-money-dollar-circle-line"></i> Add Payment
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mark Complete Confirmation Modal -->
                <div class="modal fade" id="markCompleteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Mark Order as Complete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>Confirmation Required:</strong> This will mark the order as completed and show final profit/loss summary.
                                </div>
                                
                                <div id="completionSummary">
                                    <!-- Summary will be loaded here -->
                                </div>
                                
                                <input type="hidden" id="completeInvoiceId">
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success" id="confirmComplete">
                                        <i class="ri-check-line"></i> Confirm Complete
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change P&L Status Modal -->
                <div class="modal fade" id="statusModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title">
                                    <i class="ri-edit-line me-2 text-warning"></i>Change P&L Status
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info border-0 mb-3">
                                    <i class="ri-information-line me-2"></i>
                                    <small><strong>Note:</strong> This will only update the Profit & Loss tracking status and will NOT affect the main order status on the View Order page.</small>
                                </div>
                                
                                <input type="hidden" id="statusChangeInvoiceId">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Select New Status</label>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-warning status-option" data-status="pending">
                                            <i class="ri-time-line me-2"></i>Pending
                                        </button>
                                        <button type="button" class="btn btn-outline-danger status-option" data-status="overdue">
                                            <i class="ri-alarm-warning-line me-2"></i>Overdue
                                        </button>
                                        <button type="button" class="btn btn-outline-success status-option" data-status="completed">
                                            <i class="ri-check-line me-2"></i>Completed
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery for easier AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

    <!-- Profit Loss Management JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for overdue orders on page load (sync from View Order)
            fetch('../private/view-order-tabs-backend.php?action=check_overdue')
                .then(response => response.json())
                .then(data => {
                })
                .catch(error => {
                });
            
            // DOM Elements
            const accessKeyInput = document.getElementById('accessKey');
            const verifyAccessKeyBtn = document.getElementById('verifyAccessKey');
            const toggleKeyVisibilityBtn = document.getElementById('toggleKeyVisibility');
            const keyIcon = document.getElementById('keyIcon');
            const securitySection = document.getElementById('securitySection');
            const lockAccessBtn = document.getElementById('lockAccess');

            // Toggle password visibility
            if (toggleKeyVisibilityBtn && accessKeyInput && keyIcon) {
                toggleKeyVisibilityBtn.addEventListener('click', function() {
                    if (accessKeyInput.type === 'password') {
                        accessKeyInput.type = 'text';
                        keyIcon.className = 'ri-eye-off-line';
                    } else {
                        accessKeyInput.type = 'password';
                        keyIcon.className = 'ri-eye-line';
                    }
                });
            }

            // Lock access
            if (lockAccessBtn) {
                lockAccessBtn.addEventListener('click', function() {
                    accessGranted = false; // Use global variable
                    securitySection.style.display = 'block';
                    document.getElementById('mainContent').style.display = 'none';
                    accessKeyInput.value = '';
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Access Locked',
                        text: 'Security verification is required to access the system again.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }

            // Allow Enter key to verify access key
            if (accessKeyInput) {
                accessKeyInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        verifyAccessKeyBtn.click();
                    }
                });
            }

            // Function to load profit/loss data
            function loadProfitLossData() {
                fetch('../private/profit_loss_backend.php?action=get_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProfitLossOrders(data.orders);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load profit & loss data: ' + (data.error || 'Unknown error')
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to connect to the server.'
                    });
                });
            }

            // Function to display orders in profit/loss format
            function displayProfitLossOrders(orders) {
                const profitLossList = document.getElementById('profitLossList');
                
                if (!orders || orders.length === 0) {
                    profitLossList.innerHTML = '<div class="col-12 text-center py-5"><p>No orders found.</p></div>';
                    return;
                }

                let ordersHtml = '';
                orders.forEach(order => {
                    const profit = parseFloat(order.total_profit || 0);
                    const profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                    const profitIcon = profit >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                    
                    ordersHtml += `
                        <div class="col-12 mb-3">
                            <div class="card border-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <h5 class="card-title mb-1">${order.invoice_number || 'N/A'}</h5>
                                            <p class="text-muted mb-0">${order.customer_name || 'Unknown Customer'}</p>
                                            <small class="text-muted">${order.customer_company_name || ''}</small>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Total Revenue:</strong></p>
                                            <span class="badge bg-primary">RM ${formatCurrency(order.total_amount || 0)}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Total Cost:</strong></p>
                                            <span class="badge bg-warning">¥${formatCurrency(order.total_cost_yen || 0)}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="mb-1"><strong>Profit/Loss:</strong></p>
                                            <span class="badge bg-${profit >= 0 ? 'success' : 'danger'}">
                                                <i class="${profitIcon}"></i> RM ${formatCurrency(Math.abs(profit))}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-info" onclick="viewProfitDetails('${order.invoice_id}')">
                                                    <i class="ri-eye-line"></i> View Details
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" onclick="managePayments('${order.invoice_id}')">
                                                    <i class="ri-money-dollar-circle-line"></i> Payments
                                                </button>
                                                ${order.status !== 'completed' ? 
                                                    `<button class="btn btn-sm btn-success" onclick="markAsComplete('${order.invoice_id}')">
                                                        <i class="ri-check-line"></i> Complete
                                                    </button>` : 
                                                    `<span class="badge bg-success">Completed</span>`
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                profitLossList.innerHTML = ordersHtml;
            }

            // Helper function to format currency
            function formatCurrency(value) {
                return parseFloat(value || 0).toLocaleString('en-MY', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Helper function to format numbers
            function formatNumber(value) {
                return parseFloat(value || 0).toLocaleString('en-MY', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const orderCards = document.querySelectorAll('#profitLossList .col-12');
                
                orderCards.forEach(card => {
                    const invoiceNumber = card.querySelector('.card-title').textContent.toLowerCase();
                    const customerName = card.querySelector('.text-muted').textContent.toLowerCase();
                    
                    if (invoiceNumber.includes(searchTerm) || customerName.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }); // End of DOMContentLoaded

        // Global functions (outside DOMContentLoaded to be accessible by onclick handlers)
        // Security Configuration
        const ACCESS_KEY = "PROFIT2024";
        let accessGranted = false;

        // Commission Summary Functions
        function toggleCommissionSummary() {
            const section = document.getElementById('commissionSummarySection');
            if (section.style.display === 'none') {
                section.style.display = 'block';
                loadStaffList();
            } else {
                section.style.display = 'none';
            }
        }
        
        function loadStaffList() {
            // Load staff list for the filter dropdown
            fetch('../private/forms-new-order-backend.php?action=get_staff')
            .then(response => response.json())
            .then(data => {
                const staffSelect = document.getElementById('commissionStaffFilter');
                staffSelect.innerHTML = '<option value="">All Staff</option>';
                
                if (data.staff && data.staff.length > 0) {
                    data.staff.forEach(staff => {
                        staffSelect.innerHTML += `<option value="${staff.staff_id}">${staff.staff_name}</option>`;
                    });
                }
            })
            .catch(error => {
            });
        }
        
        function loadCommissionSummary() {
            const dateFrom = document.getElementById('commissionDateFrom').value;
            const dateTo = document.getElementById('commissionDateTo').value;
            const staffId = document.getElementById('commissionStaffFilter').value;
            
            if (!dateFrom || !dateTo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date Range Required',
                    text: 'Please select both start and end dates'
                });
                return;
            }
            
            // Build query parameters
            let queryParams = new URLSearchParams();
            queryParams.append('action', 'get_staff_commission_summary');
            queryParams.append('date_from', dateFrom);
            queryParams.append('date_to', dateTo);
            if (staffId) {
                queryParams.append('staff_id', staffId);
            }
            
            fetch(`../private/profit_loss_backend.php?${queryParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayCommissionSummary(data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Failed to load commission summary'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to server'
                });
            });
        }
        
        function displayCommissionSummary(data) {
            const summary = data.overall_summary;
            const staffSummary = data.staff_summary;
            
            // Update summary cards
            document.getElementById('totalProfit').textContent = `RM ${formatNumber(summary.total_profit || 0)}`;
            document.getElementById('totalCommissionDue').textContent = `RM ${formatNumber(summary.total_commission_due || 0)}`;
            document.getElementById('totalCommissionPaid').textContent = `RM ${formatNumber(summary.total_commission_paid || 0)}`;
            document.getElementById('totalCommissionRemaining').textContent = `RM ${formatNumber((summary.total_commission_due || 0) - (summary.total_commission_paid || 0))}`;
            
            // Update staff breakdown table
            const tbody = document.getElementById('staffCommissionBreakdown');
            let tableHtml = '';
            
            if (staffSummary && staffSummary.length > 0) {
                staffSummary.forEach(staff => {
                    const remaining = parseFloat(staff.total_commission_remaining || 0);
                    const remainingClass = remaining <= 0 ? 'text-success' : 'text-danger';
                    
                    tableHtml += `
                        <tr>
                            <td class="fw-medium">${staff.staff_name}</td>
                            <td>${staff.total_orders}</td>
                            <td>RM ${formatNumber(staff.total_revenue || 0)}</td>
                            <td class="${parseFloat(staff.total_profit) >= 0 ? 'text-success' : 'text-danger'}">
                                RM ${formatNumber(staff.total_profit || 0)}
                            </td>
                            <td>RM ${formatNumber(staff.total_commission_due || 0)}</td>
                            <td class="text-info">RM ${formatNumber(staff.total_commission_paid || 0)}</td>
                            <td class="${remainingClass} fw-medium">RM ${formatNumber(remaining)}</td>
                            <td>
                                ${remaining > 0 ? `
                                    <button class="btn btn-success btn-sm" onclick="payAllCommissionForStaff('${staff.staff_id}', '${staff.staff_name}', ${remaining})" title="Pay All Remaining">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </button>
                                ` : `
                                    <span class="text-success small">✓ Paid</span>
                                `}
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableHtml = '<tr><td colspan="8" class="text-center text-muted">No commission data found for this period</td></tr>';
            }
            
            tbody.innerHTML = tableHtml;
            document.getElementById('commissionSummaryDisplay').style.display = 'block';
        }
        
        function payAllCommissionForStaff(staffId, staffName, totalRemaining) {
            // This would open a bulk payment modal for all unpaid commissions for this staff
            Swal.fire({
                title: `Pay All Commission for ${staffName}?`,
                text: `Total amount to pay: RM ${formatNumber(totalRemaining)}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Pay All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementation for bulk payment would go here
                    Swal.fire({
                        icon: 'info',
                        title: 'Feature Coming Soon',
                        text: 'Bulk commission payment feature will be implemented soon. Please pay individual orders for now.'
                    });
                }
            });
        }

        // Verify Access Function
        function verifyAccess() {
            const key = document.getElementById('accessKey').value;
            
            if (key === ACCESS_KEY) {
                accessGranted = true;
                document.getElementById('securitySection').style.display = 'none';
                document.getElementById('mainContent').style.display = 'block';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Access Granted',
                    text: 'You can now access the profit & loss management system.',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Load data
                loadProfitLossData(1); // Call the global function with page parameter
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'Invalid access key. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
                document.getElementById('accessKey').value = '';
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility() {
            const accessKeyInput = document.getElementById('accessKey');
            const keyIcon = document.getElementById('keyIcon');
            
            if (accessKeyInput && keyIcon) {
                if (accessKeyInput.type === 'password') {
                    accessKeyInput.type = 'text';
                    keyIcon.className = 'ri-eye-off-line';
                } else {
                    accessKeyInput.type = 'password';
                    keyIcon.className = 'ri-eye-line';
                }
            }
        }

        // Staff Payment Functions
        // Initialize event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set up payment form listeners
            const supplierForm = document.getElementById('supplierPaymentForm');
            const shippingForm = document.getElementById('shippingPaymentForm');
            
            if (supplierForm) {
                supplierForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const invoiceId = document.getElementById('supplierInvoiceId').value;
                    const amount = parseFloat(document.getElementById('supplierPaymentAmount').value);
                    const description = document.getElementById('supplierPaymentDescription').value;
                    
                    if (!amount || amount === 0 || isNaN(amount)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount (cannot be zero)'
                        });
                        return;
                    }
                    
                    // If negative amount, show confirmation dialog
                    if (amount < 0) {
                        Swal.fire({
                            title: 'Confirm Payment Deduction',
                            html: `
                                <div class="text-start">
                                    <p class="mb-2"><strong>You are about to DEDUCT a payment:</strong></p>
                                    <ul class="mb-3">
                                        <li>Amount to deduct: <strong class="text-danger">RM ${formatNumber(Math.abs(amount))}</strong></li>
                                        <li>This will <strong>reduce</strong> the total paid amount</li>
                                        <li>Use this to correct overpayments or mistakes</li>
                                    </ul>
                                    <p class="text-muted small mb-0">Note: ${description || 'No description provided'}</p>
                                </div>
                            `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, Deduct Payment',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('action', 'add_supplier_payment');
                                formData.append('invoice_id', invoiceId);
                                formData.append('amount', amount);
                                formData.append('description', description);
                                submitPayment(formData, 'supplier');
                            }
                        });
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'add_supplier_payment');
                    formData.append('invoice_id', invoiceId);
                    formData.append('amount', amount);
                    formData.append('description', description);
                    
                    submitPayment(formData, 'supplier');
                });
            }
            
            if (shippingForm) {
                shippingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const invoiceId = document.getElementById('shippingInvoiceId').value;
                    const amount = parseFloat(document.getElementById('shippingPaymentAmount').value);
                    const description = document.getElementById('shippingPaymentDescription').value;
                    
                    if (!amount || amount === 0 || isNaN(amount)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount (cannot be zero)'
                        });
                        return;
                    }
                    
                    // If negative amount, show confirmation dialog
                    if (amount < 0) {
                        Swal.fire({
                            title: 'Confirm Payment Deduction',
                            html: `
                                <div class="text-start">
                                    <p class="mb-2"><strong>You are about to DEDUCT a payment:</strong></p>
                                    <ul class="mb-3">
                                        <li>Amount to deduct: <strong class="text-danger">RM ${formatNumber(Math.abs(amount))}</strong></li>
                                        <li>This will <strong>reduce</strong> the total paid amount</li>
                                        <li>Use this to correct overpayments or mistakes</li>
                                    </ul>
                                    <p class="text-muted small mb-0">Note: ${description || 'No description provided'}</p>
                                </div>
                            `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, Deduct Payment',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('action', 'add_shipping_payment');
                                formData.append('invoice_id', invoiceId);
                                formData.append('amount', amount);
                                formData.append('description', description);
                                submitPayment(formData, 'shipping');
                            }
                        });
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'add_shipping_payment');
                    formData.append('invoice_id', invoiceId);
                    formData.append('amount', amount);
                    formData.append('description', description);
                    
                    submitPayment(formData, 'shipping');
                });
            }

            // Set up complete confirmation listener
            const confirmCompleteBtn = document.getElementById('confirmComplete');
            if (confirmCompleteBtn) {
                confirmCompleteBtn.addEventListener('click', function() {
                    const invoiceId = document.getElementById('completeInvoiceId').value;
                    
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=mark_complete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        
                        if (data.success) {
                            // Close modal first
                            const modalElement = document.getElementById('markCompleteModal');
                            if (modalElement) {
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }
                            }
                            
                            // Show success message with green styling info
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Completed!',
                                text: 'The order has been marked as completed and will now appear in green.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Refresh the table after success message
                                loadProfitLossData(currentPage);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Failed to mark order as complete'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                });
            }
            
            // Set up commission payment form listener
            const commissionPaymentForm = document.getElementById('commissionPaymentForm');
            if (commissionPaymentForm) {
                commissionPaymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const invoiceId = document.getElementById('commissionInvoiceId').value;
                    const amount = parseFloat(document.getElementById('commissionPaymentAmount').value);
                    const notes = document.getElementById('commissionPaymentDescription').value;
                    
                    if (!amount || amount === 0 || isNaN(amount)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount (cannot be zero)'
                        });
                        return;
                    }
                    
                    // If negative amount, show confirmation dialog
                    if (amount < 0) {
                        Swal.fire({
                            title: 'Confirm Commission Deduction',
                            html: `
                                <div class="text-start">
                                    <p class="mb-2"><strong>You are about to DEDUCT a commission payment:</strong></p>
                                    <ul class="mb-3">
                                        <li>Amount to deduct: <strong class="text-danger">RM ${formatNumber(Math.abs(amount))}</strong></li>
                                        <li>This will <strong>reduce</strong> the commission paid amount</li>
                                        <li>Use this to correct overpayments or mistakes</li>
                                    </ul>
                                    <p class="text-muted small mb-0">Note: ${notes || 'No description provided'}</p>
                                </div>
                            `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, Deduct Payment',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('action', 'pay_staff_commission');
                                formData.append('invoice_id', invoiceId);
                                formData.append('amount', amount);
                                formData.append('notes', notes);
                                submitCommissionPayment(formData);
                            }
                        });
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('action', 'pay_staff_commission');
                    formData.append('invoice_id', invoiceId);
                    formData.append('amount', amount);
                    formData.append('notes', notes);
                    
                    submitCommissionPayment(formData);
                });
            }
        });
        
        function submitPayment(formData, type) {
            const submitButton = document.querySelector(`#${type}PaymentForm button[type="submit"]`);
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="ri-loader-4-line"></i> Processing Payment...';
            submitButton.disabled = true;
            
            // Validate form data
            const amount = parseFloat(formData.get('amount'));
            const invoiceId = formData.get('invoice_id');
            
            if (!amount || amount === 0 || isNaN(amount)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please enter a valid payment amount (cannot be zero)',
                    confirmButtonColor: '#dc3545'
                });
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }
            
            if (!invoiceId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Invoice ID is required for payment processing',
                    confirmButtonColor: '#dc3545'
                });
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }
            
            // Add action to form data
            formData.append('action', type === 'supplier' ? 'add_supplier_payment' : 'add_shipping_payment');
            
            fetch('../private/profit_loss_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        const paymentAmount = parseFloat(formData.get('amount'));
                        const isDeduction = paymentAmount < 0;
                        
                        Swal.fire({
                            icon: 'success',
                            title: isDeduction ? 'Payment Deducted' : 'Payment Processed',
                            text: isDeduction 
                                ? `${type.charAt(0).toUpperCase() + type.slice(1)} payment deduction of RM ${formatNumber(Math.abs(paymentAmount))} has been recorded`
                                : `${type.charAt(0).toUpperCase() + type.slice(1)} payment of RM ${formatNumber(paymentAmount)} has been recorded successfully`,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#28a745'
                        });
                        
                        // Reset form
                        document.getElementById(`${type}PaymentForm`).reset();
                        document.getElementById(`${type}PaymentImpact`).innerHTML = 'Enter amount to see impact';
                        
                        // Reload payment data
                        const invoiceId = formData.get('invoice_id');
                        loadPaymentTabsData(invoiceId);
                        
                        // Refresh the main table
                        loadProfitLossData();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: data.message || data.error || 'Failed to process payment',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (parseError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Server returned an invalid response. Please check the console for details.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to server. Please check your connection and try again.',
                    confirmButtonColor: '#dc3545'
                });
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }
        
        function submitCommissionPayment(formData) {
            const submitButton = document.querySelector('#commissionPaymentForm button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="ri-loader-4-line"></i> Processing...';
            submitButton.disabled = true;
            
            fetch('../private/profit_loss_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const paymentAmount = parseFloat(formData.get('amount'));
                    const isDeduction = paymentAmount < 0;
                    
                    Swal.fire({
                        icon: 'success',
                        title: isDeduction ? 'Commission Deducted' : 'Payment Recorded',
                        text: isDeduction
                            ? `Commission deduction of RM ${formatNumber(Math.abs(paymentAmount))} recorded for ${data.staff_name}`
                            : `Commission payment of RM ${formatNumber(paymentAmount)} recorded for ${data.staff_name}`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Refresh the commission tab data
                    const invoiceId = document.getElementById('commissionInvoiceId').value;
                    fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
                    .then(response => response.json())
                    .then(orderData => {
                        if (orderData.success) {
                            displayCommissionData(orderData);
                            displayPaymentData(orderData); // Also refresh payment history
                        }
                    });
                    
                    // Refresh main table
                    loadProfitLossData(currentPage);
                    
                    // Clear form
                    document.getElementById('commissionPaymentForm').reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed',
                        text: data.error || 'Failed to record commission payment'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to server'
                });
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        }

        // Helper functions
        function formatCurrency(amount) {
            // Handle null, undefined, empty string, or NaN values
            if (amount === null || amount === undefined || amount === '' || isNaN(amount)) {
                return '0.0000';
            }
            
            const numValue = parseFloat(amount);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0.0000';
            }
            
            return new Intl.NumberFormat('en-MY', {
                minimumFractionDigits: 4,
                maximumFractionDigits: 4
            }).format(numValue);
        }

        function formatNumber(value) {
            // Handle null, undefined, empty string, or NaN values
            if (value === null || value === undefined || value === '' || isNaN(value)) {
                return '0';
            }
            
            const numValue = parseFloat(value);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0';
            }
            
            return new Intl.NumberFormat().format(numValue);
        }

        let currentPage = 1;
        let currentFilters = {};
        
        function loadProfitLossData(page = 1) {
            currentPage = page;
            const tableBody = document.getElementById('profitLossTableBody');
            
            // Show loading
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="ri-loader-4-line"></i> Loading data...
                        </div>
                    </td>
                </tr>
            `;
            
            // Build query string with filters
            let queryParams = new URLSearchParams();
            queryParams.append('action', 'get_orders');
            queryParams.append('page', page);
            
            // Add filters to query
            Object.keys(currentFilters).forEach(key => {
                if (currentFilters[key]) {
                    queryParams.append(key, currentFilters[key]);
                }
            });

            // Load both orders and payment summaries
            Promise.all([
                fetch(`../private/profit_loss_backend.php?${queryParams.toString()}`),
                fetch(`../private/profit_loss_backend.php?action=get_payment_summaries&${Object.keys(currentFilters).map(key => currentFilters[key] ? `${key}=${currentFilters[key]}` : '').filter(Boolean).join('&')}`)
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([ordersData, summariesData]) => {
                // Handle orders data
                if (ordersData.success && ordersData.orders) {
                    displayCleanOrdersTable(ordersData.orders);
                    displayPagination(ordersData.pagination);
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">No orders found</div>
                            </td>
                        </tr>
                    `;
                    clearPagination();
                }

                // Handle payment summaries data
                if (summariesData.success) {
                    updatePaymentSummaryCards(summariesData.summaries, summariesData.order_counts, summariesData.excluded_summaries);
                }
            })
            .catch(error => {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-danger">Error loading data</div>
                        </td>
                    </tr>
                `;
                clearPagination();
            });
        }

        function applyFilters() {
            const monthFilter = document.getElementById('monthFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const searchFilter = document.getElementById('searchInput').value;
            const dateFromFilter = document.getElementById('dateFromFilter').value;
            const dateToFilter = document.getElementById('dateToFilter').value;
            
            // Update current filters
            currentFilters = {
                month: monthFilter,
                status: statusFilter,
                search: searchFilter,
                date_from: dateFromFilter,
                date_to: dateToFilter
            };
            
            // Reset to first page when applying filters
            currentPage = 1;
            loadProfitLossData(1);
        }

        function clearFilters() {
            // Clear all filter inputs
            document.getElementById('monthFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            
            // Clear current filters
            currentFilters = {};
            
            // Reset to first page
            currentPage = 1;
            loadProfitLossData(1);
        }

        function updatePaymentSummaryCards(summaries, orderCounts, excludedSummaries) {
            // Update commission payments and remaining
            document.getElementById('totalCommissionPayments').textContent = 
                `RM ${formatNumber(summaries.total_commission_payments || 0)}`;
            document.getElementById('totalCommissionRemaining').textContent = 
                `RM ${formatNumber(summaries.total_commission_remaining || 0)}`;
            
            // Update shipping payments and remaining
            document.getElementById('totalShippingPayments').textContent = 
                `RM ${formatNumber(summaries.total_shipping_payments || 0)}`;
            document.getElementById('totalShippingRemaining').textContent = 
                `RM ${formatNumber(summaries.total_shipping_remaining || 0)}`;
            
            // Update supplier payments and remaining
            document.getElementById('totalSupplierPayments').textContent = 
                `RM ${formatNumber(summaries.total_supplier_payments || 0)}`;
            document.getElementById('totalSupplierRemaining').textContent = 
                `RM ${formatNumber(summaries.total_supplier_remaining || 0)}`;
            
            // Update zakat total (10% of profit)
            document.getElementById('totalZakat').textContent = 
                `RM ${formatNumber(summaries.total_zakat || 0)}`;
            
            // Update excluded zakat
            if (excludedSummaries) {
                document.getElementById('excludedZakat').textContent = 
                    `(RM ${formatNumber(excludedSummaries.total_zakat || 0)})`;
            }
            
            // Update excluded order totals (orders without status)
            if (excludedSummaries) {
                document.getElementById('excludedCommissionRemaining').textContent = 
                    `(RM ${formatNumber(excludedSummaries.total_commission_remaining || 0)})`;
                document.getElementById('excludedShippingRemaining').textContent = 
                    `(RM ${formatNumber(excludedSummaries.total_shipping_remaining || 0)})`;
                document.getElementById('excludedSupplierRemaining').textContent = 
                    `(RM ${formatNumber(excludedSummaries.total_supplier_remaining || 0)})`;
            }
            
            // Update order counts if provided
            if (orderCounts) {
                document.getElementById('includedOrderCount').textContent = orderCounts.included || 0;
                document.getElementById('excludedOrderCount').textContent = orderCounts.excluded || 0;
            }
        }

        function displayPagination(pagination) {
            if (!pagination) return;
            
            const infoDiv = document.getElementById('paginationInfo');
            const controlsUl = document.getElementById('paginationControls');
            
            // Update info
            const start = ((pagination.current_page - 1) * pagination.limit) + 1;
            const end = Math.min(pagination.current_page * pagination.limit, pagination.total_records);
            infoDiv.innerHTML = `Showing ${start}-${end} of ${pagination.total_records} orders`;
            
            // Update controls
            let paginationHtml = '';
            
            // Previous button
            if (pagination.current_page > 1) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadProfitLossData(${pagination.current_page - 1}); return false;">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-left-line"></i></span>
                    </li>
                `;
            }
            
            // Page numbers
            for (let i = 1; i <= pagination.total_pages; i++) {
                if (i === pagination.current_page) {
                    paginationHtml += `
                        <li class="page-item active">
                            <span class="page-link">${i}</span>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadProfitLossData(${i}); return false;">${i}</a>
                        </li>
                    `;
                }
            }
            
            // Next button
            if (pagination.current_page < pagination.total_pages) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadProfitLossData(${pagination.current_page + 1}); return false;">
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link"><i class="ri-arrow-right-line"></i></span>
                    </li>
                `;
            }
            
            controlsUl.innerHTML = paginationHtml;
        }
        
        function clearPagination() {
            document.getElementById('paginationInfo').innerHTML = '';
            document.getElementById('paginationControls').innerHTML = '';
        }

        function displayCleanOrdersTable(orders) {
            const tableBody = document.getElementById('profitLossTableBody');
            
            if (!orders || orders.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">No orders found</div>
                        </td>
                    </tr>
                `;
                return;
            }

            const rows = orders.map(order => {
                // Use the backend calculated values - convert to RM like modal
                const supplierCostYen = parseFloat(order.total_supplier_cost_yen || 0);
                const shippingCostRm = parseFloat(order.total_shipping_cost_rm || 0);
                const supplierPaidYen = parseFloat(order.supplier_payments_total || 0);
                const shippingPaidRm = parseFloat(order.shipping_payments_total || 0);
                const avgConversionRate = parseFloat(order.avg_conversion_rate || 0.032);
                
                // Convert supplier amounts to RM (Yen ÷ rate = RM, where rate is Yen per 1 RM)
                const supplierCostRm = supplierCostYen / avgConversionRate;
                const supplierPaidRm = supplierPaidYen / avgConversionRate;
                
                // Calculate remaining amounts in RM (allow negative for overpayments)
                const supplierRemainingRm = supplierCostRm - supplierPaidRm;
                const shippingRemainingRm = shippingCostRm - shippingPaidRm;
                const totalRemainingRm = supplierRemainingRm + shippingRemainingRm;
                
                // Calculate payment status based on RM amounts
                let status = 'unpaid';
                let statusText = 'Unpaid';
                const supplierFullyPaid = supplierRemainingRm <= 0.01 && supplierCostRm > 0; // Small tolerance for floating point
                const shippingFullyPaid = shippingRemainingRm <= 0.01 && shippingCostRm > 0;
                const hasSupplierPayment = supplierPaidRm > 0;
                const hasShippingPayment = shippingPaidRm > 0;
                const isOverpaid = totalRemainingRm < -0.01; // Check for overpayment
                
                if (isOverpaid) {
                    status = 'overpaid';
                    statusText = 'Overpaid';
                } else if (supplierFullyPaid && shippingFullyPaid) {
                    status = 'paid';
                    statusText = 'Fully Paid';
                } else if (hasSupplierPayment || hasShippingPayment) {
                    status = 'partial';
                    statusText = 'Partially Paid';
                }
                
                // Check order status for Profit Loss page
                // Use profit_loss_status with priority, fall back to main order.status for one-way sync
                const profitLossStatus = order.profit_loss_status ? order.profit_loss_status.toLowerCase() : null;
                const mainStatus = order.status ? order.status.toLowerCase() : null;
                const isStarted = order.production_status === 'started';
                
                // Determine completion status
                // If profit_loss_status is explicitly set (pending/overdue/completed), use it
                // Otherwise, use main order.status for one-way sync from View Order
                let isCompleted = false;
                let isOverdue = false;
                
                if (profitLossStatus === 'completed') {
                    isCompleted = true;
                } else if (profitLossStatus === 'overdue') {
                    isOverdue = true;
                } else if (profitLossStatus === 'pending') {
                    // Explicitly pending in P&L
                    isCompleted = false;
                    isOverdue = false;
                } else if (!profitLossStatus && mainStatus === 'completed') {
                    // No explicit P&L status, inherit from View Order (one-way sync)
                    isCompleted = true;
                }
                
                // Check if completed late (completed after ETA or was overdue before completion)
                let wasCompletedLate = false;
                if (isCompleted) {
                    if (order.completion_date && order.estimated_completion_date) {
                        // Has completion date - check if completed after ETA
                        const completionDate = new Date(order.completion_date);
                        const etaDate = new Date(order.estimated_completion_date);
                        wasCompletedLate = completionDate > etaDate;
                    } else if (order.estimated_completion_date) {
                        // No completion date but has ETA - check if current date is past ETA (was overdue when marked complete)
                        const now = new Date();
                        const etaDate = new Date(order.estimated_completion_date);
                        wasCompletedLate = now > etaDate;
                    }
                }
                let cellStyle = '';
                let rowClass = '';
                let statusBadge = '';
                
                if (isCompleted) {
                    // Check if completed late or on time
                    if (wasCompletedLate) {
                        // Teal for completed late (after overdue)
                        cellStyle = 'background-color: rgba(20, 184, 166, 0.15); color: #134e4a;';
                        rowClass = 'completed-late';
                        statusBadge = '<span class="badge" style="background-color: #14b8a6; color: #134e4a; font-size: 10px;">COMPLETED (OVERDUE)</span>';
                    } else {
                        // Green for completed on time
                        cellStyle = 'background-color: rgba(16, 185, 129, 0.15); color: #065f46;';
                        rowClass = 'completed-on-time';
                        statusBadge = '<span class="badge bg-success" style="font-size: 10px;">COMPLETED</span>';
                    }
                } else if (isOverdue) {
                    // Orange for overdue (past ETA, not completed)
                    cellStyle = 'background-color: rgba(249, 115, 22, 0.15); color: #7c2d12;';
                    rowClass = 'overdue-row';
                    statusBadge = '<span class="badge" style="background-color: #ea580c; color: #7c2d12; font-size: 10px;">OVERDUE</span>';
                } else if (isStarted) {
                    // Yellow for started/pending (from View Order page)
                    cellStyle = 'background-color: rgba(251, 191, 36, 0.15); color: #78350f;';
                    rowClass = 'started-row';
                    statusBadge = '<span class="badge bg-warning" style="font-size: 10px;">PENDING</span>';
                }
                
                return `
                    <tr class="${rowClass}">
                        <td style="${cellStyle}">
                            <div style="font-weight: 600;">${order.order_number || '#' + order.invoice_id}</div>
                            ${statusBadge}
                        </td>
                        <td style="${cellStyle}">
                            <div style="font-weight: 500;">${order.customer_name || 'Unknown'}</div>
                            <div style="font-size: 12px; opacity: 0.7;">${order.customer_company_name || ''}</div>
                        </td>
                        <td style="${cellStyle}">
                            <div>${formatDate(order.order_date)}</div>
                        </td>
                        <td style="${cellStyle}">
                            <div style="font-weight: 600; color: ${order.profit_with_zakat >= 0 ? '#059669' : '#dc2626'};">
                                RM${formatNumber(order.profit_with_zakat)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                ${order.profit_with_zakat >= 0 ? 'Profit (After Zakat)' : 'Loss'}
                            </div>
                        </td>
                        <td style="${cellStyle}">
                            <div style="font-weight: 500; color: #059669;">
                                RM${formatNumber(supplierPaidRm + shippingPaidRm)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                Total paid (RM${formatNumber(supplierPaidRm)} supplier + RM${formatNumber(shippingPaidRm)} shipping)
                            </div>
                        </td>
                        <td style="${cellStyle}">
                            <div style="font-weight: 600; color: ${totalRemainingRm <= 0.01 ? '#059669' : '#dc2626'};">
                                RM${formatNumber(totalRemainingRm)}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                Total remaining (RM${formatNumber(supplierRemainingRm)} supplier + RM${formatNumber(shippingRemainingRm)} shipping)
                            </div>
                        </td>
                        <td style="${cellStyle}">
                            ${order.staff_name ? `
                                <div style="font-weight: 500;">${order.staff_name}</div>
                                <div style="font-size: 12px; color: ${(() => {
                                    // Use pre-discount revenue for commission calculation
                                    const revenueForCommission = parseFloat(order.total_revenue_before_discount || order.total_revenue);
                                    const commissionDue = revenueForCommission * (order.commission_percentage / 100);
                                    const paidAmount = parseFloat(order.commission_paid_amount || 0);
                                    const remaining = commissionDue - paidAmount;
                                    return remaining <= 0 ? '#059669' : '#dc2626';
                                })()};">
                                    ${(() => {
                                        // Use pre-discount revenue for commission calculation
                                        const revenueForCommission = parseFloat(order.total_revenue_before_discount || order.total_revenue);
                                        const commissionDue = revenueForCommission * (order.commission_percentage / 100);
                                        const paidAmount = parseFloat(order.commission_paid_amount || 0);
                                        const remaining = commissionDue - paidAmount;
                                        return remaining <= 0 ? 'Paid' : `Remaining: RM${formatNumber(remaining)}`;
                                    })()}
                                </div>
                            ` : `
                                <div style="font-style: italic; opacity: 0.7;">No commission</div>
                            `}
                        </td>
                        <td style="${cellStyle}">
                            <span class="status-badge status-${status}">${statusText}</span>
                        </td>
                        <td style="${cellStyle}">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-clean btn-clean-sm" onclick="openPaymentModal(${order.invoice_id})" title="Make Payment">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </button>
                                    <button class="btn btn-clean-outline btn-clean-sm" onclick="viewProfitDetails(${order.invoice_id})" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <button class="btn btn-warning btn-clean-sm" onclick="openStatusModal(${order.invoice_id}, '${order.profit_loss_status || 'pending'}')" title="Change P&L Status">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn btn-danger btn-clean-sm" onclick="deleteOrder(${order.invoice_id})" title="Delete Order">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = rows;
        }

        // Utility Functions
        function formatNumber(value) {
            // Handle null, undefined, empty string, or NaN values
            if (value === null || value === undefined || value === '' || isNaN(value)) {
                return '0.0000';
            }
            
            const numValue = parseFloat(value);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0.0000';
            }
            
            return new Intl.NumberFormat('en-MY', {
                minimumFractionDigits: 4,
                maximumFractionDigits: 4
            }).format(numValue);
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString('en-MY');
        }

        // Add openPaymentModal function placeholder
        function openPaymentModal(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }
            
            // Open the payment modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
            
            // Load order data for the payment modal
            loadPaymentTabsData(invoiceId);
        }

        // View Profit Details Function
        function viewProfitDetails(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('profitDetailsModal'));
            modal.show();

            // Load profit details
            fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                
                if (data.success) {
                    // Log individual items for debugging
                    if (data.items && data.items.length > 0) {
                    }
                    displayProfitDetails(data);
                } else {
                    document.getElementById('profitDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Error: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                document.getElementById('profitDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
            });
        }

        // Mark as Complete Function
        function markAsComplete(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            Swal.fire({
                title: 'Mark Order as Complete?',
                text: `Are you sure you want to mark order #${invoiceId} as completed?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, mark as complete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=mark_complete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Completed!',
                                text: 'The order has been marked as completed and will now appear in green.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Refresh the table after the success message
                                loadProfitLossData(currentPage);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Failed to mark order as complete'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                }
            });
        }

        // Delete Order Function
        function deleteOrder(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Order?',
                text: `Are you sure you want to permanently delete order #${invoiceId}? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('invoice_id', invoiceId);
                    
                    fetch('../private/profit_loss_backend.php?action=delete_order', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Deleted',
                                text: 'The order has been permanently deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Refresh the table
                            loadProfitLossData(currentPage);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: data.error || 'Failed to delete order'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to connect to server'
                        });
                    });
                }
            });
        }

        // Display Profit Details Function
        function displayProfitDetails(data) {
            const order = data.order;
            const items = data.items || [];
            const summary = data.summary;
            
            let itemsHtml = '';
            
            // Calculate discount ratio for proportional distribution
            const totalRevenueTemp = parseFloat(summary?.total_revenue || 0);
            const totalRevenueBeforeDiscountTemp = parseFloat(summary?.total_revenue_before_discount || 0);
            const discountRatio = totalRevenueBeforeDiscountTemp > 0 ? totalRevenueTemp / totalRevenueBeforeDiscountTemp : 1;
            
            items.forEach(item => {
                // Safely extract and format values
                const quantity = parseFloat(item.quantity || 0);
                const unitPrice = parseFloat(item.unit_price || 0);
                const itemRevenue = parseFloat(item.item_revenue || 0);
                const itemRevenueTable = itemRevenue * discountRatio; // Apply discount proportionally
                const unitSupplierCost = parseFloat(item.unit_supplier_cost_yen || 0);
                const conversionRate = parseFloat(item.conversion_rate || 0.032);
                const unitSupplierCostRm = unitSupplierCost / conversionRate;
                const unitShippingCost = parseFloat(item.unit_shipping_cost_rm || 0);
                
                // Calculate total costs and profit
                const totalSupplierCost = unitSupplierCostRm * quantity;
                const totalShippingCost = unitShippingCost * quantity;
                const totalCost = totalSupplierCost + totalShippingCost;
                const itemProfit = itemRevenueTable - totalCost; // Use discounted revenue for profit calculation
                const itemProfitAfterZakat = itemProfit * 0.90; // Deduct 10% zakat
                const profitClass = itemProfitAfterZakat >= 0 ? 'text-success' : 'text-danger';
                
                itemsHtml += `
                    <tr>
                        <td>${item.product_name || 'N/A'}</td>
                        <td>${formatNumber(quantity)}</td>
                        <td>RM ${formatNumber(unitPrice)}</td>
                        <td>RM ${formatNumber(itemRevenueTable)}</td>
                        <td>RM ${formatNumber(totalSupplierCost)} | RM ${formatNumber(unitSupplierCostRm)}</td>
                        <td>RM ${formatNumber(totalShippingCost)} | RM ${formatNumber(unitShippingCost)}</td>
                        <td>RM ${formatNumber(totalCost)}</td>
                        <td class="${profitClass}">RM ${formatNumber(Math.abs(itemProfitAfterZakat))}${itemProfitAfterZakat < 0 ? ' (Loss)' : ''}</td>
                    </tr>
                `;
            });

            // Safely extract summary values
            const totalRevenue = parseFloat(summary?.total_revenue || 0); // After discount (what customer pays)
            const totalRevenueBeforeDiscount = parseFloat(summary?.total_revenue_before_discount || 0); // Before discount (sum of items)
            const priceDeduction = totalRevenueBeforeDiscount - totalRevenue; // Calculate discount amount
            const totalSupplierCost = parseFloat(summary?.total_supplier_cost_yen || 0);
            const avgConversionRate = parseFloat(summary?.avg_conversion_rate || 0.032);
            const totalSupplierCostRm = totalSupplierCost / avgConversionRate;
            const totalShippingCost = parseFloat(summary?.total_shipping_cost_rm || 0);
            const totalCost = totalSupplierCostRm + totalShippingCost;
            const totalProfit = totalRevenue - totalCost;
            const totalProfitClass = totalProfit >= 0 ? 'text-success' : 'text-danger';
            
            // Calculate zakat (10% of profit) and profit after zakat
            const zakatAmount = parseFloat(summary?.zakat_amount || 0);
            const profitAfterZakat = parseFloat(summary?.profit_after_zakat || 0);
            const profitAfterZakatClass = profitAfterZakat >= 0 ? 'text-success' : 'text-danger';
            
            const html = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Order Information</h6>
                        <p><strong>Invoice:</strong> #${order.invoice_id}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Unknown'}</p>
                        <p><strong>Company:</strong> ${order.customer_company_name || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="badge bg-${order.status === 'completed' ? 'success' : 'warning'}">${order.status || 'Active'}</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Financial Summary</h6>
                        <p><strong>Total Revenue:</strong> RM ${formatNumber(totalRevenueBeforeDiscount)}</p>
                        <p><strong>Price Deduction Discount:</strong> <span class="text-danger">- RM ${formatNumber(priceDeduction)}</span></p>
                        <p><strong>Supplier Cost:</strong> RM ${formatNumber(totalSupplierCostRm)}</p>
                        <p><strong>Shipping Cost:</strong> RM ${formatNumber(totalShippingCost)}</p>
                        <hr class="my-2">
                        <p><strong>Total Profit:</strong> <span class="${totalProfitClass} fw-bold">RM ${formatNumber(totalProfit)}</span></p>
                        <p><strong>10% Zakat:</strong> <span class="text-danger">- RM ${formatNumber(zakatAmount)}</span></p>
                        <p><strong>Profit With Zakat:</strong> <span class="${profitAfterZakatClass} fw-bold">RM ${formatNumber(profitAfterZakat)}</span></p>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3">Item-Level Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Selling Price</th>
                                <th>Revenue</th>
                                <th>Total Supplier Cost | Per Unit</th>
                                <th>Total Shipping Cost | Per Unit</th>
                                <th>Total Cost</th>
                                <th>Profit/Loss (After Zakat)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml || '<tr><td colspan="8" class="text-center">No items found</td></tr>'}
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('profitDetailsContent').innerHTML = html;
        }

        function displayProfitLossOrders(orders) {
            const profitLossList = document.getElementById('profitLossList');
            
            if (!orders || orders.length === 0) {
                profitLossList.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>No Orders Found</h5>
                            <p>There are currently no orders to display profit & loss information for.</p>
                        </div>
                    </div>
                `;
                return;
            }

            let ordersHtml = '';
            orders.forEach(order => {
                const profit = parseFloat(order.profit || 0);
                const profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                const profitIcon = profit >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                
                ordersHtml += `
                    <div class="col-md-6 col-lg-4 order-card" data-invoice="${order.invoice_number.toLowerCase()}" data-customer="${order.customer_name.toLowerCase()}">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-1">${order.invoice_number}</h6>
                                        <p class="text-muted mb-0">${order.customer_name}</p>
                                        ${order.customer_company_name ? `<small class="text-muted">${order.customer_company_name}</small>` : ''}
                                    </div>
                                    <span class="badge bg-${order.status === 'completed' ? 'success' : 'warning'}">${order.status}</span>
                                </div>
                                
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Revenue:</small><br>
                                        <span class="badge bg-primary">RM ${formatCurrency(order.total_amount || 0)}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Cost:</small><br>
                                        <span class="badge bg-warning">¥${formatCurrency(order.total_cost_yen || 0)}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Profit:</small><br>
                                        <span class="badge bg-${profit >= 0 ? 'success' : 'danger'}">
                                            <i class="${profitIcon}"></i> RM ${formatCurrency(Math.abs(profit))}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-info" onclick="viewProfitDetails('${order.invoice_id}')">
                                        <i class="ri-eye-line"></i> View Details
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="managePayments('${order.invoice_id}')">
                                        <i class="ri-money-dollar-circle-line"></i> Payments
                                    </button>
                                    ${order.status !== 'completed' ? 
                                        `<button class="btn btn-sm btn-success" onclick="markAsComplete('${order.invoice_id}')">
                                            <i class="ri-check-line"></i> Complete
                                        </button>` : 
                                        `<span class="badge bg-success">Completed</span>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            profitLossList.innerHTML = ordersHtml;
        }

        function loadPaymentTabsData(invoiceId) {
            // Set invoice IDs for all forms
            document.getElementById('supplierInvoiceId').value = invoiceId;
            document.getElementById('shippingInvoiceId').value = invoiceId;
            document.getElementById('commissionInvoiceId').value = invoiceId;

            // Load order details and payment history
            Promise.all([
                fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`),
                fetch(`../private/profit_loss_backend.php?action=get_payment_history&invoice_id=${invoiceId}`)
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([orderData, paymentData]) => {
                if (orderData.success && paymentData.success) {
                    displayPaymentTabs(invoiceId, orderData, paymentData);
                    setupPaymentCalculations(orderData);
                    displayCommissionData(orderData);
                } else {
                    document.getElementById('supplierSummary').innerHTML = 
                        '<div class="alert alert-danger">Error loading data</div>';
                    document.getElementById('shippingSummary').innerHTML = 
                        '<div class="alert alert-danger">Error loading data</div>';
                    document.getElementById('commissionSummary').innerHTML = 
                        '<div class="alert alert-danger">Error loading data</div>';
                }
            })
            .catch(error => {
                document.getElementById('supplierSummary').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
                document.getElementById('shippingSummary').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
                document.getElementById('commissionSummary').innerHTML = 
                    '<div class="alert alert-danger">Failed to load data</div>';
            });
        }

        function displayPaymentTabs(invoiceId, orderData, paymentData) {
            const summary = orderData.summary;
            const payments = paymentData.payments || [];
            
            // Calculate due amounts - keep currencies separate like main table
            const supplierDue = summary.total_supplier_cost_yen;
            const avgConversionRate = parseFloat(summary.avg_conversion_rate || 0.032);
            const supplierDueRm = supplierDue / avgConversionRate; // Yen ÷ rate = RM
            const shippingDue = summary.total_shipping_cost_rm; // Fixed: use RM for shipping
            const supplierPaid = summary.supplier_payments_made;
            const supplierPaidRm = supplierPaid / avgConversionRate; // Yen ÷ rate = RM
            const shippingPaid = summary.shipping_payments_made;
            const supplierBalance = supplierDueRm - supplierPaidRm; // Use RM for balance
            const shippingBalance = shippingDue - shippingPaid;

            // Supplier Summary
            const supplierSummaryHtml = `
                <div class="mb-3">
                    <p class="mb-1"><strong>Total Due:</strong></p>
                    <h5 class="text-primary">RM ${formatCurrency(supplierDueRm)}</h5>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Amount Paid:</strong></p>
                    <h6 class="text-success">RM ${formatCurrency(supplierPaidRm)}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Balance:</strong></p>
                    <h6 class="${supplierBalance > 0 ? 'text-danger' : supplierBalance < 0 ? 'text-warning' : 'text-success'}">
                        RM ${formatCurrency(Math.abs(supplierBalance))}
                        ${supplierBalance > 0 ? '(Outstanding)' : supplierBalance < 0 ? '(Overpaid)' : '(Paid)'}
                    </h6>
                </div>
                <div class="alert alert-light">
                    <small><strong>Note:</strong> Overpayment will reduce profit margin</small>
                </div>
            `;

            // Shipping Summary - use RM currency to match main table
            const shippingSummaryHtml = `
                <div class="mb-3">
                    <p class="mb-1"><strong>Total Due:</strong></p>
                    <h5 class="text-info">RM ${formatNumber(shippingDue)}</h5>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Amount Paid:</strong></p>
                    <h6 class="text-success">RM ${formatNumber(shippingPaid)}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Balance:</strong></p>
                    <h6 class="${shippingBalance > 0 ? 'text-danger' : shippingBalance < 0 ? 'text-warning' : 'text-success'}">
                        RM ${formatNumber(Math.abs(shippingBalance))}
                        ${shippingBalance > 0 ? '(Outstanding)' : shippingBalance < 0 ? '(Overpaid)' : '(Paid)'}
                    </h6>
                </div>
                <div class="alert alert-light">
                    <small><strong>Note:</strong> Underpayment will increase profit margin</small>
                </div>
            `;

            document.getElementById('supplierSummary').innerHTML = supplierSummaryHtml;
            document.getElementById('shippingSummary').innerHTML = shippingSummaryHtml;

            // Payment History
            const supplierPayments = payments.filter(p => p.type === 'supplier');
            const shippingPayments = payments.filter(p => p.type === 'shipping');
            const commissionPayments = payments.filter(p => p.type === 'commission');

            let supplierHistoryHtml = '';
            // avgConversionRate already declared above in this function scope
            supplierPayments.forEach(payment => {
                const amountRm = payment.amount / avgConversionRate; // Yen ÷ rate = RM
                const isDeduction = amountRm < 0;
                const amountClass = isDeduction ? 'text-danger' : 'text-success';
                const amountPrefix = isDeduction ? '-' : '+';
                
                supplierHistoryHtml += `
                    <tr>
                        <td>${payment.date}</td>
                        <td class="${amountClass} fw-medium">${amountPrefix}RM ${formatCurrency(Math.abs(amountRm))}</td>
                        <td>${payment.description || 'N/A'}</td>
                    </tr>
                `;
            });

            let shippingHistoryHtml = '';
            shippingPayments.forEach(payment => {
                const isDeduction = payment.amount < 0;
                const amountClass = isDeduction ? 'text-danger' : 'text-success';
                const amountPrefix = isDeduction ? '-' : '+';
                
                shippingHistoryHtml += `
                    <tr>
                        <td>${payment.date}</td>
                        <td class="${amountClass} fw-medium">${amountPrefix}RM ${formatNumber(Math.abs(payment.amount))}</td>
                        <td>${payment.description || 'N/A'}</td>
                    </tr>
                `;
            });

            let commissionHistoryHtml = '';
            commissionPayments.forEach(payment => {
                const isDeduction = payment.amount < 0;
                const amountClass = isDeduction ? 'text-danger' : 'text-success';
                const amountPrefix = isDeduction ? '-' : '+';
                
                commissionHistoryHtml += `
                    <tr>
                        <td>${payment.date}</td>
                        <td class="${amountClass} fw-medium">${amountPrefix}RM ${formatNumber(Math.abs(payment.amount))}</td>
                        <td>${payment.description || 'N/A'}</td>
                    </tr>
                `;
            });

            document.getElementById('supplierPaymentHistory').innerHTML = 
                supplierHistoryHtml || '<tr><td colspan="3" class="text-center">No payments recorded</td></tr>';
            document.getElementById('shippingPaymentHistory').innerHTML = 
                shippingHistoryHtml || '<tr><td colspan="3" class="text-center">No payments recorded</td></tr>';
            document.getElementById('commissionPaymentHistory').innerHTML = 
                commissionHistoryHtml || '<tr><td colspan="3" class="text-center">No payments recorded</td></tr>';
        }

        function setupPaymentCalculations(orderData) {
            const summary = orderData.summary;
            const avgConversionRate = parseFloat(summary.avg_conversion_rate || 0.032);
            
            // Supplier payment calculation - convert to RM
            const supplierAmountInput = document.getElementById('supplierPaymentAmount');
            const supplierImpactDiv = document.getElementById('supplierPaymentImpact');
            
            if (supplierAmountInput && supplierImpactDiv) {
                // Remove old listeners by cloning
                const newSupplierInput = supplierAmountInput.cloneNode(true);
                supplierAmountInput.parentNode.replaceChild(newSupplierInput, supplierAmountInput);
                
                newSupplierInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value) || 0;
                    const dueYen = parseFloat(summary.total_supplier_cost_yen || 0);
                    const paidYen = parseFloat(summary.supplier_payments_made || 0);
                    const dueRm = dueYen / avgConversionRate; // Convert Yen to RM
                    const paidRm = paidYen / avgConversionRate; // Convert Yen to RM
                    const newTotalRm = paidRm + amount;
                    const differenceRm = newTotalRm - dueRm;
                    
                    let impactHtml = '';
                    if (amount === 0) {
                        impactHtml = '<span class="text-muted">Enter amount to see payment impact</span>';
                    } else if (amount < 0) {
                        // Deduction/reversal
                        impactHtml = `<span class="text-danger"><i class="ri-subtract-line"></i> <strong>Deducting RM ${formatNumber(Math.abs(amount))}</strong><br><small>New total paid: RM ${formatNumber(newTotalRm)} | Remaining: RM ${formatNumber(dueRm - newTotalRm)}</small></span>`;
                    } else if (differenceRm > 0.01) { // Small tolerance for floating point
                        impactHtml = `<span class="text-warning"><i class="ri-error-warning-line"></i> Overpayment by RM ${formatNumber(differenceRm)}<br><small>This will reduce profit margin</small></span>`;
                    } else if (differenceRm < -0.01) {
                        impactHtml = `<span class="text-info"><i class="ri-information-line"></i> Partial payment: RM ${formatNumber(Math.abs(differenceRm))} remaining<br><small>Balance after this payment</small></span>`;
                    } else {
                        impactHtml = `<span class="text-success"><i class="ri-checkbox-circle-line"></i> Full payment: Exact amount due</span>`;
                    }
                    
                    supplierImpactDiv.innerHTML = impactHtml;
                });
                
                // Trigger initial update
                newSupplierInput.dispatchEvent(new Event('input'));
            }

            // Shipping payment calculation - already in RM
            const shippingAmountInput = document.getElementById('shippingPaymentAmount');
            const shippingImpactDiv = document.getElementById('shippingPaymentImpact');
            
            if (shippingAmountInput && shippingImpactDiv) {
                // Remove old listeners by cloning
                const newShippingInput = shippingAmountInput.cloneNode(true);
                shippingAmountInput.parentNode.replaceChild(newShippingInput, shippingAmountInput);
                
                newShippingInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value) || 0;
                    const dueRm = parseFloat(summary.total_shipping_cost_rm || 0);
                    const paidRm = parseFloat(summary.shipping_payments_made || 0);
                    const newTotalRm = paidRm + amount;
                    const differenceRm = newTotalRm - dueRm;
                    
                    let impactHtml = '';
                    if (amount === 0) {
                        impactHtml = '<span class="text-muted">Enter amount to see payment impact</span>';
                    } else if (amount < 0) {
                        // Deduction/reversal
                        impactHtml = `<span class="text-danger"><i class="ri-subtract-line"></i> <strong>Deducting RM ${formatNumber(Math.abs(amount))}</strong><br><small>New total paid: RM ${formatNumber(newTotalRm)} | Remaining: RM ${formatNumber(dueRm - newTotalRm)}</small></span>`;
                    } else if (differenceRm > 0.01) { // Small tolerance for floating point
                        impactHtml = `<span class="text-warning"><i class="ri-error-warning-line"></i> Overpayment by RM ${formatNumber(differenceRm)}<br><small>This will reduce profit margin</small></span>`;
                    } else if (differenceRm < -0.01) {
                        impactHtml = `<span class="text-info"><i class="ri-information-line"></i> Partial payment: RM ${formatNumber(Math.abs(differenceRm))} remaining<br><small>Balance after this payment</small></span>`;
                    } else {
                        impactHtml = `<span class="text-success"><i class="ri-checkbox-circle-line"></i> Full payment: Exact amount due</span>`;
                    }
                    
                    shippingImpactDiv.innerHTML = impactHtml;
                });
                
                // Trigger initial update
                newShippingInput.dispatchEvent(new Event('input'));
            }
        }

        function displayCommissionData(orderData) {
            const order = orderData.order;
            
            // Check if this order has staff commission
            if (!order.commission_staff_id || !order.staff_name) {
                document.getElementById('commissionSummary').innerHTML = `
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        No staff commission assigned to this order.
                    </div>
                `;
                // Disable the form
                document.getElementById('commissionPaymentForm').style.display = 'none';
                return;
            }
            
            // Calculate commission amounts
            const commissionPercentage = parseFloat(order.commission_percentage || 0);
            const totalRevenue = parseFloat(orderData.summary?.total_revenue || 0); // Use discounted revenue for commission
            const commissionDue = totalRevenue * (commissionPercentage / 100);
            const commissionPaid = parseFloat(order.commission_paid_amount || 0);
            const commissionRemaining = commissionDue - commissionPaid;
            
            // Display commission summary
            const commissionSummaryHtml = `
                <div class="mb-3">
                    <p class="mb-1"><strong>Staff Member:</strong></p>
                    <h6 class="text-primary">${order.staff_name}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Commission Rate:</strong></p>
                    <h6 class="text-info">${commissionPercentage}%</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Total Commission Due:</strong></p>
                    <h5 class="text-primary">RM ${formatNumber(commissionDue)}</h5>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Amount Paid:</strong></p>
                    <h6 class="text-success">RM ${formatNumber(commissionPaid)}</h6>
                </div>
                <div class="mb-3">
                    <p class="mb-1"><strong>Remaining Balance:</strong></p>
                    <h6 class="${commissionRemaining > 0 ? 'text-danger' : commissionRemaining < 0 ? 'text-warning' : 'text-success'}">
                        RM ${formatNumber(commissionRemaining)}
                        ${commissionRemaining <= 0 ? '<span class="badge bg-success ms-2">Paid</span>' : ''}
                    </h6>
                </div>
                <div class="alert alert-light">
                    <small><strong>Note:</strong> Commission based on total revenue of RM ${formatNumber(totalRevenue)} (fixed amount, unaffected by payments made)</small>
                </div>
            `;
            
            document.getElementById('commissionSummary').innerHTML = commissionSummaryHtml;
            
            // Clear payment amount field (don't auto-populate)
            document.getElementById('commissionPaymentAmount').value = '';
            
            // Show the form
            document.getElementById('commissionPaymentForm').style.display = 'block';
            
            // Setup payment impact calculation
            const amountInput = document.getElementById('commissionPaymentAmount');
            const impactDiv = document.getElementById('commissionPaymentImpact');
            
            if (amountInput && impactDiv) {
                // Remove old listeners by cloning
                const newAmountInput = amountInput.cloneNode(true);
                amountInput.parentNode.replaceChild(newAmountInput, amountInput);
                
                newAmountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value) || 0;
                    const newTotal = commissionPaid + amount;
                    const difference = newTotal - commissionDue;
                    
                    let impactHtml = '';
                    if (amount === 0) {
                        impactHtml = '<span class="text-muted">Enter amount to see payment impact</span>';
                    } else if (amount < 0) {
                        // Deduction/reversal
                        impactHtml = `<span class="text-danger"><i class="ri-subtract-line"></i> <strong>Deducting RM ${formatNumber(Math.abs(amount))}</strong><br><small>New total paid: RM ${formatNumber(newTotal)} | Remaining: RM ${formatNumber(commissionDue - newTotal)}</small></span>`;
                    } else if (difference > 0.01) { // Small tolerance for floating point
                        impactHtml = `<span class="text-warning"><i class="ri-error-warning-line"></i> Overpayment by RM ${formatNumber(difference)}<br><small>Staff will be overpaid</small></span>`;
                    } else if (difference < -0.01) {
                        impactHtml = `<span class="text-info"><i class="ri-information-line"></i> Partial payment: RM ${formatNumber(Math.abs(difference))} remaining<br><small>Balance after this payment</small></span>`;
                    } else {
                        impactHtml = `<span class="text-success"><i class="ri-checkbox-circle-line"></i> Full commission payment</span>`;
                    }
                    
                    impactDiv.innerHTML = impactHtml;
                });
                
                // Trigger initial update
                newAmountInput.dispatchEvent(new Event('input'));
            }
        }

        // Global functions for button actions

        function managePayments(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();

            // Load payment data for both tabs
            loadPaymentTabsData(invoiceId);
        }

        function markAsComplete(invoiceId) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Required',
                    text: 'Please verify your access key first.'
                });
                return;
            }

            document.getElementById('completeInvoiceId').value = invoiceId;
            
            // Load order summary for confirmation
            fetch(`../private/profit_loss_backend.php?action=get_order_details&invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const summary = data.summary;
                    const order = data.order;
                    
                    // Use the same calculations as main table
                    const supplierCostYen = parseFloat(summary.total_supplier_cost_yen || 0);
                    const shippingCostRm = parseFloat(summary.total_shipping_cost_rm || 0);
                    const supplierPaidYen = parseFloat(summary.supplier_payments_made || 0);
                    const shippingPaidRm = parseFloat(summary.shipping_payments_made || 0);
                    const avgConversionRate = parseFloat(summary.avg_conversion_rate || 0.032);
                    const totalRevenue = parseFloat(summary.total_revenue || 0);
                    const actualProfitLoss = parseFloat(summary.actual_profit_loss || 0);
                    
                    // Convert supplier amounts to RM (Yen ÷ rate = RM)
                    const supplierCostRm = supplierCostYen / avgConversionRate;
                    const supplierPaidRm = supplierPaidYen / avgConversionRate;
                    
                    // Calculate totals (same as main table)
                    const totalPaidRm = supplierPaidRm + shippingPaidRm;
                    const supplierRemainingRm = Math.max(0, supplierCostRm - supplierPaidRm);
                    const shippingRemainingRm = Math.max(0, shippingCostRm - shippingPaidRm);
                    const totalRemainingRm = supplierRemainingRm + shippingRemainingRm;
                    
                    const actualProfitClass = actualProfitLoss >= 0 ? 'text-success' : 'text-danger';
                    const remainingClass = totalRemainingRm <= 0.01 ? 'text-success' : 'text-danger';
                    
                    // Calculate commission information if staff is assigned (same as main table)
                    let commissionHtml = '';
                    if (order.commission_staff_id && order.staff_name) {
                        const commissionPercentage = parseFloat(order.commission_percentage || 0);
                        const commissionDue = totalRevenue * (commissionPercentage / 100);
                        const commissionPaid = parseFloat(order.commission_paid_amount || 0);
                        const commissionRemaining = commissionDue - commissionPaid;
                        const commissionClass = commissionRemaining <= 0 ? 'text-success' : 'text-danger';
                        
                        commissionHtml = `
                            <div class="col-md-12 mt-3">
                                <hr>
                                <h6 class="fw-bold">Commission Information</h6>
                                <p><strong>Staff:</strong> ${order.staff_name} (${commissionPercentage}%)</p>
                                <p><strong>Commission Due:</strong> RM ${formatNumber(commissionDue)} <small>(${commissionPercentage}% of revenue)</small></p>
                                <p><strong>Commission Paid:</strong> RM ${formatNumber(commissionPaid)}</p>
                                <p><strong>Commission Remaining:</strong> <span class="${commissionClass}">RM ${formatNumber(Math.abs(commissionRemaining))}</span></p>
                            </div>
                        `;
                    }
                    
                    const summaryHtml = `
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Final Order Summary</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Total Revenue:</strong> RM ${formatNumber(totalRevenue)}</p>
                                        <p><strong>Actual Profit/Loss:</strong> <span class="${actualProfitClass}">RM ${formatNumber(Math.abs(actualProfitLoss))}</span></p>
                                        <p><strong>Total Paid:</strong> <span class="text-success">RM ${formatNumber(totalPaidRm)}</span></p>
                                        <small class="text-muted">RM ${formatNumber(supplierPaidRm)} supplier + RM ${formatNumber(shippingPaidRm)} shipping</small>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Total Remaining:</strong> <span class="${remainingClass}">RM ${formatNumber(totalRemainingRm)}</span></p>
                                        <small class="text-muted">RM ${formatNumber(supplierRemainingRm)} supplier + RM ${formatNumber(shippingRemainingRm)} shipping</small>
                                        <br><br>
                                        <p><strong>Status:</strong> 
                                            <span class="badge ${totalRemainingRm <= 0.01 ? 'bg-success' : (totalPaidRm > 0 ? 'bg-warning' : 'bg-danger')}">
                                                ${totalRemainingRm <= 0.01 ? 'Fully Paid' : (totalPaidRm > 0 ? 'Partially Paid' : 'Unpaid')}
                                            </span>
                                        </p>
                                    </div>
                                    ${commissionHtml}
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('completionSummary').innerHTML = summaryHtml;
                    
                    const modal = new bootstrap.Modal(document.getElementById('markCompleteModal'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load order summary'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to connect to server'
                });
            });
        }

        // Open Status Change Modal
        function openStatusModal(invoiceId, currentStatus) {
            if (!accessGranted) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Access Denied',
                    text: 'Please verify your access key first'
                });
                return;
            }

            document.getElementById('statusChangeInvoiceId').value = invoiceId;
            
            // Highlight current status
            document.querySelectorAll('.status-option').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.status === currentStatus) {
                    btn.classList.add('active');
                    btn.style.borderWidth = '2px';
                }
            });
            
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        // Change Profit Loss Status Function
        function changeProfitLossStatus(newStatus) {
            const invoiceId = document.getElementById('statusChangeInvoiceId').value;
            
            // Show loading
            Swal.fire({
                title: 'Updating Status...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Hide modal first
            const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
            modal.hide();

            // Create form data
            const formData = new FormData();
            formData.append('action', 'change_profit_loss_status');
            formData.append('invoice_id', invoiceId);
            formData.append('new_status', newStatus);

            fetch('../private/profit_loss_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: `Profit/Loss status changed to ${newStatus}`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the data
                        loadProfitLossData(currentPage);
                    });
                } else {
                    throw new Error(data.error || 'Failed to update status');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to update status'
                });
            });
        }

        // Add click handlers for status buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-option').forEach(btn => {
                btn.addEventListener('click', function() {
                    const newStatus = this.dataset.status;
                    changeProfitLossStatus(newStatus);
                });
            });
        });

        function formatCurrency(amount) {
            // Handle null, undefined, empty string, or NaN values
            if (amount === null || amount === undefined || amount === '' || isNaN(amount)) {
                return '0';
            }
            
            const numValue = parseFloat(amount);
            
            // Check if the parsed value is still NaN
            if (isNaN(numValue)) {
                return '0';
            }
            
            return new Intl.NumberFormat('ja-JP').format(numValue);
        }

        // Filter Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        applyFilters();
                    }, 500); // Debounce search for 500ms
                });
                
                // Enter key support for search
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        applyFilters();
                    }
                });
            }
            
            // Month filter auto-apply
            const monthFilter = document.getElementById('monthFilter');
            if (monthFilter) {
                monthFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            // Status filter auto-apply
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            // Date range filters auto-apply
            const dateFromFilter = document.getElementById('dateFromFilter');
            const dateToFilter = document.getElementById('dateToFilter');
            
            if (dateFromFilter) {
                dateFromFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            if (dateToFilter) {
                dateToFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
        });
    </script>

<!-- Profit Details Modal -->
<div class="modal fade" id="profitDetailsModal" tabindex="-1" aria-labelledby="profitDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="profitDetailsModalLabel">
                    <i class="fas fa-chart-line me-2"></i>Profit/Loss Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="profitDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-clean-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>