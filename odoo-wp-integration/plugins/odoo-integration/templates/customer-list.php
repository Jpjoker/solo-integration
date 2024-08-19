<div class="wrap">
    <h1>Klanten</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Email</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $customers = $this->customer_manager->get_customers();
        foreach ($customers as $customer) {
            echo "<tr>";
            echo "<td>{$customer['id']}</td>";
            echo "<td>{$customer['name']}</td>";
            echo "<td>{$customer['email']}</td>";
            echo "<td>
                    <a href='#' class='edit-customer' data-id='{$customer['id']}'>Bewerken</a> |
                    <a href='#' class='delete-customer' data-id='{$customer['id']}'>Verwijderen</a>
                </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="#" class="button add-customer">Klant toevoegen</a>
</div>