<?php

class payment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function updateTransactionStatus($data)
    {
        $query = "UPDATE `order` SET `status` = :status, `transaction_id` = :transaction_id WHERE `order_id` = :order_id";

        $this->db->query($query);
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':status', $data['transaction_status']);
        $this->db->bind(':order_id', $data['order_id']);

        try {
            $this->db->execute();
            return true;
        } catch (PDOException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    public function getorderdata($user_id, $id)
    {
        // Inisialisasi koneksi database
        $db = $this->db;
    
        try {
            // Ambil data order berdasarkan order_id
            $db->query("SELECT * FROM `orders` WHERE order_id = :order_id");
            $db->bind(':order_id', $id);
            $orderData = $db->single();
    
            if (!$orderData) {
                return array('error' => "Order not found.");
            }
    
            // Ambil data customer berdasarkan customer_id
            $db->query("SELECT * FROM `customer` WHERE customer_id = :customer_id");
            $db->bind(':customer_id', $user_id);
            $customerData = $db->single();
    
            if (!$customerData) {
                return array('error' => "Customer not found.");
            }
    
            $nama = $customerData['nama_depan'];
            $email = $customerData['email'];
    
            // Ambil detail item dari order_item berdasarkan order_id
            $db->query("SELECT oi.*, p.price 
                        FROM `order_item` oi 
                        JOIN `product` p ON oi.product_id = p.product_id 
                        WHERE oi.order_id = :order_id");
            $db->bind(':order_id', $id);
            $item_details = $db->resultSet();
    
            // Debug: cek apakah ada item_details yang diambil
            if (empty($item_details)) {
                return array('error' => "No items found for this order.");
            }
    
            // Var_dump untuk melihat harga produ

    
            // Kembalikan semua data dalam bentuk array
            return array(
                'order_data' => $orderData,
                'customer_data' => array(
                    'real_name' => $nama,
                    'email' => $email
                ),
                'item_details' => $item_details
            );
        } catch (Exception $e) {
            return array('error' => "Failed to get order data: " . $e->getMessage());
        }
    }
    
}
