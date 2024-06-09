<?php 

class mahasiswa_db {
    private $table = 'customer';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllMahasiswa()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }
    public function profile($id)
    {
        $this->db->query('SELECT * FROM customer WHERE customer_id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    public function tambahDataMahasiswa($data)
    {
        $query = "INSERT INTO  $this->table  VALUES ('', :nama, :nim, :kelas)";
        
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('nim', $data['nim']);
        $this->db->bind('kelas', $data['kelas']);
    
        $this->db->execute();
    
        return $this->db->rowCount();
    }
    public function hapusDataMahasiswa($id)
    {
        $query = "DELETE FROM dartar_mahasiswa WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }
}
?>