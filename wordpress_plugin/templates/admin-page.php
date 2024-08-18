<div class="wrap">
    <h1>Odoo Integration</h1>
    <h2 class="nav-tab-wrapper">
        <a href="#customers" class="nav-tab nav-tab-active">Customers</a>
        <a href="#products" class="nav-tab">Products</a>
        <a href="#orders" class="nav-tab">Orders</a>
    </h2>
    <div id="customers" class="tab-content">
        <?php include('customer-list.php'); ?>
    </div>
    <div id="products" class="tab-content" style="display:none;">
        <?php include('product-list.php'); ?>
    </div>
    <div id="orders" class="tab-content" style="display:none;">
        <?php include('order-list.php'); ?>
    </div>
</div>