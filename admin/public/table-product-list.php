<?php

include __DIR__ . '/../private/table-product-list-backend.php';
include __DIR__ . '/../include/header.php';
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
                                <h4 class="mb-sm-0">Product List</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Product</a></li>
                                        <li class="breadcrumb-item active">Product List</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">All Products</h5>
                                    <a href="forms-product-add-new.php" class="btn btn-success">
                                        <i class="ri-add-line align-middle me-1"></i> Add New Product
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="productTable" class="table table-striped table-bordered nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Product Code</th>
                                                    <th>Section</th>
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
                                                    <th>Material</th>
                                                    <th>Product Type</th>
                                                    <th>Variant</th>
                                                    <th>Selling Price</th>
                                                    <th>Profit</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($products as $product): ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($product['product_code']) ?></strong></td>
                                                    <td><?= htmlspecialchars($product['section_name']) ?></td>
                                                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                                                    <td><?= htmlspecialchars($product['subcategory_name']) ?></td>
                                                    <td><?= htmlspecialchars($product['material_name']) ?></td>
                                                    <td><?= htmlspecialchars($product['product_type_name']) ?></td>
                                                    <td><?= htmlspecialchars($product['variant']) ?></td>
                                                    <td>
                                                        <?= $product['new_selling_price'] ? 'RM ' . number_format($product['new_selling_price'], 2) : 'N/A' ?>
                                                    </td>
                                                    <td>
                                                        <?= $product['new_unit_profit_rm'] ? 'RM ' . number_format($product['new_unit_profit_rm'], 2) : 'Price N/A' ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($product['created_at'])) ?></td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-sm btn-info" 
                                                                    onclick="viewProductDetails(<?= htmlspecialchars(json_encode($product)) ?>)" 
                                                                    title="View Details">
                                                                <i class="ri-eye-line"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-primary" 
                                                                    onclick="openUpdateModal(<?= htmlspecialchars(json_encode($product)) ?>)" 
                                                                    title="Edit Product">
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                            <a href="forms-price-add-new.php?product_id=<?= $product['product_id'] ?>" 
                                                               class="btn btn-sm btn-warning" title="Update Pricing">
                                                                <i class="ri-price-tag-3-line"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger" 
                                                                    onclick="deleteProduct(<?= $product['product_id'] ?>, '<?= htmlspecialchars($product['product_code']) ?>')" 
                                                                    title="Delete Product">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->           
            </div><!-- End Page-content -->
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- END layout-wrapper -->

    <!-- Update Product Modal - Smaller size -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProductModalLabel">Update Product & Carton Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="updateProductForm" enctype="multipart/form-data">
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Product Hierarchy Selection - READONLY -->
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Section</label>
                                    <input type="text" class="form-control" id="update_section_display" readonly style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" id="update_category_display" readonly style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Subcategory</label>
                                    <input type="text" class="form-control" id="update_subcategory_display" readonly style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Material</label>
                                    <input type="text" class="form-control" id="update_material_display"  readonly style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Product Type</label>
                                    <input type="text" class="form-control" id="update_product_type_display" readonly style="background-color: #f8f9fa;">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Supplier</label>
                                    <select class="form-select" name="supplier_id" id="update_supplier">
                                        <option value="">Select Supplier...</option>
                                        <?php foreach($suppliers as $supplier): ?>
                                            <option value="<?= $supplier['supplier_id'] ?>"><?= htmlspecialchars($supplier['supplier_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Variant, Description, Lead Time -->
                            <div class="row g-3 mt-3">
                                <div class="col-lg-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="variant" id="update_variant" placeholder="Enter Variant" required>
                                        <label>Variant</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="description" id="update_description" placeholder="Enter Description" required style="height: 60px;"></textarea>
                                        <label>Description</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" name="production_lead_time" id="update_production_lead_time" placeholder="Enter Lead Time (Days)" required>
                                        <label>Production Lead Time (Days)</label>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-12">
                                    <label>Product Image</label>
                                    <input type="file" class="form-control" name="product_image">
                                </div>
                            </div> -->

                            <!-- Size 1,2,3 with metrics -->
                            <?php for($i=1;$i<=3;$i++): ?>
                                <div class="row g-2 mt-2">
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-sm">
                                            <div class="form-floating flex-grow-1">
                                                <input type="text" class="form-control form-control-sm" name="size_<?= $i ?>" id="update_size_<?= $i ?>" placeholder="Size <?= $i ?>" required>
                                                <label>Size <?= $i ?></label>
                                            </div>
                                            <select class="form-select form-select-sm" name="metric_<?= $i ?>" id="update_metric_<?= $i ?>" style="max-width: 100px;">
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

                            <!-- Carton Info Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6>Carton Information</h6>
                                    <hr>
                                </div>
                            </div>

                            <!-- Main Carton -->
                            <div class="row g-3 mb-3">
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control" name="carton[width][]" id="update_carton_width" placeholder="Width">
                                        <label>Width (cm)</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control" name="carton[height][]" id="update_carton_height" placeholder="Height">
                                        <label>Height (cm)</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control" name="carton[length][]" id="update_carton_length" placeholder="Length">
                                        <label>Length (cm)</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" name="carton[pcs][]" id="update_pcs_per_carton" placeholder="Qty/Carton">
                                        <label>Qty/Carton</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control" name="carton[weight][]" id="update_weight_carton" placeholder="Weight">
                                        <label>Weight (kg)</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating">
                                        <input type="number" step="0.000001" class="form-control" name="carton[cbm][]" id="update_cbm_carton" placeholder="CBM" readonly>
                                        <label>CBM (m³)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Container for dynamic additional cartons -->
                            <div id="updateAdditionalCartonsContainer"></div>

                            <!-- Add Carton Button -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" id="updateAddCartonButton" class="btn btn-secondary btn-sm">Add Additional Carton</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                    <input type="hidden" name="product_id" id="update_product_id">
                    <input type="hidden" name="xero_relation" id="update_xero_relation">
                    <input type="hidden" name="product_code" id="update_product_code">
                    <input type="hidden" name="material_name" id="update_material_name">
                    <input type="hidden" name="product_type_name" id="update_product_type_name">
                    <input type="hidden" name="update_product" value="1">
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Product Details Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Basic Product Information -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Product Code</label>
                                            <div class="fw-bold" id="view_product_code"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Variant</label>
                                            <div id="view_variant"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-muted">Lead Time</label>
                                            <div id="view_lead_time"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Description</label>
                                            <div id="view_description" class="bg-light p-2 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Hierarchy -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Product Classification</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label text-muted">Section</label>
                                            <div id="view_section"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Category</label>
                                            <div id="view_category"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Subcategory</label>
                                            <div id="view_subcategory"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Material</label>
                                            <div id="view_material"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Product Type</label>
                                            <div id="view_product_type"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dimensions & Supplier -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Specifications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label text-muted">Dimensions</label>
                                            <div id="view_dimensions"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Supplier</label>
                                            <div id="view_supplier"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Selling Price</label>
                                            <div id="view_selling_price" class="fw-bold text-success"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Unit Price (RM)</label>
                                            <div id="view_unit_price_rm" class="fw-bold text-info"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">Profit</label>
                                            <div id="view_profit" class="fw-bold text-primary"></div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted">Created At</label>
                                            <div id="view_created_at"></div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted">Updated At</label>
                                            <div id="view_updated_at"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carton Information -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Carton Information</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Main Carton -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-12">
                                            <h6 class="text-primary">Main Carton</h6>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Width (cm)</label>
                                            <div id="view_carton_width" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Height (cm)</label>
                                            <div id="view_carton_height" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Length (cm)</label>
                                            <div id="view_carton_length" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Qty/Carton</label>
                                            <div id="view_pcs_per_carton" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">Weight (kg)</label>
                                            <div id="view_carton_weight" class="fw-semibold"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label text-muted small">CBM (m³)</label>
                                            <div id="view_cbm_carton" class="fw-semibold text-info"></div>
                                        </div>
                                    </div>

                                    <!-- Additional Cartons Container -->
                                    <div id="viewAdditionalCartonsContainer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="openUpdateModalFromView()">Edit Product</button>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../include/themesetting.php';?>

    <!-- JAVASCRIPT - Load in correct order -->
    <!-- jQuery is already loaded in head -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/libs/prismjs/prism.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS - After jQuery -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- App JS - Load after other libraries -->
    <script src="assets/js/app.js"></script>

    <script>
        let updateAdditionalCount = 0;
        const updateMaxAdditional = 6;
        let updateSupplierChoices = null;

        function initUpdateSupplierChoices() {
            if (updateSupplierChoices) {
                updateSupplierChoices.destroy();
                updateSupplierChoices = null;
            }
            updateSupplierChoices = new Choices('#update_supplier', {
                searchEnabled: true,
                shouldSort: false,
                allowHTML: false,
                placeholder: true,
                placeholderValue: 'Select Supplier...',
                searchPlaceholderValue: 'Search supplier...',
                itemSelectText: '',
                removeItemButton: false
            });
        }

        // Wait for DOM to be ready
        $(document).ready(function() {
            console.log('Document ready, initializing...'); // Debug log
            
            // Initialize DataTable
            $('#productTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[9, 'desc']], // Order by created date (now column 9)
                columnDefs: [
                    { orderable: false, targets: [10] } // Disable sorting on Actions column (now column 10)
                ]
            });

            console.log('DataTable initialized'); // Debug log

            // Show success/error messages from session (for regular form submissions)
            <?php if (isset($_SESSION['show_success']) && $_SESSION['show_success']): ?>
                console.log('Showing success message from session'); // Debug log
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: <?= json_encode($_SESSION['success']) ?>,
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
                <?php unset($_SESSION['success'], $_SESSION['show_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['show_error']) && $_SESSION['show_error']): ?>
                console.log('Showing error message from session'); // Debug log
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: <?= json_encode($_SESSION['error']) ?>,
                    confirmButtonColor: '#dc3545',
                    timer: 5000,
                    timerProgressBar: true
                });
                <?php unset($_SESSION['error'], $_SESSION['show_error']); ?>
            <?php endif; ?>

            // Test SweetAlert is working
            console.log('Testing if SweetAlert is available:', typeof Swal); // Debug log

            // Handle form submission with AJAX
            $('#updateProductForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted via AJAX'); // Debug log
                
                // Test SweetAlert before proceeding
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert is not loaded!');
                    alert('SweetAlert is not loaded. Please refresh the page.');
                    return;
                }
                
                // Show loading message
                Swal.fire({
                    title: 'Updating Product...',
                    text: 'Please wait while we update the product information.',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Prepare form data
                const formData = new FormData(this);
                formData.append('ajax_update', '1');

                console.log('Sending AJAX request...'); // Debug log

                // Submit form directly to backend to ensure pure JSON response
                $.ajax({
                    url: '../private/table-product-list-backend.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log('AJAX Success:', data); // Debug log
                        Swal.close();
                        
                        if (data.status === 'success') {
                            // Close modal first
                            $('#updateProductModal').modal('hide');
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#28a745',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: true
                            }).then(() => {
                                // Reload page to show updated data
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'An error occurred while updating the product.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr, status, error); // Debug log
                        console.error('Response text:', xhr.responseText); // Debug log
                        Swal.close();
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An unexpected error occurred. Please try again. (' + status + ')',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            initUpdateSupplierChoices();

            // Re-init when modal is shown, then reapply current selection so it stays preloaded
            $('#updateProductModal').on('shown.bs.modal', function () {
                initUpdateSupplierChoices();
                const currentSupplier = $('#update_supplier').val();
                if (updateSupplierChoices && currentSupplier) {
                    updateSupplierChoices.setChoiceByValue(String(currentSupplier));
                }
            });

            // Optional: clean up when hidden
            $('#updateProductModal').on('hidden.bs.modal', function () {
                if (updateSupplierChoices) {
                    updateSupplierChoices.removeActiveItems(); // reset selection
                }
            });
        });

        // CBM Calculation functions
        function calculateUpdateCBM(w, h, l) {
            return (w * h * l) / 1000000 * 1.28;
        }

        function calculateAllUpdateCBM() {
            // Main carton
            const width = parseFloat($('#update_carton_width').val()) || 0;
            const height = parseFloat($('#update_carton_height').val()) || 0;
            const length = parseFloat($('#update_carton_length').val()) || 0;
            
            $('#update_cbm_carton').val(calculateUpdateCBM(width, height, length).toFixed(6));

            // Additional cartons
            for (let i = 1; i <= updateAdditionalCount; i++) {
                const cbmField = $(`#update_add_carton${i}_cbm`);
                if (cbmField.length) {
                    const w = parseFloat($(`#update_add_carton${i}_width`).val()) || 0;
                    const h = parseFloat($(`#update_add_carton${i}_height`).val()) || 0;
                    const l = parseFloat($(`#update_add_carton${i}_length`).val()) || 0;
                    cbmField.val(calculateUpdateCBM(w, h, l).toFixed(6));
                }
            }
        }

        function openUpdateModal(product) {
            console.log('Opening modal for product:', product); // Debug log
            
            // Reset form
            $('#updateProductForm')[0].reset();
            
            // Populate basic product data
            $('#update_product_id').val(product.product_id);
            $('#update_xero_relation').val(product.xero_relation);
            $('#update_product_code').val(product.product_code);
            $('#update_material_name').val(product.material_name);
            $('#update_product_type_name').val(product.product_type_name);
            $('#update_variant').val(product.variant || '');
            $('#update_description').val(product.description || '');
            $('#update_production_lead_time').val(product.production_lead_time || '');

            // Populate readonly display fields
            $('#update_section_display').val(product.section_name || '');
            $('#update_category_display').val(product.category_name || '');
            $('#update_subcategory_display').val(product.subcategory_name || '');
            $('#update_material_display').val(product.material_name || '');
            $('#update_product_type_display').val(product.product_type_name || '');
            
            // Set supplier value on native select
            $('#update_supplier').val(product.supplier_id || '');
            // After Choices.js is initialized, set the value again
            setTimeout(function() {
                if (updateSupplierChoices && product.supplier_id) {
                    updateSupplierChoices.setChoiceByValue(String(product.supplier_id));
                }
            }, 100);

            // Handle sizes with metrics (parse combined values like "10 cm")
            for (let i = 1; i <= 3; i++) {
                const sizeValue = product[`size_${i}`] || '';
                if (sizeValue) {
                    const parts = sizeValue.split(' ');
                    $(`#update_size_${i}`).val(parts[0] || '');
                    if (parts[1]) {
                        $(`#update_metric_${i}`).val(parts[1]);
                    }
                }
            }

            // Populate carton data
            $('#update_carton_width').val(product.carton_width || '');
            $('#update_carton_height').val(product.carton_height || '');
            $('#update_carton_length').val(product.carton_length || '');
            $('#update_pcs_per_carton').val(product.pcs_per_carton || '');
            $('#update_weight_carton').val(product.carton_weight || '');
            $('#update_cbm_carton').val(product.cbm_carton || '');

            // Clear additional cartons container
            $('#updateAdditionalCartonsContainer').empty();
            updateAdditionalCount = 0;

            // Add existing additional cartons
            for (let i = 1; i <= 6; i++) {
                if (product[`add_carton${i}_width`] && product[`add_carton${i}_width`] > 0) {
                    addUpdateAdditionalCarton(i, product);
                }
            }

            // Add CBM calculation event listeners
            $('#update_carton_width, #update_carton_height, #update_carton_length').off('input').on('input', calculateAllUpdateCBM);

            // Show the modal
            $('#updateProductModal').modal('show');
        }

        function addUpdateAdditionalCarton(index = null, product = null) {
            if (updateAdditionalCount >= updateMaxAdditional) return;
            
            if (index === null) {
                updateAdditionalCount++;
                index = updateAdditionalCount;
            } else {
                updateAdditionalCount = Math.max(updateAdditionalCount, index);
            }

            const container = $('#updateAdditionalCartonsContainer');
            const row = $(`
                <div class="row g-3 mb-3" id="updateCartonRow${index}">
                    <div class="col-lg-2">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" name="carton[width][]" id="update_add_carton${index}_width" placeholder="Width" value="${product ? (product[`add_carton${index}_width`] || '') : ''}">
                            <label>Width (cm)</label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" name="carton[height][]" id="update_add_carton${index}_height" placeholder="Height" value="${product ? (product[`add_carton${index}_height`] || '') : ''}">
                            <label>Height (cm)</label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" name="carton[length][]" id="update_add_carton${index}_length" placeholder="Length" value="${product ? (product[`add_carton${index}_length`] || '') : ''}">
                            <label>Length (cm)</label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="carton[pcs][]" id="update_add_carton${index}_pcs" placeholder="Qty/Carton" value="${product ? (product[`add_carton${index}_pcs`] || '') : ''}">
                            <label>Qty/Carton</label>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" name="carton[weight][]" id="update_add_carton${index}_weight" placeholder="Weight" value="${product ? (product[`add_carton${index}_weight`] || '') : ''}">
                            <label>Weight (kg)</label>
                        </div>
                    </div>
                    <div class="col-lg-2 d-flex align-items-start gap-2">
                        <div class="form-floating flex-grow-1">
                            <input type="number" step="0.000001" class="form-control" name="carton[cbm][]" id="update_add_carton${index}_cbm" placeholder="CBM" readonly value="${product ? (product[`add_carton${index}_total_cbm`] || '') : ''}">
                            <label>CBM (m³)</label>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-update-carton" data-id="${index}">×</button>
                    </div>
                </div>
            `);

            container.append(row);

            // Add CBM calculation event listeners
            $(`#update_add_carton${index}_width, #update_add_carton${index}_height, #update_add_carton${index}_length`).on('input', calculateAllUpdateCBM);

            // Attach remove event
            row.find('.remove-update-carton').on('click', function() {
                const id = parseInt($(this).data('id'));
                $(`#updateCartonRow${id}`).remove();
                calculateAllUpdateCBM();
            });

            calculateAllUpdateCBM();
        }

        // Add carton button event listener
        $(document).on('click', '#updateAddCartonButton', function() {
            addUpdateAdditionalCarton();
        });

        function deleteProduct(productId, productCode) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete product "${productCode}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the product.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Create form and submit
                    const form = $('<form>', {
                        method: 'POST',
                        action: ''
                    });
                    
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'product_id',
                        value: productId
                    }));
                    
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'delete_product',
                        value: '1'
                    }));
                    
                    $('body').append(form);
                    form.submit();
                }
            });
        }

        // Store current product data for modal switching
        let currentProductData = null;

        function viewProductDetails(product) {
            console.log('Viewing product details:', product);
            currentProductData = product; // Store for potential edit action
            
            // Basic Information
            $('#view_product_code').text(product.product_code || 'N/A');
            $('#view_variant').text(product.variant || 'N/A');
            $('#view_lead_time').text(product.production_lead_time ? product.production_lead_time + ' days' : 'N/A');
            $('#view_description').text(product.description || 'N/A');
            
            // Product Classification
            $('#view_section').text(product.section_name || 'N/A');
            $('#view_category').text(product.category_name || 'N/A');
            $('#view_subcategory').text(product.subcategory_name || 'N/A');
            $('#view_material').text(product.material_name || 'N/A');
            $('#view_product_type').text(product.product_type_name || 'N/A');
            
            // Specifications
            $('#view_dimensions').text(product.dimensions || 'N/A');
            $('#view_supplier').text(product.supplier_name || 'N/A');
            $('#view_selling_price').text(product.new_selling_price ? 'RM ' + parseFloat(product.new_selling_price).toFixed(2) : 'N/A');
            $('#view_unit_price_rm').text(product.new_unit_price_rm ? 'RM ' + parseFloat(product.new_unit_price_rm).toFixed(2) : 'Price N/A');
            $('#view_profit').text(product.new_unit_profit_rm ? 'RM ' + parseFloat(product.new_unit_profit_rm).toFixed(2) : 'Price N/A');
            $('#view_created_at').text(product.created_at ? new Date(product.created_at).toLocaleString() : 'N/A');
            $('#view_updated_at').text(product.updated_at ? new Date(product.updated_at).toLocaleString() : 'N/A');
            
            // Main Carton Information
            $('#view_carton_width').text(product.carton_width || 'N/A');
            $('#view_carton_height').text(product.carton_height || 'N/A');
            $('#view_carton_length').text(product.carton_length || 'N/A');
            $('#view_pcs_per_carton').text(product.pcs_per_carton || 'N/A');
            $('#view_carton_weight').text(product.carton_weight || 'N/A');
            $('#view_cbm_carton').text(product.cbm_carton ? parseFloat(product.cbm_carton).toFixed(6) : 'N/A');
            
            // Clear and populate additional cartons
            $('#viewAdditionalCartonsContainer').empty();
            
            let hasAdditionalCartons = false;
            for (let i = 1; i <= 6; i++) {
                if (product[`add_carton${i}_width`] && product[`add_carton${i}_width`] > 0) {
                    hasAdditionalCartons = true;
                    const cartonHtml = `
                        <div class="row g-3 mb-3 border-top pt-3">
                            <div class="col-12">
                                <h6 class="text-secondary">Additional Carton ${i}</h6>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Width (cm)</label>
                                <div class="fw-semibold">${product[`add_carton${i}_width`] || 'N/A'}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Height (cm)</label>
                                <div class="fw-semibold">${product[`add_carton${i}_height`] || 'N/A'}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Length (cm)</label>
                                <div class="fw-semibold">${product[`add_carton${i}_length`] || 'N/A'}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Qty/Carton</label>
                                <div class="fw-semibold">${product[`add_carton${i}_pcs`] || 'N/A'}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Weight (kg)</label>
                                <div class="fw-semibold">${product[`add_carton${i}_weight`] || 'N/A'}</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">CBM (m³)</label>
                                <div class="fw-semibold text-info">${product[`add_carton${i}_total_cbm`] ? parseFloat(product[`add_carton${i}_total_cbm`]).toFixed(6) : 'N/A'}</div>
                            </div>
                        </div>
                    `;
                    $('#viewAdditionalCartonsContainer').append(cartonHtml);
                }
            }
            
            if (!hasAdditionalCartons) {
                $('#viewAdditionalCartonsContainer').append(`
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted text-center">No additional cartons configured</p>
                        </div>
                    </div>
                `);
            }
            
            // Show the modal
            $('#viewProductModal').modal('show');
        }

        function openUpdateModalFromView() {
            // Close view modal first
            $('#viewProductModal').modal('hide');
            
            // Wait for modal to close, then open update modal
            setTimeout(() => {
                if (currentProductData) {
                    openUpdateModal(currentProductData);
                }
            }, 300);
        }
    </script>

</body>
</html>