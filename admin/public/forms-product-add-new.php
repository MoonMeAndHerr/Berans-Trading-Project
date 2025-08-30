<?php

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';
?>

<?php include __DIR__ . '/../private/forms_product_add_new_backend.php';?>

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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




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
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">New Product & Carton Info</h4>
                                    </div>
                                    <div class="card-body">

                                        <?php if(!empty($successMsg)): ?>
                                            <div class="alert alert-success" id="successAlert"><?= $successMsg ?></div>
                                        <?php endif; ?>

                                        <?php if(!empty($errorMsg)): ?>
                                            <div class="alert alert-danger"><?= $errorMsg ?></div>
                                        <?php endif; ?>

                                        <div class="live-preview">
                                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

                                                <div class="row g-3">
                                                    <div class="row g-3 mt-2">

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="section" name="section" required>
                                                                <option disabled selected>Choose Section...</option>
                                                                <?php foreach($sections as $sec): ?>
                                                                    <option value="<?= $sec['section_id'] ?>"><?= htmlspecialchars($sec['section_name']) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="category" name="category" required>
                                                                <option disabled selected>Choose Category...</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="subcategory" name="subcategory" required>
                                                                <option disabled selected>Choose Subcategory...</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="material" name="material_id" required>
                                                                <option disabled selected>Choose Material...</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="product_type" name="product_type_id" required>
                                                                <option disabled selected>Choose Product Type...</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="supplier" name="supplier_id" required>
                                                                <option disabled selected>Choose Supplier...</option>
                                                                <?php foreach($suppliers as $sup): ?>
                                                                    <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['supplier_name']) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3 d-flex align-items-center">
                                                            <button type="button" class="btn btn-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#addSCSMPModal">
                                                                Add Product Hierarchy
                                                            </button>
                                                        </div>

                                                    </div>




                                                        <!-- Row 2 continued: Variant, Description, Lead Time -->
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-lg-4">
                                                                <div class="form-floating">
                                                                    <input type="text" class="form-control" name="variant" placeholder="Enter Variant" required>
                                                                    <label>Variant</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-floating">
                                                                    <textarea class="form-control" name="description" placeholder="Enter Description" required></textarea>
                                                                    <label>Description</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-floating">
                                                                    <input type="number" class="form-control" name="production_lead_time" placeholder="Enter Lead Time (Days)" required>
                                                                    <label>Production Lead Time (Days)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 3: Size 1,2,3 with metrics -->
                                                    <?php for($i=1;$i<=3;$i++): ?>
                                                        <div class="row g-2 <?= $i === 1 ? 'mt-4' : '' ?>">
                                                            <div class="col-lg-4">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="form-floating flex-grow-1">
                                                                        <input type="text" class="form-control form-control-sm" name="size_<?= $i ?>" placeholder="Size <?= $i ?>" required>
                                                                        <label>Size <?= $i ?></label>
                                                                    </div>
                                                                    <select class="form-select form-select-sm" name="metric_<?= $i ?>" style="max-width: 100px;">
                                                                        <option value="cm" selected>cm</option>
                                                                        <option value="mm">mm</option>
                                                                        <option value="inch">inch</option>
                                                                        <option value="kg">kg</option>
                                                                        <option value="g">g</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endfor; ?>

                                                <div class="row mt-5">
                                                    <div class="col-12 text-center">
                                                        <hr style="width: 95%; height:1px; border:none; background-color:#000; margin:0.5rem auto;">
                                                    </div>
                                                </div>



                                                <!-- Carton Info Section -->
                                                <div class="row mt-3">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-header align-items-center d-flex">
                                                                <h4 class="card-title mb-0 flex-grow-1">Carton Info</h4>
                                                            </div>
                                                            <div class="card-body">

                                                                <!-- Main Carton -->
                                                                <div class="row g-3 mb-3">
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" step="0.01" class="form-control" name="carton[width][]" id="carton_width" placeholder="Width" required>
                                                                            <label>Carton Width (cm)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" step="0.01" class="form-control" name="carton[height][]" id="carton_height" placeholder="Height" required>
                                                                            <label>Carton Height (cm)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" step="0.01" class="form-control" name="carton[length][]" id="carton_length" placeholder="Length" required>
                                                                            <label>Carton Length (cm)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" class="form-control" name="carton[pcs][]" id="pcs_per_carton" placeholder="Qty/Carton" required>
                                                                            <label>Quantity / Carton</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" step="0.01" class="form-control" name="carton[weight][]" id="weight_carton" placeholder="Carton Weight" required>
                                                                            <label>Carton Weight (kg)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <div class="form-floating">
                                                                            <input type="number" step="0.000001" class="form-control" name="carton[cbm][]" id="cbm_carton" placeholder="CBM/Carton" readonly>
                                                                            <label>CBM / Carton (m³)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Container for dynamic additional cartons -->
                                                                <div id="additionalCartonsContainer"></div>

                                                                <!-- Add Carton Button -->
                                                                <div class="mt-3">
                                                                    <button type="button" id="addCartonButton" class="btn btn-secondary">Add Additional Carton</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
                            <?php include __DIR__ . '/../public/modal-add-SCSMP.php'; ?>



           








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
document.addEventListener('DOMContentLoaded', () => {
    const maxAdditional = 6;
    let additionalCount = 0;
    const container = document.getElementById('additionalCartonsContainer');
    const addBtn = document.getElementById('addCartonButton');

    const getFloat = id => parseFloat(document.getElementById(id)?.value) || 0;
    const calculateCBM = (w, h, l) => (w * h * l) / 1000000 * 1.28;

    function calculateAll() {
        // Main carton
        document.getElementById('cbm_carton').value = calculateCBM(
            getFloat('carton_width'),
            getFloat('carton_height'),
            getFloat('carton_length')
        ).toFixed(6);

        // Additional cartons
        for (let i = 1; i <= additionalCount; i++) {
            const cbmField = document.getElementById(`add_carton${i}_cbm`);
            if (cbmField) {
                cbmField.value = calculateCBM(
                    getFloat(`add_carton${i}_width`),
                    getFloat(`add_carton${i}_height`),
                    getFloat(`add_carton${i}_length`)
                ).toFixed(6);
            }
        }
    }

    // Trigger calculation when main carton values change
    ['carton_width', 'carton_height', 'carton_length'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculateAll);
    });

    // Add additional carton rows dynamically
    addBtn.addEventListener('click', () => {
        if (additionalCount >= maxAdditional) return;
        additionalCount++;
        const i = additionalCount;

        const row = document.createElement('div');
        row.classList.add('row', 'g-3', 'mb-3');
        row.setAttribute('id', `cartonRow${i}`);
        row.innerHTML = `
            <div class="col-lg-2">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" name="carton[width][]" id="add_carton${i}_width" placeholder="Width" required>
                    <label>Carton Width (cm)</label>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" name="carton[height][]" id="add_carton${i}_height" placeholder="Height" required>
                    <label>Carton Height (cm)</label>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" name="carton[length][]" id="add_carton${i}_length" placeholder="Length" required>
                    <label>Carton Length (cm)</label>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-floating">
                    <input type="number" class="form-control" name="carton[pcs][]" id="add_carton${i}_pcs" placeholder="Qty/Carton" required>
                    <label>Quantity / Carton</label>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" name="carton[weight][]" id="add_carton${i}_weight" placeholder="Carton Weight" required>
                    <label>Carton Weight (kg)</label>
                </div>
            </div>
            <div class="col-lg-2 d-flex align-items-start gap-2">
                <div class="form-floating flex-grow-1">
                    <input type="number" step="0.000001" class="form-control" name="carton[cbm][]" id="add_carton${i}_cbm" placeholder="CBM/Carton" readonly required>
                    <label>CBM / Carton (m³)</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-carton" data-id="${i}">X</button>
            </div>
        `;

        container.appendChild(row);

        // Recalculate CBM whenever dimensions change
        ['width', 'height', 'length'].forEach(f => {
            document.getElementById(`add_carton${i}_${f}`).addEventListener('input', calculateAll);
        });

        // Attach remove event
        row.querySelector('.remove-carton').addEventListener('click', e => {
            const id = e.target.dataset.id;
            document.getElementById(`cartonRow${id}`).remove();
            calculateAll();
        });
    });

    // Initial calculation
    calculateAll();
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Choices.js on all selects
    const sectionChoices = new Choices('#section', { searchEnabled: true, searchPlaceholderValue: 'Search for section...', placeholder: true, placeholderValue: 'Choose Section...' });
    const categoryChoices = new Choices('#category', { searchEnabled: true, searchPlaceholderValue: 'Search for category...', placeholder: true, placeholderValue: 'Choose Category...' });
    const subcategoryChoices = new Choices('#subcategory', { searchEnabled: true, searchPlaceholderValue: 'Search for subcategory...', placeholder: true, placeholderValue: 'Choose Subcategory...' });
    const materialChoices = new Choices('#material', { searchEnabled: true, searchPlaceholderValue: 'Search for material...', placeholder: true, placeholderValue: 'Choose Material...' });
    const productTypeChoices = new Choices('#product_type', { searchEnabled: true, searchPlaceholderValue: 'Search for product type...', placeholder: true, placeholderValue: 'Choose Product Type...' });
    const supplierChoices = new Choices('#supplier', { searchEnabled: true, searchPlaceholderValue: 'Search for supplier...', placeholder: true, placeholderValue: 'Choose Supplier...' });

    function fetchOptions(type, parent_id, targetChoices) {
        fetch(`forms-product-add-new.php?ajax=1&type=${type}&parent_id=${parent_id}`)
            .then(res => res.json())
            .then(data => {
                // Clear current options
                targetChoices.clearStore();
                targetChoices.setChoices([{ value: '', label: `Choose ${type.replace('_',' ')}...`, disabled: true, selected: true }], 'value', 'label', true);
                
                // Add new options
                data.forEach(item => {
                    let value, label;
                    if(type === 'category') { value = item.category_id; label = item.category_name; }
                    if(type === 'subcategory') { value = item.subcategory_id; label = item.subcategory_name; }
                    if(type === 'material') { value = item.material_id; label = item.material_name; }
                    if(type === 'product_type') { value = item.product_type_id; label = item.product_name; }

                    targetChoices.setChoices([{ value, label }], 'value', 'label', false);
                });
            })
            .catch(err => console.error("Fetch error:", err));
    }

    sectionChoices.passedElement.element.addEventListener('change', () => {
        fetchOptions('category', sectionChoices.getValue(true), categoryChoices);
        subcategoryChoices.clearStore();
        subcategoryChoices.setChoices([{ value: '', label: 'Choose Subcategory...', disabled: true, selected: true }]);
        materialChoices.clearStore();
        materialChoices.setChoices([{ value: '', label: 'Choose Material...', disabled: true, selected: true }]);
        productTypeChoices.clearStore();
        productTypeChoices.setChoices([{ value: '', label: 'Choose Product Type...', disabled: true, selected: true }]);
    });

    categoryChoices.passedElement.element.addEventListener('change', () => {
        fetchOptions('subcategory', categoryChoices.getValue(true), subcategoryChoices);
        materialChoices.clearStore();
        materialChoices.setChoices([{ value: '', label: 'Choose Material...', disabled: true, selected: true }]);
        productTypeChoices.clearStore();
        productTypeChoices.setChoices([{ value: '', label: 'Choose Product Type...', disabled: true, selected: true }]);
    });

    subcategoryChoices.passedElement.element.addEventListener('change', () => {
        fetchOptions('material', subcategoryChoices.getValue(true), materialChoices);
        productTypeChoices.clearStore();
        productTypeChoices.setChoices([{ value: '', label: 'Choose Product Type...', disabled: true, selected: true }]);
    });

    materialChoices.passedElement.element.addEventListener('change', () => {
        fetchOptions('product_type', materialChoices.getValue(true), productTypeChoices);
    });
});
</script>















</body>

</html>