<script>
  const currentUserRole = <?= json_encode($_SESSION['role'] ?? 'guest') ?>;
</script>

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="dashboard-projects.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/beranstrading/assets/images/beranslogo.png" alt="" height="50" style="margin-top: 10px;">
                    </span>
                    <span class="logo-lg">
                        <img src="/beranstrading/assets/images/beranslogo.png" alt="" height="70" style="margin-top: 10px;">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="dashboard-projects.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="/beranstrading/assets/images/beranslogo.png" alt="" height="50" style="margin-top: 10px;">
                    </span>
                    <span class="logo-lg">
                        <img src="/beranstrading/assets/images/beranslogo.png" alt="" height="70" style="margin-top: 10px;">
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

                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarTablesCompany" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTablesCompany">
                                        <i class="ri-briefcase-2-line"></i>
                                        <span data-key="t-tables">Company</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarTablesCompany">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="siteidentity.php" class="nav-link" data-key="t-basic-elements">Change Side Identity</a>
                                            </li>
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
                                            <li class="nav-item">
                                                <a href="forms-price-add-new.php" class="nav-link" data-key="t-basic-elements">Add Product Pricing</a>
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
