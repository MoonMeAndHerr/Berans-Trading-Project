<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$errors = [];
$success = '';

$pdo = openDB();

// Get the customer ID from GET parameter
$customerId = $_GET['id'] ?? null;

if (!$customerId || !is_numeric($customerId)) {
    // Redirect or show error if no valid ID is provided
    $_SESSION['errors'] = ['Invalid customer ID.'];
    header('Location: customer-add.php'); // Assuming you have a list page
    exit;
}

// Fetch existing customer data
try {
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = :id AND deleted_at IS NULL");
    $stmt->execute([':id' => $customerId]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        $_SESSION['errors'] = ['Customer not found or has been deleted.'];
        header('Location: customer-add.php');
        exit;
    }
} catch (PDOException $e) {
    $errors[] = 'Error loading customer data: ' . $e->getMessage();
}

// Handle POST submission for update or delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_customer']) && $_POST['delete_customer'] === '1') {
        // Soft delete: set deleted_at to current timestamp
        try {
            $stmt = $pdo->prepare("UPDATE customer SET deleted_at = NOW() WHERE customer_id = :id");
            $stmt->execute([':id' => $customerId]);

            $_SESSION['success'] = '✅ Customer deleted successfully.';
            header('Location: customer-add.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Error deleting customer: ' . $e->getMessage();
        }
    } else {
        // Sanitize inputs for update
        $customer_name        = trim($_POST['customer_name'] ?? '');
        $customer_phone       = trim($_POST['customer_phone'] ?? '');
        $customer_address     = trim($_POST['customer_address'] ?? '');
        $customer_company     = trim($_POST['customer_company_name'] ?? '');
        $customer_designation = trim($_POST['customer_designation'] ?? '');

        // Validate required fields
        if ($customer_name === '') {
            $errors[] = 'Customer name is required.';
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE customer SET 
                    customer_name = :customer_name,
                    customer_phone = :customer_phone,
                    customer_address = :customer_address,
                    customer_company_name = :customer_company_name,
                    customer_designation = :customer_designation
                    WHERE customer_id = :id AND deleted_at IS NULL");

                $stmt->execute([
                    ':customer_name'        => $customer_name,
                    ':customer_phone'       => $customer_phone ?: null,
                    ':customer_address'     => $customer_address ?: null,
                    ':customer_company_name'=> $customer_company ?: null,
                    ':customer_designation' => $customer_designation ?: null,
                    ':id'                   => $customerId,
                ]);

                $_SESSION['success'] = '✅ Customer updated successfully.';
                // Redirect to avoid form resubmission
                header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . urlencode($customerId));
                exit;

            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Get flash messages for display if any
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
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

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h3 class="card-title mb-0">Edit Customer: <?= htmlspecialchars($customer['customer_name']) ?></h3>
        </div>
        <div class="card-body">
            <!-- Show Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Show Success -->
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Edit Customer Form -->
            <form method="post" action="">
                <div class="row mb-3 align-items-center">
                    <label for="customer_name" class="col-lg-3 col-form-label">Customer Name <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <input type="text" id="customer_name" name="customer_name" class="form-control" value="<?= htmlspecialchars($customer['customer_name']) ?>" required>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="customer_phone" class="col-lg-3 col-form-label">Phone</label>
                    <div class="col-lg-9">
                        <input type="text" id="customer_phone" name="customer_phone" class="form-control" value="<?= htmlspecialchars($customer['customer_phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="customer_company_name" class="col-lg-3 col-form-label">Company</label>
                    <div class="col-lg-9">
                        <input type="text" id="customer_company_name" name="customer_company_name" class="form-control" value="<?= htmlspecialchars($customer['customer_company_name'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="customer_designation" class="col-lg-3 col-form-label">Designation</label>
                    <div class="col-lg-9">
                        <input type="text" id="customer_designation" name="customer_designation" class="form-control" value="<?= htmlspecialchars($customer['customer_designation'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3 align-items-start">
                    <label for="customer_address" class="col-lg-3 col-form-label">Address</label>
                    <div class="col-lg-9">
                        <textarea id="customer_address" name="customer_address" class="form-control" rows="3"><?= htmlspecialchars($customer['customer_address'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
            </form>

            <!-- Delete form OUTSIDE the update form -->
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this customer?');" class="mt-3">
                <input type="hidden" name="delete_customer" value="1">
                <button type="submit" class="btn btn-danger">Delete Customer</button>
            </form>
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

</body>

</html>