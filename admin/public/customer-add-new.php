<?php
// ✅ Start session and include configs
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$errors = [];
$success = '';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name        = trim($_POST['customer_name'] ?? '');
    $customer_phone       = trim($_POST['customer_phone'] ?? '');
    $customer_address     = trim($_POST['customer_address'] ?? '');
    $customer_company     = trim($_POST['customer_company_name'] ?? '');
    $customer_city        = trim($_POST['customer_city'] ?? '');
    $customer_region       = trim($_POST['customer_region'] ?? '');
    $customer_postcode     = trim($_POST['customer_postcode'] ?? '');
    $customer_country     = trim($_POST['customer_country'] ?? '');
    $customer_designation = trim($_POST['customer_designation'] ?? '');

    if ($customer_name === '') {
        $errors[] = 'Customer name is required.';
    }

    if (empty($errors)) {
        try {
            $xeroAuth = refreshXeroToken();
            $accessToken = $xeroAuth['access_token'];
            $tenantId    = $xeroAuth['tenant_id'];
            
            $client = new Client();
            $response = $client->post('https://api.xero.com/api.xro/2.0/Contacts', [
                'headers' => [
                    'Authorization'   => 'Bearer ' . $accessToken,
                    'Accept'          => 'application/json',
                    'Content-Type'    => 'application/json',
                    'Xero-tenant-id'  => $tenantId,
                ],
                'json' => [
                    'Name'       => $customer_name,
                    "IsSupplier"=> false,
                    "IsCustomer"=> true,
                    'Phones' => [
                        [
                            'PhoneType'    => 'MOBILE',
                            'PhoneNumber'  => $customer_phone
                        ]
                    ],
                    'Addresses' => [
                        [
                            'AddressType'   => 'STREET',
                            'AddressLine1'  => $customer_address,
                            'City'          => $customer_city,
                            'Region'        => $customer_region,
                            'PostalCode'    => $customer_postcode,
                            'Country'       => $customer_country
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (!empty($result['Contacts'][0]['ContactID'])) {
                $xero_relation = $result['Contacts'][0]['ContactID'];
            } else {
                $errors[] = 'Failed to retrieve ContactID from Xero response.';
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $errors[] = 'Xero API error: ' . $responseBody;
        }
        
        try {
            $pdo = openDB();

            $insertSQL = "INSERT INTO customer (
                customer_name,
                customer_phone,
                customer_address,
                customer_city,
                customer_region,
                customer_postcode,
                customer_country,
                customer_company_name,
                customer_designation,
                xero_relation
            ) VALUES (
                :customer_name,
                :customer_phone,
                :customer_address,
                :customer_city,
                :customer_region,
                :customer_postcode,
                :customer_country,
                :customer_company_name,
                :customer_designation,
                :xero_relation
            )";

            $insertStmt = $pdo->prepare($insertSQL);
            $insertStmt->execute([
                ':customer_name'         => $customer_name,
                ':customer_phone'        => $customer_phone ?: null,
                ':customer_address'      => $customer_address ?: null,
                ':customer_city'        => $customer_city ?: null,
                ':customer_region'      => $customer_region ?: null,
                ':customer_postcode' => $customer_postcode ?: null,
                ':customer_country'  => $customer_country ?: null,
                ':customer_company_name' => $customer_company ?: null,
                ':customer_designation'  => $customer_designation ?: null,
                ':xero_relation'         => $xero_relation
            ]);

            $_SESSION['success'] = '✅ New customer added successfully!';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();

        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// ✅ Flash messages
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// ✅ Open DB connection
if (!isset($pdo)) {
    $pdo = openDB();
}

// ✅ Get all customers
$allCustomers = $pdo->query("SELECT * FROM customer WHERE deleted_at IS NULL ORDER BY customer_id DESC")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Calculate stats
$totalCustomers = count($allCustomers);
$lastCustomer = !empty($allCustomers) ? $allCustomers[0] : null;
$lastCustomerDate = $lastCustomer ? $lastCustomer['created_at'] : null;

// Count customers with company
$customersWithCompany = count(array_filter($allCustomers, function($c) {
    return !empty($c['customer_company_name']);
}));

?>
<?php include __DIR__ . '/../include/header.php';?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Customer Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="dashboard-projects.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Customers</li>
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
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Customers</p>
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
                                        <span class="counter-value" data-target="<?= $totalCustomers ?>">0</span>
                                    </h4>
                                    <span class="badge bg-success-subtle text-success mb-0">
                                        <i class="ri-user-check-line align-middle"></i> All Registered
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="ri-user-3-line text-success"></i>
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
                                    <p class="text-uppercase fw-medium text-muted mb-0">Corporate Clients</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-info fs-14 mb-0">
                                        <i class="ri-building-line fs-13 align-middle"></i> Business
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                        <span class="counter-value" data-target="<?= $customersWithCompany ?>">0</span>
                                    </h4>
                                    <span class="badge bg-info-subtle text-info mb-0">
                                        With Company Info
                                    </span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="ri-building-2-line text-info"></i>
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
                                <button class="btn btn-light btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                    <i class="ri-user-add-line align-middle me-1"></i> Add New
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

            <?php if ($success): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Customer Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-user-line align-middle me-2"></i> Customer Directory
                                </h5>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                        <i class="ri-user-add-line align-middle me-1"></i> Add Customer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="customerTable" class="table table-hover table-bordered nowrap align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Designation</th>
                                        <th>City</th>
                                        <th>Region</th>
                                        <th>Country</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($allCustomers as $customer): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($customer['customer_name']) ?></strong></td>
                                            <td>
                                                <?php if ($customer['customer_phone']): ?>
                                                    <i class="ri-phone-line text-success me-1"></i><?= htmlspecialchars($customer['customer_phone']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($customer['customer_company_name'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['customer_designation'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['customer_city'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['customer_region'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($customer['customer_country'] ?? 'N/A') ?></td>
                                            <td>
                                                <div class="hstack gap-2 justify-content-center">
                                                    <button class="btn btn-sm btn-soft-info" 
                                                            onclick='viewCustomerDetails(<?= json_encode($customer) ?>)'
                                                            data-bs-toggle="tooltip" title="View Details">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <a href="customer-edit-update.php?id=<?= $customer['customer_id'] ?>" 
                                                       class="btn btn-sm btn-soft-primary"
                                                       onclick="localStorage.setItem('customerFromEdit', 'true');"
                                                       data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ri-edit-2-line"></i>
                                                    </a>
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

<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle">
                <h5 class="modal-title">
                    <i class="ri-user-line me-2"></i>Customer Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-user-3-line me-1"></i>Customer Name</label>
                            <h6 class="fw-semibold" id="view_customer_name"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-phone-line me-1"></i>Phone</label>
                            <h6 id="view_customer_phone"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-building-line me-1"></i>Company</label>
                            <h6 id="view_customer_company"></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-briefcase-line me-1"></i>Designation</label>
                            <h6 id="view_customer_designation"></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1"><i class="ri-map-pin-line me-1"></i>Address</label>
                            <h6 id="view_customer_address"></h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">City</label>
                            <h6 id="view_customer_city"></h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Region</label>
                            <h6 id="view_customer_region"></h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Postal Code</label>
                            <h6 id="view_customer_postcode"></h6>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex flex-column">
                            <label class="form-label text-muted mb-1">Country</label>
                            <h6 id="view_customer_country"></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editCustomerFromModal()">
                    <i class="ri-edit-2-line me-1"></i>Edit Customer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title">
                    <i class="ri-user-add-line me-2"></i>Add New Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="addCustomerForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Enter customer name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="customer_phone" class="form-control" placeholder="+60 12-345 6789">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="customer_company_name" class="form-control" placeholder="Enter company name">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Designation</label>
                            <input type="text" name="customer_designation" class="form-control" placeholder="e.g., Manager, Director">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="customer_address" class="form-control" rows="2" placeholder="Enter complete address"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="customer_city" class="form-control" placeholder="e.g., Kuala Lumpur">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Region/State</label>
                            <input type="text" name="customer_region" class="form-control" placeholder="e.g., Selangor">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="customer_postcode" class="form-control" placeholder="e.g., 50000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="customer_country" class="form-control" placeholder="e.g., Malaysia">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="$('#addCustomerForm').submit()">
                    <i class="ri-save-line align-middle me-1"></i> Save Customer
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
var currentCustomer = null;

function refreshTable() {
    location.reload();
}

// View Customer Details
function viewCustomerDetails(customer) {
    currentCustomer = customer;
    $('#view_customer_name').text(customer.customer_name || 'N/A');
    $('#view_customer_phone').text(customer.customer_phone || 'N/A');
    $('#view_customer_company').text(customer.customer_company_name || 'N/A');
    $('#view_customer_designation').text(customer.customer_designation || 'N/A');
    $('#view_customer_address').text(customer.customer_address || 'N/A');
    $('#view_customer_city').text(customer.customer_city || 'N/A');
    $('#view_customer_region').text(customer.customer_region || 'N/A');
    $('#view_customer_postcode').text(customer.customer_postcode || 'N/A');
    $('#view_customer_country').text(customer.customer_country || 'N/A');
    $('#viewCustomerModal').modal('show');
}

function editCustomerFromModal() {
    if (currentCustomer) {
        localStorage.setItem('customerFromEdit', 'true');
        window.location.href = 'customer-edit-update.php?id=' + currentCustomer.customer_id;
    }
}

// Document Ready
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter persistence logic
    const shouldRestore = localStorage.getItem('customerFromEdit') === 'true';
    
    if (!shouldRestore) {
        localStorage.removeItem('DataTables_customerTable');
    }
    
    // Initialize DataTable
    var customerTable = $('#customerTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        order: [[0, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search customers...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ customers",
            infoEmpty: "No customers found",
            infoFiltered: "(filtered from _MAX_ total customers)",
            zeroRecords: "No matching customers found"
        },
        stateSave: true,
        stateDuration: -1,
        stateSaveCallback: function(settings, data) {
            localStorage.setItem('DataTables_customerTable', JSON.stringify(data));
        },
        stateLoadCallback: function(settings) {
            if (!shouldRestore) return null;
            try {
                return JSON.parse(localStorage.getItem('DataTables_customerTable'));
            } catch (e) {
                return null;
            }
        },
        initComplete: function() {
            if (shouldRestore) {
                localStorage.removeItem('customerFromEdit');
            }
        }
    });

    // Add form submission with SweetAlert
    $('#addCustomerForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Add Customer?',
            text: 'Are you sure you want to add this customer?',
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

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

</body>
</html>
