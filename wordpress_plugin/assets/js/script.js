$(document).ready(function() {

    // Functie om producten te laden
    function loadProducts() {
        $.ajax({
            url: odoo_integration_ajax.ajax_url,
            type: 'POST',
            data: { action: 'get_products' },
            success: function(response) {
                $('#product-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error loading products: " + error);
                alert("An error occurred while loading products.");
            }
        });
    }

    // Formulier voor product toevoegen
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: odoo_integration_ajax.ajax_url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                loadProducts();
                $('#add-product-form')[0].reset();
                alert("Product successfully added.");
            },
            error: function(xhr, status, error) {
                console.error("Error adding product: " + error);
                alert("An error occurred while adding the product.");
            }
        });
    });

    // Functie om orders te laden
    function loadOrders() {
        $.ajax({
            url: odoo_integration_ajax.ajax_url,
            type: 'POST',
            data: { action: 'get_orders' },
            success: function(response) {
                $('#order-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error loading orders: " + error);
                alert("An error occurred while loading orders.");
            }
        });
    }

    // Initial load
    loadProducts();
    loadOrders();

});
