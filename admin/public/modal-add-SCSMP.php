<div class="modal fade" id="addSCSMPModal" tabindex="-1" aria-labelledby="addSCSMPLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header py-3 px-4 border-bottom">
                <h5 class="mb-1">Product Info Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Success / Error Alerts -->
            <?php if(!empty($_SESSION['modal_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['modal_success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['modal_success']); ?>
            <?php endif; ?>

            <?php if(!empty($_SESSION['modal_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['modal_error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['modal_error']); ?>
            <?php endif; ?>

            <?php $values = $_SESSION['modal_values'] ?? []; ?>
            
            <!-- Nav Tabs -->
            <div class="card">
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-pills animation-nav nav-justified gap-2 mb-3" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link active" data-bs-toggle="tab" href="#addTabContent" role="tab">
                    Add
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#updateTabContent" role="tab">
                    Update
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#deleteTabContent" role="tab">
                    Delete
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content text-muted">
            <!-- Add Tab Content -->
            <div class="tab-pane active" id="addTabContent" role="tabpanel">
                <form action="../private/modal-add-SCSMP-backend.php" method="POST">

                <!-- Section -->
                <h6 class="fw-bold mb-2">Add Section</h6>
                <div class="mb-3">
                    <input type="text" class="form-control" name="new_section" placeholder="New Section" value="">
                    <small class="text-muted">Enter a new section name if needed.</small>
                </div>

                <!-- Category -->
                <h6 class="fw-bold mb-2">Add Category</h6>
                <div class="mb-3">
                    <select class="form-select" name="section_id" id="modal_section">
                        <option disabled <?= empty($values['section_id']) ? 'selected' : '' ?>>Choose Section for Category...</option>
                        <?php foreach($sections as $sec): ?>
                            <option value="<?= $sec['section_id'] ?>" <?= (isset($values['section_id']) && $values['section_id']==$sec['section_id'])?'selected':'' ?>>
                                <?= htmlspecialchars($sec['section_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control mt-2" name="new_category" placeholder="New Category" value="">
                </div>

                <!-- Subcategory -->
                <h6 class="fw-bold mb-2">Add Subcategory</h6>
                <div class="mb-3">
                    <select class="form-select" name="category_id" id="modal_category">
                        <option disabled selected>Choose Category for Subcategory...</option>
                    </select>
                    <input type="text" class="form-control mt-2" name="new_subcategory" placeholder="New Subcategory" value="">
                </div>

                <!-- Material -->
                <h6 class="fw-bold mb-2">Add Material</h6>
                <div class="mb-3">
                    <select class="form-select" name="subcategory_id" id="modal_subcategory">
                        <option disabled selected>Choose Subcategory for Material...</option>
                    </select>
                    <input type="text" class="form-control mt-2" name="new_material" placeholder="New Material" value="">
                </div>

                <!-- Product Type -->
                <h6 class="fw-bold mb-2">Add Product Type</h6>
                <div class="mb-3">
                    <select class="form-select" name="material_id" id="modal_material">
                        <option disabled selected>Choose Material for Product Type...</option>
                    </select>
                    <input type="text" class="form-control mt-2" name="new_product_type" placeholder="New Product Type" value="">
                </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-add-line me-1"></i> Add
                        </button>
                    </form>
                </div>

                <!-- UPDATE TAB -->
                <div class="tab-pane fade" id="updateTabContent" role="tabpanel">
                    <form action="../private/modal-add-SCSMP-backend.php" method="POST">
                        <!-- Section Update -->
                        <h6 class="fw-bold mb-2">Update Section</h6>
                        <select class="form-select mb-2" name="update_section_id" id="update_section">
                            <option disabled <?= empty($values['update_section_id']) ? 'selected' : '' ?>>Choose Section...</option>
                            <?php foreach($sections as $sec): ?>
                                <option value="<?= $sec['section_id'] ?>" <?= (isset($values['update_section_id']) && $values['update_section_id']==$sec['section_id'])?'selected':'' ?>>
                                    <?= htmlspecialchars($sec['section_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control mb-3" name="update_section_name" placeholder="New Section Name"
                               value="<?= htmlspecialchars($values['update_section_name'] ?? '') ?>">

                        <!-- Category Update -->
                        <h6 class="fw-bold mb-2">Update Category</h6>
                        <select class="form-select mb-2" name="update_category_id" id="update_category">
                            <option disabled <?= empty($values['update_category_id']) ? 'selected' : '' ?>>Choose Category...</option>
                        </select>
                        <input type="text" class="form-control mb-3" name="update_category_name" placeholder="New Category Name"
                               value="<?= htmlspecialchars($values['update_category_name'] ?? '') ?>">

                        <!-- Subcategory Update -->
                        <h6 class="fw-bold mb-2">Update Subcategory</h6>
                        <select class="form-select mb-2" name="update_subcategory_id" id="update_subcategory">
                            <option disabled <?= empty($values['update_subcategory_id']) ? 'selected' : '' ?>>Choose Subcategory...</option>
                        </select>
                        <input type="text" class="form-control mb-3" name="update_subcategory_name" placeholder="New Subcategory Name"
                               value="<?= htmlspecialchars($values['update_subcategory_name'] ?? '') ?>">

                        <!-- Material Update -->
                        <h6 class="fw-bold mb-2">Update Material</h6>
                        <select class="form-select mb-2" name="update_material_id" id="update_material">
                            <option disabled <?= empty($values['update_material_id']) ? 'selected' : '' ?>>Choose Material...</option>
                        </select>
                        <input type="text" class="form-control mb-3" name="update_material_name" placeholder="New Material Name"
                               value="<?= htmlspecialchars($values['update_material_name'] ?? '') ?>">

                        <!-- Product Type Update -->
                        <h6 class="fw-bold mb-2">Update Product Type</h6>
                        <select class="form-select mb-2" name="update_product_type_id" id="update_product_type">
                            <option disabled <?= empty($values['update_product_type_id']) ? 'selected' : '' ?>>Choose Product Type...</option>
                        </select>
                        <input type="text" class="form-control mb-3" name="update_product_type_name" placeholder="New Product Type Name"
                               value="<?= htmlspecialchars($values['update_product_type_name'] ?? '') ?>">

                        <button type="submit" class="btn btn-success w-100">
                            <i class="ri-edit-line me-1"></i> Update
                        </button>
                    </form>
                </div>

<!-- DELETE TAB -->
<div class="tab-pane fade" id="deleteTabContent" role="tabpanel">
    <!-- Security Key Section -->
    <div id="deleteSecuritySection">
        <div class="alert alert-warning" role="alert">
            <i class="ri-shield-keyhole-line me-2"></i>
            <strong>Security Required:</strong> Enter the delete key to access deletion functions.
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Delete Access Key</label>
            <div class="input-group">
                <input type="password" class="form-control" id="deleteKey" placeholder="Enter delete key..." autocomplete="off">
                <button type="button" class="btn btn-outline-secondary" id="toggleDeleteKeyVisibility">
                    <i class="ri-eye-line" id="deleteKeyIcon"></i>
                </button>
            </div>
            <small class="text-muted">Contact administrator if you don't have the delete key.</small>
        </div>
        
        <button type="button" class="btn btn-primary" id="verifyDeleteKey">
            <i class="ri-key-2-line me-1"></i> Verify Key
        </button>
    </div>

    <!-- Delete Form Section (Initially Hidden) -->
    <div id="deleteFormSection" style="display: none;">
        <div class="alert alert-success" role="alert">
            <i class="ri-shield-check-line me-2"></i>
            <strong>Access Granted:</strong> You can now proceed with deletion.
            <button type="button" class="btn btn-sm btn-outline-success ms-2" id="lockDeleteAccess">
                <i class="ri-lock-line me-1"></i> Lock Access
            </button>
        </div>

        <form method="POST" id="deleteForm">
            <!-- Top-level selector: choose delete type -->
            <h6 class="fw-bold mb-2">Choose Level to Delete</h6>
            <select class="form-select mb-3" id="delete_level">
                <option disabled selected>Choose Level...</option>
                <option value="section">Section</option>
                <option value="category">Category</option>
                <option value="subcategory">Subcategory</option>
                <option value="material">Material</option>
                <option value="product_type">Product Type</option>
            </select>

            <div id="delete_selectors">
                <!-- Section -->
                <div class="mb-3 level-section" style="display:none;">
                    <label>Section</label>
                    <select class="form-select" name="delete_section_id" id="delete_section">
                        <option disabled selected>Choose Section...</option>
                        <?php foreach($sections as $sec): ?>
                            <option value="<?= $sec['section_id'] ?>"><?= htmlspecialchars($sec['section_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Category -->
                <div class="mb-3 level-category" style="display:none;">
                    <label>Category</label>
                    <select class="form-select" name="delete_category_id" id="delete_category">
                        <option disabled selected>Choose Category...</option>
                    </select>
                </div>

                <!-- Subcategory -->
                <div class="mb-3 level-subcategory" style="display:none;">
                    <label>Subcategory</label>
                    <select class="form-select" name="delete_subcategory_id" id="delete_subcategory">
                        <option disabled selected>Choose Subcategory...</option>
                    </select>
                </div>

                <!-- Material -->
                <div class="mb-3 level-material" style="display:none;">
                    <label>Material</label>
                    <select class="form-select" name="delete_material_id" id="delete_material">
                        <option disabled selected>Choose Material...</option>
                    </select>
                </div>

                <!-- Product Type -->
                <div class="mb-3 level-product_type" style="display:none;">
                    <label>Product Type</label>
                    <select class="form-select" name="delete_product_type_id" id="delete_product_type">
                        <option disabled selected>Choose Product Type...</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100">
                <i class="ri-delete-bin-line me-1"></i> Delete Selected Item
            </button>
        </form>

        <!-- Warning Message for Pending Delete -->
        <?php if (isset($_SESSION['modal_warning'])): ?>
            <div class="alert alert-warning" role="alert">
                <pre class="mb-2"><?= htmlspecialchars($_SESSION['modal_warning']) ?></pre>
                <form method="POST">
                    <?php foreach ($_SESSION['pending_delete'] as $key => $value): ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                    <?php endforeach; ?>
                    <input type="hidden" name="confirm_delete" value="1">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
            <?php 
            unset($_SESSION['modal_warning']);
            unset($_SESSION['pending_delete']);
            ?>
        <?php endif; ?>
    </div>
</div>

            </div>
        </div>
    </div>
</div>

<!-- Select2 Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Modal JS for dynamic filtering with Select2 (Add + Update + Secure Delete) -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- DELETE ACCESS SECURITY ---
    const DELETE_KEY = "DELETE2024"; // Change this to your desired password
    let deleteAccessGranted = false;
    
    const deleteKeyInput = document.getElementById('deleteKey');
    const verifyDeleteKeyBtn = document.getElementById('verifyDeleteKey');
    const toggleDeleteKeyVisibilityBtn = document.getElementById('toggleDeleteKeyVisibility');
    const deleteKeyIcon = document.getElementById('deleteKeyIcon');
    const deleteSecuritySection = document.getElementById('deleteSecuritySection');
    const deleteFormSection = document.getElementById('deleteFormSection');
    const lockDeleteAccessBtn = document.getElementById('lockDeleteAccess');

    // Toggle password visibility
    if (toggleDeleteKeyVisibilityBtn) {
        toggleDeleteKeyVisibilityBtn.addEventListener('click', function() {
            if (deleteKeyInput.type === 'password') {
                deleteKeyInput.type = 'text';
                deleteKeyIcon.className = 'ri-eye-off-line';
            } else {
                deleteKeyInput.type = 'password';
                deleteKeyIcon.className = 'ri-eye-line';
            }
        });
    }

    // Verify delete key
    if (verifyDeleteKeyBtn) {
        verifyDeleteKeyBtn.addEventListener('click', function() {
            const enteredKey = deleteKeyInput.value.trim();
            
            if (enteredKey === DELETE_KEY) {
                deleteAccessGranted = true;
                deleteSecuritySection.style.display = 'none';
                deleteFormSection.style.display = 'block';
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Access Granted',
                    text: 'You can now proceed with deletion operations.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                // Clear the input
                deleteKeyInput.value = '';
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'Invalid delete key. Please contact your administrator.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    // Lock delete access
    if (lockDeleteAccessBtn) {
        lockDeleteAccessBtn.addEventListener('click', function() {
            deleteAccessGranted = false;
            deleteFormSection.style.display = 'none';
            deleteSecuritySection.style.display = 'block';
            deleteKeyInput.value = '';
            
            // Reset all delete form selections
            $('#delete_level').val('').trigger('change');
            Object.values(deleteSelectors).forEach(el => el.style.display = 'none');
            
            Swal.fire({
                icon: 'info',
                title: 'Access Locked',
                text: 'Delete access has been locked.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }

    // Allow Enter key to verify delete key
    if (deleteKeyInput) {
        deleteKeyInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyDeleteKeyBtn.click();
            }
        });
    }

    // --- SELECT2 INITIALIZATION ---
    const baseCfg = { 
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: false,
        dropdownParent: $('#addSCSMPModal') // Ensure dropdown shows within modal
    };

    // Initialize Select2 on all dropdowns
    $('#modal_section').select2(baseCfg).attr('data-placeholder', 'Choose Section for Category...');
    $('#modal_category').select2(baseCfg).attr('data-placeholder', 'Choose Category for Subcategory...');
    $('#modal_subcategory').select2(baseCfg).attr('data-placeholder', 'Choose Subcategory for Material...');
    $('#modal_material').select2(baseCfg).attr('data-placeholder', 'Choose Material for Product Type...');

    $('#update_section').select2(baseCfg).attr('data-placeholder', 'Choose Section...');
    $('#update_category').select2(baseCfg).attr('data-placeholder', 'Choose Category...');
    $('#update_subcategory').select2(baseCfg).attr('data-placeholder', 'Choose Subcategory...');
    $('#update_material').select2(baseCfg).attr('data-placeholder', 'Choose Material...');
    $('#update_product_type').select2(baseCfg).attr('data-placeholder', 'Choose Product Type...');

    $('#delete_level').select2(baseCfg).attr('data-placeholder', 'Choose Level...');
    $('#delete_section').select2(baseCfg).attr('data-placeholder', 'Choose Section...');
    $('#delete_category').select2(baseCfg).attr('data-placeholder', 'Choose Category...');
    $('#delete_subcategory').select2(baseCfg).attr('data-placeholder', 'Choose Subcategory...');
    $('#delete_material').select2(baseCfg).attr('data-placeholder', 'Choose Material...');
    $('#delete_product_type').select2(baseCfg).attr('data-placeholder', 'Choose Product Type...');

    // --- ELEMENTS ---
    const modalSection = document.getElementById('modal_section');
    const modalCategory = document.getElementById('modal_category');
    const modalSubcategory = document.getElementById('modal_subcategory');
    const modalMaterial = document.getElementById('modal_material');

    const updateSection = document.getElementById('update_section');
    const updateCategory = document.getElementById('update_category');
    const updateSubcategory = document.getElementById('update_subcategory');
    const updateMaterial = document.getElementById('update_material');
    const updateProductType = document.getElementById('update_product_type');

    const deleteLevel = document.getElementById('delete_level');
    const deleteSection = document.getElementById('delete_section');
    const deleteCategory = document.getElementById('delete_category');
    const deleteSubcategory = document.getElementById('delete_subcategory');
    const deleteMaterial = document.getElementById('delete_material');
    const deleteProductType = document.getElementById('delete_product_type');

    const deleteSelectors = {
        section: document.querySelector('.level-section'),
        category: document.querySelector('.level-category'),
        subcategory: document.querySelector('.level-subcategory'),
        material: document.querySelector('.level-material'),
        product_type: document.querySelector('.level-product_type')
    };

    // --- FIXED FETCH OPTIONS (Returns Promise) ---
    function fetchOptions(type, parent_id, targetSelect, selectedId = null) {
        return new Promise((resolve, reject) => {
            fetch(`../private/modal-add-SCSMP-backend.php?ajax=1&type=${type}&parent_id=${parent_id}`)
                .then(res => res.json())
                .then(data => {
                    // Clear Select2 and rebuild options
                    $(targetSelect).empty();
                    
                    const defaultOption = new Option(`Choose ${type.replace('_',' ')}...`, '', true, true);
                    defaultOption.disabled = true;
                    targetSelect.add(defaultOption);

                    data.forEach(item => {
                        let value, label;
                        if(type === 'category') { value = item.category_id; label = item.category_name; }
                        if(type === 'subcategory') { value = item.subcategory_id; label = item.subcategory_name; }
                        if(type === 'material') { value = item.material_id; label = item.material_name; }
                        if(type === 'product_type') { value = item.product_type_id; label = item.product_name; }

                        const opt = new Option(label, value, false, false);
                        targetSelect.add(opt);
                    });

                    // Set selected value if provided
                    if(selectedId) {
                        $(targetSelect).val(selectedId).trigger('change');
                    } else {
                        $(targetSelect).trigger('change');
                    }

                    resolve();
                })
                .catch(err => {
                    console.error('Error fetching options:', err);
                    reject(err);
                });
        });
    }

    // --- TAB HANDLERS (Updated for Select2) ---
    function setupCascade(parentEl, childEl, nextChildEl = null, type) {
        if(parentEl){
            $(parentEl).on('change', function() {
                const parentValue = $(this).val();
                if (parentValue) {
                    fetchOptions(type, parentValue, childEl);
                    if(nextChildEl) {
                        $(nextChildEl).empty();
                        const nextDefaultOption = new Option(`Choose ${nextChildEl.id.replace('modal_', '').replace('update_', '').replace('delete_', '').replace('_',' ')}...`, '', true, true);
                        nextDefaultOption.disabled = true;
                        nextChildEl.add(nextDefaultOption);
                        $(nextChildEl).trigger('change');
                    }
                } else {
                    // Clear child dropdown if parent is cleared
                    $(childEl).empty();
                    const childDefaultOption = new Option(`Choose ${type.replace('_',' ')}...`, '', true, true);
                    childDefaultOption.disabled = true;
                    childEl.add(childDefaultOption);
                    $(childEl).trigger('change');
                }
            });
        }
    }

    // --- ADD TAB ---
    const addValues = <?= json_encode($values) ?>;
    
    // Setup cascade for ADD tab
    setupCascade(modalSection, modalCategory, modalSubcategory, 'category');
    setupCascade(modalCategory, modalSubcategory, modalMaterial, 'subcategory');
    setupCascade(modalSubcategory, modalMaterial, null, 'material');

    // FIXED: Restore ADD tab values with proper async handling
    function restoreAddTabValues() {
        if(modalSection && addValues['section_id']) {
            // First set the section
            $(modalSection).val(addValues['section_id']).trigger('change');
            
            // Then populate and set category if exists
            if(addValues['category_id']) {
                fetchOptions('category', addValues['section_id'], modalCategory, addValues['category_id'])
                    .then(() => {
                        // Then populate and set subcategory if exists
                        if(addValues['subcategory_id']) {
                            return fetchOptions('subcategory', addValues['category_id'], modalSubcategory, addValues['subcategory_id']);
                        }
                    })
                    .then(() => {
                        // Finally populate and set material if exists
                        if(addValues['material_id']) {
                            return fetchOptions('material', addValues['subcategory_id'], modalMaterial, addValues['material_id']);
                        }
                    })
                    .catch(err => console.error('Error restoring add tab values:', err));
            }
        }
    }

    // Call restore function after a short delay to ensure DOM is ready
    setTimeout(restoreAddTabValues, 100);

    // --- UPDATE TAB ---
    const updateValues = <?= json_encode($values) ?>;
    
    // Setup cascade for UPDATE tab
    setupCascade(updateSection, updateCategory, updateSubcategory, 'category');
    setupCascade(updateCategory, updateSubcategory, updateMaterial, 'subcategory');
    setupCascade(updateSubcategory, updateMaterial, updateProductType, 'material');
    setupCascade(updateMaterial, updateProductType, null, 'product_type');

    // FIXED: Restore UPDATE tab values with proper async handling
    function restoreUpdateTabValues() {
        if(updateSection && updateValues['update_section_id']) {
            $(updateSection).val(updateValues['update_section_id']).trigger('change');
            
            if(updateValues['update_category_id']) {
                fetchOptions('category', updateValues['update_section_id'], updateCategory, updateValues['update_category_id'])
                    .then(() => {
                        if(updateValues['update_subcategory_id']) {
                            return fetchOptions('subcategory', updateValues['update_category_id'], updateSubcategory, updateValues['update_subcategory_id']);
                        }
                    })
                    .then(() => {
                        if(updateValues['update_material_id']) {
                            return fetchOptions('material', updateValues['update_subcategory_id'], updateMaterial, updateValues['update_material_id']);
                        }
                    })
                    .then(() => {
                        if(updateValues['update_product_type_id']) {
                            return fetchOptions('product_type', updateValues['update_material_id'], updateProductType, updateValues['update_product_type_id']);
                        }
                    })
                    .catch(err => console.error('Error restoring update tab values:', err));
            }
        }
    }

    // Call restore function after a short delay
    setTimeout(restoreUpdateTabValues, 100);

    // --- DELETE TAB (Updated for Select2 + Security) ---
    $(deleteLevel).on('change', function() {
        if (!deleteAccessGranted) {
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'Please verify your delete key first.',
                confirmButtonColor: '#dc3545'
            });
            $(this).val('').trigger('change');
            return;
        }

        const level = $(this).val();
        Object.values(deleteSelectors).forEach(el => el.style.display = 'none');

        if(level === 'section') deleteSelectors.section.style.display = 'block';
        if(level === 'category') { deleteSelectors.section.style.display = 'block'; deleteSelectors.category.style.display = 'block'; }
        if(level === 'subcategory') { deleteSelectors.section.style.display = 'block'; deleteSelectors.category.style.display = 'block'; deleteSelectors.subcategory.style.display = 'block'; }
        if(level === 'material') { deleteSelectors.section.style.display = 'block'; deleteSelectors.category.style.display = 'block'; deleteSelectors.subcategory.style.display = 'block'; deleteSelectors.material.style.display = 'block'; }
        if(level === 'product_type') { deleteSelectors.section.style.display = 'block'; deleteSelectors.category.style.display = 'block'; deleteSelectors.subcategory.style.display = 'block'; deleteSelectors.material.style.display = 'block'; deleteSelectors.product_type.style.display = 'block'; }
    });

    setupCascade(deleteSection, deleteCategory, deleteSubcategory, 'category');
    setupCascade(deleteCategory, deleteSubcategory, deleteMaterial, 'subcategory');
    setupCascade(deleteSubcategory, deleteMaterial, deleteProductType, 'material');
    setupCascade(deleteMaterial, deleteProductType, null, 'product_type');

    // --- DELETE CONFIRMATION ---
    const deleteForm = document.querySelector('#deleteForm');
    if(deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!deleteAccessGranted) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'Please verify your delete key first.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            const level = $(deleteLevel).val();
            let selectedText = '';
            let selectedId = '';
            
            switch(level) {
                case 'section': 
                    selectedText = $(deleteSection).find('option:selected').text();
                    selectedId = $(deleteSection).val();
                    break;
                case 'category': 
                    selectedText = $(deleteCategory).find('option:selected').text();
                    selectedId = $(deleteCategory).val();
                    break;
                case 'subcategory': 
                    selectedText = $(deleteSubcategory).find('option:selected').text();
                    selectedId = $(deleteSubcategory).val();
                    break;
                case 'material': 
                    selectedText = $(deleteMaterial).find('option:selected').text();
                    selectedId = $(deleteMaterial).val();
                    break;
                case 'product_type': 
                    selectedText = $(deleteProductType).find('option:selected').text();
                    selectedId = $(deleteProductType).val();
                    break;
            }

            if(!selectedId || !selectedText) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selection Required',
                    text: 'Please select an item to delete!',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            // Final confirmation with SweetAlert
            Swal.fire({
                title: 'Are you absolutely sure?',
                html: `You are about to delete:<br><strong>${selectedText}</strong><br><br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('delete_level', level);
                    formData.append(`delete_${level}_id`, selectedId);
                    
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we process the deletion.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch('../private/modal-add-SCSMP-backend.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.warning) {
                            Swal.fire({
                                title: 'Confirmation Required',
                                html: data.message.replace(/\n/g, '<br>'),
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Yes, Delete All',
                                cancelButtonText: 'Cancel'
                            }).then((confirmResult) => {
                                if (confirmResult.isConfirmed) {
                                    formData.append('confirm_delete', '1');
                                    fetch('../private/modal-add-SCSMP-backend.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if(data.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Deleted!',
                                                text: 'The item has been successfully deleted.',
                                                confirmButtonColor: '#28a745'
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        } else {
                                            throw new Error(data.error || 'Delete failed');
                                        }
                                    })
                                    .catch(error => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Delete Failed',
                                            text: 'An error occurred during deletion: ' + error.message,
                                            confirmButtonColor: '#dc3545'
                                        });
                                    });
                                }
                            });
                        } else if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The item has been successfully deleted.',
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.error || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: 'An error occurred during deletion: ' + error.message,
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        });
    }

    // --- Handle modal cleanup ---
    const modal = document.getElementById('addSCSMPModal');
    if(modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            // Clear any alerts
            const alerts = modal.querySelectorAll('.alert');
            alerts.forEach(alert => alert.remove());
            
            // Reset forms
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => form.reset());
            
            // Reset Select2 dropdowns
            $('.select2').val('').trigger('change');
            
            // Reset delete access
            deleteAccessGranted = false;
            if(deleteFormSection) deleteFormSection.style.display = 'none';
            if(deleteSecuritySection) deleteSecuritySection.style.display = 'block';
            if(deleteKeyInput) deleteKeyInput.value = '';
            
            // Hide all delete selectors
            Object.values(deleteSelectors).forEach(el => {
                if(el) el.style.display = 'none';
            });
        });
    }

    // --- Handle tab switching ---
    const deleteTab = document.querySelector('a[href="#deleteTabContent"]');
    if (deleteTab) {
        deleteTab.addEventListener('click', function() {
            // Reset delete access when switching to delete tab
            if (!deleteAccessGranted) {
                if(deleteFormSection) deleteFormSection.style.display = 'none';
                if(deleteSecuritySection) deleteSecuritySection.style.display = 'block';
            }
        });
    }

});
</script>

<!-- Reopen modal after redirect -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Check if modal should be reopened
    <?php if(isset($_SESSION['reopen_modal'])): ?>
        let modalEl = document.getElementById('addSCSMPModal');
        if (modalEl) {
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
        <?php unset($_SESSION['reopen_modal']); ?>
    <?php endif; ?>
    
    <?php unset($_SESSION['modal_values']); // clear after use ?>
});
</script>
