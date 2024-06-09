<?php
class Katalog extends Controller
{
    
    public function index()
    {
        $user_id = $_SESSION['user_id'];
        $data['profile'] = $this->model('profile_db')->profile($user_id);
        $data['judul'] = 'Katalog';
        $data['css'] = 'card';
        $data['category'] = $this->model('katalog_db')->getAllcategory();
        $data['produk'] = $this->model('katalog_db')->tampilproduk($_POST);
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('katalog/index', $data);
        $this->view('katalog/tampil_produk', $data);
        $this->view('templates/footer');
    }
    public function detail($id)
    {
        $user_id = $_SESSION['user_id'];
        $data['profile'] = $this->model('profile_db')->profile($user_id);
        $produk = $this->model('katalog_db')->detail_product($id);
        $data['css'] = 'detail_product';
        $data['judul'] = $produk['name'];
        $data['produk'] = $produk;
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('katalog/detail_produk', $data);
        $this->view('templates/footer');
    }
}
?>