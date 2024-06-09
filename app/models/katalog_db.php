<?php

class katalog_db
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllcategory()
    {
        $this->db->query('SELECT * FROM category');
        return $this->db->resultSet();
    }
    public function tampilproduk($data)
    {
        // Check if 'selected_items' are provided in the POST data
        if (!empty($data['id'])) {
            $ids = $data['id'];
            $placeholders = rtrim(str_repeat('?, ', count($ids)), ', ');

            $query = 'SELECT DISTINCT p.*, (
            SELECT GROUP_CONCAT(c.category_name) 
            FROM category c 
            INNER JOIN product_category pc2 ON c.category_id = pc2.category_id 
            WHERE pc2.product_id = p.product_id
        ) AS category_names
        FROM product p
        INNER JOIN product_category pc ON p.product_id = pc.product_id
        WHERE pc.category_id IN (' . $placeholders . ')';

            $this->db->query($query);

            foreach ($ids as $key => $id) {
                $this->db->bind($key + 1, $id);
            }

            error_log($query); // Log the query for debugging

            return $this->db->resultSet();
        } elseif (!empty($data['keyword'])) {
            $keyword = $data['keyword'];
            $query = "SELECT p.*, GROUP_CONCAT(c.category_name) AS category_names
        FROM product p
        LEFT JOIN product_category pc ON p.product_id = pc.product_id
        LEFT JOIN category c ON pc.category_id = c.category_id
        WHERE p.name LIKE :keyword
        GROUP BY p.product_id";
            $this->db->query($query);
            $this->db->bind('keyword', "%$keyword%");

            error_log($query); // Log the query for debugging

            return $this->db->resultSet();
        } else {
            $this->db->query('SELECT product.product_id, product.name, product.description, product.price, product.image_url, 
        GROUP_CONCAT(category.category_name) AS category_names
        FROM product
        LEFT JOIN product_category ON product.product_id = product_category.product_id
        LEFT JOIN category ON product_category.category_id = category.category_id
        GROUP BY product.product_id');

            error_log('Fetching all products'); // Log the query for debugging

            return $this->db->resultSet();
        }
    }


    public function detail_product($id)
    {
        $this->db->query('SELECT p.*, (
            SELECT GROUP_CONCAT(c.category_name) 
            FROM category c 
            INNER JOIN product_category pc ON c.category_id = pc.category_id 
            WHERE pc.product_id = :id
         ) AS category_names
         FROM product p
         WHERE p.product_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
