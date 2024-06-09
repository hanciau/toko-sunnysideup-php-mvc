<?php

class profile_db
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function profile($user_id)
    {
        // Lakukan query untuk mengambil profil pengguna beserta blob data
        $this->db->query("SELECT * FROM customer WHERE customer_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $profile = $this->db->single();

        // Mengembalikan profil pengguna dan blob data jika ditemukan
        return $profile;
    }
    public function orders($user_id)
    {
        // Lakukan query untuk mengambil profil pengguna beserta blob data
        $this->db->query("SELECT * FROM orders WHERE customer_id = :user_id");
        $this->db->bind(':user_id', $user_id);
    
        // Mengembalikan profil pengguna dan blob data jika ditemukan
        return $this->db->resultSet();
    }
    public function profileimagebaru() {
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = file_get_contents($_FILES['image']['tmp_name']);
        }
        $this->db->query("UPDATE customer SET image = :image WHERE customer_id = :user_id");
        $this->db->bind(':image', $image_tmp, PDO::PARAM_LOB);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    public function editprofile($data) {
        $this->db->query("UPDATE customer SET real_name = :real_name, email = :email, telephone = :telephone WHERE customer_id = :user_id");
        $this->db->bind(':real_name', $data['real_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':telephone', $data['telephone']);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

}
