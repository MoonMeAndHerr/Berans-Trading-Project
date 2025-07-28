<?php include __DIR__ . '/../private/all-product.php';?>


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
        <?php 
        include __DIR__ . '/../include/header.php';
        include __DIR__ . '/../include/sidebar.php'; 
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">View Product</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
                                            <li class="breadcrumb-item active">All Product</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="card mt-1 shadow">
                <div class="card-header d-flex align-items-center mx-3 mt-2">
                    <h3 class="card-title mb-0 me-3" style="white-space: nowrap;">Product List</h3>
                    <input type="text" id="staffSearch" class="form-control w-25 ms-3" placeholder="Search product...">
                </div>

                <div class="card-body px-4 py-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0" id="staffTable">
                        <thead class="table-light">
                            <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Section (ID)</th>
                            <th>Category (ID)</th>
                            <th>Subcategory (ID)</th>
                            <th>Material</th>
                            <th>Shape</th>
                            <th>Size Volume</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                                $pdo = openDB();
                                $sql = "SELECT *
                                FROM product p
			                    JOIN section s ON p.section_id = s.section_id
			                    LEFT JOIN category c ON p.category_id = c.category_id
			                    LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
                                ORDER BY p.product_id ASC";
                                $stmt = $pdo->query($sql);

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            ?>

                            <tr>
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['section_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= htmlspecialchars($row['subcategory_name']) ?></td>
                            <td><?= htmlspecialchars($row['material']) ?></td>
                            <td><?= htmlspecialchars($row['shape']) ?></td>
                            <td><?= htmlspecialchars($row['size_volume']) ?></td>
                            <td><?= htmlspecialchars($row['current_stock']) ?></td>
                            <td><?= htmlspecialchars($row['reorder_level']) ?></td>
                            <td>
                                <?php
                                
                                if($row['is_active'] == 1) {
                                    echo "Active";
                                } else {
                                    echo "Inactive";
                                }

                                ?>
                            </td>
                            <td>
                                <a href="manage-product?product_id=<?= urlencode($staff['product_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="manage-product?product_id=<?= urlencode($staff['product_id']) ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                            </tr>
                            <?php 
                            
                                }
                            
                            ?>
                        </tbody>
                        </table>
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
    <script>
document.getElementById('staffSearch').addEventListener('input', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#staffTable tbody tr');

  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
</script>


</body>

</html>