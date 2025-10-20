// Store current supplier for modal operations
let currentSupplier = null;

// Function to populate update form fields
function populateUpdateForm(supplier) {
    $('#update_supplier_id').val(supplier.supplier_id);
    $('#update_supplier_name').val(supplier.supplier_name || '');
    $('#update_supplier_contact').val(supplier.supplier_contact_person || '');
    $('#update_supplier_phone').val(supplier.phone || '');
    $('#update_supplier_email').val(supplier.email || '');
    $('#update_supplier_address').val(supplier.address || '');
    $('#update_supplier_city').val(supplier.city || '');
    $('#update_supplier_region').val(supplier.region || '');
    $('#update_supplier_postcode').val(supplier.postcode || '');
    $('#update_supplier_country').val(supplier.country || '');
    $('#update_supplier_notes').val(supplier.notes || '');
    $('#update_supplier_xero_relation').val(supplier.xero_relation || '');
}

// Function to populate delete form fields
function populateDeleteForm(supplier) {
    $('#delete_supplier_id').val(supplier.supplier_id);
    $('#delete_supplier_name').val(supplier.supplier_name || '');
    $('#delete_supplier_contact').val(supplier.supplier_contact_person || '');
    $('#delete_supplier_phone').val(supplier.phone || '');
    $('#delete_supplier_email').val(supplier.email || '');
    $('#delete_supplier_address').val(supplier.address || '');
    $('#delete_supplier_city').val(supplier.city || '');
    $('#delete_supplier_region').val(supplier.region || '');
    $('#delete_supplier_postcode').val(supplier.postcode || '');
    $('#delete_supplier_country').val(supplier.country || '');
    $('#delete_supplier_notes').val(supplier.notes || '');
}

function viewSupplierDetails(supplier) {
    currentSupplier = supplier;
    
    // Populate modal fields
    $('#view_supplier_name').text(supplier.supplier_name || 'N/A');
    $('#view_contact_person').text(supplier.supplier_contact_person || 'N/A');
    $('#view_phone').text(supplier.phone || 'N/A');
    $('#view_email').text(supplier.email || 'N/A');
    $('#view_address').text(supplier.address || 'N/A');
    $('#view_city').text(supplier.city || 'N/A');
    $('#view_region').text(supplier.region || 'N/A');
    $('#view_postcode').text(supplier.postcode || 'N/A');
    $('#view_country').text(supplier.country || 'N/A');
    $('#view_notes').text(supplier.notes || 'N/A');
    
    // Show the modal
    $('#viewSupplierModal').modal('show');
}

function editSupplier(supplierId) {
    // Find the supplier data from the table
    const supplier = Array.from(document.querySelectorAll('#supplierTable tbody tr')).map(row => {
        const cells = row.cells;
        return {
            supplier_id: supplierId,
            supplier_name: cells[0].textContent,
            supplier_contact_person: cells[1].textContent,
            phone: cells[2].textContent,
            email: cells[3].textContent,
            city: cells[4].textContent,
            country: cells[5].textContent
        };
    }).find(s => s.supplier_id === supplierId);

    // If we have current supplier data from view modal, use that instead
    const supplierData = currentSupplier && currentSupplier.supplier_id === supplierId ? currentSupplier : supplier;

    // Switch to update tab
    $('a[href="#animation-profile"]').tab('show');
    
    // Set the supplier in the dropdown and populate form
    $('#supplierSelectUpdate').val(supplierId).trigger('change');
    if (supplierData) {
        populateUpdateForm(supplierData);
    }
    
    // Close the view modal if it's open
    $('#viewSupplierModal').modal('hide');

    // Scroll to the form
    const $updateSelect = $("#supplierSelectUpdate");
    if ($updateSelect.length) {
        $('html, body').animate({
            scrollTop: $updateSelect.offset().top - 100
        }, 500);
    }
}

function deleteSupplier(supplierId, supplierName) {
    const supplier = currentSupplier && currentSupplier.supplier_id === supplierId ? currentSupplier : {
        supplier_id: supplierId,
        supplier_name: supplierName
    };

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete supplier "${supplierName}"? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send delete request to the server
            const formData = new FormData();
            formData.append('supplier_id', supplierId);
            
            $.ajax({
                url: '../private/forms-delete-supplier.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Raw response:', response);
                    try {
                        // Parse response if it's a string
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: `Supplier "${response.supplier_name}" has been deleted.`,
                                icon: 'success',
                                confirmButtonColor: '#2ab57d'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to delete supplier',
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        console.log('Invalid response:', response);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Invalid response from server',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {xhr, status, error});
                    console.log('Response Text:', xhr.responseText);
                    let errorMessage = 'An error occurred while deleting the supplier.';
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {
                        console.error('Error parsing error response:', e);
                    }
                    
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }
    });
}

// When the document is ready
$(document).ready(function() {
    // Initialize DataTable
    $('#supplierTable').DataTable({
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        responsive: true,
        order: [[0, 'asc']], // Sort by supplier name by default
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search suppliers...",
            lengthMenu: "_MENU_ suppliers per page"
        }
    });

    // Initialize Select2 for Update dropdown
    $('#supplierSelectUpdate').select2({
        placeholder: "-- Select a supplier (optional) --",
        allowClear: true,
        width: '100%',
        theme: 'default'
    });

    // Initialize Select2 for Delete dropdown
    $('#deleteSupplierSelect').select2({
        placeholder: "-- Select a supplier to delete --",
        allowClear: true,
        width: '100%',
        theme: 'default'
    });

    // Handle tab changes
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).attr('href') === '#animation-profile') {
            $('#supplierSelectUpdate').select2('destroy').select2({
                placeholder: "-- Select a supplier (optional) --",
                allowClear: true,
                width: '100%',
                theme: 'default'
            });
        } else if ($(e.target).attr('href') === '#animation-messages') {
            $('#deleteSupplierSelect').select2('destroy').select2({
                placeholder: "-- Select a supplier to delete --",
                allowClear: true,
                width: '100%',
                theme: 'default'
            });
        }
    });

    // Handle select2 events
    $('#supplierSelectUpdate').on('select2:select', function(e) {
        const selectedValue = e.params.data.id;
        const selectedOption = $(this).find('option[value="' + selectedValue + '"]');
        
        if (selectedValue) {
            populateUpdateForm({
                supplier_id: selectedValue,
                supplier_name: selectedOption.data('name'),
                supplier_contact_person: selectedOption.data('contact'),
                phone: selectedOption.data('phone'),
                email: selectedOption.data('email'),
                address: selectedOption.data('address'),
                city: selectedOption.data('city'),
                region: selectedOption.data('region'),
                postcode: selectedOption.data('postcode'),
                country: selectedOption.data('country'),
                notes: selectedOption.data('notes'),
                xero_relation: selectedOption.data('xero')
            });
        }
    }).on('select2:clear', function() {
        populateUpdateForm({});
    });

    // Add submit handler for delete form
    $('#deleteSupplierForm').on('submit', function(e) {
        e.preventDefault();
        const supplierId = $('#delete_supplier_id').val();
        const supplierName = $('#delete_supplier_name').val();
        if (supplierId) {
            deleteSupplier(supplierId, supplierName);
        }
        return false;
    });

    $('#deleteSupplierSelect').on('select2:select', function(e) {
        const selectedValue = e.params.data.id;
        const selectedOption = $(this).find('option[value="' + selectedValue + '"]');
        
        if (selectedValue) {
            populateDeleteForm({
                supplier_id: selectedValue,
                supplier_name: selectedOption.data('name'),
                supplier_contact_person: selectedOption.data('contact'),
                phone: selectedOption.data('phone'),
                email: selectedOption.data('email'),
                address: selectedOption.data('address'),
                city: selectedOption.data('city'),
                region: selectedOption.data('region'),
                postcode: selectedOption.data('postcode'),
                country: selectedOption.data('country'),
                notes: selectedOption.data('notes')
            });
        }
    }).on('select2:clear', function() {
        populateDeleteForm({});
    });
});