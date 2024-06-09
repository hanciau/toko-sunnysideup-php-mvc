<?php
class order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function order()
    {
        // Ensure 'selected_items' is provided in the POST data
        if (!isset($_POST['selected_items']) || !is_array($_POST['selected_items'])) {
            return array('error' => "No items selected.");
        }

        $selectedItems = $_POST['selected_items'];
        $placeholders = rtrim(str_repeat('?, ', count($selectedItems)), ', ');

        // Fetch cart items for the selected products
        $query = 'SELECT k.id, k.customer_id, k.product_id, k.quantity, k.harga_seluruh, p.name, p.stock, p.image_url, p.price 
    FROM keranjang k 
    JOIN product p ON k.product_id = p.product_id 
    WHERE k.id IN (' . $placeholders . ')';
        $this->db->query($query);

        // Bind the selected item IDs
        foreach ($selectedItems as $index => $itemId) {
            $this->db->bind($index + 1, $itemId, PDO::PARAM_INT);
        }

        // Execute the query and check for errors
        try {
            $cart_items = $this->db->resultSet();
        } catch (Exception $e) {
            // Log the error and return an error message
            error_log('Query error: ' . $e->getMessage());
            return array('error' => 'An error occurred while fetching cart items.');
        }

        // Validate product stock
        foreach ($cart_items as $item) {
            if ($item['quantity'] > $item['stock']) {
                // Return an error message if stock is insufficient
                return array('error' => "Stock for product '{$item['item_name']}' is insufficient.");
            }
        }

        // Return the cart items if stock is sufficient
        return $cart_items;
    }


    public function orderitem()
    {
        // Ensure 'selected_items' is provided in the POST data
        if (!isset($_POST['selected_items']) || !is_array($_POST['selected_items'])) {
            return array('error' => "No items selected.");
        }

        $selectedItems = $_POST['selected_items'];
        $placeholders = rtrim(str_repeat('?, ', count($selectedItems)), ', ');

        // Fetch cart items for the selected products
        $query = 'SELECT k.id, k.customer_id, k.product_id, k.quantity, k.harga_seluruh, p.name, p.stock, p.image_url, p.price 
    FROM keranjang k 
    JOIN product p ON k.product_id = p.product_id 
    WHERE k.id IN (' . $placeholders . ')';
        $this->db->query($query);

        // Bind the selected item IDs
        foreach ($selectedItems as $index => $itemId) {
            $this->db->bind($index + 1, $itemId, PDO::PARAM_INT);
        }

        // Execute the query and check for errors
        try {
            $cart_items = $this->db->resultSet();
        } catch (Exception $e) {
            // Log the error and return an error message
            error_log('Query error: ' . $e->getMessage());
            return array('error' => 'An error occurred while fetching cart items.');
        }

        // Validate product stock
        foreach ($cart_items as $item) {
            if ($item['quantity'] > $item['stock']) {
                // Return an error message if stock is insufficient
                return array('error' => "Stock for product '{$item['item_name']}' is insufficient.");
            }
        }

        // Return the cart items if stock is sufficient
        return $cart_items;
    }
}
