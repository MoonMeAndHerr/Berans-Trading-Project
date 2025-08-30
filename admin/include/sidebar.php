<script>
  const currentUserRole = <?= json_encode($_SESSION['role'] ?? 'guest') ?>;
</script>

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
                <a href="index" class="logo logo-light">
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
                        
                            <ul class="navbar-nav" id="navbar-nav">
                                <!-- ===== Staff ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesStaff" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesStaff">
                                        <i class="bx bx-user"></i>
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
                                        <i class="ri-customer-service-2-fill"></i>
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
                                        <i class="ri-store-3-fill"></i>
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

                                <!-- ===== Pricing ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesPricing" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesPricing">
                                        <i class="ri-file-list-3-line"></i>
                                        <span data-key="t-tables">Pricing</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesPricing">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="forms-price-add.php" class="nav-link" data-key="t-basic-elements">Add Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="forms-price-update.php" class="nav-link" data-key="t-basic-elements">Update Pricing</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesPricingTest" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesPricingTest">
                                        <i class='ri-dropbox-fill'></i> 
                                        <span data-key="t-tables">Product Test</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesPricingTest">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="forms-product-add-new.php" class="nav-link" data-key="t-basic-elements">New Product</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="forms-price-add-new.php" class="nav-link" data-key="t-basic-elements">Add Pricing</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>

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
                                    <a class="nav-link menu-link" href="xero-auth">
                                        <i class="ri-links-fill"></i>
                                        <span data-key="t-tables">Xero Auth</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="siteidentity.php">
                                        <i class="ri-folder-info-fill"></i>
                                        <span data-key="t-tables">Company Info</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="database-sync">
                                        <i class="ri-database-fill"></i>
                                        <span data-key="t-tables">Database Sync</span>
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
