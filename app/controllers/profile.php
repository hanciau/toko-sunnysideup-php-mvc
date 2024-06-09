<?php

class Profile extends Controller
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
            // Panggil model untuk mengambil profil pengguna berdasarkan id
            $profile = $this->model('profile_db')->profile($user_id);
            if ($profile) {
                // Menyiapkan data yang akan dikirimkan ke view
                $data['css'] = 'profile';
                $data['judul'] = $profile['username'];
                $data['profile'] = $profile;
                $data['js'] = "profile";

                // Tampilkan view dengan data yang telah disiapkan
                $this->view('templates/header', $data);
                $this->view('templates/navbar', $data);
                $this->view('profile/index', $data);
                $this->view('templates/footer', $data);
            } else {
                // Jika profil pengguna tidak ditemukan, kembali ke halaman login
                header('Location: ' . BASEURL . '/login');
                exit();
            }
        } else {
            // Jika user id tidak tersedia di session atau kosong, kembali ke halaman login
            header('Location: ' . BASEURL . '/login');
            exit();
        }
    }


    public function orders()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        $profile = $this->model('profile_db')->profile($user_id);
        $data['orders'] = $this->model('profile_db')->orders($user_id);
        $data['css'] = 'profile';
        $data['judul'] = 'order' . $profile['username'];
        $data['profile'] = $profile;
        $data['js'] = "profile";

        // Tampilkan view dengan data yang telah disiapkan
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('profile/orders', $data);
        $this->view('templates/footer', $data);
    }

    public function alamat()
    {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            $data['css'] = 'profile';
            $data['judul'] = 'Tambah alamat';
            $data['js'] = 'alamat';
            $profile = $this->model('profile_db')->profile($user_id);
            $data['profile'] = $profile;
            $province = $this->model('getProvinces')->getProvinces();
            $orderResult = $this->model('Customer_db')->getalamat($user_id);
            $data['alamat'] = $orderResult;
            $data['provinces'] = $province;

            $this->view('templates/header', $data);
            $this->view('templates/navbar', $data);
            $this->view('profile/alamat', $data, $province);
            $this->view('templates/footer', $data);
        }
    }
    public function getCitiesByProvince()
    {
        $cities = $this->model('getCitiesByProvince')->getCitiesByProvince($_POST);
        echo json_encode($cities);
    }

    public function tambahalamat()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if ($this->model('Customer_db')->tambahalamat($_POST, $user_id) > 0) {
            header('Location: ' . BASEURL . '/profile');
            exit;
        } else {
            Flasher::setFlash('gambar', 'danger');
            header('Location: ' . BASEURL . '/profile');
            exit;
        }
    }
    public function editprofile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }

        if ($this->model('profile_db')->editprofile($_POST) > 0) {
            header('Location: ' . BASEURL . '/profile');
            exit;
        } else {
            header('Location: ' . BASEURL . '/profile');
            exit;
        }
    }
    public function getorder()
    {
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            $data['css'] = 'alamat';
            $data['judul'] = 'alamat';
            $data['js'] = "alamat";
            // Panggil model untuk mengambil pesanan pengguna berdasarkan id
            $orderResult = $this->model('Customer_db')->getorder($user_id);
            $data['alamat'] = $orderResult;
            $this->view('templates/header', $data);
            $this->view('profile/ordesrs', $data);
            $this->view('templates/footer', $data);
        }
    }
    public function profileimagebaru()
    {
        if ($this->model('Admin_db')->profileimagebaru($_POST) > 0) {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        } else {
            Flasher::setFlash('gambar', 'danger');
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
    }
}
