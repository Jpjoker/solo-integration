<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="customer-list">
        <!-- Customers will be loaded here via JavaScript -->
    </tbody>
</table>
<button id="add-customer" class="button button-primary">Add New Customer</button>

<div id="customer-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Customer Details</h2>
        <form id="customer-form">
            <input type="hidden" id="customer-id">
            <label for="customer-name">Name:</label>
            <input type="text" id="customer-name" required>
            <label for="customer-email">Email:</label>
            <input type="email" id="customer-email" required>
            <button type="submit" class="button button-primary">Save Customer</button>
        </form>
    </div>
</div>