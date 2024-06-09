<?php
class admin extends Controller
{

    public function __construct()
    {
        $this->acustomermustgo();
    }

    public function index()
    {
        $data['css'] = 'loginsignup';
        $data['judul'] = 'Admin Login';
        $data['js'] = 'admin/login';
        $this->view('templates/header', $data);
        $this->view('admin/login/index', $data);
        $this->view('templates/footer', $data);
    }
    public function menu()
    {
        $this->requireadminLogin();
        $this->acustomermustgo();
        $data['css'] = 'admin/dashboard';
        $data['judul'] = 'dashboard';
        $user_id = $_SESSION['user_id'] ?? null;
        $data['profile'] = $this->model('Admin_db')->profile($user_id);
        $this->view('templates/header', $data);
        $this->view('admin/navbar', $data);
        $this->view('admin/menu/index', $data);
        $this->view('templates/footer', $data);
    }
    public function processLoginadmin()
    {
        $admin = $this->model('Admin_db')->authenticate($_POST);

        if (is_array($admin)) {
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['email'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: ' . BASEURL . '/admin/profile');
            exit();
        } elseif ($admin === "Email yang salah" || $admin === "Password yang salah") {
            Flasher::setFlash($admin, 'danger');
            header('Location: ' . BASEURL . '/admin/login');
            exit();
        } else {
            Flasher::setFlash('Email atau password yang salah', 'danger');
            header('Location: ' . BASEURL . '/admin/login');
            exit();
        }
    }
    public function signup()
    {
        $this->acustomermustgo();
        $data['css'] = 'signup';
        $data['judul'] = 'signup';
        $data['js'] = 'admin/signup';
        $this->view('templates/header', $data);
        $this->view('admin/signup/index', $data);
        $this->view('templates/footer', $data);
    }

    public function tambahakun()
    {
        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flasher::setFlash('Email tidak valid', 'danger');
            header('Location: ' . BASEURL . '/admin_signup');
            exit;
        }

        // Panggil model untuk membuat akun
        $result = $this->model('Admin_db')->insertakun($_POST);

        // Periksa hasil dan set flash message sesuai dengan hasil
        if ($result['status']) {
            header('Location: ' . BASEURL . '/admin/profile');
        } else {
            Flasher::setFlash($result['message'], 'danger');
            header('Location: ' . BASEURL . '/admin/signup');
        }

        // Redirect ke halaman yang sesuai
        exit;
    }
    public function profile()
    {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            // Panggil model untuk mengambil profil pengguna berdasarkan id
            $profile = $this->model('Admin_db')->profile($user_id);
            if ($profile) {
                // Menyiapkan data yang akan dikirimkan ke view
                $data['css'] = 'profile';
                $data['judul'] = $profile['username'];
                $data['profile'] = $profile;
                $data['js'] = "admin/profile";

                // Tampilkan view dengan data yang telah disiapkan
                $this->view('templates/header', $data);
                $this->view('admin/navbar', $data);
                $this->view('admin/profile/index', $data);
                $this->view('templates/footer', $data);
            } else {
                // Jika profil pengguna tidak ditemukan, kembali ke halaman login
                header('Location: ' . BASEURL . '/admin/login');
                exit();
            }
        } else {
            // Jika user id tidak tersedia di session atau kosong, kembali ke halaman login
            header('Location: ' . BASEURL . '/admin/login');
            exit();
        }
    }

    public function profileData()
    {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            // Panggil model untuk mengambil profil pengguna berdasarkan id
            $profile = $this->model('Admin_db')->profile($user_id);
            if ($profile) {
                $data['profile'] = $profile;
                $this->view('profile/data', $data);
            }
        }
    }
    public function editprofile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/admin/login');
            exit;
        }

        if ($this->model('Admin_db')->editprofile($_POST) > 0) {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        } else {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
    }
    public function daftarorderan() {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            // Panggil model untuk mengambil semua pesanan
            $orderResult = $this->model('Admin_db')->getallOrders();
            $profile = $this->model('Admin_db')->profile($user_id);
            // Pisahkan pesanan berdasarkan status
            $orders = [
                'pending' => [],
                'expired' => [],
                'sukses' => []
            ];

            foreach ($orderResult as $order) {
                $status = $order['statuspesanan'];
                if ($status === 'pending') {
                    $orders['pending'][] = $order;
                } elseif ($status === 'expired') {
                    $orders['expired'][] = $order;
                } elseif ($status === 'sukses') {
                    $orders['sukses'][] = $order;
                }
            }
            $data['css'] = 'profile';
            $data['judul'] = 'Daftar orderan';
            $data['profile'] = $profile;
            $data['js'] = '';

            $data['orders'] = $orders;
            $this->view('templates/header', $data);
            $this->view('admin/navbar', $data);
            $this->view('admin/ceck_order/index',$data);
            $this->view('templates/footer', $data);
        } else {
            echo "User ID tidak ditemukan.";
        }
    }
    public function detail_order($id) {
        // Mengambil ID pengguna dari session
        $user_id = $_SESSION['user_id'] ?? null;

        if (isset($user_id) && !empty($user_id)) {
            // Panggil model untuk mengambil semua pesanan
            $orderResult = $this->model('Admin_db')->getallinfo($id);
            $profile = $this->model('Admin_db')->profile($user_id);
            // Pisahkan pesanan berdasarkan status
            $data['css'] = 'profile';
            $data['judul'] = 'Daftar orderan';
            $data['profile'] = $profile;
            $data['js'] = '';
            $data['orders'] = $orderResult;
            
            $this->view('templates/header', $data);
            $this->view('admin/navbar', $data);
            $this->view('admin/ceck_order/detail_order',$data);
            $this->view('templates/footer', $data);
 
        } else {
            echo "User ID tidak ditemukan.";
        }
    }
    public function insert_product()
    {
        $data['css'] = 'admin/insert_product';
        $data['judul'] = 'insert product';
        $data['js'] = 'admin/insert_product';
        $user_id = $_SESSION['user_id'] ?? null;
        $data['profile'] = $this->model('Admin_db')->profile($user_id);
        $data['category'] = $this->model('Admin_db')->getcategory();
        $this->view('templates/header', $data);
        $this->view('admin/navbar', $data);
        $this->view('admin/insert_product/index', $data);
        $this->view('templates/footer', $data);
    }
    public function proses_insert_product()
    {
        if ($this->model('Admin_db')->proses_insert_product($_POST) > 0) {
            header('Location: ' . BASEURL . '/admin/insert_product');
            exit;
        } else {
            header('Location: ' . BASEURL . '/admin/insert_product');
            exit;
        }
    }
    public function profileimagebaru()
    {
        if ($this->model('Admin_db')->profileimagebaru($_POST) > 0) {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        } else {
            header('Location: ' . BASEURL . '/admin/profile');
            exit;
        }
    }
    public function logout()
    {
        $this->view('templates/header');
        $this->view('admin/logout/index');
        $this->view('templates/footer');
    }
    public function hapus_product() {
        $products = $this->model('Admin_db')->getAllProducts($_POST);
        $dipesan = [];
        $ready = [];

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $productOrders = $this->model('Admin_db')->getProductOrders($productId);

            $isOrdered = false;

            foreach ($productOrders as $productOrder) {
                $orderId = $productOrder['order_id'];
                $order = $this->model('Admin_db')->getOrderStatus($orderId);

                if ($order && ($order['status'] != 'pending')) {
                    $isOrdered = true;
                    break;
                }
            }

            if ($isOrdered) {
                $dipesan[] = $product;
            } else {
                $ready[] = $product;
            }
        }
        $data['dipesan']=$dipesan;
        $data['ready']=$ready;
        $user_id = $_SESSION['user_id'];
        $data['profile'] = $this->model('Admin_db')->profile($user_id);
        $data['judul'] = 'Katalog';
        $data['css'] = 'card';
        $data['category'] = $this->model('katalog_db')->getAllcategory();
        $this->view('templates/header', $data);
        $this->view('admin/navbar', $data);
        $this->view('admin/delete_product/index', $data);
        $this->view('admin/delete_product/tampil_produk', $data);
        $this->view('templates/footer');
    }
    public function edit_detail($id)
    {
        $user_id = $_SESSION['user_id'];
        $data['profile'] = $this->model('Admin_db')->profile($user_id);
        $produk = $this->model('katalog_db')->detail_product($id);
        $data['css'] = 'detail_product';
        $data['judul'] = $produk['name'];
        $data['produk'] = $produk;
        $data['js'] = "admin/delete_product";
        $this->view('templates/header', $data);
        $this->view('admin/navbar', $data);
        $this->view('admin/delete_product/detail_produk', $data);
        $this->view('templates/footer', $data);
    }
    public function prosesedit()
    {
        if( $this->model('Admin_db')->prosesedit($_POST) > 0 ) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        }
    }
    public function hapus()
    {
        var_dump($_POST);   
        if( $this->model('Admin_db')->deleteAndMoveProduct($_POST) > 0 ) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        }
    }
    public function updateimagebaru()
    {
        if( $this->model('Admin_db')->updateimagebaru($_POST) > 0 ) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/admin/hapus_product');
            exit;
        }
    }
}
