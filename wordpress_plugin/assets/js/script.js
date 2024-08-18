jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab-wrapper a').on('click', function(e) {
        e.preventDefault();
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });

    // Customer management
    function loadCustomers() {
        $.ajax({
            url: ajaxurl,
            data: { action: 'get_customers' },
            success: function(response) {
                var customers = response;
                var html = '';
                customers.forEach(function(customer) {
                    html += '<tr>';
                    html += '<td>' + customer.id + '</td>';
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
        var customerData = {
            id: $('#customer-id').val(),
            name: $('#customer-name').val(),
            email: $('#customer-email').val()
        };
        var action = customerData.id ? 'edit_customer' : 'add_customer';
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: action,
                customer: customerData
            },
            success: function(response) {
                $('#customer-modal').hide();
                loadCustomers();
            }
        });
    });

    $(document).on('click', '.edit-customer', function() {
        var customerId = $(this).data('id');
        // Fetch customer details and populate form
        $('#customer-id').val(customerId);
        $('#customer-modal').show();
    });

    $(document).on('click', '.delete-customer', function() {
        var customerId = $(this).data('id');
        if (confirm('Are you sure you want to delete this customer?')) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'delete_customer',
                    customer_id: customerId
                },
                success: function(response) {
                    loadCustomers();
                }
            });
        }
    });

    loadCustomers();

    // Similar functions for products and orders...
});