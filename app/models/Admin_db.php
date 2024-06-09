<?php
class Admin_db
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function authenticate($data)
    {
        $this->db->query('SELECT * FROM pengelola WHERE email = :email');
        $this->db->bind(':email', $data['email']);
        $row = $this->db->single();

        if ($row) {
            if (password_verify($data['password'], $row['password'])) {
                return $row;
            } else {
                unset($row);
                return "Password yang salah";
            }
        } else {
            unset($row);
            return "Email yang salah";
        }
    }
    public function insertakun($data)
    {
        // Cek username yang sudah ada
        $this->db->query("SELECT * FROM pengelola WHERE username = :username");
        $this->db->bind('username', $data['username']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return ['status' => false, 'message' => 'Username already exists'];
        }

        // Cek email yang sudah ada
        $this->db->query("SELECT * FROM pengelola WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return ['status' => false, 'message' => 'Email already exists'];
        }

        // Masukkan data baru ke database
        $query = "INSERT INTO pengelola (username, password, email) VALUES (:username, :password, :email)";
        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_ARGON2I));
        $this->db->bind('email', $data['email']);
        $this->db->execute();

        if ($this->db->rowCount() === 0) {
            return ['status' => false, 'message' => 'Failed to insert data'];
        } else {
            // Ambil ID admin baru
            $this->db->query("SELECT admin_id FROM pengelola WHERE email = :email");
            $this->db->bind('email', $data['email']);
            $this->db->execute();
            $newUser = $this->db->single();

            if ($newUser) {
                $_SESSION['user_id'] = $newUser['admin_id'];
                return ['status' => true, 'message' => 'User registered successfully'];
            } else {
                return ['status' => false, 'message' => 'Failed to retrieve new user ID'];
            }
        }
    }

    public function profile($user_id)
    {
        $this->db->query("SELECT * FROM pengelola WHERE admin_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
    public function getallOrders()
    {
        $this->db->query("SELECT * FROM orders");
        return $this->db->resultSet();
    }
    public function editprofile($data)
    {
        $this->db->query("UPDATE pengelola SET real_name = :real_name, email = :email, telephone = :telephone WHERE admin_id = :user_id");
        $this->db->bind(':real_name', $data['real_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':telephone', $data['telephone']);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    public function profileimagebaru()
    {

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = file_get_contents($_FILES['image']['tmp_name']);
        }
        $this->db->query("UPDATE pengelola SET image = :image WHERE admin_id = :user_id");
        $this->db->bind(':image', $image_tmp, PDO::PARAM_LOB);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    public function updateimagebaru($data)
    {
        $product_id = $data['product_id'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = file_get_contents($_FILES['image']['tmp_name']);
        }
        $this->db->query("UPDATE product SET image_url = :image WHERE product_id = :product_id");
        $this->db->bind(':image', $image_tmp, PDO::PARAM_LOB);
        $this->db->bind(':product_id', $product_id);
        $this->db->execute();
        return $this->db->rowCount();
    }
    public function getcategory()
    {

        $this->db->query('SELECT * FROM category');
        return $this->db->resultSet();
    }
    public function proses_insert_product($data)
    {

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = file_get_contents($_FILES['image']['tmp_name']);
        }

        $query = "INSERT INTO product (name, description, price, berat, stock, admin_id, image_url) VALUES (:name, :description, :price, :berat, :stock, :admin_id, :image)";

        $this->db->query($query);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':berat', $data['berat']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':admin_id', $data['admin_id']);
        $this->db->bind(':image', $image_tmp, PDO::PARAM_LOB);

        // Eksekusi query
        $this->db->execute();

        // Ambil ID produk yang baru dimasukkan
        $product_id = $this->db->lastInsertId();

        // Pastikan ada kategori yang dipilih
        if (isset($data['category_id']) && is_array($data['category_id'])) {
            $kategori_id = $data['category_id'];

            // Loop melalui kategori yang dipilih dan masukkan ke dalam tabel category_product
            foreach ($kategori_id as $category_id) {
                $insert_category_query = "INSERT INTO product_category (product_id, category_id) VALUES (:product_id, :category_id)";
                $this->db->query($insert_category_query);
                $this->db->bind(':product_id', $product_id);
                $this->db->bind(':category_id', $category_id);
                $this->db->execute();
            }
        }

        // Kembalikan jumlah baris yang terpengaruh untuk memastikan keberhasilan
        return $this->db->rowCount();
    }
    public function getallinfo($id)
    {
        $db = $this->db;

        try {
            // Ambil data order berdasarkan order_id
            $db->query("SELECT * FROM `orders` WHERE order_id = :order_id");
            $db->bind(':order_id', $id);
            $orderData = $db->single();

            if (!$orderData) {
                return array('error' => "Order not found.");
            }
            $user_id = $orderData['customer_id'];
            $idaddress = $orderData['alamat'];
            // Ambil data customer berdasarkan customer_id
            $db->query("SELECT * FROM `customer` WHERE customer_id = :customer_id");
            $db->bind(':customer_id', $user_id);
            $customerData = $db->single();

            if (!$customerData) {
                return array('error' => "Customer not found.");
            }

            // Ambil detail item dari order_item berdasarkan order_id
            $db->query("SELECT oi.*, p.* 
            FROM `order_item` oi 
            JOIN `product` p ON oi.product_id = p.product_id 
            WHERE oi.order_id = :order_id");
            $db->bind(':order_id', $id);
            $item_details = $db->resultSet();

            // Debug: cek apakah ada item_details yang diambil
            if (empty($item_details)) {
                return $id;
            }

            $db->query('SELECT * FROM customer_address WHERE address_id = :address_id');
            $db->bind(':address_id', $idaddress);
            $alamat = $db->single();
            if (empty($alamat)) {
                return $id;
            }

            // Kembalikan semua data dalam bentuk array
            return array(
                'alamat' => $alamat,
                'order_data' => $orderData,
                'customer_data' => $customerData,
                'item_details' => $item_details
            );
        } catch (Exception $e) {
            return array('error' => "Failed to get order data: " . $e->getMessage());
        }
    }
    public function getAllProducts($data)
    {
        if (!empty($data["id"])) {
            $placeholders = rtrim(str_repeat('?, ', count($data["id"])), ', ');

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

            foreach ($data["id"] as $key => $id) {
                $this->db->bind($key + 1, $id);
            }

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

            return $this->db->resultSet();
        } else {
            $query = 'SELECT product.product_id, product.name, product.description, product.price, product.image_url, 
            GROUP_CONCAT(category.category_name) AS category_names
            FROM product
            LEFT JOIN product_category ON product.product_id = product_category.product_id
            LEFT JOIN category ON product_category.category_id = category.category_id
            GROUP BY product.product_id';

            $this->db->query($query);

            return $this->db->resultSet();
        }
    }

    public function getProductOrders($productId)
    {
        $this->db->query("SELECT order_id FROM order_item WHERE product_id = :product_id");
        $this->db->bind(":product_id", $productId);
        return $this->db->resultSet();
    }

    public function getOrderStatus($orderId)
    {
        $this->db->query("SELECT statuspesanan FROM orders WHERE order_id = :order_id");
        $this->db->bind(":order_id", $orderId);
        return $this->db->resultSet();
    }
    public function deleteAndMoveProduct($data)
    {
        // Mulai transaksi
        $this->db->beginTransaction();

        try {
            foreach ($data["id"] as $product_id) {
                // Ambil data dari tabel product berdasarkan ID
                $this->db->query("SELECT * FROM product WHERE product_id = :product_id");
                $this->db->bind(":product_id", $product_id);
                $product = $this->db->single();

                if ($product) {
                    // Masukkan data ke dalam tabel product_tidaktersedia
                    $this->db->query("INSERT INTO product_tidaktersedia 
                    (product_id, name, description, berat, price, stock, created_at, updated_at, admin_id, image_url) 
                    VALUES 
                    (:product_id, :name, :description, :berat, :price, :stock, :created_at, :updated_at, :admin_id, :image_url)");
                    $this->db->bind(":product_id", $product['product_id']);
                    $this->db->bind(":name", $product['name']);
                    $this->db->bind(":description", $product['description']);
                    $this->db->bind(":berat", $product['berat']);
                    $this->db->bind(":price", $product['price']);
                    $this->db->bind(":stock", $product['stock']);
                    $this->db->bind(":created_at", $product['created_at']);
                    $this->db->bind(":updated_at", $product['updated_at']);
                    $this->db->bind(":admin_id", $product['admin_id']);
                    $this->db->bind(":image_url", $product['image_url']);
                    $this->db->execute();

                    // Hapus data dari tabel product berdasarkan ID
                    $this->db->query("DELETE FROM product WHERE product_id = :product_id");
                    $this->db->bind(":product_id", $product_id);
                    $this->db->execute();
                } else {
                    // Jika produk tidak ditemukan, batalkan transaksi dan lemparkan Exception
                    $this->db->rollBack();
                    throw new Exception("Product with ID $product_id not found");
                }
            }

            // Komit transaksi setelah semua produk berhasil dihapus dan dipindahkan
            $this->db->commit();
        } catch (Exception $e) {
            // Batalkan transaksi jika terjadi kesalahan
            $this->db->rollBack();
            throw $e;
        }
    }

    public function prosesedit($data)
    {

        // Query database untuk mengupdate data produk
        $this->db->query("UPDATE product SET name = :name, stock = :stock, price = :price, description = :description WHERE product_id = :product_id");
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
