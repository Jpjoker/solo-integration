<div class="wrap">
    <h1>Odoo Customers</h1>
    <button id="add-customer" class="button button-primary">Add New Customer</button>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="customer-list">
            <!-- Customer rows will be dynamically inserted here -->
        </tbody>
    </table>
</div>

<div id="customer-modal" style="display:none;">
    <h2>Customer Details</h2>
    <form id="customer-form">
        <input type="hidden" id="customer-id">
        <label for="customer-name">Name:</label>
        <input type="text" id="customer-name" required>
        <label for="customer-email">Email:</label>
        <input type="email" id="customer-email" required>
        <button type="submit" class="button button-primary">Save</button>
    </form>
</div>