<!-- Modal HTML -->
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
            <ul class="nav nav-tabs px-4" id="scsmpTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#addTabContent" type="button" role="tab">Add</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="update-tab" data-bs-toggle="tab" data-bs-target="#updateTabContent" type="button" role="tab">Update</button>
                </li>
                <!-- Add Delete Tab -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#deleteTabContent" type="button" role="tab">Delete</button>
                </li>
            </ul>

            <div class="tab-content px-4 py-3">

                <!-- ADD TAB -->
                <div class="tab-pane fade show active" id="addTabContent" role="tabpanel">
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


                        <button type="submit" class="btn btn-primary w-100">Add</button>
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

                        <button type="submit" class="btn btn-success w-100">Update</button>
                    </form>
                </div>

<!-- DELETE TAB -->
<div class="tab-pane fade" id="deleteTabContent" role="tabpanel">
    <form action="../private/modal-add-SCSMP-backend.php" method="POST" 
          onsubmit="return confirmDeletion();">

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

        <button type="submit" class="btn btn-danger w-100">Delete</button>
    </form>
</div>
                
            </div>

        </div>
    </div>
</div>

<!-- Modal JS for dynamic filtering (Add + Update) -->
<script>
document.addEventListener('DOMContentLoaded', function () {

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

    // --- FETCH OPTIONS ---
    function fetchOptions(type, parent_id, targetSelect, selectedId = null) {
        fetch(`../private/modal-add-SCSMP-backend.php?ajax=1&type=${type}&parent_id=${parent_id}`)
            .then(res => res.json())
            .then(data => {
                targetSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.disabled = true;
                defaultOption.selected = true;
                defaultOption.text = `Choose ${type.replace('_',' ')}...`;
                targetSelect.appendChild(defaultOption);

                data.forEach(item => {
                    let value, label;
                    if(type === 'category') { value = item.category_id; label = item.category_name; }
                    if(type === 'subcategory') { value = item.subcategory_id; label = item.subcategory_name; }
                    if(type === 'material') { value = item.material_id; label = item.material_name; }
                    if(type === 'product_type') { value = item.product_type_id; label = item.product_name; }

                    const opt = document.createElement('option');
                    opt.value = value;
                    opt.textContent = label;
                    if(selectedId && selectedId == value) opt.selected = true;
                    targetSelect.appendChild(opt);
                });
            })
            .catch(err => console.error(err));
    }

    // --- TAB HANDLERS ---
    function setupCascade(parentEl, childEl, nextChildEl = null, type) {
        if(parentEl){
            parentEl.addEventListener('change', () => {
                fetchOptions(type, parentEl.value, childEl);
                if(nextChildEl) nextChildEl.innerHTML = `<option disabled selected>Choose ${nextChildEl.id.replace('_',' ')}...</option>`;
            });
        }
    }

    // --- ADD TAB ---
    const addValues = <?= json_encode($values) ?>;
    if(modalSection){
        if(addValues['section_id']) fetchOptions('category', addValues['section_id'], modalCategory, addValues['category_id']);
        setupCascade(modalSection, modalCategory, modalSubcategory, 'category');
    }
    if(modalCategory){
        if(addValues['category_id']) fetchOptions('subcategory', addValues['category_id'], modalSubcategory, addValues['subcategory_id']);
        setupCascade(modalCategory, modalSubcategory, modalMaterial, 'subcategory');
    }
    if(modalSubcategory){
        if(addValues['subcategory_id']) fetchOptions('material', addValues['subcategory_id'], modalMaterial, addValues['material_id']);
        setupCascade(modalSubcategory, modalMaterial, null, 'material');
    }

    // --- UPDATE TAB ---
    const updateValues = <?= json_encode($values) ?>;
    if(updateSection){
        if(updateValues['update_section_id']) fetchOptions('category', updateValues['update_section_id'], updateCategory, updateValues['update_category_id']);
        setupCascade(updateSection, updateCategory, updateSubcategory, 'category');
    }
    if(updateCategory){
        if(updateValues['update_category_id']) fetchOptions('subcategory', updateValues['update_category_id'], updateSubcategory, updateValues['update_subcategory_id']);
        setupCascade(updateCategory, updateSubcategory, updateMaterial, 'subcategory');
    }
    if(updateSubcategory){
        if(updateValues['update_subcategory_id']) fetchOptions('material', updateValues['update_subcategory_id'], updateMaterial, updateValues['update_material_id']);
        setupCascade(updateSubcategory, updateMaterial, updateProductType, 'material');
    }
    if(updateMaterial){
        if(updateValues['update_material_id']) fetchOptions('product_type', updateValues['update_material_id'], updateProductType, updateValues['update_product_type_id']);
        setupCascade(updateMaterial, updateProductType, null, 'product_type');
    }

    // --- DELETE TAB ---
    deleteLevel.addEventListener('change', () => {
        const level = deleteLevel.value;
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
    const deleteForm = document.querySelector('#deleteTabContent form');
    if(deleteForm){
        deleteForm.addEventListener('submit', function(e){
            e.preventDefault();

            const level = deleteLevel.value;
            let selectedText = '';
            let warning = '';

            switch(level){
                case 'section': 
                    selectedText = deleteSection.options[deleteSection.selectedIndex]?.text; 
                    warning = 'This will delete everything under this Section!'; 
                    break;
                case 'category': 
                    selectedText = deleteCategory.options[deleteCategory.selectedIndex]?.text; 
                    warning = 'This will delete everything under this Category!'; 
                    break;
                case 'subcategory': 
                    selectedText = deleteSubcategory.options[deleteSubcategory.selectedIndex]?.text; 
                    warning = 'This will delete everything under this Subcategory!'; 
                    break;
                case 'material': 
                    selectedText = deleteMaterial.options[deleteMaterial.selectedIndex]?.text; 
                    warning = 'This will delete all Product Types under this Material!'; 
                    break;
                case 'product_type': 
                    selectedText = deleteProductType.options[deleteProductType.selectedIndex]?.text; 
                    warning = ''; 
                    break;
            }

            if(!selectedText){
                alert('Please select a value to delete!');
                return false;
            }

            const confirmed = confirm(`Are you sure you want to delete the ${level.replace('_',' ')}: "${selectedText}"?\n${warning}`);
            if(confirmed){
                deleteForm.submit();
            }
        });
    }

});
</script>



<!-- Reopen modal after redirect -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.location.hash === "#addSCSMPModal") {
        let modalEl = document.getElementById('addSCSMPModal');
        if (modalEl) {
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }
    <?php unset($_SESSION['modal_values']); // clear after use ?>
});
</script>
