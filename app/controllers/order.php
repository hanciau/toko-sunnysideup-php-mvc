
        <?php

        class order extends Controller
        {

            public function __construct()
            {
                $this->requireLogin();
                $this->adminmustgo();
            }
            public function index()
            {
                $user_id = $_SESSION['user_id'] ?? null;

                if (isset($user_id) && !empty($user_id)) {
                    $data['css'] = 'order';
                    $data['judul'] = 'Order';
                    $data['js'] = 'order';
                    $profile = $this->model('profile_db')->profile($user_id);
                    $data['profile'] = $profile;
                    $order = $this->model('Customer_db')->orderitem();
                    $orderResult = $this->model('Customer_db')->getalamat($user_id);
                    $data['cart_items'] = $order['cart_items'];
                    $data['total_weight'] = $order['total_weight'];
                    $data['alamat'] = $orderResult;
                    $this->view('templates/header', $data);
                    $this->view('order/index', $data);
                    $this->view('templates/footer', $data);
                }
            }

            public function getCoast()
            {
                $data['css'] = 'order';
                $data['judul'] = 'Order';
                $data['js'] = 'order';
                $postData = $_POST['selected_address'];

                // Decode string JSON menjadi array asosiatif
                $addressData = json_decode($postData, true);

                // Mendapatkan nilai "kota_id" dan "address_id"
                $kotaId = $addressData['kota_id'];
                $services = $this->model('getCoast')->getCoast($kotaId, $_POST);
                $user_id = $_SESSION['user_id'] ?? null;

                if (isset($user_id) && !empty($user_id)) {
                    $data['css'] = 'order';
                    $data['judul'] = 'Order';
                    $data['js'] = 'order';
                    $profile = $this->model('profile_db')->profile($user_id);
                    $data['profile'] = $profile;
                    $order = $this->model('Customer_db')->orderitem();
                    $data['cart_items'] = $order['cart_items'];
                    $data['total_weight'] = $order['total_weight'];
                    $data['paket'] = $services;
                    $data['courier'] = $_POST['courier'];
                    if (isset($_POST['selected_address'])) {
                        // Decode string JSON dan ambil 'address_id' menggunakan null coalescing operator (??)
                        $address_id = json_decode($_POST['selected_address'], true)['address_id'] ?? null;

                        // Cetak address_id, atau kosong jika tidak ada
                    }
                    $data['address_id'] = $address_id;
                    $this->view('templates/header', $data);
                    $this->view('order/pilih_paket', $data);
                    $this->view('templates/footer', $data);
                }
            }
            public function inputorder()
            {
                $data['css'] = 'order';
                $data['judul'] = 'Order';
                $data['js'] = 'order';

                // Periksa apakah pengguna masuk atau tidak
                if (!isset($_SESSION['user_id'])) {
                    return array('error' => "User not logged in.");
                }

                // Ambil data dari formulir
                $user_id = $_SESSION['user_id'];

                // Pecahkan array $_POST
                $selected_items = $_POST['selected_items'];
                $address_id = $_POST['address_id'];
                $service_name = $_POST['service_name'];
                $service_cost = $_POST['service_cost'];
                $total_harga = $_POST['totalhargabarang'];
                $courier = $_POST['courier'];
                $profile = $this->model('profile_db')->profile($user_id);
                $data['profile'] = $profile;

                // Panggil metode orderkedb dari model dengan data yang sudah dipisahkan
                $result = $this->model('Customer_db')->orderkedb($user_id, $selected_items, $address_id, $service_name, $service_cost, $total_harga, $courier);



                if( $result> 0 ){
                    header('Location: ' . BASEURL . '/profile/order');
                    exit;
                } else {
                    header('Location: ' . BASEURL . '/keranjang');
                    exit;
                }
            }

            public function payment($id) {
                
                // Periksa apakah pengguna masuk atau tidak
                if (!isset($_SESSION['user_id'])) {
                    return array('error' => "User not logged in.");
                }
      
                // Ambil data dari formulir
                $user_id = $_SESSION['user_id'];
                $data = $this->model('payment')->getorderdata($user_id,$id);
                $this->view('Payment/examples/snap/checkout-process', $data);
            }
        }
        ?>
        