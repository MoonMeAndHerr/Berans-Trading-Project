function loadPriceData(priceId) {
    if (!priceId) {
        // Clear form if no price ID selected
        resetForm();
        return;
    }

    // Show loading indicator
    $('#loadingIndicator').show();
    
    $.ajax({
        url: 'get_price_data.php',
        type: 'GET',
        data: { price_id: priceId },
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                showError(data.error);
                return;
            }

            // Update basic form fields
            $('#product_id').val(data.product_id);
            $('#supplier_id').val(data.supplier_id);
            $('#quantity').val(data.quantity);
            $('#carton_width').val(data.carton_width);
            $('#carton_height').val(data.carton_height);
            $('#carton_length').val(data.carton_length);
            $('#pcs_per_carton').val(data.pcs_per_carton);
            $('#no_of_carton').val(data.no_of_carton);
            $('#designlogo').val(data.designlogo);
            $('#price').val(data.price);
            $('#shipping_price').val(data.shipping_price);
            $('#additional_price').val(data.additional_price);
            $('#weight_carton').val(data.weight_carton);
            $('#conversion_rate').val(data.conversion_rate);
            $('#estimated_arrival').val(data.estimated_arrival);
            
            // Update calculated fields
            $('#price_rm').val(data.price_rm);
            $('#total_price_yen').val(data.total_price_yen);
            $('#total_price_rm').val(data.total_price_rm);
            $('#deposit_50_yen').val(data.deposit_50_yen);
            $('#deposit_50_rm').val(data.deposit_50_rm);
            $('#cbm_carton').val(data.cbm_carton);
            $('#total_cbm').val(data.total_cbm);
            $('#vm_carton').val(data.vm_carton);
            $('#total_vm').val(data.total_vm);
            $('#total_weight').val(data.total_weight);
            $('#sg_tax').val(data.sg_tax);
            $('#supplier_1st_yen').val(data.supplier_1st_yen);
            $('#supplier_2nd_yen').val(data.supplier_2nd_yen);
            $('#customer_1st_rm').val(data.customer_1st_rm);
            $('#customer_2nd_rm').val(data.customer_2nd_rm);
            
            // Final calculated fields
            $('#final_selling_total').val(data.final_selling_total);
            $('#final_total_price').val(data.final_total_price);
            $('#final_unit_price').val(data.final_unit_price);
            $('#final_profit_per_unit_rm').val(data.final_profit_per_unit_rm);
            $('#final_total_profit').val(data.final_total_profit);
            $('#final_profit_percent').val(data.final_profit_percent);
            $('#final_selling_unit').val(data.final_selling_unit);
            
            // Shipping fields
            if (data.shipping_totals) {
                $('#price_total_sea_shipping').val(data.shipping_totals.price_total_sea_shipping);
                $('#price_total_air_shipping_vm').val(data.shipping_totals.price_total_air_shipping_vm);
                $('#price_total_air_shipping_kg').val(data.shipping_totals.price_total_air_shipping_kg);
            }
            
            // Additional cartons
            for (let i = 1; i <= 6; i++) {
                $(`#add_carton${i}_width`).val(data[`add_carton${i}_width`] || '');
                $(`#add_carton${i}_height`).val(data[`add_carton${i}_height`] || '');
                $(`#add_carton${i}_length`).val(data[`add_carton${i}_length`] || '');
                $(`#add_carton${i}_pcs`).val(data[`add_carton${i}_pcs`] || '');
                $(`#add_carton${i}_no`).val(data[`add_carton${i}_no`] || '');
                $(`#add_carton${i}_total_cbm`).val(data[`add_carton${i}_total_cbm`] || '');
            }
            
            // Update shipping code dropdown if available
            if (data.shipping_code) {
                $('#shipping_code').val(data.shipping_code);
            }
            
            // Hide loading indicator
            $('#loadingIndicator').hide();
            
            // Show success message
            // showSuccess('Price data loaded successfully');
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            showError('Error loading price data: ' + error);
        }
    });
}

function resetForm() {
    // Reset all form fields
    $('#priceUpdateForm')[0].reset();
    
    // Reset any special fields or calculated fields
    $('.calculated-field').val('0.00');
    
    // Clear any error/success messages
    $('.alert').hide();
}

function showError(message) {
    $('#errorAlert').text(message).show();
    $('#successAlert').hide();
}

function showSuccess(message) {
    $('#successAlert').text(message).show();
    $('#errorAlert').hide();
}

// Initialize form when document is ready
$(document).ready(function() {
    // Add loading indicator HTML (if not already in your page)
    if ($('#loadingIndicator').length === 0) {
        $('body').append('<div id="loadingIndicator" style="display:none; position:fixed; top:20px; right:20px; z-index:1000;">Loading...</div>');
    }
    
    // Add message containers (if not already in your page)
    if ($('#errorAlert').length === 0) {
        $('body').append('<div id="errorAlert" class="alert alert-danger" style="display:none; position:fixed; top:60px; right:20px; z-index:1000;"></div>');
    }
    if ($('#successAlert').length === 0) {
        $('body').append('<div id="successAlert" class="alert alert-success" style="display:none; position:fixed; top:60px; right:20px; z-index:1000;"></div>');
    }
    
    // Initialize form with preselected price_id
    const initialPriceId = $('#price_id').val();
    if (initialPriceId) {
        loadPriceData(initialPriceId);
    }
    
    // Add change handler for price_id dropdown
    $('#price_id').change(function() {
        loadPriceData($(this).val());
    });
});

// Auto-submit the form once after prefill (only the first time)
if (!sessionStorage.getItem('formAutoSubmitted')) {
    sessionStorage.setItem('formAutoSubmitted', 'true');

    // Wait briefly to ensure all fields are set before submitting
    setTimeout(() => {
        $('#priceUpdateForm').submit();
    }, 300); // Adjust delay as needed
}
