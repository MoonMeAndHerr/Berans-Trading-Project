


<?php 

    include __DIR__ . '/../include/header.php'; 
    include __DIR__ . '/../private/catalogue-arranger-backend.php';

    

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
                                    <h4 class="mb-sm-0">Catalogue Arranger</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Catalogue</a></li>
                                            <li class="breadcrumb-item active">Arranger</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <?php

                if($_GET['action'] == "create"){

            ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">New Catalogue</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="live-preview">
                                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="GET" enctype="multipart/form-data">

                                                    <div class="col-xxl-12 col-md-12">
                                                        <div>
                                                            <label for="basicInput" class="form-label">Folder Name</label>
                                                            <input type="text" placeholder="Folder Name" name="foldername" class="form-control" id="basiInput">
                                                        </div>
                                                    </div><br>

                                                    <div class="row g-3">
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-xl-12">
                                                                <label for="basiInput" class="form-label">Choose A Filter</label>
                                                                <select class="form-select" data-choices data-choices-groups id="section" name="filter" required>
                                                                        <optgroup label="Sections">
                                                                            <?php foreach($sections as $sec): ?>
                                                                                <option value="sec-<?php echo $sec['section_id']; ?>"><?php echo $sec['section_name']; ?> (Section)</option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                        <optgroup label="Categories">
                                                                            <?php foreach($categories as $cat): ?>
                                                                                <option value="cat-<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?> (Category)</option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                        <optgroup label="Subcategories">
                                                                            <?php foreach($subcategories as $subcat): ?>
                                                                                <option value="subcat-<?php echo $subcat['subcategory_id']; ?>"><?php echo $subcat['subcategory_name']; ?> (Subcategory)</option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div><br>

                                                    <input type="hidden" name="action" value="nextstep">

                                                    <!-- Submit Button -->
                                                    <div class="col-lg-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Next Step</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <?php

                    } elseif($_GET['action'] == "nextstep"){

                        // 1. Get the filter parameter
                        $filterParam = isset($_GET['filter']) ? $_GET['filter'] : '';

                        // 2. Initialize variables
                        $columnName = '';
                        $filterId = 0;

                        // 3. Parse the filter string (e.g., "sec-1")
                        if (strpos($filterParam, '-') !== false) {
                            list($type, $id) = explode('-', $filterParam, 2);
                            $filterId = (int)$id;

                            // Determine which database column to use based on the prefix
                            switch ($type) {
                                case 'sec':
                                    $columnName = 'p.section_id'; // Added 'p.' for table alias
                                    break;
                                case 'cat':
                                    $columnName = 'p.category_id';
                                    break;
                                case 'subcat':
                                    $columnName = 'p.subcategory_id';
                                    break;
                                default:
                                    die("Invalid filter type.");
                            }
                        } else {
                            die("Invalid filter format.");
                        }

                        // 4. Execute the query
                        try {
                            // We construct the name using CONCAT
                            // We use LEFT JOIN in case material or product_type is missing
                            $sql = "
                            SELECT 
                                p.*, 
                                CONCAT(
                                    IFNULL(p.product_code, ''), ' | ', 
                                    IFNULL(m.material_name, ''), ' ', 
                                    IFNULL(pt.product_name, ''), ' ', 
                                    IFNULL(p.size_1, ''), '*', 
                                    IFNULL(p.size_2, ''), '*', 
                                    IFNULL(p.size_3, ''), ' ', 
                                    IFNULL(p.variant, '')
                                ) AS full_product_name
                            FROM product p
                            LEFT JOIN material m ON p.material_id = m.material_id
                            LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
                            WHERE $columnName = ? 
                            AND p.is_active = 1
                            ORDER BY p.product_id DESC
                            ";
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$filterId]);
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        } catch (PDOException $e) {
                            die("Database error: " . $e->getMessage());
                        }

                ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">New Catalogue (cont.)</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="live-preview">
                                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">

                                                    <div class="row g-3">
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-xl-12">
                                                                <label for="basiInput" class="form-label">Choose Products...</label>
                                                                <select class="form-select" data-choices data-choices-removeItem multiple id="section" name="product[]" required>
                                                                    <option value="">Choose Products...</option>
                                                                        <?php foreach ($products as $product): ?>
                                                                            <option value="<?php echo $product['product_id']; ?>"><?php echo htmlspecialchars($product['full_product_name']); ?></option>
                                                                        <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div><br>

                                                    <input type="hidden" name="foldername" value="<?php echo $_GET['foldername']; ?>">
                                                    <input type="hidden" name="filter" value="<?php echo $_GET['filter']; ?>">
                                                    <input type="hidden" name="action" value="submit">

                                                    <!-- Submit Button -->
                                                    <div class="col-lg-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>



                <?php

                    } elseif($_GET['action'] == "edit"){

                        $folderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

                        $stmt = $pdo->prepare("
                            SELECT 
                                fc.*,
                                s.section_name,
                                c.category_name,
                                sc.subcategory_name
                            FROM folder_catalogue fc
                            JOIN section s ON fc.section_id = s.section_id
                            LEFT JOIN category c ON fc.category_id = c.category_id
                            LEFT JOIN subcategory sc ON fc.subcategory_id = sc.subcategory_id
                            WHERE fc.folder_id = ?
                        ");

                        $stmt->execute([$folderId]);
                        $folder = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$folder) {
                            die("Folder not found!");
                        }

                        $currentValue = '';

                        if (!empty($folder['subcategory_id'])) {
                            // If it has a subcategory, that is the most specific filter
                            $currentValue = 'subcat-' . $folder['subcategory_id'];
                        } elseif (!empty($folder['category_id'])) {
                            // If no subcategory, check for category
                            $currentValue = 'cat-' . $folder['category_id'];
                        } else {
                            // Default to section (since section_id is mandatory)
                            $currentValue = 'sec-' . $folder['section_id'];
                        }

                ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Edit Catalogue</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="live-preview">
                                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="GET" enctype="multipart/form-data">

                                                    <div class="col-xxl-12 col-md-12">
                                                        <div>
                                                            <label for="basicInput" class="form-label">Folder Name</label>
                                                            <input type="text" placeholder="Folder Name" value="<?php echo $folder['folder_name']; ?>" name="foldername" class="form-control" id="basiInput">
                                                        </div>
                                                    </div><br>

                                                    <div class="row g-3">
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-xl-12">
                                                                <label for="basiInput" class="form-label">Choose A Filter</label>
                                                                <select class="form-select" data-choices data-choices-groups id="section" name="filter" required>
                                                                        <optgroup label="Sections">
                                                                            <?php foreach($sections as $sec): ?>
                                                                                <?php $val = 'sec-' . $sec['section_id']; ?>
                                                                                <option value="<?= $val ?>" <?= ($currentValue == $val) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($sec['section_name']) ?> (Section)
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                        <optgroup label="Categories">
                                                                            <?php foreach($categories as $cat): ?>
                                                                                <?php $val = 'cat-' . $cat['category_id']; ?>
                                                                                <option value="<?= $val ?>" <?= ($currentValue == $val) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($cat['category_name']) ?> (Category)
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                        <optgroup label="Subcategories">
                                                                            <?php foreach($subcategories as $subcat): ?>
                                                                                <?php $val = 'subcat-' . $subcat['subcategory_id']; ?>
                                                                                <option value="<?= $val ?>" <?= ($currentValue == $val) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($subcat['subcategory_name']) ?> (Subcategory)
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div><br>

                                                    <input type="hidden" name="folder_id" value="<?php echo $_GET['id']; ?>">
                                                    <input type="hidden" name="action" value="nextedit">

                                                    <!-- Submit Button -->
                                                    <div class="col-lg-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Next Step</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                <?php

                    } elseif($_GET['action'] == "nextedit"){

                        // 1. Get the folder ID to edit
                        $folderId = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : 0;

                        // 2. Fetch the existing folder data
                        $stmt = $pdo->prepare("SELECT * FROM folder_catalogue WHERE folder_id = ?");
                        $stmt->execute([$folderId]);
                        $folder = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$folder) {
                            die("Folder not found.");
                        }

                        // 3. Prepare the 'Selected Products' array
                        // The database stores "1,2,5", so we explode it into [1, 2, 5]
                        $selectedProductIds = !empty($folder['listItems']) ? explode(',', $folder['listItems']) : [];

                        // 4. Determine the 'Filter' based on saved data
                        // We need to know which list of products to show (Section, Category, or Subcategory level)
                        $filterId = 0;
                        $columnName = '';

                        if (!empty($folder['subcategory_id'])) {
                            $columnName = 'p.subcategory_id';
                            $filterId = $folder['subcategory_id'];
                        } elseif (!empty($folder['category_id'])) {
                            $columnName = 'p.category_id';
                            $filterId = $folder['category_id'];
                        } else {
                            $columnName = 'p.section_id';
                            $filterId = $folder['section_id'];
                        }

                        // 5. Fetch ALL products for this section/category (Same query as your Create page)
                        // This populates the dropdown options
                        try {
                            $sql = "
                            SELECT 
                                p.*, 
                                CONCAT(
                                    IFNULL(p.product_code, ''), ' | ', 
                                    IFNULL(m.material_name, ''), ' ', 
                                    IFNULL(pt.product_name, ''), ' ', 
                                    IFNULL(p.size_1, ''), '*', 
                                    IFNULL(p.size_2, ''), '*', 
                                    IFNULL(p.size_3, ''), ' ', 
                                    IFNULL(p.variant, '')
                                ) AS full_product_name
                            FROM product p
                            LEFT JOIN material m ON p.material_id = m.material_id
                            LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
                            WHERE $columnName = ? 
                            AND p.is_active = 1
                            ORDER BY p.product_id DESC
                            ";
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$filterId]);
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        } catch (PDOException $e) {
                            die("Database error: " . $e->getMessage());
                        }

                ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Edit Catalogue (cont.)</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="live-preview">
                                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">

                                                    <div class="row g-3">
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-xl-12">
                                                                <label for="basiInput" class="form-label">Choose A Filter</label>
                                                                    <select class="form-select" data-choices data-choices-removeItem multiple id="section" name="product[]" required>
                                                                        <option value="">Choose Products...</option>
                                                                                        
                                                                            <?php foreach ($products as $product): ?>
                                                                                <?php 
                                                                                    // CHECK: Is this product ID in the saved array?
                                                                                    $isSelected = in_array($product['product_id'], $selectedProductIds) ? 'selected' : ''; 
                                                                                    ?>
                                                                                    <option value="<?= $product['product_id']; ?>" <?= $isSelected ?>>
                                                                                        <?= htmlspecialchars($product['full_product_name']); ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>    
                                                                            </select>
                                                            </div>
                                                        </div>
                                                    </div><br>

                                                    <input type="hidden" name="folder_id" value="<?php echo $_GET['folder_id']; ?>">
                                                    <input type="hidden" name="filter" value="<?php echo $_GET['filter']; ?>">
                                                    <input type="hidden" name="action" value="update">

                                                    <!-- Submit Button -->
                                                    <div class="col-lg-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Update Catalogue</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                

                <?php

                    }

                ?>

                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    

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
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Choices.js on all selects
    const sectionChoices = new Choices('#section', { searchEnabled: true, searchPlaceholderValue: 'Search for filter...', placeholder: true, placeholderValue: 'Choose filter...' });
});
</script>















</html>