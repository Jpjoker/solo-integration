jQuery(document).ready(function($) {
    function loadCustomers() {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'get_customers'
            },
            success: function(response) {
                // Update de klantenlijst in de DOM
            }
        });
    }

    $('.add-customer').on('click', function(e) {
        e.preventDefault();
        $('#customer-form').show();
        $('#customer-id').val('');
    });

    $('.edit-customer').on('click', function(e) {
        e.preventDefault();
        var customerId = $(this).data('id');
        // Laad klantgegevens en vul het formulier
    });

    $('.delete-customer').on('click', function(e) {
        e.preventDefault();
        var customerId = $(this).data('id');
        if (confirm('Weet je zeker dat je deze klant wilt verwijderen?')) {
            // Implementeer delete logica
        }
    });

    $('#customer-form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'save_customer',
                customer_data: formData
            },
            success: function(response) {
                if (response.success) {
                    alert('Klant opgeslagen!');
                    loadCustomers();
                    $('#customer-form').hide();
                } else {
                    alert('Er is een fout opgetreden. Probeer het opnieuw.');
                }
            }
        });
    });

    loadCustomers(); // Laad klanten bij het laden van de pagina
});