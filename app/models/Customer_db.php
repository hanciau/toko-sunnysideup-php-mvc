<?php
class Customer_db
{
    private $db;
    private $apirajaongkir;

    public function __construct()
    {
        $this->db = new Database; // Assume Database is a class that handles DB connections
        $this->apirajaongkir = API_KEY_RAJA_ONGKIR;
    }

    public function authenticate($data)
    {
        $this->db->query('SELECT * FROM customer WHERE email = :email');
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
    public function getalamat($user_id)
    {
        $this->db->query('SELECT * FROM customer_address WHERE customer_id = :customerId');
        $this->db->bind(':customerId', $user_id);
        return $this->db->resultSet();
    }
    public function tambahalamat($data, $user_id)
    {
        function getCityName($city_id, $apirajaongkir)
        {
            $apirajaongkir = API_KEY_RAJA_ONGKIR;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=$city_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: $apirajaongkir"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "Error fetching city";
            } else {
                $data = json_decode($response, true);
                return isset($data['rajaongkir']['results']['city_name']) ? $data['rajaongkir']['results']['city_name'] : "City not found";
            }
        }

        // Function to get province name from RajaOngkir API
        function getProvinceName($province_id, $apirajaongkir)
        {
            $curl = curl_init();
            $apirajaongkir = API_KEY_RAJA_ONGKIR;
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=$province_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 70,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: $apirajaongkir"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "Error fetching province";
            } else {
                $data = json_decode($response, true);
                return isset($data['rajaongkir']['results']['province']) ? $data['rajaongkir']['results']['province'] : "Province not found";
            }
        }
        $city_name = getCityName($data['city'], $this->apirajaongkir);
        $province_name = getProvinceName($data['province_id'], $this->apirajaongkir);

        $query = "INSERT INTO customer_address (customer_id, label, receiver_name, phone_number, address, kota, provinsi, postal_code, kota_id, provinsi_id) 
                  VALUES (:customer_id, :label, :receiver_name, :phone_number, :address, :kota, :provinsi, :postal_code, :kota_id, :provinsi_id)";

        $this->db->query($query);
        $this->db->bind('customer_id', $user_id);
        $this->db->bind('label', $data['newAddressLabel']);
        $this->db->bind('receiver_name', $data['newReceiverName']);
        $this->db->bind('phone_number', $data['newPhoneNumber']);
        $this->db->bind('address', $data['newAddress']);
        // Menggunakan nama kota yang diambil dari fungsi getCityName
        $this->db->bind('kota', $city_name);
        // Menggunakan nama provinsi yang diambil dari fungsi getProvinceName
        $this->db->bind('provinsi', $province_name);
        $this->db->bind('postal_code', $data['newPostalCode']);
        $this->db->bind('kota_id', $data['city']);
        $this->db->bind('provinsi_id', $data['province_id']);

        $this->db->execute();
        return $this->db->rowCount();
    }



    public function getorder($user_id)
    {
        $this->db->query('
            SELECT o.id AS order_id, o.order_date, o.customer_id, 
                   oi.id AS order_item_id, oi.order_id, oi.product_id, oi.quantity, oi.price, 
                   p.id AS product_id, p.product_name, p.description, p.image_url
            FROM `order` o
            JOIN order_item oi ON o.id = oi.order_id
            JOIN produk p ON oi.product_id = p.id
            WHERE o.customer_id = :customerId
        ');
        $this->db->bind(':customerId', $user_id);
        $result = $this->db->resultSet();
        return $result;
    }

    public function insertakun($data)
    {
        // Cek username yang sudah ada
        $this->db->query("SELECT * FROM customer WHERE username = :username");
        $this->db->bind('username', $data['username']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return ['status' => false, 'message' => 'Username already exists'];
        }

        // Cek email yang sudah ada
        $this->db->query("SELECT * FROM customer WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return ['status' => false, 'message' => 'Email already exists'];
        }

        // Masukkan data baru ke database
        $query = "INSERT INTO customer (username, password, email) VALUES (:username, :password, :email)";
        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_ARGON2I));
        $this->db->bind('email', $data['email']);
        $this->db->execute();

        if ($this->db->rowCount() === 0) {
            return ['status' => false, 'message' => 'Failed to insert data'];
        } else {
            // Ambil ID admin baru
            $this->db->query("SELECT customer_id FROM customer WHERE email = :email");
            $this->db->bind('email', $data['email']);
            $this->db->execute();
            $newUser = $this->db->single();

            if ($newUser) {
                $_SESSION['user_id'] = $newUser['customer_id'];
                return ['status' => true, 'message' => 'User registered successfully'];
            } else {
                return ['status' => false, 'message' => 'Failed to retrieve new user ID'];
            }
        }
    }

    public function getkeranjang($data)
    {
        $this->db->query('SELECT k.id, k.customer_id, k.product_id, k.quantity, k.harga_seluruh, p.name, p.stock, p.image_url, p.price FROM keranjang k JOIN product p ON k.product_id = p.product_id WHERE k.customer_id = :customerId');
        $this->db->bind(':customerId', $data);
        return $this->db->resultSet();
    }
    public function deletitemkeranjang($id, $user_id)
    {
        // Ambil user_id dari session

        // Pastikan user_id tersedia
        if ($user_id) {
            // Query untuk menghapus item dari keranjang
            $this->db->query('DELETE FROM keranjang WHERE id = :item_id AND customer_id = :customerId');
            $this->db->bind(':customerId', $user_id);
            $this->db->bind(':item_id', $id);

            // Eksekusi query
            $this->db->execute();

            // Kembalikan jumlah baris yang terpengaruh
            return $this->db->rowCount();
        } else {
            // Jika user_id tidak ada, kembalikan 0
            return 0;
        }
    }
    public function tambahitemkeranjang($user_id, $data)
    {
        // Ambil data dari parameter dan filter input
        $customer_id = $user_id;
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $harga = $data['price'];

        // Hitung harga seluruh
        $harga_seluruh = $quantity * $harga;

        // Cek stok produk
        $checkStockQuery = "SELECT stock FROM product WHERE product_id = :product_id";
        $this->db->query($checkStockQuery);
        $this->db->bind(':product_id', $product_id);
        $row = $this->db->single();

        if ($row) {
            $availableStock = $row['stock'];
            if ($quantity > $availableStock) {
                header("Location: " . BASEURL . "/produk/detail/" . $product_id);
                exit();
            }

            // Cek apakah produk sudah ada di keranjang
            $checkCartQuery = "SELECT * FROM keranjang WHERE customer_id = :customer_id AND product_id = :product_id";
            $this->db->query($checkCartQuery);
            $this->db->bind(':customer_id', $customer_id);
            $this->db->bind(':product_id', $product_id);
            $row = $this->db->single();

            if ($row) {
                // Update keranjang jika produk sudah ada
                $updateCartQuery = "UPDATE keranjang SET quantity = quantity + :quantity, harga_seluruh = harga_seluruh + :harga_seluruh WHERE customer_id = :customer_id AND product_id = :product_id";
                $this->db->query($updateCartQuery);
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':harga_seluruh', $harga_seluruh);
                $this->db->bind(':customer_id', $customer_id);
                $this->db->bind(':product_id', $product_id);
                $this->db->execute();
            } else {
                // Tambahkan produk ke keranjang jika belum ada
                $insertCartQuery = "INSERT INTO keranjang (customer_id, product_id, quantity, harga_seluruh) VALUES (:customer_id, :product_id, :quantity, :harga_seluruh)";
                $this->db->query($insertCartQuery);
                $this->db->bind(':customer_id', $customer_id);
                $this->db->bind(':product_id', $product_id);
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':harga_seluruh', $harga_seluruh);
                $this->db->execute();
            }

            header("Location: " . BASEURL . "/keranjang");
            exit();
        }
    }
    public function orderitem()
    {
        // Pastikan 'selected_items' tersedia dalam data POST
        if (!isset($_POST['selected_items']) || !is_array($_POST['selected_items'])) {
            return array('error' => "No items selected.");
        }

        $selectedItems = $_POST['selected_items'];
        $placeholders = rtrim(str_repeat('?, ', count($selectedItems)), ', ');

        // Ambil barang belanja untuk produk yang dipilih
        $query = 'SELECT k.id, k.customer_id, k.product_id, k.quantity, k.harga_seluruh, p.name, p.stock, p.image_url, p.price, p.berat 
        FROM keranjang k 
        JOIN product p ON k.product_id = p.product_id 
        WHERE k.id IN (' . $placeholders . ')';

        $this->db->query($query);

        // Bind item IDs yang dipilih
        foreach ($selectedItems as $index => $itemId) {
            $this->db->bind($index + 1, $itemId, PDO::PARAM_INT);
        }

        // Jalankan query dan periksa kesalahan
        try {
            $cart_items = $this->db->resultSet();
        } catch (Exception $e) {
            // Catat kesalahan dan kembalikan pesan kesalahan
            error_log('Query error: ' . $e->getMessage());
            return array('error' => 'An error occurred while fetching cart items.');
        }

        // Validasi stok produk
        foreach ($cart_items as $item) {
            if ($item['quantity'] > $item['stock']) {
                // Kembalikan pesan kesalahan jika stok tidak mencukupi
                return array('error' => "Stock for product '{$item['name']}' is insufficient.");
            }
        }

        // Hitung total berat berdasarkan kuantitas untuk setiap item
        $total_weight = 0;
        foreach ($cart_items as &$item) {
            $item['total_weight'] = $item['berat'] * $item['quantity']; // Hitung total berat
            $total_weight += $item['total_weight']; // Tambahkan total berat ke total keseluruhan
        }

        // Kembalikan barang belanja dan total berat
        return array('cart_items' => $cart_items, 'total_weight' => $total_weight);
    }
    public function orderkedb($user_id, $selected_items, $address_id, $service_name, $service_cost, $total_harga, $courier)
    {
        $selectedItems = $selected_items;
        $total_biaya = $service_cost + $total_harga;
        if (empty($selectedItems)) {
            return array('error' => "Cart is empty.");
        } else {
            try {
                // Cek apakah ada item di keranjang
                if (empty($selectedItems)) {
                    return array('error' => "Cart is empty.");
                }

                // Mulai transaksi
                $this->db->beginTransaction();

                // Insert ke tabel orders
                $orderQuery = "INSERT INTO orders (customer_id, order_date, total_harga, alamat, metode_pengiriman, perusahaan_pengiriman, statuspesanan, ongkos_kirim, total_biaya) VALUES (:customer_id, :order_date, :total_harga, :alamat, :metode_pengiriman, :perusahaan_pengiriman, :statuspesanan, :ongkos_kirim, :total_biaya)";
                $this->db->query($orderQuery);
                $this->db->bind(':customer_id', $user_id);
                $this->db->bind(':order_date', date("Y-m-d H:i:s"));
                $this->db->bind(':total_harga', $total_harga);
                $this->db->bind(':alamat', $address_id);
                $this->db->bind(':metode_pengiriman', $service_name);
                $this->db->bind(':perusahaan_pengiriman', $courier);
                $this->db->bind(':statuspesanan', "pending");
                $this->db->bind(':ongkos_kirim', $service_cost);
                $this->db->bind(':total_biaya', $total_biaya);
                $this->db->execute();

                // Dapatkan order_id yang baru dibuat
                $order_id = $this->db->lastInsertId();

                // Ambil data dari keranjang berdasarkan selected_items
                $placeholders = rtrim(str_repeat('?, ', count($selectedItems)), ', ');
                $cartQuery = "SELECT * FROM keranjang WHERE id IN ($placeholders)";
                $this->db->query($cartQuery);

                // Bind item IDs yang dipilih
                foreach ($selectedItems as $index => $itemId) {
                    $this->db->bind($index + 1, $itemId, PDO::PARAM_INT);
                }
                $items = $this->db->resultSet();

                // Insert setiap item ke tabel order_item
                $orderItemQuery = "INSERT INTO order_item (order_id, product_id, quantity, harga_seluruh) VALUES (:order_id, :product_id, :quantity, :harga_seluruh)";
                $this->db->query($orderItemQuery);

                foreach ($items as $item) {
                    $this->db->bind(':order_id', $order_id);
                    $this->db->bind(':product_id', $item['product_id']);
                    $this->db->bind(':quantity', $item['quantity']);
                    $this->db->bind(':harga_seluruh', $item['harga_seluruh']);
                    $this->db->execute();
                }

                // Insert biaya pengiriman ke tabel order_item
                // Hapus item dari keranjang
                $deleteQuery = "DELETE FROM keranjang WHERE id IN ($placeholders)";
                $this->db->query($deleteQuery);
                foreach ($selectedItems as $index => $itemId) {
                    $this->db->bind($index + 1, $itemId, PDO::PARAM_INT);
                }
                $this->db->execute();

                // Commit transaksi
                $this->db->commit();

                // Kembalikan ID pesanan yang baru dimasukkan
                return $this->db->resultSet();
            } catch (Exception $e) {
                // Rollback transaksi jika ada kesalahan
                $this->db->rollBack();
                return array('error' => "Failed to create order: " . $e->getMessage());
            }
        }
    }
}
