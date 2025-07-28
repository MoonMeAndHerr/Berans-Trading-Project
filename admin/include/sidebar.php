<script>
  const currentUserRole = <?= json_encode($_SESSION['role'] ?? 'guest') ?>;
</script>

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index" class="logo logo-dark">
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
                                        <i class="bx bx-comment-add"></i>
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

                                <!-- ===== Product ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarProduct">
                                        <i class="ri-shopping-cart-fill"></i>
                                        <span data-key="t-tables">Product</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarProduct">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                            <a href="all-product" 
                                                class="nav-link" 
                                                data-allowed-roles='["admin","manager"]' 
                                                data-key="t-basic-tables">
                                                View All Products
                                            </a>
                                            </li>
                                            <li class="nav-item">
                                            <a href="manage-product" 
                                                class="nav-link" 
                                                data-allowed-roles='["admin","manager"]' 
                                                data-key="t-basic-tables">
                                                Manage Product
                                            </a>
                                            </li>
                                            <!-- Add more customer links here if needed -->
                                        </ul>
                                    </div>
                                </li>


                                <!-- ===== Form ===== -->
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="#sidebarFormsMenu" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarFormsMenu">
                                        <i class="ri-file-list-3-line"></i>
                                        <span data-key="t-forms">Form</span>
                                    </a>
                                    <div class="collapse menu-dropdown" id="sidebarFormsMenu">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="../public/forms-supplier.php" class="nav-link" data-key="t-basic-elements">Supplier</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="../public/forms-elements-add.php" class="nav-link" data-key="t-basic-elements">Add Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="../public/forms-elements-update.php" class="nav-link" data-key="t-basic-elements">Update Pricing</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>

                        <li class="menu-title"><span data-key="t-menu">Security and Automation</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="website-backup">
                                    <i class="ri-pages-fill"></i> <span data-key="t-widgets">Website Backup</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="database-backup">
                                    <i class="ri-database-2-line"></i> <span data-key="t-widgets">Database Backup</span>
                                </a>
                            </li>
                        

                        <li class="menu-title"><span data-key="t-menu">Miscellaneous</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="siteidentity">
                                    <i class="ri-file-user-line"></i> <span data-key="t-widgets">Site Identity</span>
                                </a>
                            </li>






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
