<div class="wrap">
    <h1>Odoo Integration</h1>
    <div id="odoo-integration-tabs">
        <ul>
            <li><a href="#customers">Customers</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#orders">Orders</a></li>
        </ul>
        <div id="customers">
            <?php include(plugin_dir_path(__FILE__) . 'customer-list.php'); ?>
        </div>
        <div id="products">
            <?php include(plugin_dir_path(__FILE__) . 'product-list.php'); ?>
        </div>
        <div id="orders">
            <?php include(plugin_dir_path(__FILE__) . 'order-list.php'); ?>
        </div>
    </div>
</div>