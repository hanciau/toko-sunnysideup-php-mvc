<?php

class keranjang extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
        $this->adminmustgo();
    }
    public function index()
    {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;
        if (isset($user_id) && !empty($user_id)) {
            $data['profile'] =$profile = $this->model('profile_db')->profile($user_id);
            $keranjang = $this->model('Customer_db')->getkeranjang($user_id);
                // Menyiapkan data yang akan dikirimkan ke view
                $data['css'] = 'keranjang';
                $data['judul'] = $profile['username'];
                $data['keranjang'] = $keranjang;
                $data['js']= "keranjang";

                // Tampilkan view dengan data yang telah disiapkan
                $this->view('templates/header', $data);
                $this->view('templates/navbar', $data);
                $this->view('keranjang/index', $data);
                $this->view('templates/footer', $data);

        } else {
            // Jika user id tidak tersedia di session atau kosong, kembali ke halaman login
            header('Location: ' . BASEURL . '/login');
            exit();
        }
    }
    public function hapus($id)
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if( $this->model('Customer_db')->deletitemkeranjang($id,$user_id) > 0 ) {
            Flasher::setFlash('barang berhasil dihapus', 'success');
            header('Location: ' . BASEURL . '/keranjang');
            exit;
        } else {
            Flasher::setFlash('barang gagal dihapus', 'danger');
            header('Location: ' . BASEURL . '/keranjang');
            exit;
        }
    }
    public function tambahkekeranjang()
    {
        // Ambil id produk dari POST data
        $id = $_POST['id'];
        
        // Ambil user_id dari session
        $user_id = $_SESSION['user_id'] ?? null;
    
        // Cek apakah user_id tersedia
        if ($user_id) {
            // Panggil metode model untuk menambah item ke keranjang
            if ($this->model('customer_db')->tambahitemkeranjang($user_id, $_POST)) {
                // Jika berhasil, arahkan ke halaman keranjang
                header('Location: ' . BASEURL . '/keranjang');
                exit;
            } else {
                // Jika gagal, arahkan kembali ke halaman detail produk
                header('Location: ' . BASEURL . '/keranjang/detail/' . $id);
                exit;
            }
        } else {
            // Jika user_id tidak ada, arahkan ke halaman login
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }
}