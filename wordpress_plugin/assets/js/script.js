jQuery(document).ready(function($) {
    function loadCustomers() {
        $.ajax({
            url: odoo_integration_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_customers'
            },
            success: function(response) {
                if (response.success) {
                    var customers = response.data;
                    var html = '';
                    customers.forEach(function(customer) {
                        html += '<tr>';
                        html += '<td>' + customer.name + '</td>';
                        html += '<td>' + customer.email + '</td>';
                        html += '<td>';
                        html += '<button class="edit-customer" data-id="' + customer.id + '">Edit</button>';
                        html += '<button class="delete-customer" data-id="' + customer.id + '">Delete</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#customer-list').html(html);
                }
            }
        });
    }

    $('#add-customer').on('click', function() {
        $('#customer-id').val('');
        $('#customer-name').val('');
        $('#customer-email').val('');
        $('#customer-modal').show();
    });

    $('#customer-form').on('submit', function(e) {
        e.preventDefault();
        var customerId = $('#customer-id').val();
        var customerData = {
            name: $('#customer-name').val(),
            email: $('#customer-email').val()
        };

        $.ajax({
            url: odoo_integration_ajax.ajax_url,
            type: 'POST',
            data: {
                action: customerId ? 'edit_customer' : 'add_customer',
                customer_data: customerData
            },
            success: function(response) {
                if (response.success) {
                    $('#customer-modal').hide();
                    loadCustomers();
                }
            }
        });
    });

    $(document).on('click', '.edit-customer', function() {
        var customerId = $(this).data('id');
        // Fetch customer details and populate the form
        $('#customer-id').val(customerId);
        $('#customer-modal').show();
    });

    $(document).on('click', '.delete-customer', function() {
        var customerId = $(this).data('id');
        if (confirm('Are you sure you want to delete this customer?')) {
            $.ajax({
                url: odoo_integration_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_customer',
                    customer_id: customerId
                },
                success: function(response) {
                    if (response.success) {
                        loadCustomers();
                    }
                }
            });
        }
    });

    loadCustomers();
});