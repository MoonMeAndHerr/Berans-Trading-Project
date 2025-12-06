<script>
  const currentUserRole = <?= json_encode($_SESSION['role'] ?? 'guest') ?>;
</script>

    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="dashboard-projects.php" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="../../media/<?php echo $logo_dark; ?>" alt="Berans Logo" height="50" style="margin: 10px 10px;">
                            </span>
                            <span class="logo-lg">
                                <img src="../../media/<?php echo $logo_dark; ?>" alt="Berans Logo" height="60" style="margin: 10px 10px;">
                            </span>
                        </a>

                        <a href="dashboard-projects.php" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="../../media/<?php echo $logo_light; ?>" alt="Berans Logo" height="50" style="margin: 10px 10px;">
                            </span>
                            <span class="logo-lg">
                                <img src="../../media/<?php echo $logo_light; ?>" alt="Berans Logo" height="60" style="margin: 10px 10px;">
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

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button"
                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>

                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <?php
                        $pdo=openDB();
                        // Debugging: Check if session ID exists
                        // echo "<pre>Session ID: "; print_r($_SESSION['staff_id'] ?? 'No session ID'); echo "</pre>";

                        $current_user = null;
                        if (isset($_SESSION['staff_id'])) {
                            try {
                                $stmt = $pdo->prepare("SELECT staff_name, staff_designation, staff_profile_picture FROM staff WHERE staff_id = :staff_id");
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
                        closeDB($pdo);
                        ?>

                        <!-- Your dropdown button -->
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="../../media/<?php echo htmlspecialchars($current_user['staff_profile_picture']); ?>"
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
                <a href="dashboard-projects.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="../../media/<?php echo $logo_dark; ?>" alt="" height="50" style="margin-top: 10px;">
                    </span>
                    <span class="logo-lg">
                        <img src="../../media/<?php echo $logo_dark; ?>" alt="" height="70" style="margin-top: 10px;">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="dashboard-projects.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="../../media/<?php echo $logo_light; ?>" alt="" height="50" style="margin-top: 10px;">
                    </span>
                    <span class="logo-lg">
                        <img src="../../media/<?php echo $logo_light; ?>" alt="" height="70" style="margin-top: 10px;">
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
                                <a class="nav-link menu-link" href="dashboard-projects.php">
                                    <i class="ri-dashboard-2-line"></i>
                                    <span data-key="t-tables">Dashboard</span>
                                 </a>
                            </li>

                        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Public Relation</span></li>

                                <!-- ===== Staff ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesStaff" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesStaff">
                                        <i class="bx bx-group"></i>
                                        <span data-key="t-tables">Staff</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesStaff">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                            <a href="staff-add.php" 
                                                class="nav-link" 
                                                data-allowed-roles='["admin"]' 
                                                data-key="t-basic-tables">
                                                Add Staff
                                            </a>
                                            </li>
                                            <!-- Add more staff links here if needed -->
                                        </ul>
                                    </div>
                                </li>

                                <!-- ===== Customer ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesCustomer" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesCustomer">
                                        <i class="bx bx-user-plus"></i>
                                        <span data-key="t-tables">Customer</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesCustomer">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                            <a href="customer-add.php" 
                                                class="nav-link" 
                                                data-allowed-roles='["admin","manager"]' 
                                                data-key="t-basic-tables">
                                                Add Customer
                                            </a>
                                            </li>
                                            <!-- Add more customer links here if needed -->
                                        </ul>
                                    </div>
                                </li>

                                 <!-- ===== Suppplier ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesSupplier" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesSupplier">
                                        <i class="bx bx-package"></i>
                                        <span data-key="t-tables">Supplier</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesSupplier">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                            <a href="forms-supplier.php" 
                                                class="nav-link" 
                                                data-allowed-roles='["admin","manager"]' 
                                                data-key="t-basic-tables">
                                                Add Supplier
                                            </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Product And Order</span></li>

                                <!-- ===== Pricing ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesPricing" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesPricing">
                                        <i class="ri-price-tag-3-line"></i>
                                        <span data-key="t-tables">Product</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesPricing">
                                        <ul class="nav nav-sm flex-column">
                                             <li class="nav-item">
                                                <a href="table-product-list.php" class="nav-link" data-key="t-basic-elements">View Products</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="forms-product-add-new.php" class="nav-link" data-key="t-basic-elements">Add Product</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesPricingTest" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesPricingTest">
                                        <i class="bx bx-receipt"></i>
                                        <span data-key="t-tables">Order</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesPricingTest">
                                        <ul class="nav nav-sm flex-column">
                                             <li class="nav-item">
                                                <a href="forms-new-order.php" class="nav-link" data-key="t-basic-elements">Add Order</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="view_order_tabs.php" class="nav-link" data-key="t-basic-elements">View Order</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="profit_loss.php" class="nav-link" data-key="t-basic-elements">Profit & Loss</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="usos-manage.php" class="nav-link" data-key="t-basic-elements">USOS System</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarDynamicUpdate" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarDynamicUpdate">
                                        <i class="bx bx-receipt"></i>
                                        <span data-key="t-tables">Dynamic Update</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarDynamicUpdate">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="forms-dynamic-update.php" class="nav-link" data-key="t-basic-elements">Dynamic Price Update</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>





                            <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Automation</span></li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="database-backup">
                                        <i class="ri-database-2-fill"></i>
                                        <span data-key="t-tables">Database Backup</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="website-backup">
                                        <i class="ri-window-2-fill"></i>
                                        <span data-key="t-tables">Website Backup</span>
                                    </a>
                                </li>

                            <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Misc</span></li>

                            
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="xero-main">
                                        <i class="ri-links-fill"></i>
                                        <span data-key="t-tables">Xero Auth</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="siteidentity">
                                        <i class="ri-database-fill"></i>
                                        <span data-key="t-tables">Site Config</span>
                                    </a>
                                </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all sidebar nav links with data-allowed-roles attribute
            const protectedLinks = document.querySelectorAll('a[data-allowed-roles]');

            protectedLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const allowedRoles = JSON.parse(this.getAttribute('data-allowed-roles'));
                if (!allowedRoles.includes(currentUserRole)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You do not have permission to access this page.',
                    confirmButtonText: 'OK'
                });
                }
            });
            });
        });
        </script>
