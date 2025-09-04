<?php
// ✅ Start session and include configs
use GuzzleHttp\Client;
require 'vendor/autoload.php';
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

            $xeroAuth = refreshXeroToken(); // always returns valid token
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

// ✅ Open DB connection if not already opened
if (!isset($pdo)) {
    $pdo = openDB();
}

// ✅ Pagination and search
$limit = 4;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$search = trim($_GET['search'] ?? '');

// ✅ Build WHERE with unique placeholders
$whereSQL = "WHERE deleted_at IS NULL";
$params = [];
if ($search !== '') {
    $whereSQL .= " AND (
        customer_name LIKE :search_name
        OR customer_company_name LIKE :search_company
        OR customer_phone LIKE :search_phone
    )";
    $params[':search_name']   = '%' . $search . '%';
    $params[':search_company'] = '%' . $search . '%';
    $params[':search_phone']  = '%' . $search . '%';
}

// ✅ Count total matching records
$countSQL = "SELECT COUNT(*) FROM customer $whereSQL";
$countStmt = $pdo->prepare($countSQL);
$countStmt->execute($params);
$totalRecords = (int)$countStmt->fetchColumn();

// ✅ Pagination calculation
$totalPages = max(1, (int)ceil($totalRecords / $limit));
if ($page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $limit;

// ✅ Fetch paginated data
$dataSQL = "SELECT * FROM customer $whereSQL ORDER BY customer_id DESC LIMIT :limit OFFSET :offset";
$selectStmt = $pdo->prepare($dataSQL);

// Bind search params
foreach ($params as $key => $value) {
    $selectStmt->bindValue($key, $value, PDO::PARAM_STR);
}

// Bind pagination params
$selectStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$selectStmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$selectStmt->execute();
$customers = $selectStmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ AJAX handler
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    if (!empty($customers)) {
        foreach ($customers as $customer) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($customer['customer_name']) . '</td>';
            echo '<td>' . htmlspecialchars($customer['customer_phone'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($customer['customer_company_name'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($customer['customer_designation'] ?? '-') . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($customer['customer_address'] ?? '-')) . '</td>';
            echo '<td><a href="customer-edit-update.php?id=' . urlencode($customer['customer_id']) . '" class="btn btn-sm btn-primary">Edit</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">No customers found.</td></tr>';
    }
    exit;
}

?>
<?php include __DIR__ . '/../include/header.php';?>

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
                                <div class="col-xxl-4">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Add New Customer</h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                            <p class="text-muted">
                                                Please complete the customer information form below. Each field helps us maintain accurate records, so ensure all required details are entered correctly. 
                                            </p>
                                            <!-- ✅ Display messages -->
                                            <?php if (!empty($errors)): ?>
                                                <div class="alert alert-danger">
                                                    <ul><?php foreach ($errors as $err) echo '<li>' . htmlspecialchars($err) . '</li>'; ?></ul>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($success)): ?>
                                                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                                            <?php endif; ?>

                                            <div class="live-preview">
                                                <form method="post" action="">
                                                    <!-- Customer Name -->
                                                    <div class="row mb-3">
                                                        <label for="customer_name" class="col-lg-3 col-form-label">Customer Name<span class="text-danger">*</span></label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                                                value="<?= isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : '' ?>"
                                                                placeholder="Enter customer name" required>
                                                        </div>
                                                    </div>

                                                    <!-- Customer Phone -->
                                                    <div class="row mb-3">
                                                        <label for="customer_phone" class="col-lg-3 col-form-label">Phone</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                                                value="<?= isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : '' ?>"
                                                                placeholder="Enter phone number">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Address -->
                                                    <div class="row mb-3">
                                                        <label for="customer_address" class="col-lg-3 col-form-label">Address Line 1</label>
                                                        <div class="col-lg-9">
                                                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3"
                                                                    placeholder="Enter address"><?= isset($_POST['customer_address']) ? htmlspecialchars($_POST['customer_address']) : '' ?></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Customer City -->
                                                    <div class="row mb-3">
                                                        <label for="customer_city" class="col-lg-3 col-form-label">City</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_city" name="customer_city"
                                                                value="<?= isset($_POST['customer_city']) ? htmlspecialchars($_POST['customer_city']) : '' ?>"
                                                                placeholder="Enter city">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Region -->
                                                    <div class="row mb-3">
                                                        <label for="customer_region" class="col-lg-3 col-form-label">Region</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_region" name="customer_region"
                                                                value="<?= isset($_POST['customer_region']) ? htmlspecialchars($_POST['customer_region']) : '' ?>"
                                                                placeholder="Enter region">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Postcode -->
                                                    <div class="row mb-3">
                                                        <label for="customer_postcode" class="col-lg-3 col-form-label">Postal Code</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_postcode" name="customer_postcode"
                                                                value="<?= isset($_POST['customer_postcode']) ? htmlspecialchars($_POST['customer_postcode']) : '' ?>"
                                                                placeholder="Enter postal code">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Country -->
                                                    <div class="row mb-3">
                                                        <label for="customer_country" class="col-lg-3 col-form-label">Country</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_country" name="customer_country"
                                                                value="<?= isset($_POST['customer_country']) ? htmlspecialchars($_POST['customer_country']) : '' ?>"
                                                                placeholder="Enter country">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Company -->
                                                    <div class="row mb-3">
                                                        <label for="customer_company_name" class="col-lg-3 col-form-label">Company Name</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_company_name" name="customer_company_name"
                                                                value="<?= isset($_POST['customer_company_name']) ? htmlspecialchars($_POST['customer_company_name']) : '' ?>"
                                                                placeholder="Enter company name">
                                                        </div>
                                                    </div>

                                                    <!-- Customer Designation -->
                                                    <div class="row mb-3">
                                                        <label for="customer_designation" class="col-lg-3 col-form-label">Designation</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" class="form-control" id="customer_designation" name="customer_designation"
                                                                value="<?= isset($_POST['customer_designation']) ? htmlspecialchars($_POST['customer_designation']) : '' ?>"
                                                                placeholder="Enter designation">
                                                        </div>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-primary">Add Customer</button>
                                                    </div>
                                                </form>
                                            </div><!-- end live-preview -->
                                        </div><!-- end card-body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->




                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Customer List</h4>
                                    </div>

                                    <div class="card-body">
                                        <p class="text-muted">This table shows all customers currently stored in the database.</p>

                                        <!-- ✅ Live search -->
                                        <div class="mb-3 d-flex">
                                            <input
                                                type="search"
                                                id="searchInput"
                                                class="form-control me-2"
                                                placeholder="Search customers by name, company, or phone"
                                                value="<?= htmlspecialchars($search) ?>"
                                                oninput="liveSearch()"
                                            >
                                        </div>

                                        <div class="live-preview">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer Name</th>
                                                            <th>Phone</th>
                                                            <th>Company</th>
                                                            <th>Designation</th>
                                                            <th>Address Line 1</th>
                                                            <th>City</th>
                                                            <th>Region</th>
                                                            <th>Postal Code</th>
                                                            <th>Country</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="customerTableBody">
                                                        <?php if (!empty($customers)): ?>
                                                            <?php foreach ($customers as $customer): ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($customer['customer_name']) ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_phone'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_company_name'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_designation'] ?? '-') ?></td>
                                                                    <td><?= nl2br(htmlspecialchars($customer['customer_address'] ?? '-')) ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_city'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_region'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_postcode'] ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($customer['customer_country'] ?? '-') ?></td>
                                                                    <td><a href="customer-edit-update.php?id=<?= urlencode($customer['customer_id']) ?>" class="btn btn-sm btn-primary">Edit</a></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr><td colspan="6" class="text-center">No customers found.</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- ✅ Pagination -->
                                        <nav aria-label="Page navigation example" class="mt-3">
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?<?= http_build_query(['search' => $search, 'page' => max(1, $page - 1)]) ?>">Previous</a>
                                                </li>
                                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                    <li class="page-item <?= ($page === $i) ? 'active' : '' ?>">
                                                        <a class="page-link" href="?<?= http_build_query(['search' => $search, 'page' => $i]) ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                                    <a class="page-link" href="?<?= http_build_query(['search' => $search, 'page' => min($totalPages, $page + 1)]) ?>">Next</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>


                        </div><!-- end row -->
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
let searchTimer = null;
function liveSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        const q = document.getElementById('searchInput').value;
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', '1');
        url.searchParams.set('search', q);
        fetch(url.toString())
            .then(response => response.text())
            .then(html => {
                document.getElementById('customerTableBody').innerHTML = html;
            })
            .catch(err => console.error(err));
    }, 300);
}
</script>




</body>

</html>