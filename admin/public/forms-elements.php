<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../private/config.php'; // $pdo

$successMsg = '';
$errorMsg = '';

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    unset($_SESSION['successMsg']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ===== Main fields =====
    $product_id       = intval($_POST['product_id'] ?? 0);
    $supplier_id      = intval($_POST['supplier_id'] ?? 0);
    $quantity         = floatval($_POST['quantity'] ?? 0);
    $carton_width     = floatval($_POST['carton_width'] ?? 0);
    $carton_height    = floatval($_POST['carton_height'] ?? 0);
    $carton_length    = floatval($_POST['carton_length'] ?? 0);
    $pcs_per_carton   = intval($_POST['pcs_per_carton'] ?? 0);
    $no_of_carton     = intval($_POST['no_of_carton'] ?? 0);
    $designlogo       = trim($_POST['designlogo'] ?? '');
    $price            = floatval($_POST['price'] ?? 0);
    $shipping_price   = floatval($_POST['shipping_price'] ?? 0);
    $additional_price = floatval($_POST['additional_price'] ?? 0);
    $conversion_rate  = floatval($_POST['conversion_rate'] ?? 0);
    $weight_carton    = floatval($_POST['weight_carton'] ?? 0);
    $estimated_arrival = $_POST['estimated_arrival'] ?? null;
    if ($estimated_arrival === '') {
        $estimated_arrival = null;  // convert empty string to null
    }

    // ===== Calculated fields =====
    $price_rm          = floatval($_POST['price_rm'] ?? 0);
    $total_price_yen   = floatval($_POST['total_price_yen'] ?? 0);
    $total_price_rm    = floatval($_POST['total_price_rm'] ?? 0);
    $deposit_50_yen    = floatval($_POST['deposit_50_yen'] ?? 0);
    $deposit_50_rm     = floatval($_POST['deposit_50_rm'] ?? 0);
    $cbm_carton        = floatval($_POST['cbm_carton'] ?? 0);
    $total_cbm         = floatval($_POST['total_cbm'] ?? 0);
    $vm_carton         = floatval($_POST['vm_carton'] ?? 0);
    $total_vm          = floatval($_POST['total_vm'] ?? 0);
    $total_weight      = floatval($_POST['total_weight'] ?? 0);
    $sg_tax            = floatval($_POST['sg_tax'] ?? 0);
    $supplier_1st_yen  = floatval($_POST['supplier_1st_yen'] ?? 0);
    $supplier_2nd_yen  = floatval($_POST['supplier_2nd_yen'] ?? 0);
    $customer_1st_rm   = floatval($_POST['customer_1st_rm'] ?? 0);
    $customer_2nd_rm   = floatval($_POST['customer_2nd_rm'] ?? 0);

    // ===== Shipping code from user input =====
    $shipping_code = trim($_POST['shipping_code'] ?? '');

    // ===== Shipping totals (calculated in JS, passed as hidden inputs) =====
    $price_total_sea_shipping    = floatval($_POST['price_total_sea_shipping'] ?? 0);
    $price_total_air_shipping_vm = floatval($_POST['price_total_air_shipping_vm'] ?? 0);
    $price_total_air_shipping_kg = floatval($_POST['price_total_air_shipping_kg'] ?? 0);

    // ===== Additional cartons =====
    $addCartons = [];
    for ($i = 1; $i <= 6; $i++) {
        $addCartons[$i] = [
            'width'     => floatval($_POST["add_carton{$i}_width"] ?? 0),
            'height'    => floatval($_POST["add_carton{$i}_height"] ?? 0),
            'length'    => floatval($_POST["add_carton{$i}_length"] ?? 0),
            'pcs'       => intval($_POST["add_carton{$i}_pcs"] ?? 0),
            'no'        => intval($_POST["add_carton{$i}_no"] ?? 0),
            'total_cbm' => floatval($_POST["add_carton{$i}_total_cbm"] ?? 0),
        ];
    }

    try {
        // Insert main price record with additional carton fields
        $sql = "
            INSERT INTO price (
                product_id, supplier_id, quantity,
                carton_width, carton_height, carton_length, pcs_per_carton, no_of_carton,
                designlogo, price, shipping_price, additional_price, conversion_rate, price_rm,
                total_price_yen, total_price_rm, deposit_50_yen, deposit_50_rm,
                cbm_carton, total_cbm, vm_carton, total_vm, total_weight, sg_tax,
                supplier_1st_yen, supplier_2nd_yen, customer_1st_rm, customer_2nd_rm,
                estimated_arrival,
                add_carton1_width, add_carton1_height, add_carton1_length, add_carton1_pcs, add_carton1_no, add_carton1_total_cbm,
                add_carton2_width, add_carton2_height, add_carton2_length, add_carton2_pcs, add_carton2_no, add_carton2_total_cbm,
                add_carton3_width, add_carton3_height, add_carton3_length, add_carton3_pcs, add_carton3_no, add_carton3_total_cbm,
                add_carton4_width, add_carton4_height, add_carton4_length, add_carton4_pcs, add_carton4_no, add_carton4_total_cbm,
                add_carton5_width, add_carton5_height, add_carton5_length, add_carton5_pcs, add_carton5_no, add_carton5_total_cbm,
                add_carton6_width, add_carton6_height, add_carton6_length, add_carton6_pcs, add_carton6_no, add_carton6_total_cbm
            ) VALUES (
                :product_id, :supplier_id, :quantity,
                :carton_width, :carton_height, :carton_length, :pcs_per_carton, :no_of_carton,
                :designlogo, :price, :shipping_price, :additional_price, :conversion_rate, :price_rm,
                :total_price_yen, :total_price_rm, :deposit_50_yen, :deposit_50_rm,
                :cbm_carton, :total_cbm, :vm_carton, :total_vm, :total_weight, :sg_tax,
                :supplier_1st_yen, :supplier_2nd_yen, :customer_1st_rm, :customer_2nd_rm,
                :estimated_arrival,
                :a1w,:a1h,:a1l,:a1p,:a1n,:a1c,
                :a2w,:a2h,:a2l,:a2p,:a2n,:a2c,
                :a3w,:a3h,:a3l,:a3p,:a3n,:a3c,
                :a4w,:a4h,:a4l,:a4p,:a4n,:a4c,
                :a5w,:a5h,:a5l,:a5p,:a5n,:a5c,
                :a6w,:a6h,:a6l,:a6p,:a6n,:a6c
            )
        ";
        $stmt = $pdo->prepare($sql);

        $bind = [
            ':product_id'       => $product_id,
            ':supplier_id'      => $supplier_id,
            ':quantity'         => $quantity,
            ':carton_width'     => $carton_width,
            ':carton_height'    => $carton_height,
            ':carton_length'    => $carton_length,
            ':pcs_per_carton'   => $pcs_per_carton,
            ':no_of_carton'     => $no_of_carton,
            ':designlogo'       => $designlogo,
            ':price'            => $price,
            ':shipping_price'   => $shipping_price,
            ':additional_price' => $additional_price,
            ':conversion_rate'  => $conversion_rate,
            ':price_rm'         => $price_rm,
            ':total_price_yen'  => $total_price_yen,
            ':total_price_rm'   => $total_price_rm,
            ':deposit_50_yen'   => $deposit_50_yen,
            ':deposit_50_rm'    => $deposit_50_rm,
            ':cbm_carton'       => $cbm_carton,
            ':total_cbm'        => $total_cbm,
            ':vm_carton'        => $vm_carton,
            ':total_vm'         => $total_vm,
            ':total_weight'     => $total_weight,
            ':sg_tax'           => $sg_tax,
            ':supplier_1st_yen' => $supplier_1st_yen,
            ':supplier_2nd_yen' => $supplier_2nd_yen,
            ':customer_1st_rm'  => $customer_1st_rm,
            ':customer_2nd_rm'  => $customer_2nd_rm,
            ':estimated_arrival'=> $estimated_arrival,
        ];

        for ($i = 1; $i <= 6; $i++) {
            $bind[":a{$i}w"] = $addCartons[$i]['width'];
            $bind[":a{$i}h"] = $addCartons[$i]['height'];
            $bind[":a{$i}l"] = $addCartons[$i]['length'];
            $bind[":a{$i}p"] = $addCartons[$i]['pcs'];
            $bind[":a{$i}n"] = $addCartons[$i]['no'];
            $bind[":a{$i}c"] = $addCartons[$i]['total_cbm'];
        }

        $stmt->execute($bind);

        // Get the newly inserted price_id
        $price_id = $pdo->lastInsertId();

        // Fetch price_shipping id by shipping_code
        $stmt2 = $pdo->prepare("SELECT shipping_price_id FROM price_shipping WHERE shipping_code = ?");
        $stmt2->execute([$shipping_code]);
        $shipping_price_id = $stmt2->fetchColumn();

        if ($shipping_price_id) {
            // Insert into price_shipping_total
            $sql_shipping_total = "
                INSERT INTO price_shipping_totals
                (shipping_price_id, price_id, price_total_sea_shipping, price_total_air_shipping_vm, price_total_air_shipping_kg)
                VALUES (:shipping_price_id, :price_id, :sea, :air_vm, :air_kg)
            ";
            $stmt3 = $pdo->prepare($sql_shipping_total);
            $stmt3->execute([
                ':shipping_price_id' => $shipping_price_id,
                ':price_id'          => $price_id,
                ':sea'               => $price_total_sea_shipping,
                ':air_vm'            => $price_total_air_shipping_vm,
                ':air_kg'            => $price_total_air_shipping_kg,
            ]);
        }

        $_SESSION['successMsg'] = "✅ Price record with additional cartons and shipping totals saved successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        $errorMsg = "❌ Error: " . $e->getMessage();
    }
}

// Fetch products for dropdown
$productOptions = [];
try {
    $stmt = $pdo->query("SELECT product_id, name, size_volume FROM product WHERE deleted_at IS NULL AND is_active = 1");
    $productOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching products: " . $e->getMessage();
}

// Fetch suppliers for dropdown
$supplierOptions = [];
try {
    $stmt = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL");
    $supplierOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching suppliers: " . $e->getMessage();
}

// Fetch shipping options for dropdown
$shippingOptions = [];
try {
    $stmt = $pdo->query("SELECT shipping_code FROM price_shipping");
    $shippingOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching shipping options: " . $e->getMessage();
}
?>


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

        <header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-dark.png" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/logo-light.png" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                <form class="app-search d-none" style="display:none;">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                            id="search-options" value="">
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                            id="search-close-options"></span>
                    </div>
                </form>
            </div>
                <!-- End App Search-->
                 
            <div class="d-flex align-items-center">

                <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-category-alt fs-22'></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"> Web Apps </h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="btn btn-sm btn-soft-info"> View All Apps
                                        <i class="ri-arrow-right-s-line align-middle"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/github.png" alt="Github">
                                        <span>GitHub</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/bitbucket.png" alt="bitbucket">
                                        <span>Bitbucket</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/dribbble.png" alt="dribbble">
                                        <span>Dribbble</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row g-0">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/dropbox.png" alt="dropbox">
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/mail_chimp.png" alt="mail_chimp">
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#!">
                                        <img src="assets/images/brands/slack.png" alt="slack">
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <?php
                    // Debugging: Check if session ID exists
                    // echo "<pre>Session ID: "; print_r($_SESSION['staff_id'] ?? 'No session ID'); echo "</pre>";

                    $current_user = null;
                    if (isset($_SESSION['staff_id'])) {
                        try {
                            $stmt = $pdo->prepare("SELECT staff_name, staff_designation FROM staff WHERE staff_id = :staff_id");
                            $stmt->bindParam(':staff_id', $_SESSION['staff_id']);
                            $stmt->execute();
                            $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            // Debugging: Check what was fetched from database
                            // echo "<pre>User Data: "; print_r($current_user); echo "</pre>";
                            
                            // Ensure we have at least empty strings if fields are NULL
                            $current_user['staff_name'] = $current_user['staff_name'] ?? 'Staff Member';
                            $current_user['staff_designation'] = $current_user['staff_designation'] ?? 'Employee';
                        } catch (PDOException $e) {
                            // Log the error for debugging
                            error_log("User data fetch error: " . $e->getMessage());
                            $current_user = [
                                'staff_name' => 'User',
                                'staff_designation' => 'Staff'
                            ];
                        }
                    }
                    ?>

<!-- Your dropdown button -->
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-<?php echo isset($_SESSION['staff_id']) ? ($_SESSION['staff_id'] % 10 + 1) : '1'; ?>.jpg"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    <?php echo htmlspecialchars($current_user['staff_name'] ?? 'Not connected'); ?>
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                    <?php echo htmlspecialchars($current_user['staff_designation'] ?? 'Not connected'); ?>
                                </span>
                            </span>
                        </span>
                    </button>

                    
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome <?php echo htmlspecialchars($_SESSION['staff_name'] ?? 'User'); ?>!</h6>
                        <a class="dropdown-item" href="pages-profile.php"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="apps-tasks-kanban.html"><i
                                class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Taskboard</span></a>
                        <a class="dropdown-item" href="pages-faqs.html"><i
                                class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Help</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="pages-profile-settings.html"><span
                                class="badge bg-soft-success text-success mt-1 float-end">New</span><i
                                class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Settings</span></a>
                        <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                                class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Lock screen</span></a>
                        <a class="dropdown-item" href="../private/logout.php">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle" data-key="t-logout">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>




        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="../public/dashboard-projects.php" class="nav-link" data-key="t-projects"> Projects </a>
                                    </li>
                                </ul>
                            </div>
                        </li> <!-- end Dashboard Menu -->


                        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>

                            <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarForms">
                                <i class="ri-file-list-3-line"></i> <span data-key="t-forms">Forms</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarForms">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="../public/forms-elements.php" class="nav-link" data-key="t-basic-elements">Set Pricing</a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>



        

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
                        <div class="col-lg-12">
                            <?php if ($successMsg): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
                            <?php elseif ($errorMsg): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
                            <?php endif; ?>

                            <div class="card">
                                <div class="card-header d-flex justify-content-center align-items-center">
                                    <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Enter Product Price Details</h2>
                                </div>

                                <div class="card-body">
                                    <form method="POST" id="priceForm">
                                        <div class="row g-3">

                                            <!-- Product -->
                                            <div class="col-md-6">
                                                <label for="product_id" class="form-label">Product (Item Name & Size)</label>
                                                <select class="form-select" id="product_id" name="product_id" required>
                                                    <option value="">Select product</option>
                                                    <?php foreach ($productOptions as $p): ?>
                                                        <option value="<?= $p['product_id'] ?>">
                                                            <?= htmlspecialchars($p['name'] . ' - ' . $p['size_volume']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- supplier -->
                                            <div class="col-md-6">
                                                <label for="supplier_id" class="form-label">Supplier</label>
                                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                                    <option value="">Select supplier</option>
                                                    <?php foreach ($supplierOptions as $s): ?>
                                                        <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="col-md-4">
                                                <label class="form-label">Minimum Order Quantity</label>
                                                <input type="number" class="form-control" name="quantity" id="quantity" value="10000" min="1" required>
                                            </div>

                                            <!-- Carton Width -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Width (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_width" id="carton_width" value="10.00" min="0" required>
                                            </div>

                                            <!-- Carton Height -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_height" id="carton_height" value="10.00" min="0" required>
                                            </div>

                                            <!-- Carton Length -->
                                            <div class="col-md-4">
                                                <label class="form-label">Carton Length (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="carton_length" id="carton_length" value="10.00" min="0" required>
                                            </div>

                                            <!-- PCS per Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">PCS per Carton</label>
                                                <input type="number" class="form-control" name="pcs_per_carton" id="pcs_per_carton" value="1000" min="1" required>
                                            </div>

                                            <!-- Number of Carton -->
                                            <div class="col-md-4">
                                                <label class="form-label">Number of Carton</label>
                                                <input type="number" class="form-control" name="no_of_carton" id="no_of_carton" value="10" min="1" required>
                                            </div>

                                            <!-- Design & Logo -->
                                            <div class="col-md-12">
                                                <label class="form-label">Design & Logo Details</label>
                                                <textarea class="form-control" name="designlogo" id="designlogo" rows="2" placeholder="Enter design and logo details"></textarea>
                                            </div>

                                            <!-- Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Price (Yen)</label>
                                                <input type="number" step="0.0000001" class="form-control" name="price" id="price" value="0.1680" min="0" required>
                                            </div>

                                            <!-- Shipping Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Shipping Price (Yen)</label>
                                                <input type="number" step="0.01" class="form-control" name="shipping_price" id="shipping_price" value="0.56" min="0" required>
                                            </div>

                                            <!-- Additional Price (Yen) -->
                                            <div class="col-md-4">
                                                <label class="form-label">Additional Price (Yen)</label>
                                                <input type="number" step="0.01" class="form-control" name="additional_price" id="additional_price" value="100" min="0" required>
                                            </div>

                                            <!-- Weight per Carton (kg) -->
                                            <div class="col-md-6">
                                                <label class="form-label">Weight per Carton (kg)</label>
                                                <input type="number" step="0.01" class="form-control" name="weight_carton" id="weight_carton" value="100.00" min="0" required>
                                            </div>

                                            <!-- Conversion Rate -->
                                            <div class="col-md-6">
                                                <label class="form-label">Conversion Rate (Yen to MYR)</label>
                                                <input type="number" step="0.0001" class="form-control" name="conversion_rate" id="conversion_rate" value="1.6" min="0" required>
                                            </div>
                                            <!-- Estimated Arrival -->
                                            <div class="col-md-6">
                                                <label class="form-label">Estimated Arrival Date</label>
                                                <input type="date" class="form-control" id="estimated_arrival" name="estimated_arrival">
                                            </div>                                

                                            <!-- ✅ ADDITIONAL CARTON -->

                                            <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Additional Carton</h2>
                                            </div>            
                                                                               

                                            <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 1</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton1_width" id="add_carton1_width" class="form-control" value="15">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton1_height" id="add_carton1_height" class="form-control" value="20">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton1_length" id="add_carton1_length" class="form-control" value="10">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton1_pcs" id="add_carton1_pcs" class="form-control" value="1000">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton1_no" id="add_carton1_no" class="form-control" value="10">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 1</label>
                                                            <input type="text" readonly id="add_carton1_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton1_total_cbm" id="add_carton1_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 2</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton2_width" id="add_carton2_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton2_height" id="add_carton2_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton2_length" id="add_carton2_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton2_pcs" id="add_carton2_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton2_no" id="add_carton2_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 2</label>
                                                            <input type="text" readonly id="add_carton2_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton2_total_cbm" id="add_carton2_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                        <div class="additional-carton" id="add-carton1">
                                                <h5>Additional Carton 3 </h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton3_width" id="add_carton3_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton3_height" id="add_carton3_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton3_length" id="add_carton3_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton3_pcs" id="add_carton3_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton3_no" id="add_carton3_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 3</label>
                                                            <input type="text" readonly id="add_carton3_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton3_total_cbm" id="add_carton3_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 4 -->
                                            <div class="additional-carton" id="add-carton4">
                                                <h5>Additional Carton 4</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton4_width" id="add_carton4_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton4_height" id="add_carton4_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton4_length" id="add_carton4_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton4_pcs" id="add_carton4_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton4_no" id="add_carton4_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 4</label>
                                                            <input type="text" readonly id="add_carton4_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton4_total_cbm" id="add_carton4_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 5 -->
                                            <div class="additional-carton" id="add-carton5">
                                                <h5>Additional Carton 5</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton5_width" id="add_carton5_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton5_height" id="add_carton5_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton5_length" id="add_carton5_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton5_pcs" id="add_carton5_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton5_no" id="add_carton5_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 5</label>
                                                            <input type="text" readonly id="add_carton5_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton5_total_cbm" id="add_carton5_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>

                                            <!-- ✅ Additional Carton 6 -->
                                            <div class="additional-carton" id="add-carton6">
                                                <h5>Additional Carton 6</h5>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Carton Width (W)</label>
                                                            <input type="number" step="0.01" name="add_carton6_width" id="add_carton6_width" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Height (H)</label>
                                                            <input type="number" step="0.01" name="add_carton6_height" id="add_carton6_height" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Carton Length (L)</label>
                                                            <input type="number" step="0.01" name="add_carton6_length" id="add_carton6_length" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>PCS / Carton</label>
                                                            <input type="number" name="add_carton6_pcs" id="add_carton6_pcs" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>No Of Carton</label>
                                                            <input type="number" name="add_carton6_no" id="add_carton6_no" class="form-control" value="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Total CBM Carton 6</label>
                                                            <input type="text" readonly id="add_carton6_total_cbm" class="form-control">
                                                            <input type="hidden" name="add_carton6_total_cbm" id="add_carton6_total_cbm_hidden">
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- ✅ SHIPPING METHOD -->

                                            <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Shipping Method</h2>
                                            </div>               

                                            <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label">Shipping Method</label>
                                                            <select class="form-select" id="shipping_code" name="shipping_code" required>
                                                                <option value="">-- Select Shipping Code --</option>
                                                                <option value="M1">M1 - Sea Normal Goods</option>
                                                                <option value="M2">M2 - Sea Sensitive Goods</option>
                                                                <option value="S1">S1 - SG Sea Normal Goods</option>
                                                                <option value="S2">S2 - SG Sea Sensitive Goods</option>
                                                                <option value="OCSG1">OCSG1 - OCOOL SG Sea Normal Goods</option>
                                                                <option value="OCSG2">OCSG2 - OCOOL SG Sea Sensitive Goods</option>
                                                                <option value="M3a">M3a - Air VM Normal Goods</option>
                                                                <option value="M3b">M3b - Air KG Normal Goods</option>
                                                                <option value="M4a">M4a - Air VM Sensitive Goods</option>
                                                                <option value="M4b">M4b - Air KG Sensitive Goods</option>
                                                                <option value="S3a">S3a - SG Air VM Normal Goods</option>
                                                                <option value="S3b">S3b - SG Air KG Normal Goods</option>
                                                                <option value="S4a">S4a - SG Air VM Sensitive Goods</option>
                                                                <option value="S4b">S4b - SG Air KG Sensitive Goods</option>
                                                            </select>
                                                    </div>

                                                    <!-- Shipping Costs - Simple 3-column layout -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">Sea Shipping (RM)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">RM</span>
                                                                    <input type="text" class="form-control" id="price_total_sea_shipping" readonly>
                                                                    <input type="hidden" name="price_total_sea_shipping" id="price_total_sea_shipping_hidden">
                                                            </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label class="form-label">Air Shipping VM (RM)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">RM</span>
                                                                <input type="text" class="form-control" id="price_total_air_shipping_vm" readonly>
                                                                <input type="hidden" name="price_total_air_shipping_vm" id="price_total_air_shipping_vm_hidden">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label class="form-label">Air Shipping KG (RM)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">RM</span>
                                                                    <input type="text" class="form-control" id="price_total_air_shipping_kg" readonly>
                                                                    <input type="hidden" name="price_total_air_shipping_kg" id="price_total_air_shipping_kg_hidden">
                                                            </div>
                                                    </div>
                                            </div>
                                             <div class="card-header d-flex justify-content-center align-items-center">
                                                <h2 class="card-title mb-0 text-center fw-bold" style="font-size: 2rem; width: 100%;">Calculated Price</h2>
                                            </div>     
                                            
                                            

                                            <!-- ✅ CALCULATED RESULTS WITH HIDDEN FIELDS -->
                                            <div class="col-md-6">
                                                <label class="form-label">Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="price_rm" readonly>
                                                            <input type="hidden" name="price_rm" id="price_rm_hidden">
                                                    </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Price (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="total_price_yen" readonly>
                                                            <input type="hidden" name="total_price_yen" id="total_price_yen_hidden">
                                                     </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">50% Deposit (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="deposit_50_yen" readonly>
                                                            <input type="hidden" name="deposit_50_yen" id="deposit_50_yen_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">50% Deposit (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="deposit_50_rm" readonly>
                                                            <input type="hidden" name="deposit_50_rm" id="deposit_50_rm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">CBM per Carton (m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">m³</span>
                                                            <input type="text" class="form-control" id="cbm_carton" readonly>
                                                            <input type="hidden" name="cbm_carton" id="cbm_carton_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total CBM (m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">m³</span>
                                                            <input type="text" class="form-control" id="total_cbm" readonly>
                                                            <input type="hidden" name="total_cbm" id="total_cbm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Vm per Carton (kg/m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg/m³</span>
                                                            <input type="text" class="form-control" id="vm_carton" readonly>
                                                            <input type="hidden" name="vm_carton" id="vm_carton_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Vm (kg/m³)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg/m³</span>
                                                            <input type="text" class="form-control" id="total_vm" readonly>
                                                            <input type="hidden" name="total_vm" id="total_vm_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Total Weight (kg)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">kg</span>
                                                            <input type="text" class="form-control" id="total_weight" readonly>
                                                            <input type="hidden" name="total_weight" id="total_weight_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">SG TAX (9%) (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="sg_tax" readonly>
                                                            <input type="hidden" name="sg_tax" id="sg_tax_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Supplier 1st (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="supplier_1st" readonly>
                                                            <input type="hidden" name="supplier_1st_yen" id="supplier_1st_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Supplier 2nd (Yen)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">¥</span>
                                                            <input type="text" class="form-control" id="supplier_2nd" readonly>
                                                            <input type="hidden" name="supplier_2nd_yen" id="supplier_2nd_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Customer 1st (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="customer_1st" readonly>
                                                            <input type="hidden" name="customer_1st_rm" id="customer_1st_hidden">
                                                    </div>       
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Customer 2nd (RM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">RM</span>
                                                            <input type="text" class="form-control" id="customer_2nd" readonly>
                                                            <input type="hidden" name="customer_2nd_rm" id="customer_2nd_hidden">
                                                    </div>       
                                            </div>
                            
                                            
                                            <div class="col-12 text-end mt-3">
                                                <button type="submit" class="btn btn-primary">Save Price</button>
                                            </div>                                                      



                                        </div><!-- row -->
                                    </form>
                                </div><!-- card-body -->
                            </div><!-- card -->
                        </div><!-- col -->

                    </div><!--end row-->
                </div> <!-- container-fluid -->

        <!-- ============================================================== -->
        <!-- End Page-content -->
        <!-- ============================================================== -->                
            </div><!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Velzon.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
            data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
            <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="p-4">
                    <h6 class="mb-0 fw-semibold text-uppercase">Layout</h6>
                    <p class="text-muted">Choose your layout</p>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout01" name="data-layout" type="radio" value="vertical"
                                    class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout01">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Vertical</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal"
                                    class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout02">
                                    <span class="d-flex h-100 flex-column gap-1">
                                        <span class="bg-light d-flex p-1 gap-1 align-items-center">
                                            <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                        </span>
                                        <span class="bg-light d-block p-1"></span>
                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn"
                                    class="form-check-input">
                                <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout03">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1">
                                                <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Two Column</h5>
                        </div>
                        <!-- end col -->
                    </div>

                    <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Color Scheme</h6>
                    <p class="text-muted">Choose Light or Dark Scheme.</p>

                    <div class="colorscheme-cardradio">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-light" value="light">
                                    <label class="form-check-label p-0 avatar-md w-100" for="layout-mode-light">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check card-radio dark">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-dark" value="dark">
                                    <label class="form-check-label p-0 avatar-md w-100 bg-dark" for="layout-mode-dark">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-soft-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-soft-light d-block p-1"></span>
                                                    <span class="bg-soft-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-width">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Width</h6>
                        <p class="text-muted">Choose Fluid or Boxed layout.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-fluid" value="fluid">
                                    <label class="form-check-label p-0 avatar-md w-100" for="layout-width-fluid">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Fluid</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-boxed" value="boxed">
                                    <label class="form-check-label p-0 avatar-md w-100 px-2" for="layout-width-boxed">
                                        <span class="d-flex gap-1 h-100 border-start border-end">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Boxed</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-position">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Position</h6>
                        <p class="text-muted">Choose Fixed or Scrollable Layout Position.</p>

                        <div class="btn-group radio" role="group">
                            <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed"
                                value="fixed">
                            <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                            <input type="radio" class="btn-check" name="data-layout-position"
                                id="layout-position-scrollable" value="scrollable">
                            <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                        </div>
                    </div>
                    <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Topbar Color</h6>
                    <p class="text-muted">Choose Light or Dark Topbar Color.</p>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light"
                                    value="light">
                                <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-light">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark"
                                    value="dark">
                                <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-dark">
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-primary d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
                    </div>

                    <div id="sidebar-size">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Size</h6>
                        <p class="text-muted">Choose a size of Sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-default" value="lg">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-default">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-compact" value="md">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-compact">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Compact</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small" value="sm">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1">
                                                    <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Small (Icon View)</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small-hover">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1">
                                                    <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-view">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar View</h6>
                        <p class="text-muted">Choose Default or Detached Sidebar view.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-default" value="default">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-default">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-detached" value="detached">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-detached">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-flex p-1 gap-1 align-items-center px-2">
                                                <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                            </span>
                                            <span class="d-flex gap-1 h-100 p-1 px-2">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Detached</h5>
                            </div>
                        </div>
                    </div>
                    <div id="sidebar-color">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Color</h6>
                        <p class="text-muted">Choose Ligth or Dark Sidebar Color.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-light" value="light">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-light">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark"
                                        value="dark">
                                    <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-dark">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
                </div>
                <div class="col-6">
                    <a href="https://1.envato.market/velzon-admin" target="_blank" class="btn btn-primary w-100">Buy Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/calculate.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>